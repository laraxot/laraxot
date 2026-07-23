<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\Models\Taggable;

/**
 * @extends Factory<Taggable>
 */
/** @extends Factory<Taggable> */
class TaggableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Taggable::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }
}
