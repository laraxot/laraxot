<?php

declare(strict_types=1);

namespace Modules\Blog\View\Composers;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Blog\Datas\ArticleData;
use Modules\Blog\Models\Article;
use Modules\Blog\Models\Category;
use Modules\Blog\Models\Profile;
use Modules\Blog\Models\Tag;
use Modules\Blog\View\Composers\Support\ThemeComposerSupport;
use Modules\UI\Datas\SliderData;

class ThemeComposer
{
    public function __construct(
        private readonly ThemeComposerSupport $support = new ThemeComposerSupport(),
    ) {
    }

    /**
     * @return Collection<int, Category>
     */
    public function categories(): Collection
    {
        return $this->support->categories()->categories();
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategoriesArticles(): Collection
    {
        return $this->categories();
    }

    /**
     * @return EloquentCollection<int, Article>
     */
    public function getArticles(): EloquentCollection
    {
        return $this->support->articles()->allSorted();
    }

    /**
     * @return Collection<int, mixed>
     */
    public function getArticlesType(string $type, int $number = 6): Collection
    {
        $method = 'get'.Str::studly($type).'Articles';
        $result = $this->{$method}($number);

        return $result instanceof Collection ? $result : collect();
    }

    /**
     * @return Collection<int, Article>
     */
    public function getFeaturedArticles(int $number = 6): Collection
    {
        return $this->support->articles()->featured($number);
    }

    /**
     * @return Collection<int, Article>
     */
    public function getLatestArticles(int $number = 6): Collection
    {
        return $this->support->articles()->latest($number);
    }

    /**
     * @return list<ArticleData>
     */
    public function getArticlesByCategory(string $categoryId, int $number = 6): array
    {
        return $this->support->articles()->byCategory($categoryId, $number);
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginateArticlesByCategory(string $categoryId, int $limit = 6): Paginator
    {
        return $this->support->articles()->paginateByCategory($categoryId, $limit);
    }

    /**
     * @return Collection<int, Category>
     */
    public function getNavCategories(): Collection
    {
        return $this->support->categories()->navCategories();
    }

    /**
     * @return Collection<int, Category>
     */
    public function getFooterCategories(): Collection
    {
        return $this->support->categories()->navCategories();
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getFooterAuthors(): Collection
    {
        return $this->support->catalog()->footerAuthors();
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->support->catalog()->tags();
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getFooterTags(): Collection
    {
        return $this->support->catalog()->footerTags();
    }

    /**
     * @return Collection<(int|string), mixed>
     */
    public function getMoreArticles(Model $model): Collection
    {
        return $this->support->articles()->moreArticles($model);
    }

    /**
     * @return LengthAwarePaginator<int, Article>
     */
    public function getPaginatedArticles(int $num = 15): LengthAwarePaginator
    {
        return $this->support->articles()->paginatedArticles($num);
    }

    public function showArticleSidebarContent(string $slug): Renderable
    {
        return $this->support->sidebar()->showArticleSidebarContent($slug);
    }

    /**
     * @return Paginator<int, Article>|array<int|string, mixed>
     */
    public function getMethodData(string $method, int $number = 6): Paginator|array
    {
        $result = $this->{$method}($number);
        if ($result instanceof Paginator) {
            return $result;
        }

        return is_array($result) ? $result : [];
    }

    /**
     * @return list<SliderData>
     */
    public function getBanner(): array
    {
        return $this->support->banners()->all();
    }

    public function getSingleBanner(object $banner): SliderData
    {
        return $this->support->banners()->single($banner);
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticlesFeatured(int $number = 6): Collection
    {
        dddx('wip con article data');

        return $this->getFeaturedArticles($number);
    }

    /**
     * @return list<ArticleData>
     */
    public function getArticlesLatest(int $number = 6): array
    {
        return $this->support->articles()->articlesLatest($number);
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginatedArticlesLatest(int $limit = 6): Paginator
    {
        return $this->support->articles()->paginatedLatest($limit);
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginatedArticlesComingSoon(int $limit = 6): Paginator
    {
        return $this->support->articles()->paginatedComingSoon($limit);
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginatedArticlesOrderByNumberOfBets(int $limit = 6): Paginator
    {
        return $this->support->articles()->paginatedByWagers($limit);
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginatedArticlesOrderByVolumes(int $limit = 6): Paginator
    {
        return $this->support->articles()->paginatedByVolume($limit);
    }

    public function mapArticle(object $article): object
    {
        return $this->support->articles()->mapArticle($article);
    }

    /**
     * @return list<ArticleData>
     */
    public function getArticlesComingSoon(int $number = 6): array
    {
        return $this->support->articles()->comingSoon($number);
    }

    /**
     * @return list<ArticleData>
     */
    public function getArticlesOrderByNumberOfBets(int $number = 6): array
    {
        return $this->support->articles()->orderedByWagers($number);
    }

    /**
     * @return list<ArticleData>
     */
    public function getArticlesOrderByVolumes(int $number = 6): array
    {
        return $this->support->articles()->orderedByVolume($number);
    }

    /**
     * @return list<ArticleData>
     */
    public function getAllArticles(): array
    {
        return $this->support->articles()->allData();
    }

    /**
     * @param Collection<int, Article> $rows
     *
     * @return list<ArticleData>
     */
    public function getArticleDataArray(Collection $rows): array
    {
        return $this->support->articles()->dataArray($rows);
    }

    public function getArticleModel(string $slug): ?Article
    {
        return $this->support->articles()->model($slug);
    }

    public function getCategoryModel(string $slug): ?Model
    {
        return $this->support->categories()->model($slug);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getHotTopics(): array
    {
        return $this->support->categories()->hotTopics();
    }
}
