<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\Wts\Filament\Pages\WtsPressingPage;
use Modules\Wts\Filament\Pages\WtsProcessingPage;
use Modules\Wts\Filament\Pages\WtsSortingPage;
use Modules\User\Models\User;
use Modules\Wts\Actions\CalculateWtsStandardOutputWeightAction;
use Modules\Wts\Actions\UpdateWtsOperationalParameterAction;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Models\WtsUnit;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

it('multiplies the configured standard weight by the quantity', function (): void {
    $weight = app(CalculateWtsStandardOutputWeightAction::class)->execute('standard_drum_weight_kg', 3);

    expect($weight)->toBe(54.0);
});

it('returns zero for an unknown operational parameter code', function (): void {
    $weight = app(CalculateWtsStandardOutputWeightAction::class)->execute('does_not_exist', 5);

    expect($weight)->toBe(0.0);
});

it('returns zero when the operational parameter value is not numeric', function (): void {
    app(UpdateWtsOperationalParameterAction::class)->execute(
        code: 'standard_pallet_weight_kg',
        value: 'non-numerico',
        updatedByUserId: null,
    );

    $weight = app(CalculateWtsStandardOutputWeightAction::class)->execute('standard_pallet_weight_kg', 4);

    expect($weight)->toBe(0.0);
});

it('computes drum, dirty drum and pallet output weight automatically when registering processing', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $lot = WtsInboundLot::factory()->create();
    $unit = WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $lot->inboundRecord?->technical_sheet_snapshot_id,
        'status_code' => 'available',
    ]);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsSortingPage::class)
        ->set('selectedUnitIds', [$unit->id])
        ->set('drumsCount', 2)
        ->set('dirtyDrumsCount', 1)
        ->set('palletsCount', 3)
        ->call('registerProcessing')
        ->assertHasNoErrors();

    $step = WtsProcessingStep::query()->latest('id')->first();
    $materials = $step?->derivedMaterials()->orderBy('output_type_code')->get();

    expect($materials?->firstWhere('output_type_code', 'drum')?->weight)->toEqualWithDelta(36.0, 0.001)
        ->and($materials?->firstWhere('output_type_code', 'dirty_drum')?->weight)->toEqualWithDelta(22.0, 0.001)
        ->and($materials?->firstWhere('output_type_code', 'pallet')?->weight)->toEqualWithDelta(60.0, 0.001)
        ->and($step?->output_weight)->toEqualWithDelta(118.0, 0.001);
});

it('uses the admin edited standard weight for the next processing instead of the config default', function (): void {
    Role::findOrCreate('wts_admin');
    Role::findOrCreate('wts_admin');

    $admin = \Modules\Wts\Tests\TestCase::createUser();
    $admin->syncRoles(['wts_admin']);

    app(UpdateWtsOperationalParameterAction::class)->execute(
        code: 'standard_pallet_weight_kg',
        value: '20',
        updatedByUserId: $admin->id,
    );

    $operator = \Modules\Wts\Tests\TestCase::createUser();
    $operator->syncRoles(['wts_admin']);

    $lot = WtsInboundLot::factory()->create();
    $unit = WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $lot->inboundRecord?->technical_sheet_snapshot_id,
        'status_code' => 'available',
    ]);

    Livewire::actingAs($operator);
    Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsPressingPage::class)
        ->set('selectedUnitIds', [$unit->id])
        ->set('palletsCount', 6)
        ->call('registerProcessing')
        ->assertHasNoErrors();

    $material = WtsProcessingStep::query()->latest('id')->first()?->derivedMaterials()->first();

    expect($material?->weight)->toEqualWithDelta(120.0, 0.001);
});

it('computes standard output weight for drum, dirty drum and pallet from the UI-07 hub page', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $lot = WtsInboundLot::factory()->create();
    $unit = WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $lot->inboundRecord?->technical_sheet_snapshot_id,
        'status_code' => 'available',
    ]);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsProcessingPage::class)
        ->set('selectedUnitIds', [$unit->id])
        ->set('stepTypeCode', 'sorting')
        ->set('drumsCount', 1)
        ->set('dirtyDrumsCount', 1)
        ->set('palletsCount', 1)
        ->call('processSelection')
        ->assertHasNoErrors();

    $materials = WtsProcessingStep::query()->latest('id')->first()?->derivedMaterials;

    expect($materials?->firstWhere('output_type_code', 'drum')?->weight)->toEqualWithDelta(18.0, 0.001)
        ->and($materials?->firstWhere('output_type_code', 'dirty_drum')?->weight)->toEqualWithDelta(22.0, 0.001)
        ->and($materials?->firstWhere('output_type_code', 'pallet')?->weight)->toEqualWithDelta(20.0, 0.001);
});

