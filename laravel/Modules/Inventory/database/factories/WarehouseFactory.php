<?php

declare(strict_types=1);

namespace Modules\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Inventory\Models\Warehouse;

/**
 * @extends Factory<Warehouse>
 */
class WarehouseFactory extends Factory
{
    /** @var class-string<Warehouse> */
    protected $model = Warehouse::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company(),
            'code' => strtoupper($this->faker->unique()->bothify('WH-###')),
            'is_active' => true,
            'is_final_destination' => false,
        ];
    }
}
