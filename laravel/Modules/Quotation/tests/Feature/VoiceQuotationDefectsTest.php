<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Modules\Catalog\Models\Product;
use Modules\Quotation\Actions\BuildQuotationDraftAction;
use Modules\Quotation\Actions\MatchDictatedItemsToCatalogAction;
use Modules\Quotation\AI\QuotationDraftExtractorInterface;
use Modules\Quotation\DTO\DictatedQuotationItem;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Tests\TestCase;

uses(TestCase::class);

/**
 * @param  array<string, mixed>  $payload
 */
function defectExtractor(array $payload): QuotationDraftExtractorInterface
{
    return new class($payload) implements QuotationDraftExtractorInterface
    {
        /** @param array<string, mixed> $payload */
        public function __construct(private array $payload) {}

        public function extract(string $transcript): array
        {
            return $this->payload;
        }
    };
}

// ─── Fix B: a matched line uses the exact catalog article (name + price) ───────

it('replaces a dictated item with the exact catalog article when it matches', function (): void {
    Product::query()->create(['name' => 'Scaldabagno elettrico 80 lt', 'sku' => 'IDR-SCALD-80', 'unit_price' => 210.0, 'is_active' => true]);

    $result = app(MatchDictatedItemsToCatalogAction::class)->execute([
        new DictatedQuotationItem('scaldabagno elettrico', 1.0),
    ]);

    expect($result[0]->isMatched())->toBeTrue();
    expect($result[0]->description)->toBe('Scaldabagno elettrico 80 lt'); // catalog name, not dictated text
    expect($result[0]->unitPrice)->toBe(210.0);
});

// ─── Fix C: "come quello di X" copies X's quotation, X is NOT the new customer ──

it('copies the referenced customer quotation without making them the recipient', function (): void {
    // Pinco Pallino already has a quotation (Ariston + canna fumaria).
    $pincoId = DB::table('customers')->insertGetId([
        'legal_name' => 'Pinco Pallino', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
    ]);
    $pinco = Quotation::query()->create(['customer_id' => $pincoId, 'status_code' => 'draft', 'title' => 'Caldaia']);
    $pinco->items()->create(['description' => 'Fornitura e posa di Ariston Clas One Wifi 24', 'quantity' => 1, 'unit_price' => 1158.0, 'line_total' => 1158.0, 'sort_order' => 0]);
    $pinco->items()->create(['description' => 'Fornitura e posa di canna fumaria', 'quantity' => 1, 'unit_price' => 190.0, 'line_total' => 190.0, 'sort_order' => 1]);

    Product::query()->create(['name' => 'Scaldabagno elettrico 80 lt', 'sku' => 'IDR-SCALD-80', 'unit_price' => 210.0, 'is_active' => true]);

    // "mi fai un preventivo come quello di Pinco Pallino con dentro uno scaldabagno elettrico"
    $extractor = defectExtractor([
        'based_on_customer' => 'Pinco Pallino',
        'customer' => ['legal_name' => ''],
        'keep_source_items' => true,
        'items' => [['description' => 'scaldabagno elettrico', 'quantity' => 1]],
    ]);

    $draft = (new BuildQuotationDraftAction($extractor, app(MatchDictatedItemsToCatalogAction::class)))
        ->execute('come quello di Pinco Pallino, tieni tutti gli articoli e aggiungi uno scaldabagno elettrico');

    // The new quotation is NOT for Pinco.
    expect($draft->customer)->toBeNull();

    $descriptions = array_map(static fn (DictatedQuotationItem $i): string => $i->description, $draft->items);
    // Pinco's structure copied verbatim...
    expect($descriptions)->toContain('Fornitura e posa di Ariston Clas One Wifi 24');
    expect($descriptions)->toContain('Fornitura e posa di canna fumaria');
    // ...plus the new article, taken from the catalog with its exact name and price.
    expect($descriptions)->toContain('Scaldabagno elettrico 80 lt');
    $scaldabagno = collect($draft->items)->firstWhere(fn (DictatedQuotationItem $i): bool => str_contains($i->description, 'Scaldabagno'));
    expect($scaldabagno?->unitPrice)->toBe(210.0);
    expect($scaldabagno?->isMatched())->toBeTrue();
});
