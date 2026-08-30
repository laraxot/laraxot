<?php

declare(strict_types=1);

use Livewire\Component;
use Modules\Timber\Actions\RegisterTimberPurchaseAction;
use Modules\Timber\Filament\Pages\TimberCuttingPage;
use Modules\Timber\Filament\Pages\TimberDashboard;
use Modules\Timber\Filament\Pages\TimberDryingPage;
use Modules\Timber\Filament\Pages\TimberInventoryPage;
use Modules\Timber\Filament\Pages\TimberLabelsPage;
use Modules\Timber\Filament\Pages\TimberLotDetailPage;
use Modules\Timber\Filament\Pages\TimberOutputsPage;
use Modules\Timber\Filament\Pages\TimberPackagingPage;
use Modules\Timber\Filament\Pages\TimberProcessingPage;
use Modules\Timber\Filament\Pages\TimberPurchasesPage;
use Modules\Timber\Filament\Pages\TimberSelectionPage;
use Modules\Timber\Filament\Pages\TimberSplittingPage;
use Modules\Timber\Filament\Pages\TimberTraceabilityPage;
use Modules\Timber\Filament\Pages\TimberYardPage;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberDryingCycle;
use Modules\Timber\Models\TimberLot;
use Modules\Timber\Models\TimberPackagedUnit;
use Modules\Timber\Models\TimberProcessingStep;
use Modules\Timber\Models\TimberPurchaseRecord;
use Modules\Timber\Models\TimberSupplier;
use Modules\Timber\Models\TimberWoodSpecies;
use Modules\Timber\Tests\TestCase;

uses(\Modules\Timber\Tests\TestCase::class);

beforeEach(function (): void {
    filament()->setCurrentPanel('timber::admin');
    actingAsTimberOperator();
});

it('exposes the wood species name, not the stored code, on stock models', function (): void {
    TimberWoodSpecies::query()->delete();
    TimberWoodSpecies::factory()->createOne(['code' => 'ciliegio', 'name' => 'Ciliegio selvatico', 'is_active' => true]);

    $lot = TimberLot::factory()->createOne(['wood_species' => 'ciliegio']);
    $material = TimberDerivedMaterial::factory()->createOne(['wood_species' => 'ciliegio']);

    expect($lot->wood_species_name)->toBe('Ciliegio selvatico')
        ->and($material->wood_species_name)->toBe('Ciliegio selvatico');

    // An unknown/legacy code still renders readably (capitalized code).
    expect(TimberLot::factory()->createOne(['wood_species' => 'zzz_legacy'])->wood_species_name)->toBe('Zzz_legacy');
});

it('registers a purchase from the purchases page', function (): void {
    $supplier = TimberSupplier::factory()->createOne();

    testTimberLivewire(TimberPurchasesPage::class)
        ->set('supplierId', $supplier->id)
        ->set('woodSpecies', 'abete')
        ->set('quantity', '1200')
        ->set('unitOfMeasure', 'kg')
        ->call('registerPurchase')
        ->assertHasNoErrors();

    expect(TimberPurchaseRecord::query()->where('supplier_id', $supplier->id)->exists())->toBeTrue();
});

it('defaults the purchase species to the first available catalog option instead of a hardcoded value', function (): void {
    TimberWoodSpecies::query()->delete();
    TimberWoodSpecies::factory()->createOne(['code' => 'ciliegio', 'name' => 'Ciliegio', 'sort_order' => 1]);
    TimberWoodSpecies::factory()->createOne(['code' => 'noce', 'name' => 'Noce', 'sort_order' => 2]);

    testTimberLivewire(TimberPurchasesPage::class)
        ->assertSet('woodSpecies', 'ciliegio');
});

it('shows a warning when registering a purchase without a supplier', function (): void {
    testTimberLivewire(TimberPurchasesPage::class)
        ->set('quantity', '100')
        ->call('registerPurchase')
        ->assertHasNoErrors();

    expect(TimberPurchaseRecord::query()->count())->toBe(0);
});

it('moves a lot from the lot detail page', function (): void {
    $record = app(RegisterTimberPurchaseAction::class)->execute(
        supplier: TimberSupplier::factory()->createOne(),
        transporter: null,
        originForest: null,
        ddtNumber: null,
        ddtDate: null,
        woodSpecies: 'abete',
        quantity: 500,
        unitOfMeasure: 'kg',
        grossWeight: null,
        netWeight: null,
        unitPurchasePrice: null,
        certifications: null,
        photoPath: null,
        notes: null,
        userId: null,
    );

    $lot = $record->lot;
    expect($lot)->not->toBeNull();
    assert($lot instanceof TimberLot);

    testTimberLivewireWithQuery(TimberLotDetailPage::class, ['lot' => $lot->id])
        ->set('moveToArea', 'zona_taglio')
        ->call('moveLot')
        ->assertHasNoErrors();

    expect($lot->fresh()?->current_area_code)->toBe('zona_taglio');
});

