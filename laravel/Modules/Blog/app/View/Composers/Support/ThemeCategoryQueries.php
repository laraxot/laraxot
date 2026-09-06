<?php

declare(strict_types=1);

namespace Modules\Blog\View\Composers\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\Blog\Models\Article;
use Modules\Blog\Models\Category;

final class ThemeCategoryQueries
{
    /**
     * @return Collection<int, Category>
     */
    public function categories(): Collection
    {
        return Category::tree()->get()->toTree();
    }

    /**
     * @return Collection<int, Category>
     */
    public function withArticles(int $limit): Collection
    {
        return Category::has('articles', '>', 0)
            ->take($limit)
            ->get();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function hotTopics(): array
    {
        /** @var array<int, array<string, mixed>> $categories */
        $categories = Category::with([
            'categoryArticles' => static function (Builder $query): Builder {
                /* @var Builder<Article> $query */
                return $query->withCount('ratings');
            },
        ])
            ->get()
            ->map(function (Category $category): array {
                $ratingsCount = $category->categoryArticles->sum('ratings_count');

                return [
                    'image' => $category->getFirstMediaUrl('category'),
                    'slug' => $category->slug,
                    'title' => $category->title,
                    'ratings_sum' => is_numeric($ratingsCount) ? (int) $ratingsCount : 0,
                ];
            })
            ->sortByDesc('ratings_sum')
            ->take(3)
            ->values()
            ->all();

        return $categories;
    }
}
