<?php

declare(strict_types=1);

use Illuminate\View\View;
use Modules\Blog\Models\Article;
use Modules\Blog\View\Composers\ThemeComposer;

use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('blog.show');

render(function (View $view, string $slug): View {
    $post = app(ThemeComposer::class)->findPublishedPostBySlug($slug);
    abort_unless($post instanceof Article, 404);

    return $view->with([
        'post' => $post,
        'pageTitle' => (string) $post->getAttribute('title'),
        'pageDescription' => (string) ($post->getAttribute('excerpt') ?: $post->getAttribute('description') ?: ''),
    ]);
});

?>

<x-layouts.app
    :title="$pageTitle"
    :meta-description="$pageDescription"
    header-active="novita"
>
</x-layouts.app>
