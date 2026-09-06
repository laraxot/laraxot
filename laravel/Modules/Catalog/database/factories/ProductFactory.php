<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Catalog\Models\CatalogFrequency;
use Modules\Catalog\Models\CatalogMeasureUnit;
use Modules\Catalog\Models\CatalogModel;
use Modules\Catalog\Models\CatalogPartType;
use Modules\Catalog\Models\Product;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /** @var class-string<Product> */
    protected $model = Product::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'sku' => mb_strtoupper($this->faker->unique()->bothify('SKU-####-???')),
            'description' => $this->faker->optional()->sentence(),
            'catalog_model_id' => CatalogModel::factory(),
            'catalog_part_type_id' => CatalogPartType::factory(),
            'catalog_measure_unit_id' => CatalogMeasureUnit::factory(),
            'catalog_frequency_id' => CatalogFrequency::factory(),
            'unit_price' => $this->faker->randomFloat(2, 1, 2500),
            'is_active' => true,
        ];
    }

    /**
     * @param array<string, mixed> $attributes
     * @return \Illuminate\Database\Eloquent\Collection<int, Product>
     */
    public function create(array $attributes = []): \Illuminate\Database\Eloquent\Collection
    {
        return parent::create($attributes);
    }

    /**
     * @param array<string, mixed> $attributes
     * @return Product
     */
    public function createOne(array $attributes = []): Product
    {
        return parent::createOne($attributes);
    }
}
