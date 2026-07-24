<?php

declare(strict_types=1);

namespace Modules\Bom\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Bom\Models\Bom;
use Modules\Catalog\Models\Product;

/**
 * @extends Factory<Bom>
 */
class BomFactory extends Factory
{
    /** @var class-string<Bom> */
    protected $model = Bom::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::query()->create(['name' => $this->faker->unique()->word()])->id,
            'name' => $this->faker->optional()->word(),
            'is_active' => true,
        ];
    }
}
