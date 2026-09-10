<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\Wts\Filament\Pages\WtsIngressesPage;
use Modules\Wts\Filament\Pages\WtsLotDetailPage;
use Modules\Wts\Filament\Pages\WtsNewIngressPage;
use Modules\User\Models\User;
use Modules\Wts\Actions\TakeChargeOfWtsIngressAction;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Modules\Wts\Models\WtsUnit;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('takes charge of an ingress and creates the operational WTS lot', function (): void {
    $snapshot = WtsTechnicalSheetSnapshot::factory()->create([
        'sheet_code' => 'SR2024-04817',
        'waste_code' => '15 01 02',
    ]);

    $record = WtsInboundRecord::factory()->create([
        'legacy_reference' => 'ECOS-1001',
        'form_number' => 'FIR-2026-001',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'gross_quantity' => 1250,
        'entry_weight' => null,
        'status_code' => 'registered',
    ]);

    $lot = app(TakeChargeOfWtsIngressAction::class)->execute(
        inboundRecord: $record,
        formNumber: 'FIR-2026-001',
        grossQuantity: 1250.0,
        tareWeight: 12.0,
        entryWeight: 1238.0,
        acceptanceStatus: 'accepted',
        notes: 'Materiale conforme',
        userId: null,
    );

    expect($lot->lot_code)->toBe('LOT-2026-0001')
        ->and($lot->current_area_code)->toBe('incoming')
        ->and($record->fresh()?->entry_weight)->toBe('1238.00')
        ->and($record->fresh()?->net_weight)->toBe('1238.00')
        ->and($record->fresh()?->acceptance_status)->toBe('accepted')
        ->and($record->fresh()?->status_code)->toBe('taken_charge');
});

it('auto-generates lot units from the ECOS declared colli count on take charge', function (): void {
    $snapshot = WtsTechnicalSheetSnapshot::factory()->create([
        'sheet_code' => 'SR2024-04999',
    ]);

    $record = WtsInboundRecord::factory()->create([
        'legacy_reference' => 'ECOS-1099',
        'form_number' => 'FIR-2026-099',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'ecos_units_count' => 14,
        'status_code' => 'registered',
    ]);

    $lot = app(TakeChargeOfWtsIngressAction::class)->execute(
        inboundRecord: $record,
        formNumber: 'FIR-2026-099',
        grossQuantity: 1000.0,
        tareWeight: null,
        entryWeight: 950.0,
        acceptanceStatus: 'accepted',
        notes: null,
        userId: null,
    );

    expect($lot->fresh()?->initial_units_count)->toBe(14)
        ->and($lot->units()->count())->toBe(14)
        ->and($lot->fresh()?->current_area_code)->toBe('to_process')
        ->and($lot->units()->where('unit_type_code', 'big_bag')->count())->toBe(14);
});

it('uses the configured default unit type for auto-generated colli', function (?string $configuredType, string $expectedType): void {
    config()->set('wts.default_unit_type', $configuredType);

    $record = WtsInboundRecord::factory()->create([
        'legacy_reference' => 'ECOS-1097-'.$expectedType,
        'form_number' => 'FIR-2026-097',
        'ecos_units_count' => 3,
        'status_code' => 'registered',
    ]);

    $lot = app(TakeChargeOfWtsIngressAction::class)->execute(
        inboundRecord: $record,
        formNumber: 'FIR-2026-097',
        grossQuantity: 300.0,
        tareWeight: null,
        entryWeight: 300.0,
        acceptanceStatus: 'accepted',
        notes: null,
        userId: null,
    );

    expect($lot->units()->count())->toBe(3)
        ->and($lot->units()->where('unit_type_code', $expectedType)->count())->toBe(3);
})->with([
    'custom valid type' => ['pallet', 'pallet'],
    'empty string falls back' => ['', 'big_bag'],
    'null falls back' => [null, 'big_bag'],
]);

