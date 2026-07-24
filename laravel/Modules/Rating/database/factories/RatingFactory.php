<?php

declare(strict_types=1);

namespace Modules\Rating\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Rating\Enums\RuleEnum;
use Modules\Rating\Models\Rating;

/**
 * @extends Factory<Rating>
 */
class RatingFactory extends Factory
{
    /** @var class-string<Rating> */
    protected $model = Rating::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->words(2, true),
            'color' => $this->faker->hexColor(),
            'txt' => $this->faker->optional()->sentence(),
            'rule' => RuleEnum::ZeroFive,
            'is_disabled' => false,
            'is_readonly' => false,
            'order_column' => $this->faker->numberBetween(0, 100),
        ];
    }
}
