<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Tests\Feature;

use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

use Modules\TimberBilling\Filament\Pages\TimberCuttingPage;
use Modules\TimberBilling\Filament\Pages\TimberDashboard;
use Modules\TimberBilling\Filament\Pages\TimberDryingPage;
use Modules\TimberBilling\Filament\Pages\TimberInventoryPage;
use Modules\TimberBilling\Filament\Pages\TimberLabelsPage;
use Modules\TimberBilling\Filament\Pages\TimberLotDetailPage;
use Modules\TimberBilling\Filament\Pages\TimberOutputsPage;
use Modules\TimberBilling\Filament\Pages\TimberPackagingPage;
use Modules\TimberBilling\Filament\Pages\TimberProcessingPage;
use Modules\TimberBilling\Filament\Pages\TimberPurchasesPage;
use Modules\TimberBilling\Filament\Pages\TimberSelectionPage;
use Modules\TimberBilling\Filament\Pages\TimberSplittingPage;
use Modules\TimberBilling\Filament\Pages\TimberTraceabilityPage;
use Modules\TimberBilling\Filament\Pages\TimberYardPage;
use Modules\User\Models\User;
use Modules\TimberBilling\Models\TimberDerivedMaterial;
use Modules\TimberBilling\Models\TimberDryingCycle;
use Modules\TimberBilling\Models\TimberLot;
use Modules\TimberBilling\Models\TimberPackagedUnit;
use Modules\TimberBilling\Models\TimberProcessingStep;
use Modules\TimberBilling\Models\TimberPurchaseRecord;
use Modules\TimberBilling\Models\TimberSupplier;
use Modules\TimberBilling\Models\TimberWoodSpecies;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Role::findOrCreate('timber_operator');
    $this->timberUser = User::factory()->create();
    $this->timberUser->syncRoles(['timber_operator']);
});

it('exposes the wood species name, not the stored code, on stock models', function (): void {
    TimberWoodSpecies::query()->delete();
    TimberWoodSpecies::factory()->create(['code' => 'ciliegio', 'name' => 'Ciliegio selvatico', 'is_active' => true]);

    $lot = TimberLot::factory()->create(['wood_species' => 'ciliegio']);
    $material = TimberDerivedMaterial::factory()->create(['wood_species' => 'ciliegio']);

    expect($lot->wood_species_name)->toBe('Ciliegio selvatico')
        ->and($material->wood_species_name)->toBe('Ciliegio selvatico');

    // An unknown/legacy code still renders readably (capitalized code).
    expect(TimberLot::factory()->create(['wood_species' => 'zzz_legacy'])->wood_species_name)->toBe('Zzz_legacy');
});

it('registers a purchase from the purchases page', function (): void {
    $supplier = TimberSupplier::factory()->create();

    Livewire::actingAs($this->timberUser)
        ->test(TimberPurchasesPage::class)
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
    TimberWoodSpecies::factory()->create(['code' => 'ciliegio', 'name' => 'Ciliegio', 'sort_order' => 1]);
    TimberWoodSpecies::factory()->create(['code' => 'noce', 'name' => 'Noce', 'sort_order' => 2]);

    Livewire::actingAs($this->timberUser)
        ->test(TimberPurchasesPage::class)
        ->assertSet('woodSpecies', 'ciliegio');
});

it('shows a warning when registering a purchase without a supplier', function (): void {
    Livewire::actingAs($this->timberUser)
        ->test(TimberPurchasesPage::class)
        ->set('quantity', '100')
        ->call('registerPurchase')
        ->assertHasNoErrors();

    expect(TimberPurchaseRecord::query()->count())->toBe(0);
});

