<?php

declare(strict_types=1);

namespace Modules\Blog\View\Composers\Support;

use Illuminate\Support\Collection;
use Modules\Blog\Models\Category;

final class ThemeCategorySupport
{
    public function __construct(
        private readonly ThemeCategoryQueries $categoryQueries = new ThemeCategoryQueries(),
    ) {
    }

    /**
     * @return Collection<int, Category>
     */
    public function categories(): Collection
    {
        return $this->categoryQueries->categories();
    }

    /**
     * @return Collection<int, Category>
     */
    public function navCategories(): Collection
    {
        return $this->categoryQueries->withArticles(limit: 8);
    }

    public function model(string $slug): ?Category
    {
        return Category::where('slug', $slug)->first();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function hotTopics(): array
    {
        return $this->categoryQueries->hotTopics();
    }
}
