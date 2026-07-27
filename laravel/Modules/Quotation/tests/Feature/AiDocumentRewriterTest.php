<?php

declare(strict_types=1);

use Modules\Quotation\Import\AiDocumentRewriter;
use Modules\Quotation\Import\QuotationDocumentRewriterInterface;
use Modules\Quotation\Import\QuotationDocumentTextExtractor;
use Modules\Quotation\Tests\TestCase;

use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\tempnam;
use function Safe\unlink;

uses(TestCase::class);

/** A rewriter that returns a fixed string (or null) regardless of input. */
function fakeRewriter(?string $return): QuotationDocumentRewriterInterface
{
    return new class($return) implements QuotationDocumentRewriterInterface
    {
        public function __construct(private ?string $return) {}

        /** @param array<string, mixed> $quotation */
        public function rewrite(string $content, string $partName, array $quotation): ?string
        {
            return $this->return;
        }
    };
}

function odsWith(string $contentXml): string
{
    $path = (string) tempnam(sys_get_temp_dir(), 'rw_').'.ods';
    $zip = new ZipArchive();
    $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $zip->addFromString('content.xml', $contentXml);
    $zip->close();

    return $path;
}

it('reassembles the ODS with the AI-rewritten content.xml', function (): void {
    $source = odsWith('<?xml version="1.0"?><doc xmlns="urn:x"><p>Pico Pallino</p><p>Ariston € 1.158,00</p></doc>');
    $newXml = '<?xml version="1.0"?><doc xmlns="urn:x"><p>Selmec S.p.A.</p><p>Scaldabagno € 210,00</p></doc>';
    $dest = (string) tempnam(sys_get_temp_dir(), 'rwout_').'.ods';

    $ok = (new AiDocumentRewriter(fakeRewriter($newXml)))->rewrite($source, 'ods', ['customer_name' => 'Selmec S.p.A.'], $dest);

    expect($ok)->toBeTrue();
    $text = app(QuotationDocumentTextExtractor::class)->extract($dest, 'x.ods');
    expect($text)->toContain('Selmec S.p.A.');
    expect($text)->toContain('Scaldabagno');
    expect($text)->not->toContain('Pico');
    expect($text)->not->toContain('Ariston');

    @unlink($source);
    @unlink($dest);
});

it('sends only the <office:body> to the AI (not the bulky styles) and splices it back', function (): void {
    $styles = '<office:automatic-styles>'.str_repeat('<style:style style:name="ce1"/>', 50).'</office:automatic-styles>';
    $content = '<?xml version="1.0"?><office:document-content xmlns:office="urn:x" xmlns:text="urn:tx">'.$styles.'<office:body><office:spreadsheet><text:p>Pico Pallino</text:p><text:p>Ariston</text:p></office:spreadsheet></office:body></office:document-content>';
    $source = odsWith($content);
    $dest = (string) tempnam(sys_get_temp_dir(), 'rwout_').'.ods';

    // Capture what the rewriter receives, and return a modified body.
    $seen = '';
    $rewriter = new class($seen) implements QuotationDocumentRewriterInterface
    {
        public function __construct(public string &$seen) {}

        /** @param array<string, mixed> $quotation */
        public function rewrite(string $content, string $partName, array $quotation): string
        {
            $this->seen = $content;

            return str_replace(['Pico Pallino', 'Ariston'], ['Selmec S.p.A.', 'Scaldabagno'], $content);
        }
    };

    $ok = (new AiDocumentRewriter($rewriter))->rewrite($source, 'ods', [], $dest);

    expect($ok)->toBeTrue();
    // The AI only saw the body — no style declarations.
    expect($seen)->toStartWith('<office:body>');
    expect($seen)->not->toContain('office:automatic-styles');
    expect($seen)->toContain('Pico Pallino');
    // The spliced ODS is valid and updated, styles preserved.
    $text = app(QuotationDocumentTextExtractor::class)->extract($dest, 'x.ods');
    expect($text)->toContain('Selmec S.p.A.')->toContain('Scaldabagno')->not->toContain('Pico');

    @unlink($source);
    @unlink($dest);
});

