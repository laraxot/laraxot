<?php

declare(strict_types=1);

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Modules\Blog\Models\Article;
use Tests\TestCase;

uses(TestCase::class);

/*
 * Soft 404 on detail pages (front-office completeness audit, item 2).
 * `/{lang}/{container}/{slug}` without a record must answer 404, not 200 with the empty `{container}.view` template.
 * Story: Themes/Sixteen/docs/stories/front-office-detail-not-found-and-sitemap-page.story.md.
 */
it('answers 404 for a detail slug that has no record', function (string $path): void {
    /** @var TestCase $this */
    $this->get($path)->assertNotFound();
})->with([
    'unknown service' => '/it/services/servizio-inesistente',
    'service template slug' => '/it/services/view',
    'unknown blog post' => '/it/blog/articolo-inesistente-xyz',
    'blog template slug' => '/it/blog/view',
    'unknown event' => '/it/events/evento-inesistente-xyz',
    'events template slug' => '/it/events/view',
]);

it('still serves the listing and the real CMS detail pages', function (string $path): void {
    /** @var TestCase $this */
    $this->get($path)->assertOk();
})->with([
    'services listing' => '/it/services',
    'blog listing' => '/it/blog',
    'events listing' => '/it/events',
    'faq' => '/it/domande-frequenti',
    'ticket track' => '/it/tickets/track',
]);

it('still serves a published post and a published event', function (string $container, string $contentType): void {
    /** @var TestCase $this */
    $title = 'Detail '.$contentType.' '.Str::lower(Str::random(6));
    $article = new Article;
    $article->forceFill([
        'uuid' => (string) Str::uuid(),
        'title' => $title,
        'slug' => Str::slug($title),
        'description' => $title,
        'content_blocks' => [['type' => 'paragraph', 'data' => ['text' => '<p>'.$title.'</p>']]],
        'sidebar_blocks' => [],
        'footer_blocks' => [],
        'type' => $contentType,
        'published_at' => Carbon::now()->subDay(),
        'event_start_date' => $contentType === 'event' ? Carbon::now()->addWeek() : null,
    ]);
    $article->save();

    $this->get('/it/'.$container.'/'.Str::slug($title))->assertOk()->assertSee($title);
})->with([
    'post' => ['blog', 'post'],
    'event' => ['events', 'event'],
]);
