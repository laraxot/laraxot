<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Blog;

use Modules\Blog\Database\Seeders\DemoContentSeeder;
use Modules\Blog\Models\Article;
use Modules\Blog\View\Composers\ThemeComposer;
use Modules\Cms\Actions\ResolvePageAction;
use Tests\TestCase;

/**
 * Behaviour under test: FO /it/blog Novità — type=post only, featured read-only, detail SEO.
 * Existing related: DemoContentSeederTest.php (seed data). No prior FO listing HTTP test.
 * User: «completa http://192.168.1.40:8000/it/blog fa tutto con bmad + second brain + filament blueprint».
 */
class BlogFrontOfficeNovitaTest extends TestCase
{
    /** @var list<string> */
    protected array $connectionsToTransact = ['blog', 'comment'];

    public function test_paginated_and_featured_posts_are_content_type_post_only(): void
    {
        app(DemoContentSeeder::class)->run();

        $composer = app(ThemeComposer::class);
        $posts = $composer->paginatedBlogPosts(20);
        $featured = $composer->featuredBlogPosts(10);

        $postItems = $posts->items();
        $this->assertGreaterThanOrEqual(8, count($postItems));
        foreach ($postItems as $post) {
            $this->assertSame('post', $post->getAttribute('type'));
        }

        $this->assertNotEmpty($featured);
        foreach ($featured as $post) {
            $this->assertSame('post', $post->getAttribute('type'));
            $this->assertTrue(
                (bool) $post->getAttribute('is_featured') || (bool) $post->getAttribute('show_on_homepage'));
        }
    }

    public function test_find_published_post_by_slug_ignores_events(): void
    {
        app(DemoContentSeeder::class)->run();

        $event = Article::query()->where('type', 'event')->firstOrFail();
        $composer = app(ThemeComposer::class);

        $this->assertNull($composer->findPublishedPostBySlug($event->slug));

        $post = Article::query()->where('type', 'post')->firstOrFail();
        $found = $composer->findPublishedPostBySlug($post->slug);
        $this->assertInstanceOf(Article::class, $found);
        $this->assertSame('post', $found->getAttribute('type'));
    }

    public function test_resolve_page_action_blog_branch_returns_only_posts(): void
    {
        app(DemoContentSeeder::class)->run();

        $action = app(ResolvePageAction::class);
        $post = Article::query()->where('type', 'post')->firstOrFail();
        $event = Article::query()->where('type', 'event')->firstOrFail();

        $resolvedPost = $action->execute('blog', $post->slug);
        $this->assertSame('model', $resolvedPost->renderMode);
        $this->assertInstanceOf(Article::class, $resolvedPost->item);
        $this->assertSame('post', $resolvedPost->item->getAttribute('type'));

        $resolvedEvent = $action->execute('blog', $event->slug);
        $this->assertNull($resolvedEvent->item);
    }

    public function test_blog_index_http_shows_featured_and_explore(): void
    {
        app(DemoContentSeeder::class)->run();

        $response = $this->get('/it/blog');
        $response->assertOk();
        $response->assertSee('Novità', false);
        $response->assertSee('Notizie in evidenza', false);
        $response->assertSee('Esplora tutte le novità', false);
        $response->assertSee('Piano asfaltature d\'autunno: 14 strade rinnovate');
    }

    public function test_blog_detail_http_uses_article_title_and_shows_credit(): void
    {
        app(DemoContentSeeder::class)->run();

        $slug = 'piano-asfaltature-autunno';
        $response = $this->get('/it/blog/'.$slug);
        $response->assertOk();
        // il layout SEO aggiunge il nome del sito al titolo
        $response->assertSee('<title>'.e('Piano asfaltature d\'autunno: 14 strade rinnovate').' | ', false);
        $response->assertSee('Asset Design Comuni', false);
        $response->assertSee('Commenti', false);
        $response->assertSee('Torna alle novità', false);
    }
}
