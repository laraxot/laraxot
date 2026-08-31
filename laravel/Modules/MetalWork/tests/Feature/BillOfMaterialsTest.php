<?php

declare(strict_types=1);

uses(\Modules\MetalWork\Tests\TestCase::class);

use Modules\MetalWork\Actions\BuildDrawingBillOfMaterialsAction;
use Modules\MetalWork\Actions\EstimateFromBillOfMaterialsAction;
use Modules\MetalWork\Actions\ImportStepDrawingAction;
use Modules\MetalWork\Actions\MatchRawMaterialToSolidAction;
use Modules\MetalWork\Database\Seeders\MetalWorkCenterSeeder;
use Modules\MetalWork\Datas\StepSolid;
use Modules\MetalWork\Support\StepSolidExtractor;
use Modules\MetalWork\Tests\TestCase;
use Modules\MetalWork\Datas\MetalCostLine;
use Modules\MetalWork\Models\MetalDrawing;
use Modules\MetalWork\Models\MetalDrawingComponent;
use PHPUnit\Framework\Assert;

it('measures every solid of a drawing separately', function (): void {
    $solids = (new StepSolidExtractor())->extract(metalWorksFixture('353271328-0_stp1.stp'));

    // The drawing holds one long profile and four identical small plates.
    expect($solids)->toHaveCount(5);

    $signatures = array_count_values(array_map(
        static fn (StepSolid $solid): string => $solid->signature(),
        $solids,
    ));

    expect($signatures)->toHaveCount(2);
    $quantities = array_values($signatures);
    $maxQty = 0;
    foreach ($quantities as $qty) {
        $maxQty = max($maxQty, $qty);
    }
    expect($maxQty)->toBe(4);
});

it('groups identical solids into one row carrying a quantity', function (): void {
    $drawing = app(ImportStepDrawingAction::class)->execute(metalWorksFixture('353271328-0_stp1.stp'));

    $components = $drawing->components;

    expect($components)->toHaveCount(2)
        ->and($components->sum('quantity'))->toBe(5);
    $firstComponent = $components->first();
    Assert::assertInstanceOf(MetalDrawingComponent::class, $firstComponent);
    // Heaviest first: the 3,2-metre profile leads.
    expect((float) $firstComponent->length_mm)->toBeGreaterThan(3000.0);
});

it('keeps a confirmed material when the same drawing is imported again', function (): void {
    $material = metalWorksMaterial('kg', 0.87, 'FP60X8', 'flat');
    $path = metalWorksFixture('353271328-0_stp1.stp');

    $drawing = app(ImportStepDrawingAction::class)->execute($path);
    $component = $drawing->components()->first();
    Assert::assertInstanceOf(MetalDrawingComponent::class, $component);
    $component->forceFill(['raw_material_id' => $material->id, 'is_material_confirmed' => true])->save();

    $freshDrawing = $drawing->fresh();
    Assert::assertInstanceOf(MetalDrawing::class, $freshDrawing);
    app(BuildDrawingBillOfMaterialsAction::class)->execute($freshDrawing, $path);

    $reimported = $drawing->fresh()?->components()->where('is_material_confirmed', true)->first();
    Assert::assertInstanceOf(MetalDrawingComponent::class, $reimported);
    expect($reimported->raw_material_id)->toBe($material->id);
});

it('never proposes stock narrower than the piece it has to yield', function (): void {
    // A 30 mm wide flat bar cannot yield a plate 374 mm across, however thick it
    // is: reading the leading number of FP30X15 as a thickness is what used to
    // make that match look perfect.
    $narrow = metalWorksMaterial('kg', 0.90, 'FP30X15', 'flat');
    $plate = new StepSolid(
        lengthMm: 500.0,
        widthMm: 374.0,
        thicknessMm: 30.0,
        vertexCount: 8,
        faceCount: 6,
        planeCount: 6,
        cylinderCount: 0,
    );

    $match = app(MatchRawMaterialToSolidAction::class)->execute($plate);

    expect($match?->id)->not->toBe($narrow->id);
});

it('proposes an oxy-cut blank for a plate too thick to come off a coil', function (): void {
    metalWorksMaterial('kg', 0.95, 'LN2H1000', 'sheet');
    $oxyCut = metalWorksMaterial('kg', 1.45, 'OSSITAGLIO', 'oxy_cut');

    $thickPlate = new StepSolid(
        lengthMm: 500.0,
        widthMm: 374.0,
        thicknessMm: 30.0,
        vertexCount: 8,
        faceCount: 6,
        planeCount: 6,
        cylinderCount: 0,
    );

    expect(app(MatchRawMaterialToSolidAction::class)->execute($thickPlate)?->id)->toBe($oxyCut->id);
});

it('costs a drawing row by row from its bill of materials', function (): void {
    (new MetalWorkCenterSeeder())->run();
    metalWorksMaterial('kg', 1.45, 'OSSITAGLIO', 'oxy_cut');
    metalWorksMaterial('kg', 0.98, 'FL100X75X8', 'angle');

    $drawing = app(ImportStepDrawingAction::class)->execute(metalWorksFixture('353271328-0_stp1.stp'));
    $costing = app(EstimateFromBillOfMaterialsAction::class)->execute($drawing);

    $materialLines = array_filter(
        $costing->lines,
        static fn (object $line): bool => $line->workCenterCode === 'material',
    );

    expect($materialLines)->toHaveCount($drawing->components()->count())
        ->and($costing->unitCost)->toBeGreaterThan(0.0)
        ->and($costing->netWeightKg)->toBeGreaterThan(0.0)
        // Machining is derived from the whole set, not from one lump.
        ->and(count($costing->lines))->toBeGreaterThan(count($materialLines));
});

it('warns instead of quoting a bill-of-materials row that has no material', function (): void {
    (new MetalWorkCenterSeeder())->run();

    $drawing = app(ImportStepDrawingAction::class)->execute(metalWorksFixture('353271328-0_stp1.stp'));
    $costing = app(EstimateFromBillOfMaterialsAction::class)->execute($drawing);

    expect($costing->warnings)->toContain('component_without_material');
});

it('prices a profile by the metre and a plate by the kilo', function (): void {
    (new MetalWorkCenterSeeder())->run();
    $tube = metalWorksMaterial('ml', 27.70, 'TQ150XX5', 'tube');

    $drawing = app(ImportStepDrawingAction::class)->execute(metalWorksFixture('353271328-0_stp1.stp'));
    $drawing->components()->update(['raw_material_id' => $tube->id, 'is_material_confirmed' => true]);

    $fresh = $drawing->fresh();
    Assert::assertInstanceOf(MetalDrawing::class, $fresh);
    $costing = app(EstimateFromBillOfMaterialsAction::class)->execute($fresh);
    $materialLine = collect($costing->lines)->firstWhere('workCenterCode', 'material');
    Assert::assertInstanceOf(MetalCostLine::class, $materialLine);

    expect($materialLine->unitCode)->toBe('ml')
        ->and($materialLine->driverCode)->toBe('component_length');
});
