{{--
    Homepage «Novità» (Design Comuni sito/homepage.html + card di sito/novita.html).
    Caller: config/local/fixcity/database/content/pages/home.json → content_blocks.{it,en,de,es}[*].data.view.
    Dati: ThemeComposer (Blog Article type=post): prima le notizie in evidenza, altrimenti le ultime pubblicate.
    Nessuna notizia pubblicata → la sezione non viene renderizzata. Copy dal CMS JSON (tradotto per locale).
--}}
@props(['data' => []])

@php
    $composer = app(\Modules\Blog\View\Composers\ThemeComposer::class);
    $limit = max(1, (int) ($data['limit'] ?? 3));
    $posts = $composer->featuredBlogPosts($limit);
    if ($posts->isEmpty()) {
        $posts = collect($composer->paginatedBlogPosts($limit)->items());
    }
    $blogIndexUrl = \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::blogIndex();
    $fallbackCategory = (string) ($data['category_fallback'] ?? __('pub_theme::navigation.news'));

    $cards = $posts->map(static function ($post) use ($blogIndexUrl, $fallbackCategory): ?array {
        if (! $post instanceof \Modules\Blog\Models\Article || $post->getTitle() === '') {
            return null;
        }
        $url = \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::view('blog', (string) $post->getAttribute('slug'));
        $category = $post->category?->getAttribute('name') ?: $post->category?->getAttribute('title');
        $categorySlug = $post->category?->getAttribute('slug');
        $date = $post->published_at?->translatedFormat('j M Y');

        return [
            'title' => $post->getTitle(),
            'url' => $url,
            'image' => (string) $post->getAttribute('main_image_url'),
            'excerpt' => (string) ($post->getAttribute('excerpt') ?: $post->getAttribute('description') ?: ''),
            'date' => is_string($date) ? mb_strtoupper($date) : '',
            'categoryLabel' => mb_strtoupper(is_string($category) && $category !== '' ? $category : $fallbackCategory),
            'categoryUrl' => filled($categorySlug) ? $blogIndexUrl.'?categoria='.urlencode((string) $categorySlug) : $blogIndexUrl,
        ];
    })->filter()->values();

    $heading = (string) ($data['heading'] ?? __('pub_theme::navigation.homepage.news'));
    $viewAllLabel = (string) ($data['view_all_label'] ?? '');
    $viewAllAria = (string) ($data['view_all_aria'] ?? $viewAllLabel);
@endphp

@if ($cards->isNotEmpty())
    <section id="novita" class="fc-home-news" aria-labelledby="fc-home-news-title" data-section="home-news">
        <div class="container py-5">
            <div class="row row-title pb-3">
                <div class="col-12 d-lg-flex justify-content-between align-items-center">
                    <h2 id="fc-home-news-title" class="text-secondary mb-4">{{ $heading }}</h2>
                    @if ($viewAllLabel !== '')
                        <a class="read-more" href="{{ $blogIndexUrl }}" aria-label="{{ $viewAllAria }}" data-element="news-view-all">
                            <span class="text">{{ $viewAllLabel }}</span>
                            <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    @endif
                </div>
            </div>
            <div class="row g-4 fc-home-news__grid">
                @foreach ($cards as $card)
                    @include('pub_theme::components.blocks.partials.news-card', ['card' => $card])
                @endforeach
            </div>
        </div>
    </section>
    <style>
        .fc-home-news .row-title > div { display: flex; flex-wrap: wrap; gap: .5rem 1.5rem; align-items: baseline; justify-content: space-between; }
        .fc-home-news .read-more { display: inline-flex; align-items: center; gap: .35rem; font-weight: 600; color: #007a52; text-decoration: none; }
        .fc-home-news .card-wrapper { background: #fff; height: 100%; }
        .fc-home-news .img-wrapper { margin: 0; }
        .fc-home-news .img-wrapper img { width: 100%; height: 12rem; object-fit: cover; display: block; }
        .fc-home-news .card-body { padding: 1.5rem; }
        .fc-home-news .category-top { display: flex; flex-wrap: wrap; gap: .75rem; font-size: .875rem; font-weight: 600; margin-bottom: .5rem; }
        .fc-home-news .category, .fc-home-news .card-title { color: #007a52; }
        .fc-home-news .card-title { font-size: 1.25rem; line-height: 1.3; margin: 0 0 .5rem; }
        .fc-home-news .card-text { color: #5c6f82; }
    </style>
@endif
