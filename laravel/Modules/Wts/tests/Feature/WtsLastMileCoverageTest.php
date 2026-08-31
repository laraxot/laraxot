<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\Wts\Filament\Pages\WtsIngressesPage;
use Modules\Wts\Filament\Pages\WtsInventoryPage;
use Modules\Wts\Filament\Pages\WtsLotDetailPage;
use Modules\Wts\Filament\Pages\WtsOutputsPage;
use Modules\User\Models\User;
use Modules\Wts\Actions\GenerateWtsUnitSeriesAction;
use Modules\Wts\Actions\LookupLegacyTechnicalSheetAction;
use Modules\Wts\Actions\MoveWtsUnitAction;
use Modules\Wts\Actions\RegisterWtsProcessingAction;
use Modules\Wts\Legacy\LegacyTechnicalSheetData;
use Modules\Wts\Legacy\LegacyTechnicalSheetGateway;
use Modules\Wts\Models\WtsDerivedMaterial;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsUnit;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

it('covers validation branches of RegisterWtsProcessingAction', function (): void {
    $unit = WtsUnit::factory()->create([
        'status_code' => 'available',
    ]);

    expect(fn () => app(RegisterWtsProcessingAction::class)->execute(
        selectedUnitIds: [],
        stepTypeCode: 'sorting',
        performedByUserId: null,
        quantityMode: 'units',
        selectedUnitsCount: 0,
        workedWeight: null,
        remainderWasteCode: null,
        notes: null,
        outputDefinitions: [],
    ))->toThrow(InvalidArgumentException::class, 'At least one unit must be selected.');

    expect(fn () => app(RegisterWtsProcessingAction::class)->execute(
        selectedUnitIds: [$unit->id],
        stepTypeCode: 'sorting',
        performedByUserId: null,
        quantityMode: 'units',
        selectedUnitsCount: 2,
        workedWeight: null,
        remainderWasteCode: null,
        notes: null,
        outputDefinitions: [],
    ))->toThrow(InvalidArgumentException::class, 'The selected units count must match the provided unit ids.');

    expect(fn () => app(RegisterWtsProcessingAction::class)->execute(
        selectedUnitIds: [$unit->id],
        stepTypeCode: 'sorting',
        performedByUserId: null,
        quantityMode: 'units',
        selectedUnitsCount: 1,
        workedWeight: -1.0,
        remainderWasteCode: null,
        notes: null,
        outputDefinitions: [],
    ))->toThrow(InvalidArgumentException::class, 'The worked weight cannot be negative.');

    expect(fn () => app(RegisterWtsProcessingAction::class)->execute(
        selectedUnitIds: [$unit->id, 999999],
        stepTypeCode: 'sorting',
        performedByUserId: null,
        quantityMode: 'units',
        selectedUnitsCount: 2,
        workedWeight: 1.0,
        remainderWasteCode: null,
        notes: null,
        outputDefinitions: [],
    ))->toThrow(InvalidArgumentException::class, 'One or more selected units are missing.');

    expect(fn () => app(RegisterWtsProcessingAction::class)->execute(
        selectedUnitIds: [$unit->id],
        stepTypeCode: 'sorting',
        performedByUserId: null,
        quantityMode: 'units',
        selectedUnitsCount: 1,
        workedWeight: 1.0,
        remainderWasteCode: null,
        notes: null,
        outputDefinitions: [[
            'output_type_code' => '',
            'container_type_code' => 'drum',
            'quantity' => 1,
            'weight' => null,
            'technical_sheet_snapshot_id' => null,
            'is_dirty' => false,
        ]],
    ))->toThrow(InvalidArgumentException::class, 'Each output definition must include output and container type codes.');

    expect(fn () => app(RegisterWtsProcessingAction::class)->execute(
        selectedUnitIds: [$unit->id],
        stepTypeCode: 'sorting',
        performedByUserId: null,
        quantityMode: 'units',
        selectedUnitsCount: 1,
        workedWeight: 1.0,
        remainderWasteCode: null,
        notes: null,
        outputDefinitions: [[
            'output_type_code' => 'drum',
            'container_type_code' => 'drum',
            'quantity' => 0,
            'weight' => null,
            'technical_sheet_snapshot_id' => null,
            'is_dirty' => false,
        ]],
    ))->toThrow(InvalidArgumentException::class, 'Each output definition must include a positive quantity.');

    expect(fn () => app(RegisterWtsProcessingAction::class)->execute(
        selectedUnitIds: [$unit->id],
        stepTypeCode: 'sorting',
        performedByUserId: null,
        quantityMode: 'units',
        selectedUnitsCount: 1,
        workedWeight: 1.0,
        remainderWasteCode: null,
        notes: null,
        outputDefinitions: [[
            'output_type_code' => 'drum',
            'container_type_code' => 'drum',
            'quantity' => 1,
            'weight' => -1.0,
            'technical_sheet_snapshot_id' => null,
            'is_dirty' => false,
        ]],
    ))->toThrow(InvalidArgumentException::class, 'Output weights cannot be negative.');

    // Bilancio di massa: output oltre il peso lavorato -> blocco.
    expect(fn () => app(RegisterWtsProcessingAction::class)->execute(
        selectedUnitIds: [$unit->id],
        stepTypeCode: 'sorting',
        performedByUserId: null,
        quantityMode: 'units',
        selectedUnitsCount: 1,
        workedWeight: 10.0,
        remainderWasteCode: null,
        notes: null,
        outputDefinitions: [[
            'output_type_code' => 'drum',
            'container_type_code' => 'drum',
            'quantity' => 1,
            'weight' => 50.0,
            'technical_sheet_snapshot_id' => null,
            'is_dirty' => false,
        ]],
    ))->toThrow(InvalidArgumentException::class, 'Bilancio di massa non valido');
});

