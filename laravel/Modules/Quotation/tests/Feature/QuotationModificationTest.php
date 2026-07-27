<?php

declare(strict_types=1);

use Modules\Catalog\Models\Product;
use Modules\Quotation\Actions\BuildQuotationModificationAction;
use Modules\Quotation\Actions\MatchDictatedItemsToCatalogAction;
use Modules\Quotation\AI\QuotationModificationExtractorInterface;
use Modules\Quotation\DTO\DictatedQuotationItem;
use Modules\Quotation\DTO\QuotationCustomerDraft;
use Modules\Quotation\Tests\TestCase;

uses(TestCase::class);

/**
 * @param  array<string, mixed>  $ops
 */
function fakeModificationExtractor(array $ops): QuotationModificationExtractorInterface
{
    return new class($ops) implements QuotationModificationExtractorInterface
    {
        /** @param array<string, mixed> $ops */
        public function __construct(private array $ops) {}

        /** @return array<string, mixed> */
        public function extract(string $transcript, array $context): array
        {
            return $this->ops;
        }
    };
}

/** @param array<string, mixed> $ops */
function modBuilder(array $ops): BuildQuotationModificationAction
{
    return new BuildQuotationModificationAction(fakeModificationExtractor($ops), app(MatchDictatedItemsToCatalogAction::class));
}

/** @param list<DictatedQuotationItem> $items
 * @return array<string, DictatedQuotationItem>
 */
function byDesc(array $items): array
{
    $map = [];
    foreach ($items as $item) {
        $map[$item->description] = $item;
    }

    return $map;
}

// ─── Structure A: boiler replacement quotation ───────────────────────────────

it('modifies a boiler quotation: replaces the boiler, keeps the rest verbatim, changes customer', function (): void {
    $base = [
        new DictatedQuotationItem('Fornitura e posa di Ariston Clas One Wifi 24', 1.0, 1158.0),
        new DictatedQuotationItem('Fornitura e posa di canna fumaria a parete o tetto (prezzo a corpo)', 1.0, 190.0),
        new DictatedQuotationItem('Manodopera', 1.0, 200.0),
    ];

    $draft = modBuilder([
        'customer' => ['legal_name' => 'Mario Rossi', 'city' => 'Cesena'],
        'replace' => [['target' => 'ariston', 'description' => 'Fornitura e posa di caldaia Vaillant ecoTEC plus', 'quantity' => 1, 'unit_price' => 1300]],
        'add' => [['description' => 'Valvola termostatica', 'quantity' => 3, 'unit_price' => 12.5]],
    ])->execute('cambia la caldaia con una Vaillant e mettila per Mario Rossi di Cesena, aggiungi tre valvole', $base, new QuotationCustomerDraft('Pinco Pallino'));

    $items = byDesc($draft->items);
    expect($draft->customer?->legalName)->toBe('Mario Rossi');
    // Boiler replaced.
    expect($items)->not->toHaveKey('Fornitura e posa di Ariston Clas One Wifi 24');
    expect($items['Fornitura e posa di caldaia Vaillant ecoTEC plus']->unitPrice)->toBe(1300.0);
    // Other lines kept VERBATIM (same price).
    expect($items['Fornitura e posa di canna fumaria a parete o tetto (prezzo a corpo)']->unitPrice)->toBe(190.0);
    expect($items['Manodopera']->unitPrice)->toBe(200.0);
    // New line added.
    expect($items['Valvola termostatica']->quantity)->toBe(3.0);
});

// ─── Structure B: bathroom renovation quotation (different structure) ─────────

it('modifies a bathroom quotation: removes a line, adds one, keeps the rest verbatim', function (): void {
    $base = [
        new DictatedQuotationItem('Piatto doccia in resina 80x120', 1.0, 250.0),
        new DictatedQuotationItem('Miscelatore doccia incasso', 1.0, 120.0),
        new DictatedQuotationItem('Posa in opera piastrellista', 1.0, 400.0),
    ];

    $draft = modBuilder([
        'customer' => ['legal_name' => 'Idraulica Bianchi', 'vat_number' => 'IT0099'],
        'remove' => ['miscelatore'],
        'add' => [['description' => 'Box doccia in cristallo temperato', 'quantity' => 1, 'unit_price' => 450]],
    ])->execute('togli il miscelatore, aggiungi un box doccia, e fallo per Bianchi', $base, new QuotationCustomerDraft('Verdi'));

    $items = byDesc($draft->items);
    expect($draft->customer?->legalName)->toBe('Idraulica Bianchi');
    expect($items)->not->toHaveKey('Miscelatore doccia incasso');       // removed
    expect($items['Piatto doccia in resina 80x120']->unitPrice)->toBe(250.0); // verbatim
    expect($items['Posa in opera piastrellista']->unitPrice)->toBe(400.0);    // verbatim
    expect($items['Box doccia in cristallo temperato']->unitPrice)->toBe(450.0); // added
    expect($draft->items)->toHaveCount(3);
});

// ─── Verbatim guarantee: preserved lines are NOT re-matched/re-priced ─────────

it('never re-prices a preserved line even if a similar catalog product exists', function (): void {
    Product::query()->create(['name' => 'Canna fumaria', 'sku' => 'CF-STD', 'unit_price' => 99.0, 'is_active' => true]);

    $base = [
        new DictatedQuotationItem('Fornitura e posa di canna fumaria a corpo', 1.0, 190.0), // free line, custom price
    ];

    $draft = modBuilder([
        'add' => [['description' => 'Manodopera aggiuntiva', 'quantity' => 1, 'unit_price' => 80]],
    ])->execute('aggiungi manodopera', $base, new QuotationCustomerDraft('Pinco'));

    $items = byDesc($draft->items);
    // The preserved canna fumaria line keeps its exact 190, NOT the 99 catalog price.
    expect($items['Fornitura e posa di canna fumaria a corpo']->unitPrice)->toBe(190.0);
    expect($items['Fornitura e posa di canna fumaria a corpo']->matchedProductId)->toBeNull();
    expect($items['Manodopera aggiuntiva']->unitPrice)->toBe(80.0);
});
