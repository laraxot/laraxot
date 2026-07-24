<?php

declare(strict_types=1);

uses(\Modules\Quotation\Tests\TestCase::class);

use Illuminate\Support\Facades\Http;
use Modules\Quotation\AI\QuotationDraftExtractorInterface;

it('extracts a quotation JSON payload from a transcript via the LLM client', function (): void {
    config()->set('services.deepseek.key', '');
    config()->set('services.groq.key', 'test-key');
    config()->set('services.groq.base_uri', 'https://api.groq.com/openai/v1');

    Http::fake([
        'api.groq.com/*' => Http::response([
            'choices' => [[
                'message' => [
                    'content' => json_encode([
                        'customer' => ['legal_name' => 'Idraulica Verdi', 'city' => 'Trento'],
                        'title' => 'Rifacimento bagno',
                        'items' => [['description' => 'Miscelatore', 'quantity' => 2, 'unit_price' => 58]],
                    ]),
                ],
            ]],
        ], 200),
    ]);

    $result = app(QuotationDraftExtractorInterface::class)->extract('rifacimento bagno per Idraulica Verdi a Trento, due miscelatori');

    expect($result['customer']['legal_name'])->toBe('Idraulica Verdi');
    expect($result['title'])->toBe('Rifacimento bagno');
    expect($result['items'][0]['description'])->toBe('Miscelatore');
});

it('sends a prompt built with placeholders, never leaking concrete example values', function (): void {
    config()->set('services.deepseek.key', '');
    config()->set('services.groq.key', 'test-key');
    config()->set('services.groq.base_uri', 'https://api.groq.com/openai/v1');
    Http::fake(['api.groq.com/*' => Http::response(['choices' => [['message' => ['content' => '{}']]]], 200)]);

    app(QuotationDraftExtractorInterface::class)->extract('un preventivo qualsiasi');

    Http::assertSent(function ($request): bool {
        $messages = $request->data()['messages'] ?? [];
        $prompt = implode("\n", array_map(static fn (array $m): string => (string) ($m['content'] ?? ''), $messages));

        // The few-shot example must be an abstract template...
        expect($prompt)->toContain('⟨ARTICOLO⟩');
        expect($prompt)->toContain('⟨CLIENTE_RIFERIMENTO⟩');
        // ...and must NOT hardcode concrete values a model could echo verbatim.
        expect($prompt)->not->toContain('scaldabagno elettrico');
        expect($prompt)->not->toContain('Pinco Pallino');
        expect($prompt)->not->toContain('Davide Cavallini');

        return true;
    });
});

it('returns an empty array when no LLM provider is configured', function (): void {
    config()->set('services.deepseek.key', '');
    config()->set('services.groq.key', '');
    config()->set('services.openai.key', '');

    expect(app(QuotationDraftExtractorInterface::class)->extract('qualcosa'))->toBe([]);
});

it('returns an empty array for a blank transcript without calling the LLM', function (): void {
    Http::fake();

    expect(app(QuotationDraftExtractorInterface::class)->extract('   '))->toBe([]);

    Http::assertNothingSent();
});
