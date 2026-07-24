<?php

declare(strict_types=1);

namespace Modules\Vehicle\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Vehicle\Models\Vehicle;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    /** @var class-string<Vehicle> */
    protected $model = Vehicle::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'plate_number' => strtoupper($this->faker->unique()->bothify('??###??')),
            'make' => $this->faker->optional()->company(),
            'model' => $this->faker->optional()->word(),
            'year' => $this->faker->optional()->numberBetween(2005, 2026),
            'odometer_km' => $this->faker->optional()->numberBetween(0, 300000),
            'is_active' => true,
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
