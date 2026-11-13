<?php

declare(strict_types=1);

namespace Themes\Sixteen\Tests\Feature;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Modules\Blog\Models\Article;
use Tests\TestCase;

/**
 * Homepage «Novità» and «Eventi» sections (blocks home.news / home.events in pages/home.json, every locale).
 * Story: Themes/Sixteen/docs/stories/home-news-events-sections.story.md.
 * CMS pages are only READ here (Sushi over the tenant JSON); nothing is written to the content directory.
 */
final class HomeNewsEventsTest extends TestCase
{
    /** @var list<string> */
    protected array $connectionsToTransact = ['mariadb', 'user', 'fixcity', 'blog'];

    /** `articles.type` (Parental STI) exists in the cloned schema; a full migrate would run DDL inside the transaction. */
    protected function runTestingMigrations(): void {}

    public function test_homepage_lists_posts_and_upcoming_events_in_italian_and_english(): void
    {
        $now = Carbon::now();
        $this->makeArticle('post', 'Home news post alpha', $now->copy()->subDay());
        $this->makeArticle('event', 'Home upcoming event beta', $now->copy()->subDay(), $now->copy()->addDays(5));
        $this->makeArticle('event', 'Home past event gamma', $now->copy()->subMonth(), $now->copy()->subDays(5));

        foreach (['it' => ['Vedi tutte le novità', 'Vedi tutti gli eventi'], 'en' => ['See all news', 'See all events']] as $locale => $labels) {
            $response = $this->get('/'.$locale)->assertOk();

            $response->assertSee('data-section="home-news"', false);
            $response->assertSee('data-section="home-events"', false);
            $response->assertSee('Home news post alpha');
            $response->assertSee('Home upcoming event beta');
            $response->assertDontSee('Home past event gamma');
            $response->assertSee('aria-label="'.e($labels[0]).'"', false);
            $response->assertSee('aria-label="'.e($labels[1]).'"', false);
            $response->assertSee('alt="Home news post alpha"', false);
        }
    }

    public function test_sections_are_hidden_without_published_articles(): void
    {
        $this->makeArticle('event', 'Home only past event', Carbon::now()->subMonth(), Carbon::now()->subDays(2));

        $response = $this->get('/it')->assertOk();

        $response->assertDontSee('data-section="home-news"', false);
        $response->assertDontSee('data-section="home-events"', false);
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
            'main_image_url' => '/themes/Sixteen/design-comuni/assets/images/notizia-ludoteca.png',
        ]);
        $article->save();

        return $article;
    }
}
