<?php

declare(strict_types=1);

namespace Modules\Production\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Catalog\Models\Product;
use Modules\Production\Models\ProductionOrder;

/**
 * @extends Factory<ProductionOrder>
 */
class ProductionOrderFactory extends Factory
{
    /** @var class-string<ProductionOrder> */
    protected $model = ProductionOrder::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'PO-'.Str::upper(Str::random(8)),
            'product_id' => Product::query()->create(['name' => $this->faker->unique()->word()])->id,
            'quantity' => $this->faker->numberBetween(1, 100),
            'status' => 'planned',
            'work_order_id' => null,
            'notes' => $this->faker->optional()->sentence(),
            'due_at' => null,
        ];
    }
}
