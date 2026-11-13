<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Blog;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Blog\Models\Article;
use Modules\Blog\View\Composers\ThemeComposer;
use Tests\TestCase;

/**
 * Public Novità and Eventi listings: every published item is reachable through a length-aware pager,
 * featured items are excluded in the query (not after paging), and events list upcoming first.
 * Story: Modules/Blog/docs/stories/blog-events-listing-body-and-search.story.md.
 */
final class ThemeArticleListingTest extends TestCase
{
    /** @var list<string> */
    protected array $connectionsToTransact = ['blog'];

    /** `articles.type` (Parental STI) exists in the cloned schema; a full migrate would run DDL inside the transaction. */
    protected function runTestingMigrations(): void {}

    public function test_blog_posts_are_length_aware_and_every_page_is_reachable(): void
    {
        foreach (range(1, 15) as $index) {
            $this->makeArticle('post', 'Listing post '.$index, Carbon::now()->subDays($index));
        }

        Paginator::currentPageResolver(static fn (): int => 2);
        $posts = app(ThemeComposer::class)->paginatedBlogPosts(12);

        $this->assertInstanceOf(LengthAwarePaginator::class, $posts);
        $this->assertSame(15, $posts->total());
        $this->assertSame(2, $posts->lastPage());
        $this->assertCount(3, $posts->items());
    }

    public function test_blog_posts_exclude_featured_ids_inside_the_query(): void
    {
        $featured = $this->makeArticle('post', 'Featured post', Carbon::now()->subDay());
        $this->makeArticle('post', 'Regular post', Carbon::now()->subDays(2));

        $featuredId = $featured->getKey();
        if (! is_int($featuredId) && ! is_string($featuredId)) {
            $this->fail('Article key must be an int or a string.');
        }
        $posts = app(ThemeComposer::class)->paginatedBlogPosts(12, null, null, [$featuredId]);

        $this->assertSame(1, $posts->total());
        $titles = array_map(static fn (Article $article): string => $article->getTitle(), $posts->items());
        $this->assertSame(['Regular post'], $titles);
    }

    public function test_events_list_upcoming_first_ascending_then_past_most_recent_first(): void
    {
        $now = Carbon::now();
        $this->makeArticle('event', 'Past old', $now->copy()->subMonth(), $now->copy()->subDays(60));
        $this->makeArticle('event', 'Upcoming far', $now->copy()->subMonth(), $now->copy()->addDays(30));
        $this->makeArticle('event', 'Past recent', $now->copy()->subMonth(), $now->copy()->subDays(3));
        $this->makeArticle('event', 'Upcoming soon', $now->copy()->subMonth(), $now->copy()->addDays(2));

        $events = app(ThemeComposer::class)->paginatedEvents(9);

        $this->assertInstanceOf(LengthAwarePaginator::class, $events);
        $this->assertSame(4, $events->total());
        $titles = array_map(static fn (Article $article): string => $article->getTitle(), $events->items());
        $this->assertSame(['Upcoming soon', 'Upcoming far', 'Past recent', 'Past old'], $titles);
    }

    public function test_blog_index_block_renders_a_pager_that_links_every_page(): void
    {
        foreach (range(1, 15) as $index) {
            $this->makeArticle('post', 'Pager post '.$index, Carbon::now()->subDays($index));
        }

        /** @var view-string $blogIndexView */
        $blogIndexView = 'pub_theme::components.blocks.blog.index';
        $html = view($blogIndexView, ['data' => []])->render();

        $this->assertStringContainsString('15 notizie trovate', $html);
        $this->assertStringContainsString('page=2', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
        $this->assertStringContainsString('rel="next"', $html);
    }

    public function test_events_index_block_renders_a_pager_when_events_exceed_one_page(): void
    {
        foreach (range(1, 11) as $index) {
            $this->makeArticle('event', 'Pager event '.$index, Carbon::now()->subMonth(), Carbon::now()->addDays($index));
        }

        /** @var view-string $eventsIndexView */
        $eventsIndexView = 'pub_theme::components.blocks.events.index';
        $html = view($eventsIndexView, ['data' => []])->render();

        $this->assertStringContainsString('class="pagination-wrapper', $html);
        $this->assertStringContainsString('page=2', $html);
        $this->assertStringContainsString('rel="next"', $html);
    }

    private function makeArticle(string $contentType, string $title, Carbon $publishedAt, ?Carbon $eventStart = null): Article
    {
        $article = new Article;
        $article->forceFill([
            'uuid' => (string) Str::uuid(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::lower(Str::random(6)),
            'description' => $title.' description',
            'content_blocks' => [['type' => 'paragraph', 'data' => ['text' => '<p>'.$title.'</p>']]],
            'sidebar_blocks' => [],
            'footer_blocks' => [],
            'type' => $contentType,
            'published_at' => $publishedAt,
            'event_start_date' => $eventStart,
        ]);
        $article->save();

        return $article;
    }
}
