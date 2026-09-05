<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Livewire\Livewire;
use Modules\User\Models\User;
use Modules\Wts\Filament\Pages\WtsLotDetailPage;
use Modules\Wts\Filament\Pages\WtsNewIngressPage;
use Modules\Wts\Filament\Pages\WtsOperationalParametersPage;
use Modules\Wts\Filament\Pages\WtsOutputsPage;
use Modules\Wts\Filament\Pages\WtsProcessingPage;
use Modules\Wts\Models\WtsDerivedMaterial;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsOperationalParameter;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Modules\Wts\Models\WtsUnit;
use Modules\Wts\Queries\GetWtsOperationalParametersQuery;
use Modules\Wts\Queries\GetWtsReportOverviewQuery;
use Modules\Wts\Tests\TestCase;
use Spatie\Permission\Models\Role;

it('covers label utility actions merged into the lot detail page', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $lot = WtsInboundLot::factory()->create();

    Livewire::actingAs($user);
    Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsLotDetailPage::class)
        ->call('addUnits', 'pallet', 2)
        ->assertHasNoErrors();

    $unit = $lot->fresh()?->units()->orderBy('id')->first();
    assert($unit instanceof WtsUnit);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsLotDetailPage::class)
        ->call('updateUnitType', $unit->id, 'drum')
        ->call('reprintUnitLabel', $unit->id)
        ->call('reprintAllLabels')
        ->assertHasNoErrors();

    $freshUnit = $unit->fresh();

    expect($freshUnit?->unit_type_code)->toBe('drum')
        ->and($freshUnit?->labelPrints()->count())->toBe(3)
        ->and($lot->fresh()?->units()->count())->toBe(2);
});

it('rejects an empty non conformity note from the lot detail page', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $lot = WtsInboundLot::factory()->create();

    Livewire::actingAs($user);
    Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsLotDetailPage::class)
        ->set('nonConformityNote', '   ')
        ->call('reportNonConformity')
        ->assertHasNoErrors();

    expect($lot->nonConformityReports()->count())->toBe(0);
});

it('registers mixed outputs from the generic WTS processing page', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $lot = WtsInboundLot::factory()->create();
    $unit = WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $lot->inboundRecord?->technical_sheet_snapshot_id,
        'status_code' => 'available',
        'current_area_code' => 'to_process',
    ]);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsProcessingPage::class)
        ->set('selectedUnitIds', [$unit->id])
        ->set('stepTypeCode', 'grinding')
        ->set('quantityMode', 'weight')
        ->set('workedWeight', 100)
        ->set('drumsCount', 1)
        ->set('dirtyDrumsCount', 1)
        ->set('palletsCount', 1)
        ->set('washingEnabled', true)
        ->set('deferredIronWeight', 12.5)
        ->set('generatedLiquidWeight', 5.5)
        ->call('processSelection')
        ->assertHasNoErrors();

    $step = WtsProcessingStep::query()->latest('id')->first();

    expect($step)->not->toBeNull()
        ->and($step?->step_type_code)->toBe('grinding')
        ->and($step?->selected_units_count)->toBe(1)
        ->and($step?->notes)->toContain('lavaggio: lavaggio on')
        ->and($step?->notes)->toContain('ferro_deferizzato_kg: 12.5')
        ->and($step?->notes)->toContain('liquido_generato_kg: 5.5')
        ->and($step?->remainder_waste_code)->toBe($lot->inboundRecord?->technicalSheetSnapshot?->waste_code)
        ->and($step?->derivedMaterials()->count())->toBe(5)
        ->and($unit->fresh()?->status_code)->toBe('finished');
});

it('rejects processing selections outside the selected lot', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $lotA = WtsInboundLot::factory()->create();
    $lotB = WtsInboundLot::factory()->create();

    $unitA = WtsUnit::factory()->create([
        'inbound_lot_id' => $lotA->id,
        'technical_sheet_snapshot_id' => $lotA->inboundRecord?->technical_sheet_snapshot_id,
        'status_code' => 'available',
    ]);
    $unitB = WtsUnit::factory()->create([
        'inbound_lot_id' => $lotB->id,
        'technical_sheet_snapshot_id' => $lotB->inboundRecord?->technical_sheet_snapshot_id,
        'status_code' => 'available',
    ]);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['lot' => $lotA->id])
        ->test(WtsProcessingPage::class)
        ->set('selectedUnitIds', [$unitA->id, $unitB->id])
        ->set('workedWeight', 50)
        ->call('processSelection')
        ->assertHasNoErrors();

    expect(WtsProcessingStep::query()->count())->toBe(0)
        ->and($unitA->fresh()?->status_code)->toBe('available')
        ->and($unitB->fresh()?->status_code)->toBe('available');
});

