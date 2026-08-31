<?php

declare(strict_types=1);

uses(TestCase::class);

use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Modules\User\Models\User;
use Modules\Wts\Actions\EnqueueWtsLabelPrintAction;
use Modules\Wts\Actions\GenerateWtsUnitSeriesAction;
use Modules\Wts\Actions\TakeChargeOfWtsIngressAction;
use Modules\Wts\Filament\Pages\WtsLotDetailPage;
use Modules\Wts\Filament\Pages\WtsPrintingPage;
use Modules\Wts\Models\WtsAuditLog;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsPrintJob;
use Modules\Wts\Models\WtsUnit;
use Modules\Wts\Tests\TestCase;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

it('writes a created audit row attributed to the acting user', function (): void {
    $user = User::factory()->create(['name' => 'Mario Rossi']);
    actingAs($user);

    $unit = WtsUnit::factory()->create(['unit_code' => 'LOT-2026-0400-BIG-BAG-01']);

    /** @var WtsAuditLog|null $log */
    $log = WtsAuditLog::query()
        ->where('auditable_type', WtsUnit::class)
        ->where('auditable_id', $unit->id)
        ->where('action', 'created')
        ->first();

    expect($log)->not->toBeNull()
        ->and($log?->old_values)->toBeNull()
        ->and($log?->new_values)->toHaveKey('unit_code', 'LOT-2026-0400-BIG-BAG-01')
        ->and($log?->performed_by_user_id)->toBe($user->id)
        ->and($log?->performed_by_name)->toBe('Mario Rossi')
        ->and($log?->performed_at)->not->toBeNull();
});

it('writes an updated audit row with exact old and new values and no actor when unauthenticated', function (): void {
    $record = WtsInboundRecord::factory()->create(['form_number' => 'FIR-OLD-01']);

    $record->forceFill(['form_number' => 'FIR-NEW-01'])->save();

    /** @var WtsAuditLog|null $log */
    $log = WtsAuditLog::query()
        ->where('auditable_type', WtsInboundRecord::class)
        ->where('auditable_id', $record->id)
        ->where('action', 'updated')
        ->first();

    expect($log)->not->toBeNull()
        ->and($log?->old_values)->toBe(['form_number' => 'FIR-OLD-01'])
        ->and($log?->new_values)->toBe(['form_number' => 'FIR-NEW-01'])
        ->and($log?->performed_by_user_id)->toBeNull()
        ->and($log?->performed_by_name)->toBeNull();
});

it('does not audit timestamp-only updates', function (): void {
    $record = WtsInboundRecord::factory()->create();

    Carbon::setTestNow(now()->addMinute());
    $record->touch();
    Carbon::setTestNow();

    expect(WtsAuditLog::query()
        ->where('auditable_type', WtsInboundRecord::class)
        ->where('auditable_id', $record->id)
        ->where('action', 'updated')
        ->count())->toBe(0);
});

it('audits soft deletes and restores without losing the row', function (): void {
    $unit = WtsUnit::factory()->create();

    $unit->delete();

    /** @var WtsAuditLog|null $deletedLog */
    $deletedLog = WtsAuditLog::query()
        ->where('auditable_type', WtsUnit::class)
        ->where('auditable_id', $unit->id)
        ->where('action', 'deleted')
        ->first();

    expect(WtsUnit::query()->find($unit->id))->toBeNull()
        ->and(WtsUnit::withTrashed()->find($unit->id)?->deleted_at)->not->toBeNull()
        ->and($deletedLog)->not->toBeNull()
        ->and($deletedLog?->old_values)->toBeNull()
        ->and($deletedLog?->new_values)->toBeNull();

    WtsUnit::withTrashed()->find($unit->id)?->restore();

    expect(WtsUnit::query()->find($unit->id))->not->toBeNull()
        ->and(WtsAuditLog::query()
            ->where('auditable_type', WtsUnit::class)
            ->where('auditable_id', $unit->id)
            ->where('action', 'restored')
            ->count())->toBe(1);
});

it('blocks taking charge when the record is already taken in charge even without lots', function (): void {
    $record = WtsInboundRecord::factory()->create(['taken_charge_at' => now()]);

    expect(fn () => app(TakeChargeOfWtsIngressAction::class)->execute(
        inboundRecord: $record,
        formNumber: 'FIR-X',
        grossQuantity: 10.0,
        tareWeight: null,
        entryWeight: null,
        acceptanceStatus: 'accepted',
        notes: null,
        userId: null,
    ))->toThrow(InvalidArgumentException::class, 'Ingresso gia preso in carico: i dati accettati non sono modificabili.');
});

it('blocks taking charge when a lot already exists for the record', function (): void {
    $record = WtsInboundRecord::factory()->create();
    WtsInboundLot::factory()->create(['inbound_record_id' => $record->id]);

    expect(fn () => app(TakeChargeOfWtsIngressAction::class)->execute(
        inboundRecord: $record,
        formNumber: 'FIR-Y',
        grossQuantity: 10.0,
        tareWeight: null,
        entryWeight: null,
        acceptanceStatus: 'accepted',
        notes: null,
        userId: null,
    ))->toThrow(InvalidArgumentException::class, 'Ingresso gia preso in carico: i dati accettati non sono modificabili.');

    expect($record->fresh()?->status_code)->toBe('registered');
});

