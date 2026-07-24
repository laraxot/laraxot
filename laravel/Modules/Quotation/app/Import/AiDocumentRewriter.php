<?php

declare(strict_types=1);

namespace Modules\Quotation\Import;

use ZipArchive;

use function Safe\copy;
use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\preg_match;
use function Safe\preg_replace_callback;
use function Safe\simplexml_load_string;

/**
 * Produces a modified copy of a quotation document by handing its editable parts
 * to an {@see QuotationDocumentRewriterInterface} (AI) together with the new
 * quotation data, then reassembling the file. Works on the XML parts of ODF
 * (.ods/.odt/.odp) and OOXML (.docx/.xlsx) and on plain CSV/TXT. Rewritten XML is
 * validated for well-formedness before being written back; anything unsafe is
 * skipped so a broken AI answer can never corrupt the document.
 */
final class AiDocumentRewriter
{
    public function __construct(
        private readonly QuotationDocumentRewriterInterface $rewriter,
    ) {}

    /**
     * Rewrites the source document into $destinationAbsolutePath. Returns true when
     * at least one part was successfully rewritten; false otherwise (caller falls
     * back). PDFs and unknown types are not editable → false.
     *
     * @param  array<string, mixed>  $quotation
     */
    public function rewrite(string $sourceAbsolutePath, string $extension, array $quotation, string $destinationAbsolutePath): bool
    {
        copy($sourceAbsolutePath, $destinationAbsolutePath);

        return match (strtolower($extension)) {
            'ods', 'odt', 'odp' => $this->rewriteZipParts($destinationAbsolutePath, ['content.xml'], $quotation, xml: true),
            'docx' => $this->rewriteZipParts($destinationAbsolutePath, ['word/document.xml'], $quotation, xml: true),
            'xlsx' => $this->rewriteZipParts($destinationAbsolutePath, $this->xlsxEntries($destinationAbsolutePath), $quotation, xml: true),
            'csv', 'txt' => $this->rewritePlainFile($destinationAbsolutePath, $quotation),
            default => false, // pdf and others: not editable
        };
    }

    /**
     * @param  list<string>  $entries
     * @param  array<string, mixed>  $quotation
     */
    private function rewriteZipParts(string $path, array $entries, array $quotation, bool $xml): bool
    {
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            return false;
        }

        $rewrote = false;
        foreach ($entries as $entry) {
            $content = $zip->getFromName($entry);
            if (! is_string($content) || $content === '') {
                continue;
            }

            $modified = $xml
                ? $this->rewriteXmlPart($content, $entry, $quotation)
                : $this->rewritePlainPart($content, $entry, $quotation);
            if ($modified === null) {
                continue;
            }

            $zip->addFromString($entry, $modified);
            $rewrote = true;
        }

        $zip->close();