it('reflects the admin edited standard weight in the UI-07 hub informational summary', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    app(UpdateWtsOperationalParameterAction::class)->execute(
        code: 'standard_drum_weight_kg',
        value: '99',
        updatedByUserId: null,
    );

    Livewire::actingAs($user);
    Livewire::test(WtsProcessingPage::class)
        ->assertViewHas('operationalParameters', function (array $parameters): bool {
            $drum = $parameters['standard_drum_weight_kg'] ?? null;

            return is_array($drum) && ($drum['value'] ?? null) === '99';
        });
});

it('uses the exceptional manual weight override instead of the automatic calculation', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $lot = WtsInboundLot::factory()->create();
    $unit = WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $lot->inboundRecord?->technical_sheet_snapshot_id,
        'status_code' => 'available',
    ]);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsSortingPage::class)
        ->set('selectedUnitIds', [$unit->id])
        ->set('drumsCount', 2)
        ->set('drumsWeightOverride', '50,5')
        ->set('dirtyDrumsCount', 1)
        ->set('dirtyDrumsWeightOverride', '30')
        ->set('palletsCount', 3)
        ->set('palletsWeightOverride', '200')
        ->call('registerProcessing')
        ->assertHasNoErrors();

    $materials = WtsProcessingStep::query()->latest('id')->first()?->derivedMaterials()->orderBy('output_type_code')->get();

    expect($materials?->firstWhere('output_type_code', 'drum')?->weight)->toEqualWithDelta(50.5, 0.001)
        ->and($materials?->firstWhere('output_type_code', 'dirty_drum')?->weight)->toEqualWithDelta(30.0, 0.001)
        ->and($materials?->firstWhere('output_type_code', 'pallet')?->weight)->toEqualWithDelta(200.0, 0.001);
});

it('falls back to automatic calculation when the manual override is empty or zero', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $lot = WtsInboundLot::factory()->create();
    $unit = WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $lot->inboundRecord?->technical_sheet_snapshot_id,
        'status_code' => 'available',
    ]);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsSortingPage::class)
        ->set('selectedUnitIds', [$unit->id])
        ->set('drumsCount', 1)
        ->set('drumsWeightOverride', '')
        ->set('palletsCount', 1)
        ->set('palletsWeightOverride', '0')
        ->call('registerProcessing')
        ->assertHasNoErrors();

    $materials = WtsProcessingStep::query()->latest('id')->first()?->derivedMaterials()->orderBy('output_type_code')->get();

    expect($materials?->firstWhere('output_type_code', 'drum')?->weight)->toEqualWithDelta(18.0, 0.001)
        ->and($materials?->firstWhere('output_type_code', 'pallet')?->weight)->toEqualWithDelta(20.0, 0.001);
});

it('uses the exceptional manual weight override from the UI-07 hub page', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $lot = WtsInboundLot::factory()->create();
    $unit = WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $lot->inboundRecord?->technical_sheet_snapshot_id,
        'status_code' => 'available',
    ]);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsProcessingPage::class)
        ->set('selectedUnitIds', [$unit->id])
        ->set('stepTypeCode', 'sorting')
        ->set('drumsCount', 2)
        ->set('drumsWeightOverride', 60)
        ->set('dirtyDrumsCount', 1)
        ->set('dirtyDrumsWeightOverride', 0)
        ->set('palletsCount', 1)
        ->call('processSelection')
        ->assertHasNoErrors();

    $materials = WtsProcessingStep::query()->latest('id')->first()?->derivedMaterials()->orderBy('output_type_code')->get();

    expect($materials?->firstWhere('output_type_code', 'drum')?->weight)->toEqualWithDelta(60.0, 0.001)
        ->and($materials?->firstWhere('output_type_code', 'dirty_drum')?->weight)->toEqualWithDelta(22.0, 0.001);
});
