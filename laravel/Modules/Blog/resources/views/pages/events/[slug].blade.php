<?php

declare(strict_types=1);

use Illuminate\View\View;
use Modules\Blog\Models\Article;
use Modules\Blog\View\Composers\ThemeComposer;

use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('events.show');

render(function (View $view, string $slug): View {
    $event = app(ThemeComposer::class)->findPublishedEventBySlug($slug);
    abort_unless($event instanceof Article, 404);

    return $view->with([
        'event' => $event,
        'pageTitle' => (string) $event->getAttribute('title'),
        'pageDescription' => (string) ($event->getAttribute('excerpt') ?: $event->getAttribute('description') ?: ''),
    ]);
});

?>

<x-layouts.app
    :title="$pageTitle"
    :meta-description="$pageDescription"
    header-active="live"
>
    <x-pub_theme::blocks.events.detail :event="$event" />
</x-layouts.app>
