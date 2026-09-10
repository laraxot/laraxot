<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\Wts\Actions\ReportWtsNonConformityAction;
use Modules\Wts\Actions\UpdateWtsOperationalParameterAction;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsNonConformityReport;
use Modules\Wts\Models\WtsOperationalParameter;
use Modules\Wts\Queries\GetWtsOperationalParametersQuery;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('seeds operational parameters from the WTS configuration when missing', function (): void {
    $parameters = app(GetWtsOperationalParametersQuery::class)->execute();

    expect($parameters)
        ->toHaveKey('standard_drum_weight_kg')
        ->and($parameters['standard_drum_weight_kg']->value)->toBe('18')
        ->and($parameters['standard_drum_weight_kg']->unit)->toBe('kg')
        ->and(WtsOperationalParameter::query()->where('code', 'standard_dirty_drum_weight_kg')->exists())->toBeTrue();
});

it('updates an operational parameter with validation', function (): void {
    app(GetWtsOperationalParametersQuery::class)->execute();

    $parameter = app(UpdateWtsOperationalParameterAction::class)->execute(
        code: 'standard_pallet_weight_kg',
        value: '28',
        updatedByUserId: null,
    );

    expect($parameter->value)->toBe('28')
        ->and($parameter->unit)->toBe('kg')
        ->and($parameter->fresh()?->value)->toBe('28');

    expect(fn () => app(UpdateWtsOperationalParameterAction::class)->execute(
        code: 'missing_parameter',
        value: '10',
        updatedByUserId: null,
    ))->toThrow(InvalidArgumentException::class);
});

it('records a WTS non conformity against the selected lot and formulario', function (): void {
    $record = WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1001',
        'form_number' => 'FIR-2026-001',
        'source_company_name' => 'Grafica Veneta Spa',
        'gross_quantity' => 1250,
        'status_code' => 'ready_for_labelling',
        'received_at' => now(),
    ]);

    $lot = WtsInboundLot::query()->create([
        'inbound_record_id' => $record->id,
        'lot_code' => 'LOT-2026-0001',
        'status_code' => 'registered',
        'current_area_code' => 'incoming',
    ]);

    $report = app(ReportWtsNonConformityAction::class)->execute(
        lot: $lot,
        note: 'Imballo danneggiato in scarico',
        reportedByUserId: null,
    );

    expect($report->form_number)->toBe('FIR-2026-001')
        ->and($report->note)->toBe('Imballo danneggiato in scarico')
        ->and($report->recipient_email)->toBe('qualita@wts.example')
        ->and($report->lot?->is($lot))->toBeTrue();

    expect(fn () => app(ReportWtsNonConformityAction::class)->execute(
        lot: $lot,
        note: ' ',
        reportedByUserId: null,
    ))->toThrow(InvalidArgumentException::class);
});

it('shows and updates WTS operational parameters from the WTS panel', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts', 'wts_admin']);

    actingAs($user);
    get('/wts/operational-parameters')
        ->assertOk()
        ->assertSee('Parametri operativi WTS')
        ->assertSee('Peso standard fusto');

    Livewire::actingAs($user);
    Livewire::test(Modules\Wts\Filament\Pages\WtsOperationalParametersPage::class)
        ->set('parameterValues.standard_drum_weight_kg', '19')
        ->call('saveParameter', 'standard_drum_weight_kg')
        ->assertHasNoErrors();

    expect(WtsOperationalParameter::query()->where('code', 'standard_drum_weight_kg')->value('value'))->toBe('19');
});