it('moves a lot from the lot detail page', function (): void {
    $record = app(\Modules\TimberBilling\Actions\RegisterTimberPurchaseAction::class)->execute(
        supplier: TimberSupplier::factory()->create(),
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

    Livewire::actingAs($this->timberUser)
        ->withQueryParams(['lot' => $record->lot->id])
        ->test(TimberLotDetailPage::class)
        ->set('moveToArea', 'zona_taglio')
        ->call('moveLot')
        ->assertHasNoErrors();

    expect($record->lot->fresh()?->current_area_code)->toBe('zona_taglio');
});

it('prints the incoming lot label from the lot detail page', function (): void {
    $lot = TimberLot::factory()->create(['lot_code' => 'LOT-2026-0500']);

    Livewire::actingAs($this->timberUser)
        ->withQueryParams(['lot' => $lot->id])
        ->test(TimberLotDetailPage::class)
        ->call('printLotLabel')
        ->assertHasNoErrors();

    expect($lot->labelPrints()->where('label_code', 'LOT-2026-0500')->where('print_reason_code', 'reprint')->exists())->toBeTrue();
});

it('moves lots and materials from the yard page', function (): void {
    $lot = TimberLot::factory()->create(['current_area_code' => 'piazzale']);
    $material = TimberDerivedMaterial::factory()->create(['current_area_code' => 'zona_taglio', 'is_waste' => false]);

    Livewire::actingAs($this->timberUser)
        ->test(TimberYardPage::class)
        ->call('moveLot', $lot->id, 'zona_taglio')
        ->call('moveMaterial', $material->id, 'deposito_semilavorati')
        ->assertHasNoErrors();

    expect($lot->fresh()?->current_area_code)->toBe('zona_taglio')
        ->and($material->fresh()?->current_area_code)->toBe('deposito_semilavorati');
});

it('registers processing from the dispatcher and specialized processing pages', function (string $pageClass, string $expectedStepType): void {
    $lot = TimberLot::factory()->create(['status_code' => 'available', 'quantity_available' => 300]);

    $component = Livewire::actingAs($this->timberUser)
        ->withQueryParams(['lot' => $lot->id])
        ->test($pageClass);

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
})->with([
    [TimberProcessingPage::class, 'taglio'],
    [TimberCuttingPage::class, 'taglio'],
    [TimberSplittingPage::class, 'spaccatura'],
    [TimberSelectionPage::class, 'selezione'],
]);

it('generates packaged units from the packaging page', function (): void {
    $material = TimberDerivedMaterial::factory()->create([
        'quantity' => 400,
        'is_waste' => false,
        'status_code' => 'pronto_da_confezionare',
    ]);

    Livewire::actingAs($this->timberUser)
        ->withQueryParams(['material' => $material->id])
        ->test(TimberPackagingPage::class)
        ->set('unitsCount', 2)
        ->set('quantityPerUnit', '200')
        ->call('generatePackagedUnits')
        ->assertHasNoErrors();

    expect(TimberPackagedUnit::query()->where('derived_material_id', $material->id)->count())->toBe(2);
});

it('runs the full drying cycle flow from the drying page', function (): void {
    $lot = TimberLot::factory()->create(['status_code' => 'available', 'quantity_available' => 100]);

    Livewire::actingAs($this->timberUser)
        ->test(TimberDryingPage::class)
        ->set('sourceType', 'lot')
        ->set('sourceId', $lot->id)
        ->call('startDrying')
        ->assertHasNoErrors();

    $cycle = TimberDryingCycle::query()->where('dryable_type', TimberLot::class)->where('dryable_id', $lot->id)->firstOrFail();

    Livewire::actingAs($this->timberUser)
        ->test(TimberDryingPage::class)
        ->set('humidityPercent', '25')
        ->call('recordHumidity', $cycle->id)
        ->assertHasNoErrors();

    expect($cycle->humidityChecks()->count())->toBe(1);

    Livewire::actingAs($this->timberUser)
        ->test(TimberDryingPage::class)
        ->call('completeDrying', $cycle->id)
        ->assertHasNoErrors();

    expect($cycle->fresh()?->status_code)->toBe('completed');
});

it('preselects a derived material on the drying page when deep-linked from the outputs page', function (): void {
    $material = TimberDerivedMaterial::factory()->create(['is_waste' => false, 'quantity' => 120]);

    Livewire::actingAs($this->timberUser)
        ->withQueryParams(['material' => $material->id])
        ->test(TimberDryingPage::class)
        ->assertSet('sourceType', 'material')
        ->assertSet('sourceId', $material->id);
});

it('ignores the drying deep-link for a waste or depleted derived material', function (): void {
    $waste = TimberDerivedMaterial::factory()->create(['is_waste' => true, 'quantity' => 120]);

    Livewire::actingAs($this->timberUser)
        ->withQueryParams(['material' => $waste->id])
        ->test(TimberDryingPage::class)
        ->assertSet('sourceType', 'lot')
        ->assertSet('sourceId', null);
});

it('renders the outputs page with materials and packaged units', function (): void {
    TimberDerivedMaterial::factory()->create();
    TimberPackagedUnit::factory()->create();

    Livewire::actingAs($this->timberUser)
        ->test(TimberOutputsPage::class)
        ->assertOk();
});

it('downloads a label pdf for a lot, a material and a packaged unit', function (): void {
    $lot = TimberLot::factory()->create();
    $material = TimberDerivedMaterial::factory()->create();
    $packagedUnit = TimberPackagedUnit::factory()->create();

    Livewire::actingAs($this->timberUser)
        ->test(TimberLabelsPage::class)
        ->call('downloadLotLabel', $lot->id)
        ->call('downloadMaterialLabel', $material->id)
        ->call('downloadPackagedUnitLabel', $packagedUnit->id)
        ->assertHasNoErrors();
});

it('searches and downloads traceability from the traceability page', function (): void {
    $lot = TimberLot::factory()->create(['lot_code' => 'LOT-PAGE-TEST']);

    Livewire::actingAs($this->timberUser)
        ->withQueryParams(['search' => 'LOT-PAGE-TEST'])
        ->test(TimberTraceabilityPage::class)
        ->assertSet('search', 'LOT-PAGE-TEST')
        ->call('downloadTraceabilityPdf')
        ->assertHasNoErrors();

    expect($lot)->not->toBeNull();
});

it('downloads the inventory pdf from the inventory page', function (): void {
    TimberLot::factory()->create();

    Livewire::actingAs($this->timberUser)
        ->test(TimberInventoryPage::class)
        ->call('downloadInventoryPdf')
        ->assertHasNoErrors();
});

it('renders the dashboard with all four charts', function (): void {
    $supplier = TimberSupplier::factory()->create(['legal_name' => 'Boschi Test Srl']);
    TimberDerivedMaterial::factory()->create(['status_code' => 'pronto_per_vendita', 'quantity' => 120, 'is_waste' => false]);
    TimberLot::factory()->create(['status_code' => 'available', 'wood_species' => 'abete', 'quantity_available' => 500]);
    TimberPurchaseRecord::factory()->create(['supplier_id' => $supplier->id, 'quantity' => 250, 'received_at' => now()]);

    Livewire::actingAs($this->timberUser)
        ->test(TimberDashboard::class)
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
    TimberLot::factory()->create(['status_code' => 'available', 'wood_species' => '323', 'quantity_available' => 90]);

    Livewire::actingAs($this->timberUser)
        ->test(TimberDashboard::class)
        ->assertOk()
        ->assertSee('Giacenza per specie legnosa');
});

it('renders the dashboard empty states when there is no data', function (): void {
    Livewire::actingAs($this->timberUser)
        ->test(TimberDashboard::class)
        ->assertOk()
        ->assertSee('Nessun materiale derivato registrato.')
        ->assertSee('Nessun lotto disponibile.')
        ->assertSee('Nessun acquisto registrato nelle ultime 8 settimane.')
        ->assertSee('Nessun acquisto registrato.');
});