it('covers ingress, outputs, lot detail and operational parameter utility branches', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    Livewire::actingAs($user);
    Livewire::test(WtsNewIngressPage::class)
        ->call('confirmTakingCharge')
        ->assertHasNoErrors();

    $record = WtsInboundRecord::factory()->create([
        'entry_weight' => null,
        'tare_weight' => null,
        'acceptance_status' => null,
    ]);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['record' => $record->id])
        ->test(WtsNewIngressPage::class)
        ->set('grossQuantity', '123,5')
        ->set('tareWeight', '10,2')
        ->set('entryWeight', '113,3')
        ->call('confirmTakingCharge')
        ->assertHasNoErrors();

    expect($record->fresh()?->gross_quantity)->toBe('123.50')
        ->and($record->fresh()?->tare_weight)->toBe('10.20')
        ->and($record->fresh()?->entry_weight)->toBe('113.30');

    $lot = $record->fresh()?->lots()->first();
    assert($lot instanceof \Modules\Wts\Models\WtsInboundLot);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsLotDetailPage::class)
        ->call('addUnits', '', 1)
        ->assertHasNoErrors();

    $finishedUnit = WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $lot->inboundRecord?->technical_sheet_snapshot_id,
        'status_code' => 'finished',
    ]);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsLotDetailPage::class)
        ->call('removeUnit', $finishedUnit->id)
        ->assertHasNoErrors();

    expect($finishedUnit->fresh())->not->toBeNull();

    $material = WtsDerivedMaterial::factory()->create();
    $newSnapshot = WtsTechnicalSheetSnapshot::factory()->create([
        'sheet_code' => 'ST-COV-001',
    ]);

    Livewire::actingAs($user);
    Livewire::test(WtsOutputsPage::class)
        ->call('updateMaterialTechnicalSheet', $material->id, (string) $newSnapshot->id)
        ->call('updateMaterialTechnicalSheet', $material->id, '')
        ->assertHasNoErrors();

    expect($material->fresh()?->technical_sheet_snapshot_id)->toBeNull();

    $parameterBefore = app(GetWtsOperationalParametersQuery::class)->execute()['standard_drum_weight_kg'] ?? null;

    Livewire::actingAs($user);
    Livewire::test(WtsOperationalParametersPage::class)
        ->set('parameterValues.standard_drum_weight_kg', '')
        ->call('saveParameter', 'standard_drum_weight_kg')
        ->assertHasNoErrors();

    $parameterAfter = WtsOperationalParameter::query()->where('code', 'standard_drum_weight_kg')->first();

    expect($parameterBefore)->not->toBeNull()
        ->and($parameterAfter?->value)->toBe($parameterBefore?->value);
});

it('returns a complete WTS report overview with stats, steps and materials', function (): void {
    $availableUnit = WtsUnit::factory()->create([
        'status_code' => 'available',
    ]);
    WtsUnit::factory()->create([
        'status_code' => 'finished',
    ]);

    $step = WtsProcessingStep::factory()->create([
        'work_sheet_code' => 'WS-REPORT-001',
    ]);
    $step->selectedUnits()->sync([$availableUnit->id]);

    $material = WtsDerivedMaterial::factory()->create([
        'processing_step_id' => $step->id,
        'output_code' => 'OUT-REPORT-001',
    ]);

    $overview = app(GetWtsReportOverviewQuery::class)->execute();

    expect($overview['stats']['inbound_records'])->toBe(WtsInboundRecord::query()->count())
        ->and($overview['stats']['processing_steps'])->toBe(WtsProcessingStep::query()->count())
        ->and($overview['stats']['derived_materials'])->toBe(WtsDerivedMaterial::query()->count())
        ->and($overview['stats']['available_units'])->toBe(1)
        ->and($overview['steps']->first()?->id)->toBe($step->id)
        ->and($overview['materials']->first()?->id)->toBe($material->id);
});
