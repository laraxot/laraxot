<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\Wts\Filament\Pages\WtsLotDetailPage;
use Modules\Wts\Filament\Pages\WtsNewIngressPage;
use Modules\User\Models\User;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsUnit;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

it('covers missing lot branches for label and non-conformity actions in lot detail', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    Livewire::actingAs($user);
    /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
    $livewire = Livewire::test(WtsLotDetailPage::class);
    $livewire->call('reprintAllLabels')
        ->set('nonConformityNote', 'Nota ignorata')
        ->call('reportNonConformity')
        ->call('updateUnitType', 999999, 'drum')
        ->call('reprintUnitLabel', 999999);
    $livewire->assertSet('lotId', null);
    $livewire->assertViewHas('selectedLot', null);
    $livewire->assertHasNoErrors();

    expect(WtsInboundLot::query()->count())->toBe(0);
});

it('normalizes lot detail unit type config and ignores non string keys', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $lot = WtsInboundLot::factory()->create();

    config()->set('wts.unit_types', [
        'big_bag' => 'Big bag',
        5 => 'Da ignorare',
        'drum' => ['invalid'],
    ]);

    Livewire::actingAs($user);
    /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
    $livewire = Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsLotDetailPage::class);
    $livewire->assertViewHas('unitTypeLabels', [
            'big_bag' => 'Big bag',
            'drum' => '',
        ]);
});

it('covers missing lot and config fallback branches in lot detail page', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    config()->set('wts.unit_types', 'invalid-config');

    Livewire::actingAs($user);
    /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
    $livewire = Livewire::test(WtsLotDetailPage::class);
    $livewire->call('addUnits', 'big_bag', 1)
        ->call('removeUnit', 999999);
    $livewire->assertSet('lotId', null);
    $livewire->assertViewHas('selectedLot', null);
    $livewire->assertViewHas('unitTypeLabels', []);
    $livewire->assertHasNoErrors();
});

it('ignores non string unit type keys in lot detail page config', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $lot = WtsInboundLot::factory()->create();

    config()->set('wts.unit_types', [
        'big_bag' => 'Big bag',
        7 => 'Salta',
        'pallet' => ['invalid'],
    ]);

    Livewire::actingAs($user);
    /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
    $livewire = Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsLotDetailPage::class);
    $livewire->assertViewHas('unitTypeLabels', [
            'big_bag' => 'Big bag',
            'pallet' => '',
        ]);
});

it('covers missing record branch and default date in new ingress page', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    Livewire::actingAs($user);
    /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
    $livewire = Livewire::test(WtsNewIngressPage::class);
    $livewire->call('confirmTakingCharge');
    $livewire->assertSet('recordId', null);
    $livewire->assertSet('receivedDate', now()->toDateString());
    $livewire->assertViewHas('selectedRecord', null);
    $livewire->assertHasNoErrors();
});

it('covers invalid taking charge input branch in new ingress page', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $record = WtsInboundRecord::factory()->create([
        'status_code' => 'registered',
    ]);

    Livewire::actingAs($user);
    /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
    $livewire = Livewire::withQueryParams(['record' => $record->id])
        ->test(WtsNewIngressPage::class);
    $livewire->set('grossQuantity', '0')
        ->call('confirmTakingCharge');
    $livewire->assertHasNoErrors();

    expect($record->fresh()?->status_code)->toBe('registered')
        ->and($record->fresh()?->lots()->count())->toBe(0);
});

it('ignores invalid unit ids on the lot detail page when a lot exists', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $lot = WtsInboundLot::factory()->create();
    $unit = WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $lot->inboundRecord?->technical_sheet_snapshot_id,
    ]);

    Livewire::actingAs($user);
    /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
    $livewire = Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsLotDetailPage::class);
    $livewire->call('updateUnitType', 999999, 'drum')
        ->call('reprintUnitLabel', 999999);
    $livewire->assertHasNoErrors();

    expect($unit->fresh()?->unit_type_code)->toBe('big_bag')
        ->and($unit->fresh()?->labelPrints()->count())->toBe(0);
});

it('prints single and bulk labels directly from the lot detail page', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $lot = WtsInboundLot::factory()->create();
    $unit = WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'unit_type_code' => 'big_bag',
        'status_code' => 'available',
    ]);

    Livewire::actingAs($user);
    $component = Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsLotDetailPage::class);

    // Cambio tipologia collo senza passare dalla UI-04.
    $component->call('updateUnitType', $unit->id, 'drum')->assertHasNoErrors();
    expect($unit->fresh()?->unit_type_code)->toBe('drum');

    // Tipologia non valida: ignorata, nessuna modifica.
    $component->call('updateUnitType', $unit->id, 'tipologia-inesistente')->assertHasNoErrors();
    expect($unit->fresh()?->unit_type_code)->toBe('drum');

    $printsBefore = $unit->labelPrints()->count();

    $component->call('reprintUnitLabel', $unit->id)->assertHasNoErrors();
    expect($unit->labelPrints()->count())->toBe($printsBefore + 1);

    $component->call('reprintAllLabels')->assertHasNoErrors();
    expect($unit->labelPrints()->count())->toBe($printsBefore + 2);

    // Collo inesistente: nessuna stampa registrata.
    $component->call('reprintUnitLabel', 999999)->assertHasNoErrors();
    expect($unit->labelPrints()->count())->toBe($printsBefore + 2);
});

it('warns instead of printing when the lot has no units yet', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $lot = WtsInboundLot::factory()->create();

    Livewire::actingAs($user);
    /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
    $livewire = Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsLotDetailPage::class);
    $livewire->call('reprintAllLabels');
    $livewire->assertHasNoErrors();

    expect($lot->units()->count())->toBe(0);
});

it('ignores lot detail label actions when no lot is selected', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    Livewire::actingAs($user);
    /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
    $livewire = Livewire::test(WtsLotDetailPage::class);
    $livewire->call('updateUnitType', 999999, 'drum')
        ->call('reprintUnitLabel', 999999)
        ->call('reprintAllLabels');
    $livewire->assertSet('lotId', null);
    $livewire->assertHasNoErrors();
});
