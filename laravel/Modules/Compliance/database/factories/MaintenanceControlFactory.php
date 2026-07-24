<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Compliance\Models\HeatingSystem;
use Modules\Compliance\Models\MaintenanceControl;

/**
 * @extends Factory<MaintenanceControl>
 */
class MaintenanceControlFactory extends Factory
{
    /** @var class-string<MaintenanceControl> */
    protected $model = MaintenanceControl::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'heating_system_id' => HeatingSystem::factory(),
            'boiler_id' => null,
            'control_type' => $this->faker->randomElement(['maintenance', 'efficiency_check']),
            'performed_on' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'performed_by_user_id' => null,
            'intervention_id' => null,
            'rcee_type' => $this->faker->optional()->randomElement(['tipo1', 'tipo2']),
            'outcome' => $this->faker->randomElement(['conforme', 'non_conforme']),
            'co_ppm' => $this->faker->randomFloat(2, 0, 100),
            'co2_percent' => $this->faker->randomFloat(2, 5, 12),
            'o2_percent' => $this->faker->randomFloat(2, 3, 15),
            'flue_gas_temp_c' => $this->faker->randomFloat(2, 80, 200),
            'air_temp_c' => $this->faker->randomFloat(2, 15, 30),
            'combustion_efficiency_percent' => $this->faker->randomFloat(2, 85, 99),
            'smoke_index' => $this->faker->numberBetween(0, 9),
            'draft_pa' => $this->faker->randomFloat(2, 1, 20),
            'bollino_code' => strtoupper($this->faker->bothify('BOL-#####')),
            'bollino_paid' => $this->faker->boolean(),
            'next_due_on' => $this->faker->dateTimeBetween('now', '+1 year'),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