it('prints the incoming lot label from the lot detail page', function (): void {
    $lot = TimberLot::factory()->createOne(['lot_code' => 'LOT-2026-0500']);

    testTimberLivewireWithQuery(TimberLotDetailPage::class, ['lot' => $lot->id])
        ->call('printLotLabel')
        ->assertHasNoErrors();

    expect($lot->labelPrints()->where('label_code', 'LOT-2026-0500')->where('print_reason_code', 'reprint')->exists())->toBeTrue();
});

it('moves lots and materials from the yard page', function (): void {
    $lot = TimberLot::factory()->createOne(['current_area_code' => 'piazzale']);
    $material = TimberDerivedMaterial::factory()->createOne(['current_area_code' => 'zona_taglio', 'is_waste' => false]);

    testTimberLivewire(TimberYardPage::class)
        ->call('moveLot', $lot->id, 'zona_taglio')
        ->call('moveMaterial', $material->id, 'deposito_semilavorati')
        ->assertHasNoErrors();

    expect($lot->fresh()?->current_area_code)->toBe('zona_taglio')
        ->and($material->fresh()?->current_area_code)->toBe('deposito_semilavorati');
});

/**
 * @param  class-string<Component>  $pageClass
 */
function assertTimberProcessingPageRegisters(string $pageClass, string $expectedStepType): void
{
    $lot = TimberLot::factory()->createOne(['status_code' => 'available', 'quantity_available' => 300]);

    $component = testTimberLivewireWithQuery($pageClass, ['lot' => $lot->id]);

    if ($pageClass === TimberProcessingPage::class) {
        $component->set('stepTypeCode', $expectedStepType);
    }

    $component
        ->set('outputQuantities.0', '250')
        ->call('registerProcessing')
        ->assertHasNoErrors();

    expect(TimberProcessingStep::query()->where('step_type_code', $expectedStepType)->exists())->toBeTrue()
        ->and($lot->fresh()?->status_code)->toBe('esaurito');

    // The output species is inherited from the processed lot, not picked by hand.
    expect(TimberDerivedMaterial::query()->where('wood_species', $lot->wood_species)->where('quantity', 250)->exists())->toBeTrue();
}

it('registers processing from the dispatcher page', function (): void {
    assertTimberProcessingPageRegisters(TimberProcessingPage::class, 'taglio');
});

it('registers processing from the cutting page', function (): void {
    assertTimberProcessingPageRegisters(TimberCuttingPage::class, 'taglio');
});

it('registers processing from the splitting page', function (): void {
    assertTimberProcessingPageRegisters(TimberSplittingPage::class, 'spaccatura');
});

it('registers processing from the selection page', function (): void {
    assertTimberProcessingPageRegisters(TimberSelectionPage::class, 'selezione');
});

it('generates packaged units from the packaging page', function (): void {
    $material = TimberDerivedMaterial::factory()->createOne([
        'quantity' => 400,
        'is_waste' => false,
        'status_code' => 'pronto_da_confezionare',
    ]);

    testTimberLivewireWithQuery(TimberPackagingPage::class, ['material' => $material->id])
        ->set('unitsCount', 2)
        ->set('quantityPerUnit', '200')
        ->call('generatePackagedUnits')
        ->assertHasNoErrors();

    expect(TimberPackagedUnit::query()->where('derived_material_id', $material->id)->count())->toBe(2);
});

it('runs the full drying cycle flow from the drying page', function (): void {
    $lot = TimberLot::factory()->createOne(['status_code' => 'available', 'quantity_available' => 100]);

    testTimberLivewire(TimberDryingPage::class)
        ->set('sourceType', 'lot')
        ->set('sourceId', $lot->id)
        ->call('startDrying')
        ->assertHasNoErrors();

    $cycle = TimberDryingCycle::query()->where('dryable_type', TimberLot::class)->where('dryable_id', $lot->id)->firstOrFail();

    testTimberLivewire(TimberDryingPage::class)
        ->set('humidityPercent', '25')
        ->call('recordHumidity', $cycle->id)
        ->assertHasNoErrors();

    expect($cycle->humidityChecks()->count())->toBe(1);

    testTimberLivewire(TimberDryingPage::class)
        ->call('completeDrying', $cycle->id)
        ->assertHasNoErrors();

    expect($cycle->fresh()?->status_code)->toBe('completed');
});

