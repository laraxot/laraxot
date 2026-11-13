@props(['post' => null, 'item' => null, 'container0' => 'blog', 'slug0' => null, 'slug' => null])

{{-- Callers: CMS pages/blog/view.json (slug view → blog.view) e Folio blog/[slug]. Il corpo è l'Article, non il JSON. --}}
{{-- Layout: lead+body PRIMA dell'immagine così il testo non resta sotto la cookiebar fissa (y~749). --}}
@php
    if (! $post instanceof \Modules\Blog\Models\Article && $item instanceof \Modules\Blog\Models\Article) {
        $post = $item;
    }
    if (! $post instanceof \Modules\Blog\Models\Article) {
        $lookup = is_string($slug0) && $slug0 !== '' ? $slug0 : (is_string($slug) ? $slug : '');
        $found = $lookup === ''
            ? null
            : app(\Modules\Blog\View\Composers\ThemeComposer::class)->findPublishedPostBySlug($lookup);
        $post = $found instanceof \Modules\Blog\Models\Article ? $found : null;
    }
@endphp
@if ($post instanceof \Modules\Blog\Models\Article)
@php
    $title = (string) $post->getAttribute('title');
    $isEvent = $container0 === 'events';
        $category = $post->category?->getAttribute('name');
    $description = (string) ($post->getAttribute('description') ?? '');
@endphp

<div class="container" id="main-container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="cmp-breadcrumbs" role="navigation">
                <nav class="breadcrumb-container" aria-label="{{ __('pub_theme::blocks_common.shared.breadcrumb.label') }}">
                    <ol class="breadcrumb p-0" data-element="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">{{ __('pub_theme::navigation.home') }}</a>
                            <span class="separator">/</span>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('container0.index', ['container0' => $isEvent ? 'events' : 'blog']) }}">{{ $isEvent ? __('pub_theme::navigation.homepage.events') : __('pub_theme::navigation.homepage.news') }}</a>
                            <span class="separator">/</span>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<article class="container pt-3 pb-5 blog-article-detail" aria-labelledby="post-title">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <header class="mb-3">
                @if (is_string($category) && $category !== '')
                    <p class="category-top mb-2">
                        <span class="category">{{ \Illuminate\Support\Str::upper($category) }}</span>
                    </p>
                @endif
                <h1 id="post-title" class="text-black h2 mb-2" data-element="page-name">{{ $title }}</h1>
                <p class="text-muted mb-0">
                    @if ($isEvent && $post->event_start_date)
                        <time datetime="{{ $post->event_start_date->toIso8601String() }}">
                            {{ $post->event_start_date->translatedFormat('j F Y, H:i') }}
                        </time>
                        @if ($post->event_end_date)
                            <span aria-hidden="true"> – </span>
                            <time datetime="{{ $post->event_end_date->toIso8601String() }}">
                                {{ $post->event_end_date->translatedFormat('j F Y, H:i') }}
                            </time>
                        @endif
                    @else
                        <time datetime="{{ optional($post->published_at)->toDateString() }}">
                            {{ optional($post->published_at)->translatedFormat('j F Y') }}
                        </time>
                    @endif
                </p>
            </header>

            @if ($description !== '')
                <p class="lead mb-3">{{ $description }}</p>
            @endif

            {{-- Corpo: tutti i blocchi dell'editor + figura principale (partial condiviso con events/detail). --}}
            @include('pub_theme::components.blocks.partials.article-body', [
                'article' => $post,
                'emptyMessage' => $description === '' ? __('blog::front_office.body.empty') : '',
            ])

            <section class="mt-5 pt-4 border-top" aria-labelledby="comments-title">
                <h2 id="comments-title" class="h3 mb-4">{{ __('pub_theme::blocks_content.shared.comments.label') }}</h2>
                @auth
                    @livewire(\Modules\Comment\Filament\Widgets\Commentable\CommentsWidget::class, [
                        'commentableType' => $post::class,
                        'commentableKey' => (string) $post->getKey(),
                        'readOnly' => false,
                        'hideNotifications' => true,
                        'noReplies' => false,
                        'noReactions' => true,
                    ], key('blog-comments-'.$post->getKey()))
                @endauth
                @guest
                    @livewire(\Modules\Comment\Filament\Widgets\Commentable\CommentsWidget::class, [
                        'commentableType' => $post::class,
                        'commentableKey' => (string) $post->getKey(),
                        'readOnly' => true,
                        'hideNotifications' => true,
                        'noReplies' => false,
                        'noReactions' => true,
                    ], key('blog-comments-ro-'.$post->getKey()))
                    <p class="mt-3 text-sm text-muted">
                        <x-comment::login-link class="underline" />
                    </p>
                @endguest
            </section>

            <p class="mt-5">
                <a class="read-more" href="{{ route('container0.index', ['container0' => $isEvent ? 'events' : 'blog']) }}">
                    <span class="text">{{ $isEvent ? 'Torna agli eventi' : 'Torna alle novità' }}</span>
                </a>
            </p>
        </div>
    </div>
</article>
@endif
