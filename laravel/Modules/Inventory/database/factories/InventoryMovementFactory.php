<?php

declare(strict_types=1);

namespace Modules\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Catalog\Models\Product;
use Modules\Inventory\Models\InventoryMovement;

/**
 * @extends Factory<InventoryMovement>
 */
class InventoryMovementFactory extends Factory
{
    /** @var class-string<InventoryMovement> */
    protected $model = InventoryMovement::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::query()->create(['name' => $this->faker->unique()->word()])->id,
            'to_warehouse_id' => WarehouseFactory::new()->createOne()->id,
            'movement_type' => 'carico',
            'quantity' => $this->faker->numberBetween(1, 50),
            'origin_auto' => false,
        ];
    }
}
