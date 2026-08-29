<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Timber\Models\TimberAgent;

/**
 * @extends Factory<TimberAgent>
 */
final class TimberAgentFactory extends Factory
{
    protected $model = TimberAgent::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'zone' => $this->faker->randomElement(['Nord-Est', 'Nord-Ovest', 'Centro', 'Sud']),
            'commission_percent' => $this->faker->randomFloat(2, 2, 8),
            'is_active' => true,
            'notes' => null,
        ];
    }
}
