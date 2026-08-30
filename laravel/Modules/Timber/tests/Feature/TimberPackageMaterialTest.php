<?php

declare(strict_types=1);

use InvalidArgumentException;
use Modules\Timber\Actions\PackageTimberMaterialAction;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberPackagedUnit;
use Modules\Timber\Models\TimberProduct;
use Modules\Timber\Queries\GetTimberStockMovementsQuery;
use Modules\Timber\Tests\TestCase;

uses(\Modules\Timber\Tests\TestCase::class);

it('packages a semi-finished into multiple articles, tracing and loading stock', function (): void {
    $material = TimberDerivedMaterial::factory()->createOne([
        'traceable_code' => 'LOT-2026-0001·TAG-0703',
        'quantity' => 1000,
        'is_waste' => false,
        'unit_of_measure' => 'kg',
    ]);
    $sacchi = TimberProduct::factory()->createOne(['code' => '80590-A', 'unit_of_measure' => 'kg']);
    $bancali = TimberProduct::factory()->createOne(['code' => '80590-B', 'unit_of_measure' => 'kg']);

    $units = app(PackageTimberMaterialAction::class)->execute($material, [
        ['product_id' => $sacchi->id, 'package_type_code' => 'sacco', 'packages_count' => 10, 'quantity_per_package' => 15.0],
        ['product_id' => $bancali->id, 'package_type_code' => 'bancale', 'packages_count' => 2, 'quantity_per_package' => 200.0],
    ]);

    $md = now()->format('md');

    expect($units)->toHaveCount(12)
        ->and((float) $material->refresh()->quantity)->toBe(450.0); // 1000 - 150 - 400

    $sacchiUnit = $units->firstWhere('article_code', '80590-A');
    expect($sacchiUnit)->not->toBeNull();
    assert($sacchiUnit instanceof TimberPackagedUnit);

    expect($sacchiUnit->traceable_code)->toBe('LOT-2026-0001·TAG-0703·CNF-'.$md.'-ART80590-A')
        ->and($sacchiUnit->package_type_code)->toBe('sacco');

    $stock = app(GetTimberStockMovementsQuery::class);
    expect($stock->stockForProduct($sacchi->id))->toBe(150.0)
        ->and($stock->stockForProduct($bancali->id))->toBe(400.0);
});

it('rejects packaging with no article lines', function (): void {
    $material = TimberDerivedMaterial::factory()->createOne(['is_waste' => false, 'quantity' => 100]);

    expect(fn (): mixed => app(PackageTimberMaterialAction::class)->execute($material, []))
        ->toThrow(InvalidArgumentException::class);
});

it('rejects packaging a waste material', function (): void {
    $material = TimberDerivedMaterial::factory()->createOne(['is_waste' => true, 'quantity' => 100]);
    $product = TimberProduct::factory()->createOne();

    expect(fn (): mixed => app(PackageTimberMaterialAction::class)->execute($material, [
        ['product_id' => $product->id, 'package_type_code' => 'sacco', 'packages_count' => 1, 'quantity_per_package' => 10.0],
    ]))->toThrow(InvalidArgumentException::class);
});
