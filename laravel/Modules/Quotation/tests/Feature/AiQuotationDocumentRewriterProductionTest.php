<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Modules\Quotation\Import\QuotationDocumentRewriterInterface;
use Modules\Quotation\Tests\TestCase;

uses(\Modules\Quotation\Tests\TestCase::class);

use function Safe\json_encode;

/**
 * @param  callable(string): list<array{find: string, replace: string}>  $byModel
 */
function fakeRewriterHttp(callable $byModel): void
{
    config([
        'services.deepseek.key' => 'test-key',
        'services.deepseek.base_uri' => 'https://api.deepseek.com/v1',
        'services.deepseek.model' => 'deepseek-chat',
        'services.llm.pro_model' => 'deepseek-reasoner',
    ]);

    Http::fake([
        '*/chat/completions' => function (Request $request) use ($byModel) {
            $data = httpClientRequestData($request);
            $model = (string) ($data['model'] ?? '');
            $payload = ['replacements' => $byModel($model)];

            return Http::response(['choices' => [['message' => ['content' => json_encode($payload, JSON_UNESCAPED_UNICODE)]]]], 200);
        },
    ]);
}

it('applies the AI replacements to the content (small output, no full rewrite)', function (): void {
    fakeRewriterHttp(fn (string $model): array => [
        ['find' => 'Pico Pallino', 'replace' => 'Selmec S.p.A.'],
        ['find' => 'office:value="1158"', 'replace' => 'office:value="210"'],
        ['find' => '€ 1.158,00', 'replace' => '€ 210,00'],
    ]);

    $content = '<office:body><text:p>Pico Pallino</text:p><cell office:value="1158"><text:p>€ 1.158,00</text:p></cell></office:body>';
    $out = app(QuotationDocumentRewriterInterface::class)->rewrite($content, 'content.xml', ['customer_name' => 'Selmec S.p.A.']);

    expect($out)->toContain('Selmec S.p.A.');
    expect($out)->toContain('office:value="210"');
    expect($out)->toContain('€ 210,00');
    expect($out)->not->toContain('Pico Pallino');
    expect($out)->not->toContain('1158');
    Http::assertSentCount(1);
});

it('keeps the kept line quantity when the AI only changes description and price', function (): void {
    // The AI must NOT touch the quantity cell of a kept line — only desc + price.
    fakeRewriterHttp(fn (string $model): array => [
        ['find' => 'Ariston Clas One', 'replace' => 'Scaldabagno elettrico 80 lt'],
        ['find' => 'office:value="1158"', 'replace' => 'office:value="210"'],
        ['find' => '€ 1.158,00', 'replace' => '€ 210,00'],
    ]);

    // Body with a quantity cell (=1) and a price cell for the kept line.
    $content = '<office:body>'
        .'<table:table-cell office:value-type="float" office:value="1"><text:p>1</text:p></table:table-cell>'
        .'<text:p>Ariston Clas One</text:p>'
        .'<table:table-cell office:value-type="currency" office:value="1158"><text:p>€ 1.158,00</text:p></table:table-cell>'
        .'</office:body>';

    $out = app(QuotationDocumentRewriterInterface::class)->rewrite($content, 'content.xml', ['customer_name' => 'X']);

    // Quantity cell is intact...
    expect($out)->toContain('office:value="1"');
    expect($out)->toContain('<text:p>1</text:p>');
    // ...description and price updated so the line total (qty×price) is non-zero.
    expect($out)->toContain('Scaldabagno elettrico 80 lt');
    expect($out)->toContain('office:value="210"');
    expect($out)->not->toContain('office:value="1158"');
});

it('retries with the pro model when the first attempt returns no replacements', function (): void {
    fakeRewriterHttp(fn (string $model): array => $model === 'deepseek-reasoner'
        ? [['find' => 'Pico', 'replace' => 'Selmec']]
        : []);

    $out = app(QuotationDocumentRewriterInterface::class)->rewrite('<p>Pico</p>', 'content.xml', []);

    expect($out)->toBe('<p>Selmec</p>');
    Http::assertSentCount(2);
    Http::assertSent(fn (Request $request): bool => (httpClientRequestData($request)['model'] ?? '') === 'deepseek-reasoner');
});

it('returns null when no replacement matches / none are produced', function (): void {
    fakeRewriterHttp(fn (string $model): array => []);

    expect(app(QuotationDocumentRewriterInterface::class)->rewrite('<p>x</p>', 'content.xml', []))->toBeNull();
    Http::assertSentCount(2);
});

it('returns null and calls nothing when no LLM provider is configured', function (): void {
    config(['services.deepseek.key' => '', 'services.groq.key' => '', 'services.openai.key' => '']);
    Http::fake();

    expect(app(QuotationDocumentRewriterInterface::class)->rewrite('<p>x</p>', 'content.xml', []))->toBeNull();
    Http::assertNothingSent();
});

it('sends the new quotation data to the model in the prompt', function (): void {
    fakeRewriterHttp(fn (string $model): array => [['find' => 'x', 'replace' => 'y']]);

    app(QuotationDocumentRewriterInterface::class)->rewrite('<p>x</p>', 'content.xml', [
        'customer_name' => 'Selmec S.p.A.',
        'lines' => [['description' => 'Scaldabagno elettrico 80 lt', 'unit_price' => 210.0]],
    ]);

    Http::assertSent(function (Request $request): bool {
        $data = httpClientRequestData($request);
        $messages = $data['messages'] ?? [];
        if (! is_array($messages)) {
            return false;
        }
        $prompt = httpMessagesPrompt($messages);

        return str_contains($prompt, 'Selmec S.p.A.')
            && str_contains($prompt, 'Scaldabagno elettrico 80 lt')
            // ...map lines, fully empty surplus rows (no "0"), and NEVER empty the
            // kept line's quantity (that would zero the line total and all totals).
            && str_contains($prompt, 'SVUOTA')
            && str_contains(mb_strtolower($prompt), 'in eccesso')
            && str_contains($prompt, 'NON mettere "0"')
            && str_contains($prompt, 'NON VA MAI SVUOTATA');
    });
});
