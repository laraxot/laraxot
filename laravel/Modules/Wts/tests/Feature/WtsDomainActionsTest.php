<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\User\Models\User;
use Modules\Wts\Actions\GenerateWtsUnitSeriesAction;
use Modules\Wts\Actions\LookupLegacyTechnicalSheetAction;
use Modules\Wts\Actions\RegisterWtsProcessingAction;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Modules\Wts\Models\WtsUnit;
use Spatie\Permission\Models\Role;

it('imports a technical sheet snapshot from the configured legacy fallback mapping', function (): void {
    config()->set('wts.legacy.connection', null);
    config()->set('wts.legacy.table', null);
    config()->set('wts.legacy.fallback_records', [
        'SR2024-04817' => [
            'sheet_code' => 'SR2024-04817',
            'producer_name' => 'Grafica Veneta Spa',
            'waste_code' => '15 01 02',
            'hp_codes' => 'HP4, HP14',
            'packaging_description' => 'Big bag e bancali misti',
            'production_description' => 'Sfridi da linea stampa',
            'note_1' => 'Linea trattamento 3',
            'note_2' => 'Ciclo produzione cartotecnica',
        ],
    ]);

    $snapshot = app(LookupLegacyTechnicalSheetAction::class)->execute('SR2024-04817');

    expect($snapshot)->toBeInstanceOf(WtsTechnicalSheetSnapshot::class)
        ->and($snapshot?->sheet_code)->toBe('SR2024-04817')
        ->and($snapshot?->producer_name)->toBe('Grafica Veneta Spa')
        ->and($snapshot?->waste_code)->toBe('15 01 02')
        ->and($snapshot?->hp_codes)->toBe('HP4, HP14')
        ->and($snapshot?->note_1)->toBe('Linea trattamento 3')
        ->and($snapshot?->note_2)->toBe('Ciclo produzione cartotecnica');
});

it('generates units with internal qr codes and label prints', function (): void {
    $snapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04817',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
    ]);

    $record = WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1001',
        'form_number' => 'FIR-2026-001',
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'AB123CD',
        'gross_quantity' => 1250,
        'status_code' => 'ready_for_labelling',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'received_at' => now(),
    ]);

    $lot = WtsInboundLot::query()->create([
        'inbound_record_id' => $record->id,
        'lot_code' => 'LOT-2026-0001',
        'status_code' => 'registered',
        'current_area_code' => 'incoming',
    ]);

    /** @var \Illuminate\Support\Collection<int, \Modules\Wts\Models\WtsUnit> $generatedUnits */
    $generatedUnits = app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $lot,
        unitTypeCode: 'big_bag',
        unitsCount: 3,
        technicalSheetSnapshot: $snapshot,
        printReasonCode: 'initial_print',
    );

    expect($generatedUnits)->toHaveCount(3)
        ->and(($generatedUnits[0] ?? throw new \RuntimeException('missing unit 0'))->unit_code)->toBe('LOT-2026-0001-BIG-BAG-01')
        ->and(($generatedUnits[0] ?? throw new \RuntimeException('missing unit 0'))->qr_code)->toStartWith('WTS-QR-')
        ->and(($generatedUnits[0] ?? throw new \RuntimeException('missing unit 0'))->labelPrints)->toHaveCount(1);

    expect($lot->fresh()?->initial_units_count)->toBe(3);
});

it('registers processing on selected units and creates derived materials', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $snapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04817',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
    ]);

    $record = WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1001',
        'form_number' => 'FIR-2026-001',
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'AB123CD',
        'gross_quantity' => 1250,
        'status_code' => 'ready_for_processing',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'received_at' => now(),
    ]);

    $lot = WtsInboundLot::query()->create([
        'inbound_record_id' => $record->id,
        'lot_code' => 'LOT-2026-0001',
        'status_code' => 'registered',
        'current_area_code' => 'to_process',
    ]);

    /** @var \Illuminate\Support\Collection<int, \Modules\Wts\Models\WtsUnit> $units */
    $units = app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $lot,
        unitTypeCode: 'big_bag',
        unitsCount: 3,
        technicalSheetSnapshot: $snapshot,
        printReasonCode: 'initial_print',
    );

    $step = app(RegisterWtsProcessingAction::class)->execute(
        selectedUnitIds: [($units[0] ?? throw new \RuntimeException('missing unit 0'))->id, ($units[1] ?? throw new \RuntimeException('missing unit 1'))->id],
        stepTypeCode: 'grinding',
        performedByUserId: $user->id,
        quantityMode: 'units',
        selectedUnitsCount: 2,
        workedWeight: null,
        remainderWasteCode: '15 01 02',
        notes: 'Triturazione parziale',
        outputDefinitions: [
            [
                'output_type_code' => 'drum',
                'container_type_code' => 'drum',
                'quantity' => 2,
                'weight' => 60.0,
                'technical_sheet_snapshot_id' => $snapshot->id,
                'is_dirty' => false,
            ],
            [
                'output_type_code' => 'dirty_drum',
                'container_type_code' => 'drum',
                'quantity' => 1,
                'weight' => 15.0,
                'technical_sheet_snapshot_id' => $snapshot->id,
                'is_dirty' => true,
            ],
        ],
    );

    expect($step->selectedUnits)->toHaveCount(2)
        ->and($step->derivedMaterials)->toHaveCount(2)
        ->and($step->selected_units_count)->toBe(2)
        ->and($step->quantity_mode)->toBe('units');

    expect(WtsUnit::query()->findOrFail(($units[0] ?? throw new \RuntimeException('missing unit 0'))->id)->status_code)->toBe('finished')
        ->and(WtsUnit::query()->findOrFail(($units[1] ?? throw new \RuntimeException('missing unit 1'))->id)->status_code)->toBe('finished')
        ->and(WtsUnit::query()->findOrFail(($units[2] ?? throw new \RuntimeException('missing unit 2'))->id)->status_code)->toBe('available');
});

