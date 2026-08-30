<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Modules\Catalog\Models\Product;
use Modules\Quotation\Actions\ApplyTechnicalParamsToQuotationItemsAction;
use Modules\Quotation\Actions\BuildQuotationDraftAction;
use Modules\Quotation\Actions\CreateQuotationFromDraftAction;
use Modules\Quotation\Actions\DuplicateQuotationAsDraftAction;
use Modules\Quotation\Actions\MatchDictatedItemsToCatalogAction;
use Modules\Quotation\AI\QuotationDraftExtractorInterface;
use Modules\Quotation\DTO\DictatedQuotationItem;
use Modules\Quotation\DTO\QuotationCustomerDraft;
use Modules\Quotation\DTO\QuotationDraft;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Tests\TestCase;

uses(\Modules\Quotation\Tests\TestCase::class);

/**
 * @param  array<string, mixed>  $payload
 */
function fakeExtractorWithParams(array $payload): QuotationDraftExtractorInterface
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

function seedThermoRuleProducts(): void
{
    Product::query()->create(['sku' => 'THERMO-CANNA-1M', 'name' => 'Canna fumaria al metro', 'unit_price' => 28.00, 'is_active' => true]);
    Product::query()->create(['sku' => 'THERMO-VALV-TERM', 'name' => 'Valvola termostatica', 'unit_price' => 12.50, 'is_active' => true]);
    Product::query()->create(['sku' => 'THERMO-CRONO', 'name' => 'Cronotermostato WiFi', 'unit_price' => 79.00, 'is_active' => true]);
}

/**
 * @param  list<DictatedQuotationItem>  $items
 * @return array<string, DictatedQuotationItem>
 */
function itemsByDescription(array $items): array
{
    $map = [];
    foreach ($items as $item) {
        $map[$item->description] = $item;
    }

    return $map;
}

it('recomputes rule-governed lines from technical params and preserves manual lines', function (): void {
    seedThermoRuleProducts();
    $manual = new DictatedQuotationItem('Manodopera', 2.0, 30.0);

    $result = app(ApplyTechnicalParamsToQuotationItemsAction::class)->execute(
        [$manual],
        ['flue_length_m' => 5, 'radiators_count' => 3, 'thermostat_present' => false],
    );

    $byDesc = itemsByDescription($result);
    expect($result)->toHaveCount(4);
    expect($byDesc['Manodopera']->unitPrice)->toBe(30.0); // preserved untouched
    expect($byDesc['Canna fumaria al metro']->quantity)->toBe(5.0);
    expect($byDesc['Canna fumaria al metro']->unitPrice)->toBe(28.0);
    expect($byDesc['Valvola termostatica']->quantity)->toBe(3.0);
    expect($byDesc['Cronotermostato WiFi']->quantity)->toBe(1.0);
});

it('omits the thermostat line when the thermostat is present', function (): void {
    seedThermoRuleProducts();

    $result = app(ApplyTechnicalParamsToQuotationItemsAction::class)->execute(
        [],
        ['flue_length_m' => 2, 'thermostat_present' => true],
    );

    $byDesc = itemsByDescription($result);
    expect($byDesc)->toHaveKey('Canna fumaria al metro');
    expect($byDesc)->not->toHaveKey('Cronotermostato WiFi');
});

it('replaces rule lines on re-run without duplicating them', function (): void {
    seedThermoRuleProducts();
    $params = ['flue_length_m' => 4, 'thermostat_present' => false];

    $first = app(ApplyTechnicalParamsToQuotationItemsAction::class)->execute([], $params);
    $second = app(ApplyTechnicalParamsToQuotationItemsAction::class)->execute($first, ['flue_length_m' => 6, 'thermostat_present' => false]);

    $byDesc = itemsByDescription($second);
    expect($second)->toHaveCount(2); // canna + crono, not duplicated
    expect($byDesc['Canna fumaria al metro']->quantity)->toBe(6.0);
});

it('skips a rule whose product is missing from the catalog', function (): void {
    // Only the flue product exists; the thermostat rule product is absent.
    Product::query()->create(['sku' => 'THERMO-CANNA-1M', 'name' => 'Canna fumaria al metro', 'unit_price' => 28.00, 'is_active' => true]);

    $result = app(ApplyTechnicalParamsToQuotationItemsAction::class)->execute(
        [],
        ['flue_length_m' => 3, 'thermostat_present' => false],
    );

    expect($result)->toHaveCount(1);
    expect($result[0]->description)->toBe('Canna fumaria al metro');
});

it('parses and whitelists technical params from the extractor payload', function (): void {
    $extractor = fakeExtractorWithParams([
        'items' => [],
        'technical_params' => [
            'surface_m2' => '80',            // numeric string -> 80.0
            'thermostat_present' => 'assente', // -> false
            'flue_length_m' => 6,
            'unknown_key' => 'x',             // dropped (not in config)
        ],
    ]);

    $draft = (new BuildQuotationDraftAction($extractor, new MatchDictatedItemsToCatalogAction()))->execute('x');

    expect($draft->technicalParams)->toBe([
        'surface_m2' => 80.0,
        'flue_length_m' => 6.0,
        'thermostat_present' => false,
    ]);
});

it('persists technical params on the created quotation', function (): void {
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Tecnico', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
    ]);

    $draft = new QuotationDraft(
        customer: new QuotationCustomerDraft('Tecnico'),
        title: null,
        notes: null,
        items: [new DictatedQuotationItem('Riga', 1.0, 10.0)],
        taxPercent: null,
        technicalParams: ['surface_m2' => 90.0, 'thermostat_present' => false],
    );

    $quotation = app(CreateQuotationFromDraftAction::class)->execute($draft, $customerId);

    expect($quotation->refresh()->technical_params)->toEqual(['surface_m2' => 90.0, 'thermostat_present' => false]);
});

it('carries technical params when duplicating a source quotation', function (): void {
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Origine Tecnica', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
    ]);

    $source = Quotation::query()->create([
        'customer_id' => $customerId, 'status_code' => 'draft', 'title' => 'X',
        'technical_params' => ['flue_length_m' => 7.0],
    ]);

    $draft = app(DuplicateQuotationAsDraftAction::class)->execute($source);

    expect($draft->technicalParams)->toEqual(['flue_length_m' => 7.0]);
});
