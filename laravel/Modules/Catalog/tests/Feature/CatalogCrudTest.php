<?php

declare(strict_types=1);

namespace Modules\Catalog\Tests\Feature;

use Modules\Catalog\Models\CatalogFrequency;
use Modules\Catalog\Models\CatalogMeasureUnit;
use Modules\Catalog\Models\CatalogModel;
use Modules\Catalog\Models\CatalogPartType;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Tests\TestCase;

uses(TestCase::class);

it('creates catalog lookups and a product', function (): void {
    $model = CatalogModel::query()->create(['name' => 'Model X']);
    $partType = CatalogPartType::query()->create(['name' => 'Part']);
    $unit = CatalogMeasureUnit::query()->create(['name' => 'pcs']);
    $freq = CatalogFrequency::query()->create(['name' => 'once']);
    $product = Product::query()->create(['name' => 'Test product',
        'sku' => 'SKU-001',
        'description' => 'Desc',
        'unit_price' => 12.34,
        'is_active' => 1,
        'catalog_model_id' => $model->id,
        'catalog_part_type_id' => $partType->id,
        'catalog_measure_unit_id' => $unit->id,
        'catalog_frequency_id' => $freq->id,
    ]);

    expect($product->id)->not->toBeNull();
    expect(Product::query()->count())->toBe(1);
});
