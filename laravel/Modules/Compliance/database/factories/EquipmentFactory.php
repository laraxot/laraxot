<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Compliance\Models\Equipment;

/**
 * @extends Factory<Equipment>
 */
class EquipmentFactory extends Factory
{
    /** @var class-string<Equipment> */
    protected $model = Equipment::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'type' => $this->faker->randomElement(['ladder', 'drill', 'multimeter', 'harness', 'generator', 'analyzer']),
            'serial_number' => strtoupper($this->faker->bothify('EQ-####-???')),
            'status' => $this->faker->randomElement(['available', 'in_use', 'maintenance', 'retired']),
            'last_checked_on' => $this->faker->optional()->dateTimeBetween('-1 year', '-1 month'),
            'next_check_due_on' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'maintenance_due_on' => $this->faker->optional()->dateTimeBetween('now', '+6 months'),
            'assigned_work_order_id' => null,
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