it('blanks orphan quantities on deleted rows but keeps the kept line quantity', function (): void {
    // Kept row: qty 1 + real price 210. Orphan row (deleted): qty 1 + price 0.
    // Both quantity cells have IDENTICAL markup (style ce48, value 1) — only the
    // positional cleanup (no price on the row) can tell them apart.
    $keptRow = '<table:table-row><table:table-cell office:value-type="string"><text:p>Scaldabagno</text:p></table:table-cell>'
        .'<table:table-cell table:style-name="ce48" office:value-type="float" office:value="1"><text:p>1</text:p></table:table-cell>'
        .'<table:table-cell office:value-type="currency" office:currency="EUR" office:value="210"><text:p>€ 210,00</text:p></table:table-cell></table:table-row>';
    $orphanRow = '<table:table-row><table:table-cell office:value-type="string"><text:p></text:p></table:table-cell>'
        .'<table:table-cell table:style-name="ce48" office:value-type="float" office:value="1"><text:p>1</text:p></table:table-cell>'
        .'<table:table-cell office:value-type="currency" office:currency="EUR" office:value="0"><text:p>€ 0,00</text:p></table:table-cell></table:table-row>';
    $content = '<?xml version="1.0"?><office:document-content xmlns:office="urn:o" xmlns:table="urn:t" xmlns:text="urn:x">'
        .'<office:body><office:spreadsheet><table:table>'.$keptRow.$orphanRow.'</table:table></office:spreadsheet></office:body></office:document-content>';
    $source = odsWith($content);
    $dest = (string) tempnam(sys_get_temp_dir(), 'rwout_').'.ods';

    // The AI just makes a trivial change so the pipeline proceeds to the cleanup.
    $rewriter = new class() implements QuotationDocumentRewriterInterface
    {
        /** @param array<string, mixed> $quotation */
        public function rewrite(string $content, string $partName, array $quotation): string
        {
            return str_replace('Scaldabagno', 'Scaldabagno elettrico', $content);
        }
    };

    (new AiDocumentRewriter($rewriter))->rewrite($source, 'ods', [], $dest);

    $zip = new ZipArchive();
    $zip->open($dest);
    $out = (string) $zip->getFromName('content.xml');
    $zip->close();

    // Only ONE quantity=1 float cell survives (the kept one, on the row with 210).
    expect(substr_count($out, 'office:value-type="float"'))->toBe(1);
    expect($out)->toContain('office:value="210"');            // kept price intact
    expect($out)->toContain('<table:table-cell table:style-name="ce48"/>'); // orphan qty blanked

    @unlink($source);
    @unlink($dest);
});

it('never writes back a malformed XML part (keeps the copy intact)', function (): void {
    $source = odsWith('<?xml version="1.0"?><doc xmlns="urn:x"><p>Pico Pallino</p></doc>');
    $dest = (string) tempnam(sys_get_temp_dir(), 'rwout_').'.ods';

    // AI returned broken XML (unclosed tag) -> must be rejected.
    $ok = (new AiDocumentRewriter(fakeRewriter('<doc><p>Selmec')))->rewrite($source, 'ods', [], $dest);

    expect($ok)->toBeFalse();
    $text = app(QuotationDocumentTextExtractor::class)->extract($dest, 'x.ods');
    expect($text)->toContain('Pico Pallino'); // unchanged, not corrupted

    @unlink($source);
    @unlink($dest);
});

it('returns false when the rewriter yields nothing', function (): void {
    $source = odsWith('<?xml version="1.0"?><doc xmlns="urn:x"><p>Pico</p></doc>');
    $dest = (string) tempnam(sys_get_temp_dir(), 'rwout_').'.ods';

    expect((new AiDocumentRewriter(fakeRewriter(null)))->rewrite($source, 'ods', [], $dest))->toBeFalse();

    @unlink($source);
    @unlink($dest);
});

it('rewrites a plain CSV/TXT body', function (): void {
    $source = (string) tempnam(sys_get_temp_dir(), 'rw_').'.csv';
    file_put_contents($source, "Cliente;Pico Pallino\nAriston;1158\n");
    $dest = (string) tempnam(sys_get_temp_dir(), 'rwout_').'.csv';

    $ok = (new AiDocumentRewriter(fakeRewriter("Cliente;Selmec S.p.A.\nScaldabagno;210\n")))->rewrite($source, 'csv', [], $dest);

    expect($ok)->toBeTrue();
    expect((string) file_get_contents($dest))->toContain('Selmec S.p.A.')->toContain('Scaldabagno');

    @unlink($source);
    @unlink($dest);
});

it('cannot rewrite a PDF (not editable)', function (): void {
    $source = (string) tempnam(sys_get_temp_dir(), 'rw_').'.pdf';
    file_put_contents($source, '%PDF-1.4 dummy');
    $dest = (string) tempnam(sys_get_temp_dir(), 'rwout_').'.pdf';

    expect((new AiDocumentRewriter(fakeRewriter('anything')))->rewrite($source, 'pdf', [], $dest))->toBeFalse();

    @unlink($source);
    @unlink($dest);
});
