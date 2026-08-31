<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\Wts\Filament\Pages\WtsMaterialDetailPage;
use Modules\Wts\Filament\Pages\WtsWorkQueuePage;
use Modules\User\Models\User;
use Modules\Wts\Actions\MoveWtsUnitAction;
use Modules\Wts\Models\WtsUnit;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

it('moves a WTS unit between operational warehouse areas with location', function (): void {
    $unit = WtsUnit::factory()->create([
        'status_code' => 'available',
        'current_area_code' => 'incoming',
        'location_label' => null,
    ]);

    $moved = app(MoveWtsUnitAction::class)->execute(
        unit: $unit,
        areaCode: 'to_process',
        locationLabel: 'Corsia A - scaffale 2',
    );

    expect($moved->current_area_code)->toBe('to_process')
        ->and($moved->location_label)->toBe('Corsia A - scaffale 2')
        ->and($moved->status_code)->toBe('available');
});

it('updates warehouse area from UI-05', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $unit = WtsUnit::factory()->create([
        'current_area_code' => 'incoming',
        'status_code' => 'available',
    ]);

    Livewire::actingAs($user)
        ->test(WtsWorkQueuePage::class)
        ->call('moveUnit', $unit->id, 'to_process', 'Corsia B')
        ->assertHasNoErrors();

    expect($unit->fresh()?->current_area_code)->toBe('to_process')
        ->and($unit->fresh()?->location_label)->toBe('Corsia B');
});

it('renders the real material detail page for a scanned unit', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $unit = WtsUnit::factory()->create([
        'unit_code' => 'LOT-2026-0200-BIG-BAG-01',
        'current_area_code' => 'to_process',
        'location_label' => 'Corsia A - scaffale 2',
    ]);

    actingAs($user)
        ->get('/wts/material-detail?unit='.$unit->id)
        ->assertOk()
        ->assertSee('LOT-2026-0200-BIG-BAG-01')
        ->assertSee('Corsia A - scaffale 2')
        ->assertSee($unit->inboundLot?->lot_code)
        ->assertSee($unit->inboundLot?->inboundRecord?->form_number);

    Livewire::actingAs($user)
        ->withQueryParams(['unit' => $unit->id])
        ->test(WtsMaterialDetailPage::class)
        ->call('moveToArea', 'in_process', 'Reparto pressa')
        ->assertHasNoErrors();

    expect($unit->fresh()?->current_area_code)->toBe('in_process')
        ->and($unit->fresh()?->location_label)->toBe('Reparto pressa');
});

it('reprints the label of a collo directly from the material detail page', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $unit = WtsUnit::factory()->create([
        'unit_code' => 'LOT-2026-0201-BIG-BAG-01',
        'current_area_code' => 'to_process',
    ]);

    Livewire::actingAs($user)
        ->withQueryParams(['unit' => $unit->id])
        ->test(WtsMaterialDetailPage::class)
        ->call('reprintUnitLabel')
        ->assertHasNoErrors();

    expect($unit->fresh()?->labelPrints()->where('print_reason_code', 'reprint')->count())->toBe(1);

    // Nessun collo selezionato: nessuna ristampa, nessun errore.
    Livewire::actingAs($user)
        ->test(WtsMaterialDetailPage::class)
        ->call('reprintUnitLabel')
        ->assertHasNoErrors();
});
