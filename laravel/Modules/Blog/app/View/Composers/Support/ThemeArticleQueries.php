<?php

declare(strict_types=1);

namespace Modules\Blog\View\Composers\Support;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Modules\Blog\Models\Article;

final class ThemeArticleQueries
{
    /**
     * @return EloquentCollection<int, Article>
     */
    public function allSorted(): EloquentCollection
    {
        return Article::all()->sortBy(['created_at', 'desc']);
    }

    /**
     * @return Collection<int, Article>
     */
    public function featured(int $number): Collection
    {
        $rows = Article::published()
            ->showHomepage()
            ->publishedUntilToday()
            ->take($number)
            ->orderBy('published_at', 'desc')
            ->get();

        if (0 !== $rows->count()) {
            return $rows;
        }

        $rows = Article::get();
        Article::whereRaw('1=1')->update(['show_on_homepage' => true]);

        return $rows;
    }

    /**
     * @return Collection<int, Article>
     */
    public function latest(int $number): Collection
    {
        return Article::published()
            ->publishedUntilToday()
            ->orderBy('published_at', 'desc')
            ->take($number)
            ->get();
    }

    /**
     * @return Collection<int, Article>
     */
    public function byCategory(string $categoryId, int $number): Collection
    {
        return Article::where('category_id', $categoryId)
            ->orderBy('published_at', 'desc')
            ->take($number)
            ->get();
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginateByCategory(string $categoryId, int $limit): Paginator
    {
        return Article::where('category_id', $categoryId)
            ->orderBy('published_at', 'desc')
            ->simplePaginate($limit);
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginatedLatest(int $limit): Paginator
    {
        return Article::published()
            ->publishedUntilToday()
            ->orderBy('published_at', 'desc')
            ->simplePaginate($limit);
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginatedComingSoon(int $limit): Paginator
    {
        return Article::published()
            ->where('event_start_date', '>', now())
            ->orderBy('event_start_date')
            ->simplePaginate($limit);
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginatedByWagers(int $limit): Paginator
    {
        return Article::published()
            ->publishedUntilToday()
            ->orderBy('wagers_count', 'desc')
            ->simplePaginate($limit);
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginatedByVolume(int $limit): Paginator
    {
        return Article::published()
            ->publishedUntilToday()
            ->orderBy('volume_play_money', 'desc')
            ->simplePaginate($limit);
    }

    /**
     * @return Collection<int, Article>
     */
    public function comingSoon(int $number): Collection
    {
        return Article::published()
            ->where('event_start_date', '>', now())
            ->orderBy('event_start_date')
            ->take($number)
            ->get();
    }

    /**
     * @return Collection<int, Article>
     */
    public function orderedByWagers(int $number): Collection
    {
        return Article::published()
            ->publishedUntilToday()
            ->orderBy('wagers_count', 'desc')
            ->take($number)
            ->get();
    }

    /**
     * @return Collection<int, Article>
     */
    public function orderedByVolume(int $number): Collection
    {
        return Article::published()
            ->publishedUntilToday()
            ->orderBy('volume_play_money', 'desc')
            ->take($number)
            ->get();
    }

    /**
     * @return Collection<int, Article>
     */
    public function allByCreatedAt(): Collection
    {
        return Article::orderBy('created_at', 'desc')->get();
    }
}