it('does not generate units when ECOS declares no colli', function (): void {
    $record = WtsInboundRecord::factory()->create([
        'legacy_reference' => 'ECOS-1098',
        'form_number' => 'FIR-2026-098',
        'ecos_units_count' => null,
        'status_code' => 'registered',
    ]);

    $lot = app(TakeChargeOfWtsIngressAction::class)->execute(
        inboundRecord: $record,
        formNumber: 'FIR-2026-098',
        grossQuantity: 500.0,
        tareWeight: null,
        entryWeight: null,
        acceptanceStatus: 'accepted',
        notes: null,
        userId: null,
    );

    expect($lot->fresh()?->initial_units_count)->toBe(0)
        ->and($lot->units()->count())->toBe(0)
        ->and($lot->fresh()?->current_area_code)->toBe('incoming');
});

it('stores UI-02 taking charge data from the WTS panel', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts', 'wts_admin']);

    $record = WtsInboundRecord::factory()->create([
        'legacy_reference' => 'ECOS-1002',
        'form_number' => 'FIR-2026-002',
        'gross_quantity' => 980,
        'entry_weight' => null,
        'status_code' => 'registered',
    ]);

    actingAs($user);
    get('/wts/new-ingress?record='.$record->id)
        ->assertOk()
        ->assertSee('ECOS-1002')
        ->assertSee('FIR-2026-002');

    Livewire::actingAs($user);
    /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
    $livewire = Livewire::withQueryParams(['record' => $record->id])
        ->test(WtsNewIngressPage::class);
    $livewire->set('entryWeight', '970')
        ->set('tareWeight', '10')
        ->set('acceptanceStatus', 'accepted_with_note')
        ->set('notes', 'Accettato con nota operativa')
        ->call('confirmTakingCharge');
    $livewire->assertHasNoErrors();

    expect($record->fresh()?->status_code)->toBe('taken_charge')
        ->and($record->fresh()?->entry_weight)->toBe('970.00')
        ->and(WtsInboundLot::query()->where('inbound_record_id', $record->id)->exists())->toBeTrue();
});

it('adds and removes lot units directly from UI-03', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts', 'wts_admin']);

    $lot = WtsInboundLot::factory()->create([
        'lot_code' => 'LOT-2026-0007',
    ]);

    Livewire::actingAs($user);
    /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
    $livewire = Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsLotDetailPage::class);
    $livewire->call('addUnits', 'big_bag', 2);
    $livewire->assertHasNoErrors();

    expect($lot->fresh()?->units()->count())->toBe(2)
        ->and($lot->fresh()?->initial_units_count)->toBe(2);

    $unitId = (int) WtsUnit::query()->where('inbound_lot_id', $lot->id)->firstOrFail()->id;

    Livewire::actingAs($user);
    /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
    $livewire = Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsLotDetailPage::class);
    $livewire->call('removeUnit', $unitId);
    $livewire->assertHasNoErrors();

    expect($lot->fresh()?->units()->count())->toBe(1)
        ->and($lot->fresh()?->initial_units_count)->toBe(1);
});

it('takes charge straight from UI-01 with ECOS data and lands on the lot detail', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts', 'wts_admin']);

    $record = WtsInboundRecord::factory()->create([
        'legacy_reference' => 'ECOS-2001',
        'form_number' => 'FIR-2026-201',
        'gross_quantity' => 0,
        'entry_weight' => 820,
        'tare_weight' => 15,
        'acceptance_status' => null,
        'notes' => '   ',
        'ecos_units_count' => 4,
        'status_code' => 'registered',
    ]);

    Livewire::actingAs($user);
    /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
    $livewire = Livewire::test(WtsIngressesPage::class);
    $livewire->call('takeChargeAndOpenLot', $record->id);
    $livewire->assertRedirect(WtsLotDetailPage::getUrl(panel: 'wts', parameters: [
        'lot' => (int) WtsInboundLot::query()->where('inbound_record_id', $record->id)->firstOrFail()->id,
        ]));
    $livewire->assertHasNoErrors();

    $lot = WtsInboundLot::query()->where('inbound_record_id', $record->id)->firstOrFail();

    expect($record->fresh()?->status_code)->toBe('taken_charge')
        ->and($record->fresh()?->gross_quantity)->toBe('820.00')
        ->and($record->fresh()?->entry_weight)->toBe('820.00')
        ->and($record->fresh()?->tare_weight)->toBe('15.00')
        ->and($record->fresh()?->acceptance_status)->toBe('accepted')
        ->and($record->fresh()?->notes)->toBeNull()
        ->and($lot->units()->count())->toBe(4);
});

