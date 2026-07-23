<?php

declare(strict_types=1);

namespace Modules\Quotation\Import;

use Smalot\PdfParser\Parser as PdfParser;
use Throwable;
use ZipArchive;

/**
 * Extracts plain text from an uploaded quotation document so the AI can read it.
 * Supports LibreOffice/OpenOffice (.ods/.odt/.odp), Office OOXML (.docx/.xlsx),
 * PDF, and CSV/TXT. ODF/OOXML are ZIP containers of XML — we read the relevant
 * parts and strip tags; PDF uses smalot/pdfparser.
 */
final class QuotationDocumentTextExtractor
{
    public function extract(string $path, ?string $originalName = null): string
    {
        $extension = strtolower(pathinfo($originalName ?? $path, PATHINFO_EXTENSION));

        return match ($extension) {
            'ods', 'odt', 'odp' => $this->fromZip($path, ['content.xml']),
            'docx' => $this->fromZip($path, ['word/document.xml']),
            'xlsx' => $this->fromXlsx($path),
            'pdf' => $this->fromPdf($path),
            'csv', 'txt' => $this->normalize((string) @file_get_contents($path)),
            default => $this->fromUnknown($path),
        };
    }

    /**
     * @param  list<string>  $entries
     */
    private function fromZip(string $path, array $entries): string
    {
        $zip = new ZipArchive;
        if ($zip->open($path) !== true) {
            return '';
        }

        $xml = '';
        foreach ($entries as $entry) {
            $content = $zip->getFromName($entry);
            if (is_string($content)) {
                $xml .= ' '.$content;
            }
        }
        $zip->close();

        return $this->stripXml($xml);
    }

    private function fromXlsx(string $path): string
    {
        $zip = new ZipArchive;
        if ($zip->open($path) !== true) {
            return '';
        }

        $xml = (string) $zip->getFromName('xl/sharedStrings.xml');
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            $name = is_array($stat) ? (string) $stat['name'] : '';
            if (str_starts_with($name, 'xl/worksheets/') && str_ends_with($name, '.xml')) {
                $xml .= ' '.(string) $zip->getFromName($name);
            }
        }
        $zip->close();

        return $this->stripXml($xml);
    }

    private function fromPdf(string $path): string
    {
        try {
            return $this->normalize((new PdfParser)->parseFile($path)->getText());
        } catch (Throwable) {
            return '';
        }
    }

    private function fromUnknown(string $path): string
    {
        // Best effort: try as a ZIP (OOXML/ODF), else read raw.
        $asZip = $this->fromZip($path, ['content.xml', 'word/document.xml']);

        return $asZip !== '' ? $asZip : $this->normalize((string) @file_get_contents($path));
    }

    private function stripXml(string $xml): string
    {
        // Replace tags with a space so adjacent cell/run texts don't glue together.
        $text = preg_replace('/<[^>]+>/', ' ', $xml) ?? '';

        return $this->normalize(html_entity_decode($text, ENT_QUOTES | ENT_XML1));
    }

    private function normalize(string $text): string
    {
        return trim((string) preg_replace('/\s+/', ' ', $text));
    }
}
