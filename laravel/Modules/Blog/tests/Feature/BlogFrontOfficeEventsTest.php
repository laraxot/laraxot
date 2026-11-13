<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Blog;

use Modules\Blog\Database\Seeders\DemoContentSeeder;
use Modules\Blog\Models\Article;
use Modules\Blog\View\Composers\ThemeComposer;
use Modules\Cms\Actions\ResolvePageAction;
use Tests\TestCase;

/**
 * Behaviour: FO /it/events — type=event only, featured read-only, detail SEO.
 * Related: DemoContentSeederTest.php, BlogFrontOfficeNovitaTest.php.
 * User: «completa http://192.168.1.40:8000/it/events utilizzando bmad + second brain + filament blueprint».
 */
class BlogFrontOfficeEventsTest extends TestCase
{

    /** @var list<string> */
    protected array $connectionsToTransact = ['blog', 'comment'];

    public function test_paginated_and_featured_events_are_type_event_only(): void
    {
        app(DemoContentSeeder::class)->run();

        $composer = app(ThemeComposer::class);
        $events = $composer->paginatedEvents(20);
        $featured = $composer->featuredEvents(10);

        $this->assertGreaterThanOrEqual(5, count($events->items()));
        foreach ($events->items() as $event) {
            $this->assertSame('event', $event->getAttribute('type'));
        }

        $this->assertNotEmpty($featured);
        foreach ($featured as $event) {
            $this->assertSame('event', $event->getAttribute('type'));
            $this->assertTrue(
                (bool) $event->getAttribute('is_featured') || (bool) $event->getAttribute('show_on_homepage'));
        }
    }

    public function test_find_published_event_by_slug_ignores_posts(): void
    {
        app(DemoContentSeeder::class)->run();

        $post = Article::query()->where('type', 'post')->firstOrFail();
        $composer = app(ThemeComposer::class);

        $this->assertNull($composer->findPublishedEventBySlug($post->slug));

        $event = Article::query()->where('type', 'event')->firstOrFail();
        $found = $composer->findPublishedEventBySlug($event->slug);
        $this->assertInstanceOf(Article::class, $found);
        $this->assertSame('event', $found->getAttribute('type'));
    }

    public function test_resolve_page_action_events_branch_returns_only_events(): void
    {
        app(DemoContentSeeder::class)->run();

        $action = app(ResolvePageAction::class);
        $event = Article::query()->where('type', 'event')->firstOrFail();
        $post = Article::query()->where('type', 'post')->firstOrFail();

        $resolvedEvent = $action->execute('events', $event->slug);
        $this->assertSame('model', $resolvedEvent->renderMode);
        $this->assertInstanceOf(Article::class, $resolvedEvent->item);
        $this->assertSame('event', $resolvedEvent->item->getAttribute('type'));

        $resolvedPost = $action->execute('events', $post->slug);
        $this->assertNull($resolvedPost->item);
    }

    public function test_events_index_http_shows_featured_and_explore(): void
    {
        app(DemoContentSeeder::class)->run();

        $response = $this->get('/it/events');
        $response->assertOk();
        $response->assertSee('Eventi', false);
        $response->assertSee('Eventi in evidenza', false);
        $response->assertSee('Tutti gli eventi', false);
    }

    public function test_events_detail_http_uses_article_title(): void
    {
        app(DemoContentSeeder::class)->run();

        $slug = 'festa-delle-associazioni';
        $response = $this->get('/it/events/'.$slug);
        $response->assertOk();
        $response->assertSee('Festa delle associazioni', false);
        $response->assertSee('Torna agli eventi', false);
        $response->assertSee('Asset Design Comuni', false);
    }
}
