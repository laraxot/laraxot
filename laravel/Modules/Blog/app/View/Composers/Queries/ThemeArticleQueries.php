<?php

declare(strict_types=1);

namespace Modules\Blog\View\Composers\Queries;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
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
        return $this->contentQuery()->get()->sortBy(['created_at', 'desc']);
    }

    /**
     * @return Collection<int, Article>
     */
    public function featured(int $number): Collection
    {
        $rows = $this->contentQuery()->published()
            ->showHomepage()
            ->publishedUntilToday()
            ->take($number)
            ->orderBy('published_at', 'desc')
            ->get();

        if ($rows->count() !== 0) {
            return $rows;
        }

        $rows = $this->contentQuery()->get();
        $this->contentQuery()->update(['show_on_homepage' => true]);

        return $rows;
    }

    /**
     * @return Collection<int, Article>
     */
    public function latest(int $number): Collection
    {
        return $this->contentQuery()->published()
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
        return $this->contentQuery()->where('category_id', $categoryId)
            ->orderBy('published_at', 'desc')
            ->take($number)
            ->get();
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginateByCategory(string $categoryId, int $limit): Paginator
    {
        return $this->contentQuery()->where('category_id', $categoryId)
            ->orderBy('published_at', 'desc')
            ->simplePaginate($limit);
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginatedLatest(int $limit): Paginator
    {
        return $this->contentQuery()->published()
            ->publishedUntilToday()
            ->orderBy('published_at', 'desc')
            ->simplePaginate($limit);
    }

    /**
     * Public editorial posts; events have their own Design Comuni listing.
     * Optional Design Comuni lista filters: keyword search + category slug.
     * Length-aware so the pager can link every page; $excludeIds (featured posts) is applied in the query,
     * so pages stay full and the total matches what the list can reach.
     *
     * @param  list<int|string>  $excludeIds
     * @return LengthAwarePaginator<int, Article>
     */
    public function paginatedPosts(int $limit, ?string $search = null, ?string $categorySlug = null, array $excludeIds = []): LengthAwarePaginator
    {
        $query = $this->contentQuery()
            ->where('type', 'post')
            ->with(['category'])
            ->published()
            ->publishedUntilToday();

        if ($excludeIds !== []) {
            $query->whereKeyNot($excludeIds);
        }

        if (is_string($categorySlug) && $categorySlug !== '') {
            $query->whereHas('category', static function (EloquentBuilder $categoryQuery) use ($categorySlug): void {
                $categoryQuery->where('slug', $categorySlug);
            });
        }

        if (is_string($search) && $search !== '') {
            $this->whereMatches($query, $search);
        }

        return $query
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate($limit)
            ->withQueryString();
    }

    public function findPublishedPostBySlug(string $slug): ?Article
    {
        /** @var Article|null $post */
        $post = $this->publishedPostsQuery()
            ->where('slug', $slug)
            ->with(['category'])
            ->first();

        return $post;
    }

    /**
     * Slug dei post serviti da /{lang}/blog/{slug}: la stessa query di findPublishedPostBySlug, cosi' la sitemap (DCP-273)
     * elenca un URL se e solo se la sua pagina risponde con l'articolo.
     *
     * @return Collection<int, mixed>
     */
    public function publishedPostSlugs(): Collection
    {
        return $this->publishedPostsQuery()->orderBy('slug')->pluck('slug');
    }

    /**
     * Post in evidenza per lista Novità (Design Comuni) — sola lettura, no side-effect.
     * Callers: ThemeArticleBag::featuredPosts → ThemeComposer::featuredBlogPosts → blocks/blog/index.
     * Differisce da featured(): non aggiorna show_on_homepage.
     *
     * @return Collection<int, Article>
     */
    public function featuredPosts(int $number): Collection
    {
        return $this->contentQuery()
            ->where('type', 'post')
            ->where(static function (EloquentBuilder $query): void {
                $query->where('is_featured', true)
                    ->orWhere('show_on_homepage', true);
            })
            ->with(['category'])
            ->published()
            ->publishedUntilToday()
            ->orderByDesc('published_at')
            ->take($number)
            ->get();
    }

    /**
     * Public events listing (Design Comuni «Eventi» / Vivere il comune).
     * Order: upcoming or ongoing events first (soonest start first), then past events (most recent first),
     * then events without a start date. An event is past once its end (or, without an end, its start) is before now.
     *
     * @param  list<int|string>  $excludeIds
     * @return LengthAwarePaginator<int, Article>
     */
    public function paginatedEvents(int $limit, array $excludeIds = []): LengthAwarePaginator
    {
        $now = now()->toDateTimeString();
        $query = $this->contentQuery()
            ->where('type', 'event')
            ->with(['category'])
            ->published();

        if ($excludeIds !== []) {
            $query->whereKeyNot($excludeIds);
        }

        return $query
            ->orderByRaw('CASE WHEN event_start_date IS NULL THEN 2 WHEN COALESCE(event_end_date, event_start_date) >= ? THEN 0 ELSE 1 END', [$now])
            ->orderByRaw('CASE WHEN COALESCE(event_end_date, event_start_date) >= ? THEN event_start_date END ASC', [$now])
            ->orderByDesc('event_start_date')
            ->orderByDesc('id')
            ->paginate($limit)
            ->withQueryString();
    }

    public function findPublishedEventBySlug(string $slug): ?Article
    {
        /** @var Article|null $event */
        $event = $this->publishedEventsQuery()
            ->where('slug', $slug)
            ->with(['category'])
            ->first();

        return $event;
    }

    /**
     * Slug degli eventi serviti da /{lang}/events/{slug}: la stessa query di findPublishedEventBySlug (sitemap, DCP-273).
     *
     * @return Collection<int, mixed>
     */
    public function publishedEventSlugs(): Collection
    {
        return $this->publishedEventsQuery()->orderBy('slug')->pluck('slug');
    }

    /**
     * Eventi in evidenza per lista Eventi (Design Comuni) — sola lettura, no side-effect.
     * Callers: ThemeArticleBag::featuredEvents → ThemeComposer::featuredEvents → blocks/events/index.
     *
     * @return Collection<int, Article>
     */
    public function featuredEvents(int $number): Collection
    {
        return $this->contentQuery()
            ->where('type', 'event')
            ->where(static function (EloquentBuilder $query): void {
                $query->where('is_featured', true)
                    ->orWhere('show_on_homepage', true);
            })
            ->with(['category'])
            ->published()
            ->orderBy('event_start_date')
            ->take($number)
            ->get();
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginatedComingSoon(int $limit): Paginator
    {
        return $this->contentQuery()->where('type', 'event')->published()
            ->where('event_start_date', '>', now())
            ->orderBy('event_start_date')
            ->simplePaginate($limit);
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginatedByWagers(int $limit): Paginator
    {
        return $this->contentQuery()->published()
            ->publishedUntilToday()
            ->orderBy('wagers_count', 'desc')
            ->simplePaginate($limit);
    }

    /**
     * @return Paginator<int, Article>
     */
    public function paginatedByVolume(int $limit): Paginator
    {
        return $this->contentQuery()->published()
            ->publishedUntilToday()
            ->orderBy('volume_play_money', 'desc')
            ->simplePaginate($limit);
    }

    /**
     * @return Collection<int, Article>
     */
    public function comingSoon(int $number): Collection
    {
        return $this->contentQuery()->where('type', 'event')->published()
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
        return $this->contentQuery()->published()
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
        return $this->contentQuery()->published()
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
        return $this->contentQuery()->orderBy('created_at', 'desc')->get();
    }

    /**
     * Cosa significa «pubblicato» per un post: SSoT del dettaglio /blog/{slug} e della sitemap.
     *
     * @return EloquentBuilder<Article>
     */
    private function publishedPostsQuery(): EloquentBuilder
    {
        return $this->contentQuery()
            ->where('type', 'post')
            ->published()
            ->publishedUntilToday();
    }

    /**
     * Cosa significa «pubblicato» per un evento: SSoT del dettaglio /events/{slug} e della sitemap.
     *
     * @return EloquentBuilder<Article>
     */
    private function publishedEventsQuery(): EloquentBuilder
    {
        return $this->contentQuery()
            ->where('type', 'event')
            ->published();
    }

    /**
     * Published posts and events matching $search, newest first: the site-wide front-office search (/{lang}/ricerca).
     * «Published» is the same as the detail pages: posts via publishedPostsQuery(), events via publishedEventsQuery().
     *
     * @return EloquentCollection<int, Article>
     */
    public function searchPublishedPostsAndEvents(string $search, int $limit): EloquentCollection
    {
        $query = $this->contentQuery()->where(function (EloquentBuilder $visible): void {
            $visible->whereIn('id', $this->publishedPostsQuery()->select('id'))
                ->orWhereIn('id', $this->publishedEventsQuery()->select('id'));
        });

        return $this->whereMatches($query, $search)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->take($limit)
            ->get();
    }

    /**
     * Keyword match on title, description and excerpt (LIKE wildcards must already be stripped by the caller).
     *
     * @param  EloquentBuilder<Article>  $query
     * @return EloquentBuilder<Article>
     */
    private function whereMatches(EloquentBuilder $query, string $search): EloquentBuilder
    {
        $like = '%'.$search.'%';

        return $query->where(static function (EloquentBuilder $searchQuery) use ($like): void {
            $searchQuery
                ->where('title', 'like', $like)
                ->orWhere('description', 'like', $like)
                ->orWhere('excerpt', 'like', $like);
        });
    }

    /** @return EloquentBuilder<Article> */
    private function contentQuery(): EloquentBuilder
    {
        // SSoT: Parental STI `type` = post|event (Post, Event); legacy rows senza tipo escluse
        return Article::query()->whereIn('type', ['post', 'event']);
    }
}
