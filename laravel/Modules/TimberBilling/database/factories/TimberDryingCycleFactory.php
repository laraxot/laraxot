<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TimberBilling\Models\TimberDryingCycle;
use Modules\TimberBilling\Models\TimberLot;

/**
 * @extends Factory<TimberDryingCycle>
 */
final class TimberDryingCycleFactory extends Factory
{
    protected $model = TimberDryingCycle::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dryable_type' => TimberLot::class,
            'dryable_id' => TimberLot::factory(),
            'entered_at' => now(),
            'expected_exit_at' => now()->addDays(45),
            'actual_exit_at' => null,
            'target_humidity_percent' => 18,
            'available_quantity' => $this->faker->randomFloat(2, 100, 1000),
            'status_code' => 'in_progress',
            'notes' => null,
        ];
    }
}
