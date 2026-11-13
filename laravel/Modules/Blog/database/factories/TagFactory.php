<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\Models\Tag;

/**
 * @extends Factory<Tag>
 */
class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        $word = $this->faker->word();
        $slug = \Illuminate\Support\Str::slug($word);

        return [
            'name' => [$word],
            'slug' => [$slug],
            'type' => $this->faker->optional()->word(),
            'order_column' => $this->faker->optional()->numberBetween(1, 100),
        ];
    }
}
