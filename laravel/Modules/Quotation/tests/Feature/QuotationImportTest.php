<?php

declare(strict_types=1);

use Modules\Catalog\Models\MetelCatalog;
use Modules\Catalog\Models\Product;
use Modules\Customer\Models\Customer;
use Modules\Quotation\Actions\DuplicateQuotationAsDraftAction;
use Modules\Quotation\Actions\ImportQuotationFromFileAction;
use Modules\Quotation\Actions\MatchDictatedItemsToCatalogAction;
use Modules\Quotation\AI\QuotationDraftExtractorInterface;
use Modules\Quotation\DTO\DictatedQuotationItem;
use Modules\Quotation\Import\QuotationDocumentTextExtractor;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Tests\TestCase;

uses(\Modules\Quotation\Tests\TestCase::class);

function pincoFixturePath(): string
{
    return base_path('tests/Fixtures/quotations/pinco-pallino.ods');
}

/**
 * The line items exactly as written in the real .ods (verbatim descriptions).
 *
 * @return array<string, mixed>
 */
function pincoExtractorPayload(): array
{
    return [
        'customer' => ['legal_name' => 'Pinco Pallino', 'city' => 'Rimini', 'province' => 'RN'],
        'title' => 'Preventivo per sostituzione caldaia',
        'items' => [
            ['description' => 'Fornitura e posa di Ariston Clas One Wifi 24 in con produzione di 15,7 litri acqua calda sanitaria al minuto. Scambiatore in acciaio 316.', 'quantity' => 1, 'unit_price' => 1158.00],
            ['description' => 'Fornitura e posa di canna fumaria a parete o tetto (prezzo a corpo)', 'quantity' => 1, 'unit_price' => 190.00],
            ['description' => 'Fornitura e posa di punto scarico condensa in zona limitrofa', 'quantity' => 1, 'unit_price' => 95.00],
            ['description' => 'Fornitura e posa di filtro defangatore magnetico', 'quantity' => 1, 'unit_price' => 125.00],
            ['description' => 'Fornitura e posa di dosatore di polifosfati su acqua fredda', 'quantity' => 1, 'unit_price' => 115.00],
            ['description' => 'Manodopera', 'quantity' => 1, 'unit_price' => 200.00],
        ],
    ];
}

/**
 * @param  array<string, mixed>  $payload
 */
function bindImportExtractor(array $payload): void
{
    app()->bind(QuotationDraftExtractorInterface::class, fn (): QuotationDraftExtractorInterface => new class($payload) implements QuotationDraftExtractorInterface
    {
        /** @param array<string, mixed> $payload */
        public function __construct(private array $payload) {}

        public function extract(string $transcript): array
        {
            return $this->payload;
        }
    });
}

// ─── 1. Real document text extraction ────────────────────────────────────────

it('extracts the readable text from the real .ods quotation', function (): void {
    $text = app(QuotationDocumentTextExtractor::class)->extract(pincoFixturePath(), 'pinco-pallino.ods');

    expect($text)->toContain('Ariston Clas One Wifi 24');
    expect($text)->toContain('canna fumaria');
    expect($text)->toContain('1.158,00');
    expect($text)->toContain('Pallino');
});

// ─── 2. Import a quotation FROM the file (creates the customer if missing) ─────

it('imports the .ods as a quotation and creates the customer', function (): void {
    bindImportExtractor(pincoExtractorPayload());

    expect(Customer::query()->where('legal_name', 'Pinco Pallino')->exists())->toBeFalse();

    $quotation = app(ImportQuotationFromFileAction::class)->execute(pincoFixturePath(), 'pinco-pallino.ods');

    $customer = Customer::query()->where('legal_name', 'Pinco Pallino')->firstOrFail();
    expect((int) $quotation->customer_id)->toBe((int) $customer->id);
    expect($customer->city)->toBe('Rimini');
    expect($quotation->items()->count())->toBe(6);
    // Verbatim line preserved.
    expect($quotation->items()->where('description', 'LIKE', '%Ariston Clas One Wifi 24%')->exists())->toBeTrue();
    expect((float) $quotation->subtotal)->toBeGreaterThan(1800.0);

    // Re-importing the same customer does not duplicate the anagraphic.
    app(ImportQuotationFromFileAction::class)->execute(pincoFixturePath(), 'pinco-pallino.ods');
    expect(Customer::query()->where('legal_name', 'Pinco Pallino')->count())->toBe(1);
});

// ─── 3. Copy-then-modify: duplicate preserves rows VERBATIM ───────────────────

it('duplicates a quotation keeping every line verbatim (copy, then modify)', function (): void {
    bindImportExtractor(pincoExtractorPayload());
    $source = app(ImportQuotationFromFileAction::class)->execute(pincoFixturePath(), 'pinco-pallino.ods');

    $draft = app(DuplicateQuotationAsDraftAction::class)->execute($source);

    expect($draft->items)->toHaveCount(6);
    $descriptions = array_map(static fn (DictatedQuotationItem $i): string => $i->description, $draft->items);
    expect($descriptions)->toContain('Fornitura e posa di canna fumaria a parete o tetto (prezzo a corpo)');
    // Prices carried over unchanged.
    $ariston = collect($draft->items)->firstWhere(fn (DictatedQuotationItem $i): bool => str_contains($i->description, 'Ariston'));
    expect($ariston?->unitPrice)->toBe(1158.0);
});

// ─── 4. METEL-aware matching: articles from the METEL catalog become usable ───

it('matches a dictated article against the imported METEL catalog and prices it', function (): void {
    $catalog = MetelCatalog::query()->create(['code' => 'demo', 'name' => 'Demo', 'source_type' => 'file', 'is_active' => true]);
    $catalog->items()->create([
        'metel_code' => 'DMO0100010',
        'description' => 'Tubo multistrato PEX-AL-PEX 16x2 mm',
        'gross_price' => 1.85,
        'net_price' => 0.92,
    ]);

    $result = app(MatchDictatedItemsToCatalogAction::class)->execute([
        new DictatedQuotationItem('tubo multistrato 16', 10.0),
    ]);

    expect($result[0]->isMatched())->toBeTrue();
    expect($result[0]->unitPrice)->toBe(0.92); // net price from METEL
    // The METEL article was promoted into the products catalog.
    expect(Product::query()->where('sku', 'DMO0100010')->exists())->toBeTrue();
});
