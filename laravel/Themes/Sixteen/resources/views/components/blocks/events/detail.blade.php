@props(['event' => null, 'item' => null, 'slug0' => null, 'slug' => null])

{{-- Callers: CMS pages/events/view.json (slug view → events.view). Il corpo è l'Article, non il JSON. --}}
@php
    if (! $event instanceof \Modules\Blog\Models\Article && $item instanceof \Modules\Blog\Models\Article) {
        $event = $item;
    }
    if (! $event instanceof \Modules\Blog\Models\Article) {
        $lookup = is_string($slug0) && $slug0 !== '' ? $slug0 : (is_string($slug) ? $slug : '');
        $found = $lookup === ''
            ? null
            : app(\Modules\Blog\View\Composers\ThemeComposer::class)->findPublishedEventBySlug($lookup);
        $event = $found instanceof \Modules\Blog\Models\Article ? $found : null;
    }
@endphp
@if ($event instanceof \Modules\Blog\Models\Article)
@php
    $title = (string) $event->getAttribute('title');
    $category = $event->category?->getAttribute('name');
    $start = filled($event->event_start_date)
        ? \Illuminate\Support\Carbon::parse($event->event_start_date)
        : null;
    $end = filled($event->event_end_date)
        ? \Illuminate\Support\Carbon::parse($event->event_end_date)
        : null;
    // Comments list/form: CommentsWidget (Filament) — no pre-fetch gate.
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
                            <a href="{{ route('container0.index', ['container0' => 'events']) }}">{{ __('pub_theme::navigation.homepage.events') }}</a>
                            <span class="separator">/</span>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<article class="container pt-3 pb-5 blog-article-detail" aria-labelledby="event-title">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <header class="mb-4">
                @if (is_string($category) && $category !== '')
                    <p class="category-top mb-2">
                        <span class="category">{{ \Illuminate\Support\Str::upper($category) }}</span>
                    </p>
                @endif
                <h1 id="event-title" class="text-black" data-element="page-name">{{ $title }}</h1>
                @if ($start)
                    <p class="text-muted mb-0">
                        <time datetime="{{ $start->toIso8601String() }}">
                            {{ $start->translatedFormat('j F Y, H:i') }}
                        </time>
                        @if ($end)
                            <span aria-hidden="true"> – </span>
                            <time datetime="{{ $end->toIso8601String() }}">
                                {{ $end->translatedFormat('j F Y, H:i') }}
                            </time>
                        @endif
                    </p>
                @endif
            </header>

            @if ($event->getAttribute('description'))
                <p class="lead mb-3">{{ $event->getAttribute('description') }}</p>
            @endif

            {{-- Corpo: tutti i blocchi dell'editor + figura principale (partial condiviso con blog/detail). --}}
            @include('pub_theme::components.blocks.partials.article-body', ['article' => $event])

            <section class="mt-5 pt-4 border-top" aria-labelledby="comments-title">
                <h2 id="comments-title" class="h3 mb-4">{{ __('pub_theme::blocks_content.shared.comments.label') }}</h2>
                @auth
                    @livewire(\Modules\Comment\Filament\Widgets\Commentable\CommentsWidget::class, [
                        'commentableType' => $event::class,
                        'commentableKey' => (string) $event->getKey(),
                        'readOnly' => false,
                        'hideNotifications' => true,
                        'noReplies' => false,
                        'noReactions' => true,
                    ], key('event-comments-'.$event->getKey()))
                @endauth
                @guest
                    @livewire(\Modules\Comment\Filament\Widgets\Commentable\CommentsWidget::class, [
                        'commentableType' => $event::class,
                        'commentableKey' => (string) $event->getKey(),
                        'readOnly' => true,
                        'hideNotifications' => true,
                        'noReplies' => false,
                        'noReactions' => true,
                    ], key('event-comments-ro-'.$event->getKey()))
                    <p class="mt-3 text-sm text-muted">
                        <x-comment::login-link class="underline" />
                    </p>
                @endguest
            </section>

            <p class="mt-5">
                <a class="read-more" href="{{ route('container0.index', ['container0' => 'events']) }}">
                    <span class="text">{{ __('pub_theme::blocks_content.detail.back_to_events.label') }}</span>
                </a>
            </p>
        </div>
    </div>
</article>
@endif
