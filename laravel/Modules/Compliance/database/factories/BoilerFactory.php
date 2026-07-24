<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Compliance\Models\Boiler;
use Modules\Compliance\Models\HeatingSystem;

/**
 * @extends Factory<Boiler>
 */
class BoilerFactory extends Factory
{
    /** @var class-string<Boiler> */
    protected $model = Boiler::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'heating_system_id' => HeatingSystem::factory(),
            'brand' => $this->faker->randomElement(['Ariston', 'Vaillant', 'Baxi', 'Immergas', 'Beretta']),
            'model' => strtoupper($this->faker->bothify('MOD-###')),
            'serial_number' => strtoupper($this->faker->bothify('SN-####-???')),
            'generator_type' => $this->faker->randomElement(['standard', 'condensazione', 'pompa_calore']),
            'fuel_type' => $this->faker->randomElement(['metano', 'gpl', 'gasolio']),
            'power_kw' => $this->faker->randomFloat(2, 18, 120),
            'installation_date' => $this->faker->optional()->dateTimeBetween('-10 years', '-1 year'),
            'maintenance_interval_months' => $this->faker->randomElement([12, 24]),
            'efficiency_check_interval_months' => $this->faker->randomElement([24, 48]),
            'next_maintenance_due_on' => $this->faker->dateTimeBetween('-1 month', '+1 year'),
            'next_efficiency_check_due_on' => $this->faker->dateTimeBetween('now', '+2 years'),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
