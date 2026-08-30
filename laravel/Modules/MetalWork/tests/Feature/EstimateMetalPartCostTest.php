<?php

declare(strict_types=1);

uses(\Modules\MetalWork\Tests\TestCase::class);

use Modules\Costing\Models\WorkCenter;
use Modules\MetalWork\Actions\EstimateMetalPartCostAction;
use Modules\MetalWork\Database\Seeders\MetalWorkCenterSeeder;
use Modules\MetalWork\Datas\StepGeometry;
use Modules\MetalWork\Models\MetalRawMaterial;
use Modules\MetalWork\Support\MetalWorksCosting;
use Modules\MetalWork\Tests\TestCase;

function metalWorksGeometry(array $overrides = []): StepGeometry
{
    return new StepGeometry(
        partCode: $overrides['partCode'] ?? '358447106-1',
        name: $overrides['name'] ?? 'VASCA SCHIUMATRICE',
        sourceFile: '358447106-1_stp1.stp',
        cadSystem: 'NX',
        lengthUnitCode: 'mm',
        lengthMm: $overrides['lengthMm'] ?? 1000.0,
        widthMm: $overrides['widthMm'] ?? 1000.0,
        thicknessMm: $overrides['thicknessMm'] ?? 10.0,
        pointCount: 121,
        faceCount: 22,
        planeCount: $overrides['planeCount'] ?? 6,
        cylinderCount: $overrides['cylinderCount'] ?? 0,
        solidCount: $overrides['solidCount'] ?? 1,
        isAssembly: false,
    );
}

beforeEach(function (): void {
    (new MetalWorkCenterSeeder())->run();
});

it('weighs a flat part from footprint x thickness', function (): void {
    // A square metre of 10 mm steel weighs 78,5 kg: footprint x thickness x 7,85.
    $costing = app(EstimateMetalPartCostAction::class)->execute(
        metalWorksGeometry(),
        metalWorksMaterial(),
        ['scrap_percent' => 0],
    );

    expect($costing->netWeightKg)->toBe(78.5)
        ->and($costing->grossWeightKg)->toBe(78.5)
        ->and($costing->warnings)->not->toContain('occupancy_estimated');
});

it('adds the offcut allowance on top of the net weight', function (): void {
    $costing = app(EstimateMetalPartCostAction::class)->execute(
        metalWorksGeometry(),
        metalWorksMaterial(),
        ['scrap_percent' => 10],
    );

    expect($costing->grossWeightKg)->toBe(86.35);
});

it('derives a solid part from the bounding box and says so', function (): void {
    // 1 m x 1 m x 100 mm = 100 dm3, at 50% occupancy and 7,85 kg/dm3.
    $costing = app(EstimateMetalPartCostAction::class)->execute(
        metalWorksGeometry(['thicknessMm' => 100.0]),
        metalWorksMaterial(),
        ['occupancy_percent' => 50, 'scrap_percent' => 0],
    );

    expect($costing->netWeightKg)->toBe(392.5)
        ->and($costing->warnings)->toContain('occupancy_estimated');
});

it('costs the material in the unit the supplier prices it in', function (): void {
    $costing = app(EstimateMetalPartCostAction::class)->execute(
        metalWorksGeometry(),
        metalWorksMaterial('ml', 5.0),
        ['scrap_percent' => 0],
    );

    $material = collect($costing->lines)->firstWhere('workCenterCode', 'material');

    // A metre-priced profile is consumed by length, not by weight.
    expect($material->unitCode)->toBe('ml')
        ->and($material->quantity)->toBe(1.0)
        ->and($material->lineCost)->toBe(5.0)
        ->and($material->driverCode)->toBe('bbox_length');
});

it('warns instead of quoting a material that was never bought', function (): void {
    $material = MetalRawMaterial::factory()->create();

    $costing = app(EstimateMetalPartCostAction::class)->execute(metalWorksGeometry(), $material);

    expect($costing->warnings)->toContain('no_material_price')
        ->and(collect($costing->lines)->firstWhere('workCenterCode', 'material'))->toBeNull();
});

it('warns when no material was chosen at all', function (): void {
    $costing = app(EstimateMetalPartCostAction::class)->execute(metalWorksGeometry());

    expect($costing->warnings)->toContain('no_material_selected');
});

it('bills bending only on flat parts, and only for the folds beyond a blank', function (): void {
    $flat = app(EstimateMetalPartCostAction::class)->execute(
        metalWorksGeometry(['planeCount' => 12]),
        metalWorksMaterial(),
    );
    $solid = app(EstimateMetalPartCostAction::class)->execute(
        metalWorksGeometry(['thicknessMm' => 100.0, 'planeCount' => 12]),
        metalWorksMaterial(),
    );

    expect(collect($flat->lines)->firstWhere('workCenterCode', 'bending'))->not->toBeNull()
        ->and(collect($solid->lines)->firstWhere('workCenterCode', 'bending'))->toBeNull();
});

