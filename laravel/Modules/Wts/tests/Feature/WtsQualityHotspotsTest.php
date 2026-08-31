<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\Wts\Filament\Pages\WtsSortingPage;
use Modules\User\Models\User;
use Modules\Wts\Actions\TakeChargeOfWtsIngressAction;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Models\WtsUnit;
use Modules\Wts\Queries\GetWtsInventoryOverviewQuery;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

it('takes charge normalizing inputs and blocks any second take charge on the same ingress', function (): void {
    $creator = User::factory()->create();

    $record = WtsInboundRecord::factory()->create([
        'status_code' => 'registered',
        'created_by_user_id' => $creator->id,
    ]);

    $lot = app(TakeChargeOfWtsIngressAction::class)->execute(
        inboundRecord: $record,
        formNumber: ' FIR-2026-0099 ',
        grossQuantity: 250.0,
        tareWeight: null,
        entryWeight: 240.0,
        acceptanceStatus: ' accepted ',
        notes: '  ',
        userId: 77,
    );

    expect(WtsInboundLot::query()->where('inbound_record_id', $record->id)->count())->toBe(1)
        ->and($record->fresh()?->form_number)->toBe('FIR-2026-0099')
        ->and($record->fresh()?->acceptance_status)->toBe('accepted')
        ->and($record->fresh()?->created_by_user_id)->toBe($creator->id)
        ->and($record->fresh()?->notes)->toBeNull();

    // Blindatura: la ripresa in carico non deve sovrascrivere i dati accettati.
    expect(fn () => app(TakeChargeOfWtsIngressAction::class)->execute(
        inboundRecord: $record->fresh() ?? $record,
        formNumber: 'FIR-OVERWRITE',
        grossQuantity: 999.0,
        tareWeight: null,
        entryWeight: 999.0,
        acceptanceStatus: 'accepted',
        notes: 'tentata sovrascrittura',
        userId: 78,
    ))->toThrow(InvalidArgumentException::class, 'Ingresso gia preso in carico: i dati accettati non sono modificabili.');

    expect($record->fresh()?->form_number)->toBe('FIR-2026-0099')
        ->and($record->fresh()?->notes)->toBeNull()
        ->and(WtsInboundLot::query()->where('inbound_record_id', $record->id)->count())->toBe(1);
});

it('validates mandatory ingress taking charge fields and positive weights', function (): void {
    $record = WtsInboundRecord::factory()->create();

    expect(fn () => app(TakeChargeOfWtsIngressAction::class)->execute(
        inboundRecord: $record,
        formNumber: '   ',
        grossQuantity: 10.0,
        tareWeight: null,
        entryWeight: 9.0,
        acceptanceStatus: 'accepted',
        notes: null,
        userId: null,
    ))->toThrow(InvalidArgumentException::class, 'Il formulario e obbligatorio.');

    expect(fn () => app(TakeChargeOfWtsIngressAction::class)->execute(
        inboundRecord: $record,
        formNumber: 'FIR-ERR-01',
        grossQuantity: 0.0,
        tareWeight: null,
        entryWeight: 9.0,
        acceptanceStatus: 'accepted',
        notes: null,
        userId: null,
    ))->toThrow(InvalidArgumentException::class, 'La quantita iniziale deve essere positiva.');

    expect(fn () => app(TakeChargeOfWtsIngressAction::class)->execute(
        inboundRecord: $record,
        formNumber: 'FIR-ERR-02',
        grossQuantity: 10.0,
        tareWeight: null,
        entryWeight: -1.0,
        acceptanceStatus: 'accepted',
        notes: null,
        userId: null,
    ))->toThrow(InvalidArgumentException::class, 'Il peso in ingresso non puo essere negativo.');

    expect(fn () => app(TakeChargeOfWtsIngressAction::class)->execute(
        inboundRecord: $record,
        formNumber: 'FIR-ERR-03',
        grossQuantity: 10.0,
        tareWeight: -1.0,
        entryWeight: 9.0,
        acceptanceStatus: 'accepted',
        notes: null,
        userId: null,
    ))->toThrow(InvalidArgumentException::class, 'La tara non puo essere negativa.');

    expect(fn () => app(TakeChargeOfWtsIngressAction::class)->execute(
        inboundRecord: $record,
        formNumber: 'FIR-ERR-04',
        grossQuantity: 10.0,
        tareWeight: 1.0,
        entryWeight: 9.0,
        acceptanceStatus: '   ',
        notes: null,
        userId: null,
    ))->toThrow(InvalidArgumentException::class, 'L esito accettazione e obbligatorio.');
});