it('rejects processing when one of the selected units is no longer available', function (): void {
    $snapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04817',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
    ]);

    $record = WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1001',
        'form_number' => 'FIR-2026-001',
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'AB123CD',
        'gross_quantity' => 1250,
        'status_code' => 'ready_for_processing',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'received_at' => now(),
    ]);

    $lot = WtsInboundLot::query()->create([
        'inbound_record_id' => $record->id,
        'lot_code' => 'LOT-2026-0001',
        'status_code' => 'registered',
        'current_area_code' => 'to_process',
    ]);

    /** @var \Illuminate\Support\Collection<int, \Modules\Wts\Models\WtsUnit> $units */
    $units = app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $lot,
        unitTypeCode: 'big_bag',
        unitsCount: 2,
        technicalSheetSnapshot: $snapshot,
        printReasonCode: 'initial_print',
    );

    ($units[1] ?? throw new \RuntimeException('missing unit 1'))->forceFill([
        'status_code' => 'finished',
        'current_area_code' => 'finished',
    ])->save();

    app(RegisterWtsProcessingAction::class)->execute(
        selectedUnitIds: [($units[0] ?? throw new \RuntimeException('missing unit 0'))->id],
        stepTypeCode: 'sorting',
        performedByUserId: null,
        quantityMode: 'units',
        selectedUnitsCount: 1,
        workedWeight: null,
        remainderWasteCode: '15 01 02',
        notes: null,
        outputDefinitions: [
            [
                'output_type_code' => 'pallet',
                'container_type_code' => 'pallet',
                'quantity' => 1,
                'weight' => 20.0,
                'technical_sheet_snapshot_id' => $snapshot->id,
                'is_dirty' => false,
            ],
        ],
        expectedLotId: $lot->id,
    );

    expect(fn () => app(RegisterWtsProcessingAction::class)->execute(
        selectedUnitIds: [($units[0] ?? throw new \RuntimeException('missing unit 0'))->id, ($units[1] ?? throw new \RuntimeException('missing unit 1'))->id],
        stepTypeCode: 'sorting',
        performedByUserId: null,
        quantityMode: 'units',
        selectedUnitsCount: 2,
        workedWeight: null,
        remainderWasteCode: '15 01 02',
        notes: null,
        outputDefinitions: [
            [
                'output_type_code' => 'pallet',
                'container_type_code' => 'pallet',
                'quantity' => 1,
                'weight' => 20.0,
                'technical_sheet_snapshot_id' => $snapshot->id,
                'is_dirty' => false,
            ],
        ],
        expectedLotId: $lot->id,
    ))->toThrow(InvalidArgumentException::class, 'Only available units can be processed.');
});

it('rejects processing when selected units do not belong to the expected lot', function (): void {
    $snapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04817',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
    ]);

    $record = WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1001',
        'form_number' => 'FIR-2026-001',
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'AB123CD',
        'gross_quantity' => 1250,
        'status_code' => 'ready_for_processing',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'received_at' => now(),
    ]);

    $firstLot = WtsInboundLot::query()->create([
        'inbound_record_id' => $record->id,
        'lot_code' => 'LOT-2026-0001',
        'status_code' => 'registered',
        'current_area_code' => 'to_process',
    ]);

    $secondLot = WtsInboundLot::query()->create([
        'inbound_record_id' => $record->id,
        'lot_code' => 'LOT-2026-0002',
        'status_code' => 'registered',
        'current_area_code' => 'to_process',
    ]);

    /** @var \Illuminate\Support\Collection<int, \Modules\Wts\Models\WtsUnit> $firstLotUnits */
    $firstLotUnits = app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $firstLot,
        unitTypeCode: 'big_bag',
        unitsCount: 1,
        technicalSheetSnapshot: $snapshot,
        printReasonCode: 'initial_print',
    );

    /** @var \Illuminate\Support\Collection<int, \Modules\Wts\Models\WtsUnit> $secondLotUnits */
    $secondLotUnits = app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $secondLot,
        unitTypeCode: 'big_bag',
        unitsCount: 1,
        technicalSheetSnapshot: $snapshot,
        printReasonCode: 'initial_print',
    );

    expect(fn () => app(RegisterWtsProcessingAction::class)->execute(
        selectedUnitIds: [($firstLotUnits[0] ?? throw new \RuntimeException('missing unit 0'))->id, ($secondLotUnits[0] ?? throw new \RuntimeException('missing unit 0'))->id],
        stepTypeCode: 'sorting',
        performedByUserId: null,
        quantityMode: 'units',
        selectedUnitsCount: 2,
        workedWeight: null,
        remainderWasteCode: '15 01 02',
        notes: null,
        outputDefinitions: [
            [
                'output_type_code' => 'pallet',
                'container_type_code' => 'pallet',
                'quantity' => 1,
                'weight' => 20.0,
                'technical_sheet_snapshot_id' => $snapshot->id,
                'is_dirty' => false,
            ],
        ],
        expectedLotId: $firstLot->id,
    ))->toThrow(InvalidArgumentException::class, 'All selected units must belong to the expected lot.');
});
