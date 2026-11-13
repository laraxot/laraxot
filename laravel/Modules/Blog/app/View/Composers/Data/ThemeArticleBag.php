<?php

declare(strict_types=1);

namespace Modules\Blog\View\Composers\Data;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Blog\Datas\ArticleData;
use Modules\Blog\Models\Article;
use Modules\Blog\View\Composers\Queries\ThemeArticleQueries;

final class ThemeArticleBag
{
    public function __construct(
        private readonly ThemeArticleQueries $articleQueries = new ThemeArticleQueries,
        private readonly ThemeArticleDataMapper $articleDataMapper = new ThemeArticleDataMapper,
    ) {}

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Article>
     */
    public function allSorted(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->articleQueries->allSorted();
    }

    /**
     * @return Collection<int, Article>
     */
    public function featured(int $number): Collection
    {
        return $this->articleQueries->featured($number);
    }

    /**
     * @return Collection<int, Article>
     */
    public function latest(int $number): Collection
    {
        return $this->articleQueries->latest($number);
    }

    /**
     * @return list<ArticleData>
     */
    public function byCategory(string $categoryId, int $number): array
    {
        return $this->articleDataMapper->fromCollection(
            $this->articleQueries->byCategory($categoryId, $number),
        );
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginateByCategory(string $categoryId, int $limit): Paginator
    {
        return $this->articleQueries->paginateByCategory($categoryId, $limit);
    }

    /**
     * @return LengthAwarePaginator<int, Article>
     */
    public function paginatedArticles(int $num): LengthAwarePaginator
    {
        return Article::query()
            ->whereIn('type', ['post', 'event'])
            ->paginate($num);
    }

    /**
     * @return list<ArticleData>
     */
    public function articlesLatest(int $number): array
    {
        return $this->articleDataMapper->fromCollection($this->latest($number));
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginatedLatest(int $limit): Paginator
    {
        return $this->articleQueries->paginatedLatest($limit);
    }

    /**
     * Caller: ThemeComposer::paginatedBlogPosts (ThemeComposer.php ~209) → blocks/blog/index.
     * Existing support layer (no alternate). Reads Article posts via ThemeArticleQueries.
     * User: «per migliorare http://192.168.1.40:8000/it/blog/ … studiare … novita.html …».
     *
     * @param  list<int|string>  $excludeIds
     * @return LengthAwarePaginatorContract<int, Article>
     */
    public function paginatedPosts(int $limit, ?string $search = null, ?string $categorySlug = null, array $excludeIds = []): LengthAwarePaginatorContract
    {
        return $this->articleQueries->paginatedPosts($limit, $search, $categorySlug, $excludeIds);
    }

    /**
     * @return Collection<int, Article>
     */
    public function featuredPosts(int $number): Collection
    {
        return $this->articleQueries->featuredPosts($number);
    }

    public function findPublishedPostBySlug(string $slug): ?Article
    {
        return $this->articleQueries->findPublishedPostBySlug($slug);
    }

    /**
     * @param  list<int|string>  $excludeIds
     * @return LengthAwarePaginatorContract<int, Article>
     */
    public function paginatedEvents(int $limit, array $excludeIds = []): LengthAwarePaginatorContract
    {
        return $this->articleQueries->paginatedEvents($limit, $excludeIds);
    }

    public function findPublishedEventBySlug(string $slug): ?Article
    {
        return $this->articleQueries->findPublishedEventBySlug($slug);
    }

    /**
     * @return Collection<int, Article>
     */
    public function featuredEvents(int $number): Collection
    {
        return $this->articleQueries->featuredEvents($number);
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginatedComingSoon(int $limit): Paginator
    {
        return $this->articleQueries->paginatedComingSoon($limit);
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginatedByWagers(int $limit): Paginator
    {
        return $this->articleQueries->paginatedByWagers($limit);
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginatedByVolume(int $limit): Paginator
    {
        return $this->articleQueries->paginatedByVolume($limit);
    }

    public function mapArticle(object $article): ArticleData
    {
        return $this->articleDataMapper->map($article);
    }

    /**
     * @return list<ArticleData>
     */
    public function comingSoon(int $number): array
    {
        return $this->articleDataMapper->fromCollection($this->articleQueries->comingSoon($number));
    }

    /**
     * @return list<ArticleData>
     */
    public function orderedByWagers(int $number): array
    {
        return $this->articleDataMapper->fromCollection($this->articleQueries->orderedByWagers($number));
    }

    /**
     * @return list<ArticleData>
     */
    public function orderedByVolume(int $number): array
    {
        return $this->articleDataMapper->fromCollection($this->articleQueries->orderedByVolume($number));
    }

    /**
     * @return list<ArticleData>
     */
    public function allData(): array
    {
        return $this->articleDataMapper->fromCollection($this->articleQueries->allByCreatedAt());
    }

    /**
     * @param  Collection<int, Article>  $rows
     * @return list<ArticleData>
     */
    public function dataArray(Collection $rows): array
    {
        return $this->articleDataMapper->fromCollection($rows);
    }

    public function model(string $slug): ?Article
    {
        return Article::query()
            ->whereIn('type', ['post', 'event'])
            ->where('slug', $slug)
            ->first();
    }

    /**
     * @return Collection<(int|string), mixed>
     */
    public function moreArticles(Model $model): Collection
    {
        unset($model);

        return collect([]);
    }
}
