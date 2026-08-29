<?php

declare(strict_types=1);

uses(\Modules\Timber\Tests\TestCase::class);
use Modules\Timber\Actions\CompleteTimberDryingCycleAction;
use Modules\Timber\Actions\MoveTimberMaterialAction;
use Modules\Timber\Actions\StartTimberDryingCycleAction;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberLot;
use Modules\Timber\Tests\TestCase;

function semiFinished(string $code): TimberDerivedMaterial
{
    return TimberDerivedMaterial::factory()->createOne([
        'traceable_code' => $code,
        'quantity' => 100,
        'is_waste' => false,
        'current_area_code' => 'zona_taglio',
        'status_code' => 'da_lavorare',
    ]);
}

it('appends PIAZ and reprints the label when a semi-finished is moved to stagionatura', function (): void {
    $material = semiFinished('LOT-2026-0001·TAG-0703');

    app(MoveTimberMaterialAction::class)->execute($material, 'area_stagionatura', null, null);
    $material->refresh();

    expect($material->traceable_code)->toBe('LOT-2026-0001·TAG-0703·PIAZ')
        ->and($material->current_area_code)->toBe('area_stagionatura')
        ->and($material->labelPrints()->where('print_reason_code', 'move')->exists())->toBeTrue();
});

it('appends ESS and reprints when drying starts on a semi-finished', function (): void {
    $material = semiFinished('LOT-2026-0002·TAG-0703');

    app(StartTimberDryingCycleAction::class)->execute($material, null, 18.0, null);
    $material->refresh();

    expect($material->traceable_code)->toBe('LOT-2026-0002·TAG-0703·ESS')
        ->and($material->current_area_code)->toBe('essiccatoio')
        ->and($material->labelPrints()->where('print_reason_code', 'drying_start')->exists())->toBeTrue();
});

it('records the drying completion without doubling ESS and reprints', function (): void {
    $material = semiFinished('LOT-2026-0003·TAG-0703');
    $cycle = app(StartTimberDryingCycleAction::class)->execute($material, null, 18.0, null);

    app(CompleteTimberDryingCycleAction::class)->execute($cycle);
    $material->refresh();

    expect($material->traceable_code)->toBe('LOT-2026-0003·TAG-0703·ESS')
        ->and(collect($material->stage_history)->contains(fn (array $h): bool => ($h['stage'] ?? null) === 'essiccazione_fine'))->toBeTrue()
        ->and($material->labelPrints()->where('print_reason_code', 'drying_complete')->exists())->toBeTrue();
});

it('does not append the same stage marker twice in a row', function (): void {
    $material = semiFinished('LOT-2026-0005·TAG-0703');

    app(MoveTimberMaterialAction::class)->execute($material, 'area_stagionatura', null, null);
    app(MoveTimberMaterialAction::class)->execute($material->refresh(), 'area_stagionatura', null, null);

    expect($material->refresh()->traceable_code)->toBe('LOT-2026-0005·TAG-0703·PIAZ');
});

it('moves a lot without touching a traceable code', function (): void {
    $lot = TimberLot::factory()->createOne(['current_area_code' => 'piazzale']);

    app(MoveTimberMaterialAction::class)->execute($lot, 'area_stagionatura', null, null);

    expect($lot->fresh()?->current_area_code)->toBe('area_stagionatura');
});
