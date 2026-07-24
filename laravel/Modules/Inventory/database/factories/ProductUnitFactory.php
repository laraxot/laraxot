<?php

declare(strict_types=1);

namespace Modules\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Catalog\Models\Product;
use Modules\Inventory\Models\ProductUnit;

/**
 * @extends Factory<ProductUnit>
 */
class ProductUnitFactory extends Factory
{
    /** @var class-string<ProductUnit> */
    protected $model = ProductUnit::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::query()->create(['name' => $this->faker->unique()->word()])->id,
            'serial' => strtoupper($this->faker->unique()->bothify('SN-########')),
            'status' => 'available',
        ];
    }
}