        return $rewrote;
    }

    /**
     * Rewrites an XML part. To stay well under the LLM's output-token limit, only
     * the document BODY (<office:body>/<w:body> — the actual data, not the bulky
     * style declarations) is sent to the AI and spliced back; the whole spliced
     * document is then validated. Falls back to rewriting the whole part when no
     * body markers are found (small parts like xlsx sharedStrings).
     *
     * @param  array<string, mixed>  $quotation
     */
    private function rewriteXmlPart(string $content, string $entry, array $quotation): ?string
    {
        $body = $this->extractBody($content);
        if ($body !== null) {
            [$before, $region, $after] = $body;
            $newRegion = $this->rewriter->rewrite($region, $entry, $quotation);
            if ($newRegion === null || trim($newRegion) === '' || $newRegion === $region) {
                return null;
            }
            $candidate = $this->blankOrphanQuantities($before.$newRegion.$after);

            return $this->isWellFormedXml($candidate) ? $candidate : null;
        }

        $modified = $this->rewriter->rewrite($content, $entry, $quotation);
        if ($modified === null || trim($modified) === '' || $modified === $content) {
            return null;
        }

        $modified = $this->blankOrphanQuantities($modified);

        return $this->isWellFormedXml($modified) ? $modified : null;
    }

    /**
     * Deterministic cleanup for ODS spreadsheets: the quantity cell of the KEPT
     * line and of a deleted line have IDENTICAL markup (same style, same "1"), so
     * the AI's string find/replace can't tell them apart — it either blanks all of
     * them (zeroing the kept line's total) or none (leaving orphan "1"s on emptied
     * rows). We resolve it positionally: in a table row that has a quantity but NO
     * non-zero price, the quantity is orphan -> blank it. The kept line has a real
     * price on its row, so its quantity is preserved.
     */
    private function blankOrphanQuantities(string $xml): string
    {
        return preg_replace_callback('#<table:table-row\b.*?</table:table-row>#s', function (array $match): string {
            $row = $match[0] ?? '';
            if (! is_string($row)) {
                return '';
            }

            // Only rows that carry a numeric quantity are candidates.
            if (! str_contains($row, 'office:value-type="float"')) {
                return $row;
            }
            // A row with a real (non-zero) currency value is a live line -> keep it.
            if (preg_match('/office:value-type="currency"[^>]*office:value="(?!0(?:\.0+)?")[0-9.]+"/', $row) === 1) {
                return $row;
            }

            // Orphan row: blank its numeric quantity cells, keeping only the style.
            return preg_replace_callback(
                '#<table:table-cell([^>]*?)\s+office:value-type="float"[^>]*>(?:<text:p>[^<]*</text:p>)?</table:table-cell>#',
                static function (array $cell): string {
                    $attributes = $cell[1] ?? '';
                    if (! is_string($attributes)) {
                        return is_string($cell[0] ?? null) ? $cell[0] : '';
                    }
                    $style = '';
                    if (preg_match('/table:style-name="[^"]*"/', $attributes, $s) === 1 && isset($s[0])) {
                        $style = ' '.$s[0];
                    }

                    return '<table:table-cell'.$style.'/>';
                },
                $row,
            );
        }, $xml);
    }

    /**
     * @param  array<string, mixed>  $quotation
     */
    private function rewritePlainPart(string $content, string $entry, array $quotation): ?string
    {
        $modified = $this->rewriter->rewrite($content, $entry, $quotation);

        return $modified !== null && trim($modified) !== '' && $modified !== $content ? $modified : null;
    }

    /**
     * Splits an XML string around its body element. Returns [before, body, after]
     * (body includes its tags) or null when no known body element is present.
     *
     * @return array{0: string, 1: string, 2: string}|null
     */
    private function extractBody(string $xml): ?array
    {
        foreach (['office:body', 'w:body'] as $tag) {
            $open = strpos($xml, '<'.$tag);
            $closeTag = '</'.$tag.'>';
            $close = strrpos($xml, $closeTag);
            if ($open !== false && $close !== false && $close > $open) {
                $end = $close + strlen($closeTag);

                return [substr($xml, 0, $open), substr($xml, $open, $end - $open), substr($xml, $end)];
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $quotation
     */
    private function rewritePlainFile(string $path, array $quotation): bool
    {
        $content = file_get_contents($path);
        if ($content === '') {
            return false;
        }

        $modified = $this->rewriter->rewrite($content, basename($path), $quotation);
        if ($modified === null || $modified === '' || $modified === $content) {
            return false;
        }

        file_put_contents($path, $modified);

        return true;
    }

    /**
     * @return list<string>
     */
    private function xlsxEntries(string $path): array
    {
        $entries = ['xl/sharedStrings.xml'];
        $zip = new ZipArchive();
        if ($zip->open($path) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                $name = is_array($stat) ? (string) $stat['name'] : '';
                if (str_starts_with($name, 'xl/worksheets/') && str_ends_with($name, '.xml')) {
                    $entries[] = $name;
                }
            }
            $zip->close();
        }

        return $entries;
    }

    private function isWellFormedXml(string $xml): bool
    {
        $previous = libxml_use_internal_errors(true);

        try {
            simplexml_load_string($xml);

            return true;
        } catch (\Throwable) {
            return false;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }
    }
}
