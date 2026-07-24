<?php

declare(strict_types=1);

namespace Modules\Bom\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Bom\Models\BomItem;
use Modules\Catalog\Models\Product;

/**
 * @extends Factory<BomItem>
 */
class BomItemFactory extends Factory
{
    /** @var class-string<BomItem> */
    protected $model = BomItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bom_id' => BomFactory::new()->createOne()->id,
            'component_product_id' => Product::query()->create(['name' => $this->faker->unique()->word()])->id,
            'quantity' => $this->faker->randomFloat(3, 1, 10),
        ];
    }
}
