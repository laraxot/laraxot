<?php

declare(strict_types=1);

namespace Modules\Xot\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Xot\Models\XotBaseMorphPivot;

/**
 * Base MorphPivot Factory.
 *
 * Factory for XotBaseMorphPivot model.
 * This is the base factory for all module-specific MorphPivot implementations.
 *
 * @extends Factory<XotBaseMorphPivot>
 */
class XotBaseMorphPivotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<XotBaseMorphPivot>
     */
    protected $model = XotBaseMorphPivot::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->uuid(),
            'morph_type' => 'Modules\\'.fake()->word().'\\Models\\'.fake()->word(),
            'morph_id' => fake()->randomNumber(),
            'related_type' => fake()->optional(0.7)->randomElement([
                'Modules\Xot\Contracts\UserContract',
                'Modules\Post\Models\Post',
                'Modules\Comment\Models\Comment',
            ]),
            'related_id' => fake()->optional()->randomNumber(),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'deleted_at' => fake()->optional(0.1)->dateTime(),
            'created_by' => fake()->optional(0.7)->uuid(),
            'updated_by' => fake()->optional(0.7)->uuid(),
            'deleted_by' => fake()->optional(0.1)->uuid(),
        ];
    }
}
