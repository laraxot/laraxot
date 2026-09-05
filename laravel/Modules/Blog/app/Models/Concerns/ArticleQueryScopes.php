<?php

declare(strict_types=1);

namespace Modules\Blog\Models\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Modules\Blog\Models\Article;

/**
 * Query scopes for Article — extracted for claude-audit file size (SRP).
 */
trait ArticleQueryScopes
{
    /**
     * @param EloquentBuilder<Article> $query
     *
     * @return EloquentBuilder<Article>
     */
    public function scopeDifferentFromCurrentArticle(EloquentBuilder $query, string $currentArticle): EloquentBuilder
    {
        return $query->where('id', '!=', $currentArticle);
    }

    /**
     * @param EloquentBuilder<Article> $query
     *
     * @return EloquentBuilder<Article>
     */
    public function scopeArticle(EloquentBuilder $query, string $id): EloquentBuilder
    {
        return $query->where('author_id', $id);
    }

    /**
     * @param EloquentBuilder<Article> $query
     *
     * @return EloquentBuilder<Article>
     */
    public function scopePublished(EloquentBuilder $query): EloquentBuilder
    {
        return $query->whereNotNull('published_at');
    }

    /**
     * @param EloquentBuilder<Article> $query
     *
     * @return EloquentBuilder<Article>
     */
    public function scopeShowHomepage(EloquentBuilder $query): EloquentBuilder
    {
        return $query->where('show_on_homepage', 1);
    }

    /**
     * @param EloquentBuilder<Article> $query
     *
     * @return EloquentBuilder<Article>
     */
    public function scopePublishedUntilToday(EloquentBuilder $query): EloquentBuilder
    {
        return $query->whereDate('published_at', '<=', Carbon::today()->toDateString());
    }

    /**
     * @param EloquentBuilder<Article> $query
     *
     * @return EloquentBuilder<Article>
     */
    public function scopeCategory(EloquentBuilder $query, string $id): EloquentBuilder
    {
        return $query->where('category_id', $id);
    }

    /**
     * @param EloquentBuilder<Article> $query
     *
     * @return EloquentBuilder<Article>
     */
    public function scopeAuthor(EloquentBuilder $query, string $profileId): EloquentBuilder
    {
        return $query->where('author_id', $profileId);
    }

    /**
     * @param EloquentBuilder<Article> $query
     *
     * @return EloquentBuilder<Article>
     */
    public function scopeTag(EloquentBuilder $query, string $id): EloquentBuilder
    {
        return $query->whereHas('tags', static function ($q) use ($id): void {
            $q->where('id', $id);
        });
    }

    /**
     * @param EloquentBuilder<Article> $query
     *
     * @return EloquentBuilder<Article>
     */
    public function scopeSearch(EloquentBuilder $query, string $searching): EloquentBuilder
    {
        return $query->where('title', 'LIKE', "%{$searching}%")
            ->orWhere('content', 'LIKE', "%{$searching}%")
            ->orWhere('excerpt', 'LIKE', "%{$searching}%");
    }
}
