<?php

declare(strict_types=1);

use InvalidArgumentException;
use Modules\TimberBilling\Actions\MoveTimberMaterialAction;
use Modules\TimberBilling\Models\TimberDerivedMaterial;
use Modules\TimberBilling\Models\TimberLot;
use Modules\TimberBilling\Models\TimberMovement;
use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

it('moves a lot to a valid area and records the movement history', function (): void {
    $lot = TimberLot::factory()->createOne(['current_area_code' => 'piazzale']);

    $moved = app(MoveTimberMaterialAction::class)->execute($lot, 'zona_taglio', null, 'Spostamento test');

    expect($moved->current_area_code)->toBe('zona_taglio')
        ->and(TimberMovement::query()
            ->where('movable_type', TimberLot::class)
            ->where('movable_id', $lot->id)
            ->where('from_area_code', 'piazzale')
            ->where('to_area_code', 'zona_taglio')
            ->exists())->toBeTrue();
});

it('moves a derived material to a valid area', function (): void {
    $material = TimberDerivedMaterial::factory()->createOne(['current_area_code' => 'zona_taglio']);

    $moved = app(MoveTimberMaterialAction::class)->execute($material, 'deposito_semilavorati', null, null);

    expect($moved->current_area_code)->toBe('deposito_semilavorati');
});

it('rejects an unknown area code', function (): void {
    $lot = TimberLot::factory()->createOne();

    expect(fn (): mixed => app(MoveTimberMaterialAction::class)->execute($lot, 'area-inesistente', null, null))
        ->toThrow(InvalidArgumentException::class);
});

it('rejects an empty area code', function (): void {
    $lot = TimberLot::factory()->createOne();

    expect(fn (): mixed => app(MoveTimberMaterialAction::class)->execute($lot, '', null, null))
        ->toThrow(InvalidArgumentException::class);
});
