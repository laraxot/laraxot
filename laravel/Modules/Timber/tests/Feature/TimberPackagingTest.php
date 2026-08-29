<?php

declare(strict_types=1);

use InvalidArgumentException;
use Modules\Timber\Actions\GenerateTimberPackagedUnitsAction;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberPackagedUnit;
use Modules\Timber\Tests\TestCase;

uses(TestCase::class);

it('generates packaged units, decrements the source material and prints labels', function (): void {
    $material = TimberDerivedMaterial::factory()->createOne([
        'quantity' => 1000,
        'unit_of_measure' => 'kg',
        'is_waste' => false,
        'status_code' => 'pronto_da_confezionare',
    ]);

    $units = app(GenerateTimberPackagedUnitsAction::class)->execute($material, 'bancale', 4, 200.0, null);

    expect($units)->toHaveCount(4)
        ->and(TimberPackagedUnit::query()->where('derived_material_id', $material->id)->count())->toBe(4)
        ->and((float) $material->fresh()?->quantity)->toBe(200.0);

    $firstUnit = $units->first();
    expect($firstUnit)->not->toBeNull();
    assert($firstUnit instanceof TimberPackagedUnit);

    expect($firstUnit->package_type_code)->toBe('bancale')
        ->and($firstUnit->status_code)->toBe('pronto_per_vendita')
        ->and($firstUnit->labelPrints)->toHaveCount(1)
        ->and($firstUnit->qr_code)->toContain('CONFEZIONE=');
});

it('marks the source material ready for sale once fully packaged', function (): void {
    $material = TimberDerivedMaterial::factory()->createOne([
        'quantity' => 400,
        'is_waste' => false,
        'status_code' => 'pronto_da_confezionare',
    ]);

    app(GenerateTimberPackagedUnitsAction::class)->execute($material, 'sacco', 2, 200.0, null);

    expect((float) $material->fresh()?->quantity)->toBe(0.0)
        ->and($material->fresh()?->status_code)->toBe('pronto_per_vendita');
});

it('rejects an unknown package type', function (): void {
    $material = TimberDerivedMaterial::factory()->createOne(['quantity' => 100, 'is_waste' => false]);

    expect(fn (): mixed => app(GenerateTimberPackagedUnitsAction::class)->execute($material, 'not-a-type', 1, 10.0, null))
        ->toThrow(InvalidArgumentException::class);
});

it('rejects a non positive units count', function (): void {
    $material = TimberDerivedMaterial::factory()->createOne(['quantity' => 100, 'is_waste' => false]);

    expect(fn (): mixed => app(GenerateTimberPackagedUnitsAction::class)->execute($material, 'bancale', 0, 10.0, null))
        ->toThrow(InvalidArgumentException::class);
});

it('rejects a non positive quantity per unit', function (): void {
    $material = TimberDerivedMaterial::factory()->createOne(['quantity' => 100, 'is_waste' => false]);

    expect(fn (): mixed => app(GenerateTimberPackagedUnitsAction::class)->execute($material, 'bancale', 1, 0.0, null))
        ->toThrow(InvalidArgumentException::class);
});

it('rejects packaging a waste material', function (): void {
    $material = TimberDerivedMaterial::factory()->createOne(['quantity' => 100, 'is_waste' => true]);

    expect(fn (): mixed => app(GenerateTimberPackagedUnitsAction::class)->execute($material, 'bancale', 1, 10.0, null))
        ->toThrow(InvalidArgumentException::class);
});

it('rejects packaging a quantity exceeding the material availability', function (): void {
    $material = TimberDerivedMaterial::factory()->createOne(['quantity' => 100, 'is_waste' => false]);

    expect(fn (): mixed => app(GenerateTimberPackagedUnitsAction::class)->execute($material, 'bancale', 2, 100.0, null))
        ->toThrow(InvalidArgumentException::class);
});