it('covers remaining branches of MoveWtsUnitAction', function (): void {
    $unit = WtsUnit::factory()->create([
        'status_code' => 'finished',
        'current_area_code' => 'finished',
    ]);

    expect(fn () => app(MoveWtsUnitAction::class)->execute($unit, 'to_process', null))
        ->toThrow(InvalidArgumentException::class, 'Un collo finito non puo tornare operativo.');

    config()->set('wts.stock_views', 'invalid');

    expect(fn () => app(MoveWtsUnitAction::class)->execute($unit, 'finished', null))
        ->toThrow(InvalidArgumentException::class, 'Area WTS non valida.');
});

it('covers empty and missing branches of LookupLegacyTechnicalSheetAction', function (): void {
    $gateway = new class implements LegacyTechnicalSheetGateway
    {
        public function findBySheetCode(string $sheetCode): ?LegacyTechnicalSheetData
        {
            return null;
        }
    };

    $action = new LookupLegacyTechnicalSheetAction($gateway);

    expect($action->execute('   '))->toBeNull()
        ->and($action->execute('ST-NOT-FOUND'))->toBeNull();
});

it('covers ingresses and inventory page utility config branches', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    config()->set('wts.unit_types', [
        'big_bag' => 'Big bag',
        4 => 'skip',
        'drum' => ['invalid'],
    ]);
    config()->set('wts.stock_views', [
        'to_process' => 'Da lavorare',
        3 => 'skip',
        'finished' => ['invalid'],
    ]);

    Livewire::actingAs($user);
    Livewire::test(WtsIngressesPage::class)
        ->assertSet('receivedDate', now()->toDateString())
        ->assertViewHas('unitTypeLabels', [
            'big_bag' => 'Big bag',
            'drum' => '',
        ]);

    Livewire::actingAs($user);
    Livewire::test(WtsInventoryPage::class)
        ->assertSet('referenceDate', now()->format('Y-m-d'))
        ->assertViewHas('stockViews', [
            'to_process' => 'Da lavorare',
            'finished' => '',
        ]);
});

it('covers missing material branches in outputs page', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $material = WtsDerivedMaterial::factory()->create();

    Livewire::actingAs($user);
    Livewire::test(WtsOutputsPage::class)
        ->call('updateMaterialTechnicalSheet', 999999, '')
        ->call('reprintOutputLabel', 999999)
        ->assertHasNoErrors();

    expect($material->fresh()?->labelPrints()->count())->toBe(0);
});

it('covers invalid unit type config branch in the lot detail page', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    config()->set('wts.unit_types', 'invalid');

    Livewire::actingAs($user);
    Livewire::test(WtsLotDetailPage::class)
        ->assertViewHas('unitTypeLabels', []);
});

it('validates unit series generation input before creating units', function (): void {
    $lot = WtsInboundLot::factory()->create();

    expect(fn () => app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $lot,
        unitTypeCode: 'big_bag',
        unitsCount: 0,
        technicalSheetSnapshot: $lot->inboundRecord?->technicalSheetSnapshot,
        printReasonCode: 'initial_print',
    ))->toThrow(InvalidArgumentException::class, 'The unit count must be greater than zero.');

    expect(fn () => app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $lot,
        unitTypeCode: '   ',
        unitsCount: 1,
        technicalSheetSnapshot: $lot->inboundRecord?->technicalSheetSnapshot,
        printReasonCode: 'initial_print',
    ))->toThrow(InvalidArgumentException::class, 'The unit type is required.');
});
