<?php

declare(strict_types=1);

use Modules\Timber\Actions\CompleteTimberDryingCycleAction;
use Modules\Timber\Actions\RecordTimberHumidityCheckAction;
use Modules\Timber\Actions\StartTimberDryingCycleAction;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberDryingCycle;
use Modules\Timber\Models\TimberLot;
use Modules\Timber\Tests\TestCase;

uses(\Modules\Timber\Tests\TestCase::class);

it('starts a drying cycle for a raw lot and moves it to the drying area', function (): void {
    $lot = TimberLot::factory()->createOne([
        'quantity_available' => 500,
        'current_area_code' => 'piazzale',
        'status_code' => 'available',
    ]);

    $cycle = app(StartTimberDryingCycleAction::class)->execute($lot, '2026-03-01', 18.0, 'nota');

    expect($cycle)
        ->and($cycle->status_code)->toBe('in_progress')
        ->and((float) $cycle->available_quantity)->toBe(500.0)
        ->and($lot->fresh()?->status_code)->toBe('in_essiccazione')
        ->and($lot->fresh()?->current_area_code)->toBe('essiccatoio');
});

it('starts a drying cycle for a derived material', function (): void {
    $material = TimberDerivedMaterial::factory()->createOne(['quantity' => 300]);

    $cycle = app(StartTimberDryingCycleAction::class)->execute($material, null, null, null);

    $targetHumidity = config('timber.timber.operational_parameters.target_humidity_percent.value');
    $expectedHumidity = is_numeric($targetHumidity) ? (float) $targetHumidity : 0.0;

    expect((float) $cycle->target_humidity_percent)->toBe($expectedHumidity)
        ->and($material->fresh()?->status_code)->toBe('in_essiccazione')
        ->and($material->fresh()?->current_area_code)->toBe('essiccatoio');
});

it('rejects starting a drying cycle without available quantity', function (): void {
    $lot = TimberLot::factory()->createOne(['quantity_available' => 0]);

    expect(fn (): mixed => app(StartTimberDryingCycleAction::class)->execute($lot, null, null, null))
        ->toThrow(\InvalidArgumentException::class);
});

it('rejects an out of range target humidity', function (): void {
    $lot = TimberLot::factory()->createOne(['quantity_available' => 100]);

    expect(fn (): mixed => app(StartTimberDryingCycleAction::class)->execute($lot, null, 150.0, null))
        ->toThrow(\InvalidArgumentException::class);
});

it('records a humidity check on an in progress cycle', function (): void {
    $lot = TimberLot::factory()->createOne(['quantity_available' => 100]);
    $cycle = app(StartTimberDryingCycleAction::class)->execute($lot, null, null, null);

    $check = app(RecordTimberHumidityCheckAction::class)->execute($cycle, 32.5, null, 'controllo');

    expect((float) $check->humidity_percent)->toBe(32.5)
        ->and($cycle->humidityChecks()->count())->toBe(1);
});

it('rejects a humidity check on a completed cycle', function (): void {
    $lot = TimberLot::factory()->createOne(['quantity_available' => 100]);
    $cycle = app(StartTimberDryingCycleAction::class)->execute($lot, null, null, null);
    app(CompleteTimberDryingCycleAction::class)->execute($cycle);

    $refreshedCycle = $cycle->fresh();
    expect($refreshedCycle)->not->toBeNull();
    assert($refreshedCycle instanceof TimberDryingCycle);

    expect(function () use ($refreshedCycle): void {
        app(RecordTimberHumidityCheckAction::class)->execute($refreshedCycle, 20.0, null, null);
    })->toThrow(\InvalidArgumentException::class);
});

it('rejects an out of range humidity check', function (): void {
    $lot = TimberLot::factory()->createOne(['quantity_available' => 100]);
    $cycle = app(StartTimberDryingCycleAction::class)->execute($lot, null, null, null);

    expect(fn (): mixed => app(RecordTimberHumidityCheckAction::class)->execute($cycle, 250.0, null, null))
        ->toThrow(\InvalidArgumentException::class);
});

it('completes a drying cycle for a raw lot returning it to available status', function (): void {
    $lot = TimberLot::factory()->createOne(['quantity_available' => 100]);
    $cycle = app(StartTimberDryingCycleAction::class)->execute($lot, null, null, null);

    $completed = app(CompleteTimberDryingCycleAction::class)->execute($cycle);

    expect($completed->status_code)->toBe('completed')
        ->and($completed->actual_exit_at)->not->toBeNull()
        ->and($lot->fresh()?->status_code)->toBe('available')
        ->and($lot->fresh()?->current_area_code)->toBe('area_stagionatura');
});

it('completes a drying cycle for a derived material marking it ready to package', function (): void {
    $material = TimberDerivedMaterial::factory()->createOne(['quantity' => 100]);
    $cycle = app(StartTimberDryingCycleAction::class)->execute($material, null, null, null);

    app(CompleteTimberDryingCycleAction::class)->execute($cycle);

    expect($material->fresh()?->status_code)->toBe('pronto_da_confezionare')
        ->and($material->fresh()?->current_area_code)->toBe('deposito_semilavorati');
});

it('rejects completing a cycle that is not in progress', function (): void {
    $lot = TimberLot::factory()->createOne(['quantity_available' => 100]);
    $cycle = app(StartTimberDryingCycleAction::class)->execute($lot, null, null, null);
    app(CompleteTimberDryingCycleAction::class)->execute($cycle);

    $refreshedCycle = $cycle->fresh();
    expect($refreshedCycle)->not->toBeNull();
    assert($refreshedCycle instanceof TimberDryingCycle);

    expect(fn (): mixed => app(CompleteTimberDryingCycleAction::class)->execute($refreshedCycle))
        ->toThrow(\InvalidArgumentException::class);
});
