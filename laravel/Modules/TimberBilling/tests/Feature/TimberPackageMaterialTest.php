<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Tests\Feature;

use InvalidArgumentException;
use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

use Modules\TimberBilling\Actions\PackageTimberMaterialAction;
use Modules\TimberBilling\Models\TimberDerivedMaterial;
use Modules\TimberBilling\Models\TimberProduct;
use Modules\TimberBilling\Queries\GetTimberStockMovementsQuery;

it('packages a semi-finished into multiple articles, tracing and loading stock', function (): void {
    $material = TimberDerivedMaterial::factory()->create([
        'traceable_code' => 'LOT-2026-0001·TAG-0703',
        'quantity' => 1000,
        'is_waste' => false,
        'unit_of_measure' => 'kg',
    ]);
    $sacchi = TimberProduct::factory()->create(['code' => '80590-A', 'unit_of_measure' => 'kg']);
    $bancali = TimberProduct::factory()->create(['code' => '80590-B', 'unit_of_measure' => 'kg']);

    $units = app(PackageTimberMaterialAction::class)->execute($material, [
        ['product_id' => $sacchi->getKey(), 'package_type_code' => 'sacco', 'packages_count' => 10, 'quantity_per_package' => 15.0],
        ['product_id' => $bancali->getKey(), 'package_type_code' => 'bancale', 'packages_count' => 2, 'quantity_per_package' => 200.0],
    ]);

    $md = now()->format('md');

    expect($units)->toHaveCount(12)
        ->and((float) $material->refresh()->quantity)->toBe(450.0); // 1000 - 150 - 400

    $sacchiUnit = $units->firstWhere('article_code', '80590-A');
    expect($sacchiUnit->traceable_code)->toBe('LOT-2026-0001·TAG-0703·CNF-'.$md.'-ART80590-A')
        ->and($sacchiUnit->package_type_code)->toBe('sacco');

    $stock = app(GetTimberStockMovementsQuery::class);
    expect($stock->stockForProduct($sacchi->getKey()))->toBe(150.0)
        ->and($stock->stockForProduct($bancali->getKey()))->toBe(400.0);
});

it('rejects packaging with no article lines', function (): void {
    $material = TimberDerivedMaterial::factory()->create(['is_waste' => false, 'quantity' => 100]);

    app(PackageTimberMaterialAction::class)->execute($material, []);
})->throws(InvalidArgumentException::class);

it('rejects packaging a waste material', function (): void {
    $material = TimberDerivedMaterial::factory()->create(['is_waste' => true, 'quantity' => 100]);
    $product = TimberProduct::factory()->create();

    app(PackageTimberMaterialAction::class)->execute($material, [
        ['product_id' => $product->getKey(), 'package_type_code' => 'sacco', 'packages_count' => 1, 'quantity_per_package' => 10.0],
    ]);
})->throws(InvalidArgumentException::class);