it('bills welding only when the part is made of more than one solid', function (): void {
    $single = app(EstimateMetalPartCostAction::class)->execute(metalWorksGeometry(), metalWorksMaterial());
    $many = app(EstimateMetalPartCostAction::class)->execute(
        metalWorksGeometry(['solidCount' => 4]),
        metalWorksMaterial(),
    );

    expect(collect($single->lines)->firstWhere('workCenterCode', 'welding'))->toBeNull()
        ->and(collect($many->lines)->firstWhere('workCenterCode', 'welding'))->not->toBeNull();
});

it('lets an operator override the minutes of one work centre', function (): void {
    $costing = app(EstimateMetalPartCostAction::class)->execute(
        metalWorksGeometry(),
        metalWorksMaterial(),
        ['operations' => ['welding' => 120]],
    );

    $welding = collect($costing->lines)->firstWhere('workCenterCode', 'welding');

    // 120 minutes at the seeded 50 €/h.
    expect($welding)->not->toBeNull()
        ->and($welding->quantity)->toBe(2.0)
        ->and($welding->lineCost)->toBe(100.0)
        ->and($welding->driverCode)->toBe('manual');
});

it('resolves the markup most specific first', function (): void {
    WorkCenter::query()->forContext(MetalWorksCosting::CONTEXT)->where('code', 'material')->update(['default_markup_percent' => 40]);

    $fromWorkCenter = app(EstimateMetalPartCostAction::class)->execute(
        metalWorksGeometry(),
        metalWorksMaterial(),
    );
    $fromEstimate = app(EstimateMetalPartCostAction::class)->execute(
        metalWorksGeometry(),
        metalWorksMaterial(),
        ['markup_percent' => 5],
    );

    expect(collect($fromWorkCenter->lines)->firstWhere('workCenterCode', 'material')->markupSourceCode)->toBe('work_center')
        ->and(collect($fromWorkCenter->lines)->firstWhere('workCenterCode', 'material')->markupPercent)->toBe(40.0)
        ->and(collect($fromEstimate->lines)->firstWhere('workCenterCode', 'material')->markupSourceCode)->toBe('estimate')
        ->and(collect($fromEstimate->lines)->firstWhere('workCenterCode', 'material')->markupPercent)->toBe(5.0);
});

it('falls back to the configured markup when nothing else says otherwise', function (): void {
    WorkCenter::query()->forContext(MetalWorksCosting::CONTEXT)->update(['default_markup_percent' => null]);
    config(['metalworks.default_markup_percent' => 20]);

    $costing = app(EstimateMetalPartCostAction::class)->execute(metalWorksGeometry(), metalWorksMaterial());
    $material = collect($costing->lines)->firstWhere('workCenterCode', 'material');

    expect($material->markupSourceCode)->toBe('config')
        ->and($material->markupPercent)->toBe(20.0);
});

it('lets a hand-written price win over the computed one', function (): void {
    $costing = app(EstimateMetalPartCostAction::class)->execute(
        metalWorksGeometry(),
        metalWorksMaterial(),
        ['manual_sale_price' => 500, 'quantity' => 3],
    );

    expect($costing->isManualPrice)->toBeTrue()
        ->and($costing->unitSalePrice)->toBe(500.0)
        ->and($costing->totalSalePrice())->toBe(1500.0)
        ->and($costing->margin())->toBe(round(500.0 - $costing->unitCost, 2));
});

it('reports a price per kilo, which is how a steel quotation is sanity-checked', function (): void {
    $costing = app(EstimateMetalPartCostAction::class)->execute(
        metalWorksGeometry(),
        metalWorksMaterial(),
        ['manual_sale_price' => 157, 'scrap_percent' => 0],
    );

    expect($costing->pricePerKg())->toBe(2.0);
});

it('produces nothing but warnings when the file had no geometry', function (): void {
    $geometry = new StepGeometry(
        partCode: 'X', name: 'X', sourceFile: 'x.stp', cadSystem: null, lengthUnitCode: 'mm',
        lengthMm: null, widthMm: null, thicknessMm: null,
        pointCount: 0, faceCount: 0, planeCount: 0, cylinderCount: 0, solidCount: 0, isAssembly: false,
    );

    $costing = app(EstimateMetalPartCostAction::class)->execute($geometry, metalWorksMaterial());

    expect($costing->warnings)->toContain('no_bounding_box')
        ->and($costing->warnings)->toContain('no_operations_derived')
        ->and($costing->netWeightKg)->toBe(0.0);
});