it('never reuses lot codes of soft deleted lots', function (): void {
    $year = now()->format('Y');

    $trashedLot = WtsInboundLot::factory()->create(['lot_code' => sprintf('LOT-%s-0002', $year)]);
    $trashedLot->delete();

    $record = WtsInboundRecord::factory()->create();

    $lot = app(TakeChargeOfWtsIngressAction::class)->execute(
        inboundRecord: $record,
        formNumber: 'FIR-2026-0500',
        grossQuantity: 100.0,
        tareWeight: null,
        entryWeight: 90.0,
        acceptanceStatus: 'accepted',
        notes: null,
        userId: null,
    );

    expect($lot->lot_code)->toBe(sprintf('LOT-%s-0003', $year));
});

it('never reuses unit sequences of soft deleted units', function (): void {
    $lot = WtsInboundLot::factory()->create(['lot_code' => 'LOT-2026-0500']);

    $units = app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $lot,
        unitTypeCode: 'big_bag',
        unitsCount: 2,
        technicalSheetSnapshot: null,
        printReasonCode: 'initial_print',
    );

    $units->last()?->delete();

    $newUnits = app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $lot->fresh() ?? $lot,
        unitTypeCode: 'big_bag',
        unitsCount: 1,
        technicalSheetSnapshot: null,
        printReasonCode: 'initial_print',
    );

    expect($newUnits->first()?->unit_code)->toBe('LOT-2026-0500-BIG-BAG-03')
        ->and($lot->fresh()?->initial_units_count)->toBe(2);
});

it('records label prints when labels are queued to the printer', function (): void {
    $user = User::factory()->create();
    $unit = WtsUnit::factory()->create(['unit_code' => 'LOT-2026-0600-BIG-BAG-01']);

    app(EnqueueWtsLabelPrintAction::class)->execute($unit, 1, $user->id);
    app(EnqueueWtsLabelPrintAction::class)->execute($unit);

    $prints = $unit->labelPrints()->orderBy('id')->get();

    expect($prints)->toHaveCount(2)
        ->and($prints[0]?->label_code)->toBe('LOT-2026-0600-BIG-BAG-01-Z01')
        ->and($prints[0]?->print_reason_code)->toBe('zpl_queue')
        ->and($prints[0]?->printed_by_user_id)->toBe($user->id)
        ->and($prints[0]?->printed_at)->not->toBeNull()
        ->and($prints[1]?->label_code)->toBe('LOT-2026-0600-BIG-BAG-01-Z02')
        ->and($prints[1]?->printed_by_user_id)->toBeNull();
});

it('attributes queued label prints to the acting user from the lot detail page', function (): void {
    Role::findOrCreate('wts_admin');
    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $unit = WtsUnit::factory()->create(['unit_code' => 'LOT-2026-0601-BIG-BAG-01']);
    $lot = $unit->inboundLot;

    Livewire::actingAs($user)
        ->withQueryParams(['lot' => $lot?->id])
        ->test(WtsLotDetailPage::class)
        ->call('queueUnitLabelPrint', $unit->id)
        ->call('queueAllLabelsPrint')
        ->assertHasNoErrors();

    expect($unit->labelPrints()
        ->where('print_reason_code', 'zpl_queue')
        ->where('printed_by_user_id', $user->id)
        ->count())->toBe(2);
});

it('soft deletes removed units keeping their print history and audit trail', function (): void {
    Role::findOrCreate('wts_admin');
    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $lot = WtsInboundLot::factory()->create();
    app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $lot,
        unitTypeCode: 'big_bag',
        unitsCount: 1,
        technicalSheetSnapshot: null,
        printReasonCode: 'initial_print',
    );

    /** @var WtsUnit $unit */
    $unit = $lot->units()->firstOrFail();

    Livewire::actingAs($user)
        ->withQueryParams(['lot' => $lot->id])
        ->test(WtsLotDetailPage::class)
        ->call('removeUnit', $unit->id)
        ->assertHasNoErrors();

    $trashed = WtsUnit::withTrashed()->find($unit->id);

    expect(WtsUnit::query()->find($unit->id))->toBeNull()
        ->and($trashed?->deleted_at)->not->toBeNull()
        ->and($trashed?->labelPrints()->count())->toBe(1)
        ->and($lot->fresh()?->initial_units_count)->toBe(0)
        ->and(WtsAuditLog::query()
            ->where('auditable_type', WtsUnit::class)
            ->where('auditable_id', $unit->id)
            ->where('action', 'deleted')
            ->count())->toBe(1);
});

it('ignores queue print requests without a selected lot or without units', function (): void {
    Role::findOrCreate('wts_admin');
    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    Livewire::actingAs($user)
        ->test(WtsLotDetailPage::class)
        ->call('queueAllLabelsPrint')
        ->assertHasNoErrors();

    $emptyLot = WtsInboundLot::factory()->create();

    Livewire::actingAs($user)
        ->withQueryParams(['lot' => $emptyLot->id])
        ->test(WtsLotDetailPage::class)
        ->call('queueAllLabelsPrint')
        ->assertHasNoErrors();

    expect(WtsPrintJob::query()->count())->toBe(0);
});

it('keeps cleared print jobs in the database as soft deleted history', function (): void {
    Role::findOrCreate('wts_admin');
    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    filament()->setCurrentPanel('wts');

    WtsPrintJob::factory()->create(['status_code' => 'printed']);

    Livewire::actingAs($user)
        ->test(WtsPrintingPage::class)
        ->call('clearPrinted')
        ->assertHasNoErrors();

    expect(WtsPrintJob::query()->count())->toBe(0)
        ->and(WtsPrintJob::withTrashed()->count())->toBe(1);
});
