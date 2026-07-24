<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Compliance\Models\HeatingSystem;
use Modules\Customer\Models\Customer;

/**
 * @extends Factory<HeatingSystem>
 */
class HeatingSystemFactory extends Factory
{
    /** @var class-string<HeatingSystem> */
    protected $model = HeatingSystem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->bothify('IMP-#####')),
            'customer_id' => Customer::factory(),
            'customer_site_id' => null,
            'description' => $this->faker->optional()->sentence(),
            'fuel_type' => $this->faker->randomElement(['metano', 'gpl', 'gasolio', 'biomassa']),
            'total_power_kw' => $this->faker->randomFloat(2, 10, 150),
            'plant_registry_code' => 'CAT-'.$this->faker->unique()->numerify('######'),
            'installation_date' => $this->faker->optional()->dateTimeBetween('-10 years', '-1 year'),
            'notes' => $this->faker->optional()->sentence(),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
