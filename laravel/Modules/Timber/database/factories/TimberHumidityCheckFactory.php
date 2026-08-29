<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Timber\Models\TimberDryingCycle;
use Modules\Timber\Models\TimberHumidityCheck;

/**
 * @extends Factory<TimberHumidityCheck>
 */
final class TimberHumidityCheckFactory extends Factory
{
    protected $model = TimberHumidityCheck::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'drying_cycle_id' => TimberDryingCycle::factory(),
            'checked_at' => now(),
            'humidity_percent' => $this->faker->randomFloat(2, 18, 45),
            'notes' => null,
        ];
    }
}
