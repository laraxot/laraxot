<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Compliance\Models\CustomerSystem;
use Modules\Customer\Models\Customer;

/**
 * @extends Factory<CustomerSystem>
 */
class CustomerSystemFactory extends Factory
{
    /** @var class-string<CustomerSystem> */
    protected $model = CustomerSystem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->bothify('SYS-#####')),
            'customer_id' => Customer::factory(),
            'customer_site_id' => null,
            'system_type' => $this->faker->randomElement(['video_surveillance', 'electrical_panel', 'photovoltaic']),
            'description' => $this->faker->optional()->sentence(),
            'brand' => $this->faker->randomElement(['Hikvision', 'ABB', 'SMA', 'Schneider', 'Dahua']),
            'model' => strtoupper($this->faker->bothify('MOD-###')),
            'serial_number' => strtoupper($this->faker->bothify('SN-####-???')),
            'power_kw' => $this->faker->optional()->randomFloat(2, 1, 100),
            'installation_date' => $this->faker->optional()->dateTimeBetween('-8 years', '-1 month'),
            'is_active' => $this->faker->boolean(85),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