it('preselects a derived material on the drying page when deep-linked from the outputs page', function (): void {
    $material = TimberDerivedMaterial::factory()->createOne(['is_waste' => false, 'quantity' => 120]);

    testTimberLivewireWithQuery(TimberDryingPage::class, ['material' => $material->id])
        ->assertSet('sourceType', 'material')
        ->assertSet('sourceId', $material->id);
});

it('ignores the drying deep-link for a waste or depleted derived material', function (): void {
    $waste = TimberDerivedMaterial::factory()->createOne(['is_waste' => true, 'quantity' => 120]);

    testTimberLivewireWithQuery(TimberDryingPage::class, ['material' => $waste->id])
        ->assertSet('sourceType', 'lot')
        ->assertSet('sourceId', null);
});

it('renders the outputs page with materials and packaged units', function (): void {
    TimberDerivedMaterial::factory()->createOne();
    TimberPackagedUnit::factory()->createOne();

    testTimberLivewire(TimberOutputsPage::class)
        ->assertOk();
});

it('downloads a label pdf for a lot, a material and a packaged unit', function (): void {
    $lot = TimberLot::factory()->createOne();
    $material = TimberDerivedMaterial::factory()->createOne();
    $packagedUnit = TimberPackagedUnit::factory()->createOne();

    testTimberLivewire(TimberLabelsPage::class)
        ->call('downloadLotLabel', $lot->id)
        ->call('downloadMaterialLabel', $material->id)
        ->call('downloadPackagedUnitLabel', $packagedUnit->id)
        ->assertHasNoErrors();
});

it('searches and downloads traceability from the traceability page', function (): void {
    $lot = TimberLot::factory()->createOne(['lot_code' => 'LOT-PAGE-TEST']);

    testTimberLivewireWithQuery(TimberTraceabilityPage::class, ['search' => 'LOT-PAGE-TEST'])
        ->assertSet('search', 'LOT-PAGE-TEST')
        ->call('downloadTraceabilityPdf')
        ->assertHasNoErrors();

    expect($lot)->not->toBeNull();
});

it('downloads the inventory pdf from the inventory page', function (): void {
    TimberLot::factory()->createOne();

    testTimberLivewire(TimberInventoryPage::class)
        ->call('downloadInventoryPdf')
        ->assertHasNoErrors();
});

it('renders the dashboard with all four charts', function (): void {
    $supplier = TimberSupplier::factory()->createOne(['legal_name' => 'Boschi Test Srl']);
    TimberDerivedMaterial::factory()->createOne(['status_code' => 'pronto_per_vendita', 'quantity' => 120, 'is_waste' => false]);
    TimberLot::factory()->createOne(['status_code' => 'available', 'wood_species' => 'abete', 'quantity_available' => 500]);
    TimberPurchaseRecord::factory()->createOne(['supplier_id' => $supplier->id, 'quantity' => 250, 'received_at' => now()]);

    testTimberLivewire(TimberDashboard::class)
        ->assertOk()
        ->assertSee('Composizione materiale per stato')
        ->assertSee('Giacenza per specie legnosa')
        ->assertSee('Andamento acquisti')
        ->assertSee('Top fornitori per quantita acquistata')
        ->assertSee('Pronto per vendita')
        ->assertSee('Abete')
        ->assertSee('Boschi Test Srl')
        ->assertDontSee('Percorso operativo legname');
});

it('renders the dashboard without crashing when a lot has a numeric species code', function (): void {
    // Numeric strings become int array keys in PHP; the species-bar builder must
    // not choke on that (regression: ucfirst(int) TypeError).
    TimberLot::factory()->createOne(['status_code' => 'available', 'wood_species' => '323', 'quantity_available' => 90]);

    testTimberLivewire(TimberDashboard::class)
        ->assertOk()
        ->assertSee('Giacenza per specie legnosa');
});

it('renders the dashboard empty states when there is no data', function (): void {
    testTimberLivewire(TimberDashboard::class)
        ->assertOk()
        ->assertSee('Nessun materiale derivato registrato.')
        ->assertSee('Nessun lotto disponibile.')
        ->assertSee('Nessun acquisto registrato nelle ultime 8 settimane.')
        ->assertSee('Nessun acquisto registrato.');
});
