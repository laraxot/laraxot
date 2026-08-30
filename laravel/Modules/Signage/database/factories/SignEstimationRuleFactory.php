<?php

declare(strict_types=1);

namespace Modules\Signage\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Signage\Models\SignEstimationRule;

/**
 * @extends Factory<SignEstimationRule>
 */
final class SignEstimationRuleFactory extends Factory
{
    protected $model = SignEstimationRule::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role_code' => $this->faker->unique()->randomElement(['body', 'face', 'cutting', 'led', 'cable', 'fixing', 'paint']),
            'unit_code' => $this->faker->randomElement(['mq', 'mt', 'nr', 'min']),
            'strategy_code' => $this->faker->randomElement(['area', 'perimeter', 'constant']),
            'factor' => 1,
            'base_value' => 0,
            'min_per_piece' => null,
            'is_active' => true,
        ];
    }
}
