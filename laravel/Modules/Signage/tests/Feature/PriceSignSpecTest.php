<?php

declare(strict_types=1);

uses(\Modules\Signage\Tests\TestCase::class);

use Modules\Signage\Actions\PriceSignSpecAction;
use Modules\Signage\Database\Seeders\SignEstimationRuleSeeder;
use Modules\Signage\Database\Seeders\SignPriceListSeeder;
use Modules\Signage\Models\SignComponent;
use Modules\Signage\Models\SignEstimationRule;
use Modules\Signage\Models\SignPriceListItem;
use Modules\Signage\Models\SignSpec;
use Modules\Signage\Tests\TestCase;

function seedSignagePriceList(): void
{
    (new SignPriceListSeeder())->run();
}

function seedSignageRules(): void
{
    (new SignEstimationRuleSeeder())->run();
}

it('seeds the reference price list (idempotently)', function (): void {
    seedSignagePriceList();
    seedSignagePriceList(); // second run must not duplicate

    expect(SignPriceListItem::query()->count())->toBe(11);

    $led = SignPriceListItem::query()
        ->where('code', 'FN-2016A-182-24-35K')->firstOrFail();

    expect($led->category_code)->toBe('led')
        ->and((float) $led->unit_cost)->toBe(3.65)
        ->and($led->unit_code)->toBe('mt');
});

it('seeds the estimation rules idempotently', function (): void {
    seedSignageRules();
    seedSignageRules();

    expect(SignEstimationRule::query()->count())->toBe(7);

    $led = SignEstimationRule::query()->where('role_code', 'led')->firstOrFail();
    expect($led->strategy_code)->toBe('perimeter')
        ->and((float) $led->factor)->toBe(0.9);
});

it('auto-matches a material component and estimates the m² from geometry', function (): void {
    seedSignagePriceList();
    seedSignageRules();

    $spec = SignSpec::factory()->create([
        'width_mm' => 2000,
        'height_mm' => 1000,
        'quantity' => 1,
    ]);
    $spec->components()->create([
        'position' => 0,
        'role_code' => 'body',
        'label' => 'ACRYLBLOCK',
        'material' => 'OTTONE',
        'source_code' => 'callout',
    ]);

    $quotation = app(PriceSignSpecAction::class)->execute($spec);
    $line = $quotation->lines[0];

    expect($line->category)->toBe('material')
        ->and($line->unit)->toBe('mq')
        ->and($line->quantity)->toBe(2.0)                 // 2m × 1m × 1 piece
        ->and($line->unitCost)->toBe(93.16)               // cheapest OTTONE sheet
        ->and($line->lineTotal)->toBe(186.32)
        ->and($line->needsInput)->toBeFalse();
});

it('prices an LED line with a manual quantity and applies markup', function (): void {
    seedSignagePriceList();

    $spec = SignSpec::factory()->create();
    $spec->components()->create([
        'position' => 0,
        'role_code' => 'led',
        'label' => 'LED LIGHTING',
        'material' => 'STRIPLED',
        'lighting' => '3500K',
        'source_code' => 'callout',
    ]);

    $quotation = app(PriceSignSpecAction::class)->execute($spec, [
        'quantities' => ['led' => 10.0],
        'markup_percent' => 100.0,
    ]);
    $line = $quotation->lines[0];

    expect($line->priceListItemCode)->toBe('FN-2016A-182-24-35K') // matched by 3500K
        ->and($line->quantity)->toBe(10.0)
        ->and($line->lineTotal)->toBe(36.5)
        ->and($quotation->totalCost)->toBe(36.5)
        ->and($quotation->totalSell)->toBe(73.0);
});

it('estimates milling metres from the perimeter when a rule exists', function (): void {
    seedSignagePriceList();
    seedSignageRules();

    $spec = SignSpec::factory()->create(['width_mm' => 2000, 'height_mm' => 1000, 'quantity' => 1]);
    $spec->components()->create([
        'position' => 0,
        'role_code' => 'cutting',
        'label' => 'Vector cut path',
        'source_code' => 'layer',
    ]);

    $line = app(PriceSignSpecAction::class)->execute($spec)->lines[0];

    expect($line->category)->toBe('machining')
        ->and($line->unit)->toBe('mt')
        ->and($line->quantity)->toBe(6.0)   // 2×(2+1) m × 1 piece
        ->and($line->needsInput)->toBeFalse();
});

it('flags lines that need a manual quantity when geometry is missing', function (): void {
    seedSignagePriceList();
    seedSignageRules();

    // No width/height => the perimeter rule cannot compute.
    $spec = SignSpec::factory()->create(['width_mm' => null, 'height_mm' => null]);
    $spec->components()->create([
        'position' => 0,
        'role_code' => 'cutting',
        'label' => 'Vector cut path',
        'source_code' => 'layer',
    ]);

    $quotation = app(PriceSignSpecAction::class)->execute($spec);
    $line = $quotation->lines[0];

    expect($line->quantity)->toBeNull()
        ->and($line->needsInput)->toBeTrue()
        ->and($line->note)->toContain('Quantità da inserire')
        ->and($quotation->hasMissing)->toBeTrue();
});

it('does not cost drawing-helper roles like holes/text/dimension', function (): void {
    seedSignagePriceList();

    $spec = SignSpec::factory()->create();
    foreach (['holes', 'text', 'dimension'] as $i => $role) {
        $spec->components()->create([
            'position' => $i,
            'role_code' => $role,
            'label' => ucfirst($role),
            'source_code' => 'layer',
        ]);
    }

    $quotation = app(PriceSignSpecAction::class)->execute($spec);

    expect($quotation->lines)->toBeEmpty()
        ->and($quotation->totalCost)->toBe(0.0);
});

it('persists prices back onto the components when asked', function (): void {
    seedSignagePriceList();
    seedSignageRules();

    $spec = SignSpec::factory()->create(['width_mm' => 1000, 'height_mm' => 500, 'quantity' => 2]);
    $component = $spec->components()->create([
        'position' => 0,
        'role_code' => 'body',
        'label' => 'Body',
        'material' => 'OTTONE',
        'source_code' => 'callout',
    ]);

    app(PriceSignSpecAction::class)->execute($spec, ['persist' => true]);

    /** @var \Modules\Signage\Models\SignComponent $fresh */
    $fresh = $component->fresh();
    expect($fresh->price_list_item_id)->not->toBeNull()
        ->and((float) $fresh->quantity)->toBe(1.0)     // 1m × 0.5m × 2 pieces
        ->and((float) $fresh->line_total)->toBe(93.16);
});
