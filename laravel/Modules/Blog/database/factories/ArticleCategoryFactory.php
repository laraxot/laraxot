<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\Models\ArticleCategory;

/**
 * @extends Factory<ArticleCategory>
 */
/** @extends Factory<ArticleCategory> */
class ArticleCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = ArticleCategory::class;

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
