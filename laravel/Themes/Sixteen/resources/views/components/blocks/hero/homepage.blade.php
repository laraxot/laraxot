{{--
    Hero Homepage Block
    Reference: design-comuni-pagine-statiche/src/pages/sito/homepage.hbs #head-section
--}}
@php
    // Support both @include (direct vars) and @component (via $data)
    $data = $data ?? [];
    $title = isset($title) ? $title : ($data['title'] ?? 'Nome del comune');
    $news = isset($news) && is_array($news) ? $news : ($data['news'] ?? []);
    $image = isset($image) ? $image : ($data['image'] ?? 'https://picsum.photos/800/600');
    $allNewsLabel = isset($all_news_label) ? $all_news_label : ($data['all_news_label'] ?? 'Tutte le novità');
    $allNewsUrl = isset($all_news_url) ? $all_news_url : ($data['all_news_url'] ?? '#');
    $excerpt = $news['excerpt'] ?? '';
    $excerptLead = $excerpt;
    $excerptTail = '';

    if ($excerpt !== '') {
        // Split: first 4 words bold, rest plain
        $parts = preg_split('/\s+/', trim($excerpt), 5);
        if (is_array($parts) && count($parts) >= 5) {
            $excerptLead = implode(' ', array_slice($parts, 0, 4));
            $excerptTail = $parts[4];
        }
    }
@endphp

<h1 class="visually-hidden">{{ $title }}</h1>
<section id="head-section" class="section">
    <h2 class="visually-hidden">Contenuti in evidenza</h2>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 order-2 order-lg-1">
                <div class="card mb-5">
                    <div class="card-body pb-5 px-0">
                        <div class="category-top mb-2">
                            <svg class="icon icon-sm" aria-hidden="true">
                                <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-calendar"></use>
                            </svg>
                            <span class="title-xsmall-semi-bold fw-semibold">{{ $news['category'] ?? 'Notizie' }}</span>
                            <span class="data fw-normal">{{ $news['date'] ?? '' }}</span>
                        </div>
                        <a href="{{ $news['url'] ?? '#' }}" class="text-decoration-none">
                            <h3 class="card-title">{{ $news['title'] ?? '' }}</h3>
                        </a>
                        <p class="mb-4 pt-3 lora"><strong>{{ $excerptLead }}</strong>@if($excerptTail !== '') {{ $excerptTail }}@endif</p>
                        <a class="chip chip-simple" href="{{ $news['url'] ?? '#' }}">
                            <span class="chip-label">{{ $news['tag'] ?? 'Estate in città' }}</span>
                        </a>
                        <div class="d-block">
                            <a class="read-more pb-3" href="{{ $allNewsUrl }}">
                                <span class="text">{{ $allNewsLabel }}</span>
                                <svg class="icon icon-sm ms-1">
                                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-arrow-right"></use>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-1 order-lg-2 px-0 px-lg-3">
                <img src="{{ $image }}" title="titolo immagine" alt="descrizione immagine" class="img-fluid">
            </div>
        </div>
    </div>
</section>
