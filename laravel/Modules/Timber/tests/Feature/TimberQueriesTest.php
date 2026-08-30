<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Modules\Timber\Actions\RegisterTimberProcessingAction;
use Modules\Timber\Actions\StartTimberDryingCycleAction;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberLot;
use Modules\Timber\Models\TimberPackagedUnit;
use Modules\Timber\Models\TimberProcessingStep;
use Modules\Timber\Models\TimberPurchaseRecord;
use Modules\Timber\Models\TimberSupplier;
use Modules\Timber\Queries\GetTimberDashboardChartsQuery;
use Modules\Timber\Queries\GetTimberDashboardStatsQuery;
use Modules\Timber\Queries\GetTimberDryingOverviewQuery;
use Modules\Timber\Queries\GetTimberInventoryOverviewQuery;
use Modules\Timber\Queries\GetTimberYardOverviewQuery;
use Modules\Timber\Queries\SearchTimberTraceabilityQuery;
use Modules\Timber\Tests\TestCase;

uses(\Modules\Timber\Tests\TestCase::class);

it('filters the yard overview by area and search term', function (): void {
    TimberLot::factory()->createOne(['lot_code' => 'LOT-YARD-A', 'wood_species' => 'abete', 'current_area_code' => 'piazzale', 'status_code' => 'available']);
    TimberLot::factory()->createOne(['lot_code' => 'LOT-YARD-B', 'wood_species' => 'faggio', 'current_area_code' => 'zona_taglio', 'status_code' => 'available']);

    $overview = app(GetTimberYardOverviewQuery::class)->execute('piazzale', null);
    $firstLot = $overview['lots']->first();
    expect($firstLot)->not->toBeNull();
    assert($firstLot instanceof TimberLot);

    expect($overview['lots'])->toHaveCount(1)
        ->and($firstLot->lot_code)->toBe('LOT-YARD-A');

    $searched = app(GetTimberYardOverviewQuery::class)->execute(null, 'YARD-B');
    $searchedLot = $searched['lots']->first();
    expect($searchedLot)->not->toBeNull();
    assert($searchedLot instanceof TimberLot);

    expect($searched['lots'])->toHaveCount(1)
        ->and($searchedLot->lot_code)->toBe('LOT-YARD-B');
});

it('returns only drying cycles matching the requested status', function (): void {
    $lot = TimberLot::factory()->createOne(['quantity_available' => 100]);
    app(StartTimberDryingCycleAction::class)->execute($lot, null, null, null);

    $cycles = app(GetTimberDryingOverviewQuery::class)->execute('in_progress');
    expect($cycles)->toHaveCount(1);

    $completedCycles = app(GetTimberDryingOverviewQuery::class)->execute('completed');
    expect($completedCycles)->toHaveCount(0);
});

it('computes inventory overview stats per status', function (): void {
    TimberLot::factory()->createOne(['status_code' => 'available']);
    TimberDerivedMaterial::factory()->createOne(['status_code' => 'da_lavorare', 'is_waste' => false, 'quantity' => 10]);
    TimberDerivedMaterial::factory()->createOne(['status_code' => 'pronto_per_vendita', 'is_waste' => false, 'quantity' => 10]);
    TimberDerivedMaterial::factory()->createOne(['status_code' => 'scarto', 'is_waste' => true, 'quantity' => 50]);

    $overview = app(GetTimberInventoryOverviewQuery::class)->execute();

    expect($overview['stats']['lots_available'])->toBe(1)
        ->and($overview['stats']['materials_to_process'])->toBe(1)
        ->and($overview['stats']['materials_ready_to_sell'])->toBe(1)
        ->and((float) $overview['stats']['total_waste_quantity'])->toBe(50.0);
});

it('computes dashboard stats including yield percent and stuck lots', function (): void {
    TimberPurchaseRecord::factory()->createOne(['received_at' => now()]);

    $stuckLot = TimberLot::factory()->createOne(['status_code' => 'available']);
    DB::table('timber_lots')->where('id', $stuckLot->id)->update(['updated_at' => now()->subDays(60)]);

    $freshLot = TimberLot::factory()->createOne(['status_code' => 'available']);

    $step = TimberProcessingStep::factory()->createOne(['input_quantity' => 100, 'output_quantity' => 80]);
    TimberDerivedMaterial::factory()->createOne(['processing_step_id' => $step->id, 'is_waste' => true, 'quantity' => 20]);

    $stats = app(GetTimberDashboardStatsQuery::class)->execute();

    expect($stats['purchases_today'])->toBeGreaterThanOrEqual(1)
        ->and($stats['lots_stuck'])->toBeGreaterThanOrEqual(1)
        ->and($stats['yield_percent'])->toBe(80.0)
        ->and((float) $stats['total_waste_quantity'])->toBeGreaterThanOrEqual(20.0);

    expect($freshLot)->not->toBeNull();
});

it('reports a null yield percent when no processing has been recorded', function (): void {
    $stats = app(GetTimberDashboardStatsQuery::class)->execute();

    expect($stats['yield_percent'])->toBeNull();
});