it('keeps the ECOS acceptance status and notes when taking charge from UI-01', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts', 'wts_admin']);

    $record = WtsInboundRecord::factory()->create([
        'form_number' => '  FIR-2026-202  ',
        'gross_quantity' => 640,
        'entry_weight' => null,
        'tare_weight' => null,
        'acceptance_status' => ' accepted_with_note ',
        'notes' => '  Nota da ECOS  ',
        'status_code' => 'registered',
    ]);

    Livewire::actingAs($user);
    /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
    $livewire = Livewire::test(WtsIngressesPage::class);
    $livewire->call('takeChargeAndOpenLot', $record->id);
    $livewire->assertHasNoErrors();

    expect($record->fresh()?->form_number)->toBe('FIR-2026-202')
        ->and($record->fresh()?->acceptance_status)->toBe('accepted_with_note')
        ->and($record->fresh()?->notes)->toBe('Nota da ECOS')
        ->and($record->fresh()?->entry_weight)->toBeNull()
        ->and($record->fresh()?->tare_weight)->toBeNull();
});

it('reopens the existing lot instead of taking charge twice from UI-01', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts', 'wts_admin']);

    $record = WtsInboundRecord::factory()->create([
        'form_number' => 'FIR-2026-203',
        'gross_quantity' => 500,
        'status_code' => 'taken_charge',
    ]);

    $lot = WtsInboundLot::factory()->create([
        'inbound_record_id' => $record->id,
        'lot_code' => 'LOT-2026-0203',
    ]);

    Livewire::actingAs($user);
    /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
    $livewire = Livewire::test(WtsIngressesPage::class);
    $livewire->call('takeChargeAndOpenLot', $record->id);
    $livewire->assertRedirect(WtsLotDetailPage::getUrl(panel: 'wts', parameters: ['lot' => $lot->id]));

    expect(WtsInboundLot::query()->where('inbound_record_id', $record->id)->count())->toBe(1);
});

it('refuses the take charge without sending the operator to UI-02 when ECOS data is missing', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts', 'wts_admin']);

    $record = WtsInboundRecord::factory()->create([
        'form_number' => null,
        'gross_quantity' => 700,
        'status_code' => 'registered',
    ]);

    Livewire::actingAs($user);
    /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
    $livewire = Livewire::test(WtsIngressesPage::class);
    $livewire->call('takeChargeAndOpenLot', $record->id)
        ->assertNoRedirect();

    expect(WtsInboundLot::query()->where('inbound_record_id', $record->id)->exists())->toBeFalse()
        ->and($record->fresh()?->status_code)->toBe('registered');
});

it('blocks the take charge button until ECOS declares formulario and quantita', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts', 'wts_admin']);

    // Viaggio programmato ma non ancora arrivato: nessun formulario da ECOS.
    $waiting = WtsInboundRecord::factory()->create([
        'legacy_reference' => '128423-1',
        'form_number' => null,
        'gross_quantity' => 0,
        'entry_weight' => null,
        'status_code' => 'ready_for_labelling',
        'received_at' => now()->startOfDay(),
    ]);

    // Viaggio arrivato e pesato.
    $arrived = WtsInboundRecord::factory()->create([
        'legacy_reference' => '128759-1',
        'form_number' => 'XBRDH 008028 GH',
        'gross_quantity' => 960,
        'status_code' => 'ready_for_labelling',
        'received_at' => now()->startOfDay(),
    ]);

    actingAs($user);
    get('/wts/ingresses')
        ->assertOk()
        ->assertSee('In attesa di arrivo')
        ->assertSee('takeChargeAndOpenLot('.$arrived->id.')', false)
        ->assertDontSee('takeChargeAndOpenLot('.$waiting->id.')', false);
});

it('warns instead of taking charge when the UI-01 ingress does not exist', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts', 'wts_admin']);

    Livewire::actingAs($user);
    /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
    $livewire = Livewire::test(WtsIngressesPage::class);
    $livewire->call('takeChargeAndOpenLot', 999999)
        ->assertNoRedirect();

    expect(WtsInboundLot::query()->count())->toBe(0);
});