it('covers empty selection invalid lot selection and stock view normalization in processing trait', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    config()->set('wts.stock_views', [
        'to_process' => 'Da lavorare',
        12 => 'Chiave non valida',
        'finished' => ['nope'],
    ]);

    $lot = WtsInboundLot::factory()->create();
    $otherLot = WtsInboundLot::factory()->create();

    $unit = WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $lot->inboundRecord?->technical_sheet_snapshot_id,
        'status_code' => 'available',
    ]);
    $otherUnit = WtsUnit::factory()->create([
        'inbound_lot_id' => $otherLot->id,
        'technical_sheet_snapshot_id' => $otherLot->inboundRecord?->technical_sheet_snapshot_id,
        'status_code' => 'available',
    ]);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsSortingPage::class) /** @phpstan-ignore-line argument.templateType */
        ->set('selectedUnitIds', [])
        ->call('registerProcessing')
        ->assertHasNoErrors();

    expect(WtsProcessingStep::query()->count())->toBe(0);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsSortingPage::class) /** @phpstan-ignore-line argument.templateType */
        ->set('selectedUnitIds', [$unit->id, $otherUnit->id])
        ->set('workedWeight', '10,5')
        ->set('deferredIronWeight', '2,5')
        ->set('generatedLiquidWeight', '1,5')
        ->set('processingNotes', 'nota test')
        ->set('washingEnabled', true)
        ->call('registerProcessing')
        ->assertHasNoErrors();

    expect(WtsProcessingStep::query()->count())->toBe(0);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsSortingPage::class) /** @phpstan-ignore-line argument.templateType */
        ->assertViewHas('stockViews', [
            'to_process' => 'Da lavorare',
            'finished' => '',
        ]);
});

it('filters inventory by waste code and zone and computes reference snapshots', function (): void {
    $snapshotA = \Modules\Wts\Models\WtsTechnicalSheetSnapshot::factory()->create([
        'waste_code' => '15 01 02',
    ]);
    $snapshotB = \Modules\Wts\Models\WtsTechnicalSheetSnapshot::factory()->create([
        'waste_code' => '19 12 04',
    ]);

    $recordA = WtsInboundRecord::factory()->create([
        'technical_sheet_snapshot_id' => $snapshotA->id,
    ]);
    $recordB = WtsInboundRecord::factory()->create([
        'technical_sheet_snapshot_id' => $snapshotB->id,
    ]);

    $lotA = WtsInboundLot::factory()->create([
        'inbound_record_id' => $recordA->id,
    ]);
    $lotB = WtsInboundLot::factory()->create([
        'inbound_record_id' => $recordB->id,
    ]);

    $unitA = WtsUnit::factory()->create([
        'inbound_lot_id' => $lotA->id,
        'technical_sheet_snapshot_id' => $snapshotA->id,
        'status_code' => 'available',
        'current_area_code' => 'to_process',
        'created_at' => '2026-06-01 10:00:00',
        'updated_at' => '2026-06-01 10:00:00',
    ]);

    $unitB = WtsUnit::factory()->create([
        'inbound_lot_id' => $lotB->id,
        'technical_sheet_snapshot_id' => $snapshotB->id,
        'status_code' => 'finished',
        'current_area_code' => 'finished',
        'created_at' => '2026-06-03 10:00:00',
        'updated_at' => '2026-06-03 10:00:00',
    ]);

    $all = app(GetWtsInventoryOverviewQuery::class)->execute();
    $filteredByWaste = app(GetWtsInventoryOverviewQuery::class)->execute($unitA->technicalSheetSnapshot?->waste_code);
    $filteredByZone = app(GetWtsInventoryOverviewQuery::class)->execute(null, 'finished');
    $snapshotBeforeUnitB = app(GetWtsInventoryOverviewQuery::class)->execute(null, null, '2026-06-01');

    expect($all['stats']['total'])->toBe(2)
        ->and($all['stats']['available'])->toBe(1)
        ->and($all['stats']['consumed'])->toBe(1)
        ->and($filteredByWaste['stats']['total'])->toBe(1)
        ->and($filteredByWaste['rows']->first()?->wasteCode)->toBe($snapshotA->waste_code)
        ->and($filteredByZone['stats']['total'])->toBe(1)
        ->and($filteredByZone['rows']->first()?->zoneCode)->toBe('finished')
        ->and($snapshotBeforeUnitB['stats']['total'])->toBe(1)
        ->and($snapshotBeforeUnitB['filters']['zones'])->toBe(['to_process']);
});
