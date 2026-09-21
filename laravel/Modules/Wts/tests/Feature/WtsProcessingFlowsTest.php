<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\Wts\Filament\Pages\WtsGrindingWashingPage;
use Modules\Wts\Filament\Pages\WtsPressingPage;
use Modules\Wts\Filament\Pages\WtsSortingPage;
use Modules\Wts\Filament\Pages\WtsUnpackagingPage;
use Modules\User\Models\User;
use Modules\Wts\Actions\GenerateWtsProcessingSheetPdfAction;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Models\WtsUnit;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

it('registers processing from each WTS processing subpage', function (string $pageClass, string $stepType): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
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
        ->test($pageClass)
        ->set('selectedUnitIds', [$unit->id])
        ->set('workedWeight', '120')
        ->set('drumsCount', 1)
        ->call('registerProcessing')
        ->assertHasNoErrors();

    expect(WtsProcessingStep::query()->where('step_type_code', $stepType)->exists())->toBeTrue()
        ->and($unit->fresh()?->status_code)->toBe('finished');
})->with([
    [WtsSortingPage::class, 'sorting'],
    [WtsUnpackagingPage::class, 'unpackaging'],
    [WtsGrindingWashingPage::class, 'grinding_washing'],
    [WtsPressingPage::class, 'pressing'],
]);

it('auto-computes the worked weight from the selected colli', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $record = WtsInboundRecord::factory()->create([
        'entry_weight' => 300,
        'gross_quantity' => 300,
    ]);
    $lot = WtsInboundLot::factory()->create(['inbound_record_id' => $record->id]);
    /** @var \Illuminate\Support\Collection<int, \Modules\Wts\Models\WtsUnit> $units */
    $units = WtsUnit::factory()->count(3)->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $lot->inboundRecord?->technical_sheet_snapshot_id,
        'status_code' => 'available',
        'current_area_code' => 'to_process',
    ]);

    Livewire::actingAs($user);
    $component = Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsSortingPage::class)
        // Al mount tutti i colli sono selezionati -> peso totale del lotto.
        ->assertSet('workedWeight', '300');

    // Selezione parziale (2 di 3) -> somma proporzionale.
    $component->set('selectedUnitIds', [($units[0] ?? throw new \RuntimeException('missing unit 0'))->id, ($units[1] ?? throw new \RuntimeException('missing unit 1'))->id])
        ->assertSet('workedWeight', '200');

    // Nessun collo selezionato -> campo vuoto.
    $component->set('selectedUnitIds', [])
        ->assertSet('workedWeight', '');

    // Il pulsante "Tutti" riporta al peso totale.
    $component->call('selectAllUnits')
        ->assertSet('workedWeight', '300');
});

it('exposes computed output weights and the remaining waste weight', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    config()->set('wts.operational_parameters.standard_drum_weight_kg', [
        'label' => 'Peso fusto', 'value' => 20, 'unit' => 'kg',
    ]);

    $record = WtsInboundRecord::factory()->create(['entry_weight' => 200, 'gross_quantity' => 200]);
    $lot = WtsInboundLot::factory()->create(['inbound_record_id' => $record->id]);
    WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $lot->inboundRecord?->technical_sheet_snapshot_id,
        'status_code' => 'available',
        'current_area_code' => 'to_process',
    ]);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsSortingPage::class)
        ->set('workedWeight', '200')
        ->set('drumsCount', 3) // 3 x 20 kg = 60 kg di output
        ->assertViewHas('outputTotalWeight', 60.0)
        ->assertViewHas('remainderWeight', 140.0) // 200 - 60
        ->assertViewHas('outputWeights', [
            ['label' => 'Fusti', 'count' => 3, 'weight' => 60.0],
        ]);
});

it('generates a PDF processing sheet for a recorded processing step', function (): void {
    $step = WtsProcessingStep::factory()->create([
        'work_sheet_code' => 'WS-SORTING-20260610',
        'step_type_code' => 'sorting',
    ]);

    $pdf = app(GenerateWtsProcessingSheetPdfAction::class)->execute($step);

    expect(strlen($pdf))->toBeGreaterThan(1000)
        ->and($pdf)->toStartWith('%PDF');
});
