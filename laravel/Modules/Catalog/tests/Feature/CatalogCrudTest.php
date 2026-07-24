<?php

declare(strict_types=1);

uses(\Modules\Catalog\Tests\TestCase::class);

use Modules\Catalog\Models\CatalogFrequency;
use Modules\Catalog\Models\CatalogMeasureUnit;
use Modules\Catalog\Models\CatalogModel;
use Modules\Catalog\Models\CatalogPartType;
use Modules\Catalog\Models\Product;

it('creates catalog lookups and a product', function (): void {
    /** @var CatalogModel $model */
    $model = CatalogModel::query()->create(['name' => 'Model X']);

    /** @var CatalogPartType $partType */
    $partType = CatalogPartType::query()->create(['name' => 'Part']);

    /** @var CatalogMeasureUnit $unit */
    $unit = CatalogMeasureUnit::query()->create(['name' => 'pcs']);

    /** @var CatalogFrequency $freq */
    $freq = CatalogFrequency::query()->create(['name' => 'once']);

    /** @var Product $product */
    $product = Product::query()->create([
        'name' => 'Test product',
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