it('searches traceability by lot code and reconstructs the chain', function (): void {
    $lot = TimberLot::factory()->createOne(['lot_code' => 'LOT-TRACE-01', 'status_code' => 'available']);

    $step = app(RegisterTimberProcessingAction::class)->execute(
        selectedLotIds: [$lot->id],
        selectedDerivedMaterialIds: [],
        stepTypeCode: 'taglio',
        performedByUserId: null,
        recoverableQuantity: null,
        unitOfMeasure: 'kg',
        notes: null,
        outputDefinitions: [['wood_species' => 'abete', 'quantity' => 50, 'is_waste' => false]],
    );

    $result = app(SearchTimberTraceabilityQuery::class)->execute('LOT-TRACE-01');
    $firstStep = $result['processingSteps']->first();
    expect($firstStep)->not->toBeNull();
    assert($firstStep instanceof TimberProcessingStep);

    expect($result['lot']?->lot_code)->toBe('LOT-TRACE-01')
        ->and($result['processingSteps'])->toHaveCount(1)
        ->and($firstStep->id)->toBe($step->id);
});

it('searches traceability by packaged unit code', function (): void {
    $unit = TimberPackagedUnit::factory()->createOne(['package_code' => 'PKG-TRACE-01']);

    $result = app(SearchTimberTraceabilityQuery::class)->execute('PKG-TRACE-01');

    expect($result['packagedUnit']?->package_code)->toBe('PKG-TRACE-01')
        ->and($result['derivedMaterial'])->not->toBeNull();
});

it('returns empty results for an unknown traceability search term', function (): void {
    $result = app(SearchTimberTraceabilityQuery::class)->execute('DOES-NOT-EXIST');

    expect($result['purchaseRecord'])->toBeNull()
        ->and($result['lot'])->toBeNull()
        ->and($result['processingSteps'])->toHaveCount(0);
});

it('groups derived material quantity by status and lot quantity by wood species', function (): void {
    TimberDerivedMaterial::factory()->createOne(['status_code' => 'da_lavorare', 'quantity' => 100, 'is_waste' => false]);
    TimberDerivedMaterial::factory()->createOne(['status_code' => 'da_lavorare', 'quantity' => 50, 'is_waste' => false]);
    TimberDerivedMaterial::factory()->createOne(['status_code' => 'pronto_per_vendita', 'quantity' => 30, 'is_waste' => false]);
    TimberDerivedMaterial::factory()->createOne(['status_code' => 'scarto', 'quantity' => 0, 'is_waste' => true]);

    TimberLot::factory()->createOne(['status_code' => 'available', 'wood_species' => 'abete', 'quantity_available' => 200]);
    TimberLot::factory()->createOne(['status_code' => 'available', 'wood_species' => 'abete', 'quantity_available' => 100]);
    TimberLot::factory()->createOne(['status_code' => 'available', 'wood_species' => 'faggio', 'quantity_available' => 40]);
    TimberLot::factory()->createOne(['status_code' => 'esaurito', 'wood_species' => 'quercia', 'quantity_available' => 0]);

    $charts = app(GetTimberDashboardChartsQuery::class)->execute();

    expect($charts['materialsByStatus'])->toBe([
        'da_lavorare' => 150.0,
        'pronto_per_vendita' => 30.0,
    ])->and($charts['stockBySpecies'])->toBe([
        'abete' => 300.0,
        'faggio' => 40.0,
    ]);
});

it('builds an 8 week purchases trend bucketing quantity by week', function (): void {
    TimberPurchaseRecord::factory()->createOne(['quantity' => 400, 'received_at' => now()]);
    TimberPurchaseRecord::factory()->createOne(['quantity' => 100, 'received_at' => now()]);
    TimberPurchaseRecord::factory()->createOne(['quantity' => 999, 'received_at' => now()->subWeeks(20)]);

    $charts = app(GetTimberDashboardChartsQuery::class)->execute();

    expect($charts['purchasesTrend'])->toHaveCount(8);

    $currentWeek = end($charts['purchasesTrend']);
    expect($currentWeek)->toBeArray();
    assert(is_array($currentWeek));
    expect($currentWeek['quantity'])->toBe(500.0);

    $totalTracked = array_sum(array_column($charts['purchasesTrend'], 'quantity'));
    expect($totalTracked)->toBe(500.0);
});

it('ranks the top 5 suppliers by total purchased quantity', function (): void {
    $topSupplier = TimberSupplier::factory()->createOne(['legal_name' => 'Fornitore Top']);
    $secondSupplier = TimberSupplier::factory()->createOne(['legal_name' => 'Fornitore Secondo']);

    TimberPurchaseRecord::factory()->createOne(['supplier_id' => $topSupplier->id, 'quantity' => 1000]);
    TimberPurchaseRecord::factory()->createOne(['supplier_id' => $topSupplier->id, 'quantity' => 500]);
    TimberPurchaseRecord::factory()->createOne(['supplier_id' => $secondSupplier->id, 'quantity' => 300]);

    $charts = app(GetTimberDashboardChartsQuery::class)->execute();

    expect($charts['topSuppliers'])->toHaveCount(2)
        ->and($charts['topSuppliers'][0])->toBe(['supplierName' => 'Fornitore Top', 'quantity' => 1500.0])
        ->and($charts['topSuppliers'][1])->toBe(['supplierName' => 'Fornitore Secondo', 'quantity' => 300.0]);
});
