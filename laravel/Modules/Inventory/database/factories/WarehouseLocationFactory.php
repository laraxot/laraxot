<?php

declare(strict_types=1);

namespace Modules\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Inventory\Models\WarehouseLocation;

/**
 * @extends Factory<WarehouseLocation>
 */
class WarehouseLocationFactory extends Factory
{
    /** @var class-string<WarehouseLocation> */
    protected $model = WarehouseLocation::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'warehouse_id' => WarehouseFactory::new()->createOne()->id,
            'code' => strtoupper($this->faker->unique()->bothify('LOC-###')),
            'label' => $this->faker->optional()->word(),
            'is_active' => true,
        ];
    }
}
