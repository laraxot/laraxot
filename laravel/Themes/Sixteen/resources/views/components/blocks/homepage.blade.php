@props(['data' => []])
{{--
    Blocco homepage Design Comuni: tutto il testo, i link e le immagini arrivano dal JSON della pagina
    (config/.../content/pages/homepage.json), la struttura segue la homepage di riferimento.
    Gli stili stanno in resources/css/design-comuni/homepage.css (scope .dc-home), il comportamento in
    resources/js/theme/design-comuni-home.js (Alpine). Niente testo cablato qui dentro.
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $hero = $d('hero', []);
@endphp

<h1 class="visually-hidden" id="home-page-title">{{ $d('hero.title') }}</h1>

<section id="head-section">
    <h2 class="visually-hidden">{{ $d('hero.eyebrow') }}</h2>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 order-2 order-lg-1">
                <div class="card mb-5">
                    <div class="card-body pb-5 px-0">
                        <div class="category-top">
                            <svg class="icon icon-sm" aria-hidden="true"><use xlink:href="{{ $icon('it-calendar') }}"></use></svg>
                            <span class="title-xsmall-semi-bold fw-semibold">{{ $d('hero.category') }}</span>
                            <span class="data fw-normal">{{ $d('hero.date') }}</span>
                        </div>
                        <a href="{{ $d('hero.url', '#') }}" class="text-decoration-none">
                            <h3 class="card-title">{{ $d('hero.heading') }}</h3>
                        </a>
                        <p class="mb-4 pt-3 lora"><strong>{{ $d('hero.lead') }}</strong> {{ $d('hero.intro') }}</p>
                        <a class="chip chip-simple" href="{{ $d('hero.badge.url', '#') }}"><span class="chip-label">{{ $d('hero.badge.label') }}</span></a>
                        <a class="read-more pb-3" href="{{ $d('hero.cta.url', '#') }}">
                            <span class="text">{{ $d('hero.cta.label') }}</span>
                            <svg class="icon"><use xlink:href="{{ $icon('it-arrow-right') }}"></use></svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-1 order-lg-2 px-0 px-lg-3">
                <img src="{{ $d('hero.image.src') }}" title="{{ $d('hero.image.title') }}" alt="{{ $d('hero.image.alt') }}" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<section id="calendario">
    <div class="section section-muted pb-90 pb-lg-50 px-lg-5 pt-0">
        <div class="container">
            <div class="row mb-2">
                <div class="card-wrapper px-0 card-overlapping card-teaser-wrapper card-teaser-wrapper-equal card-teaser-block-3">
                    @foreach ($d('government.items', []) as $item)
                        @php($hasImage = filled(data_get($item, 'image.src')))
                        <div class="card card-teaser {{ $hasImage ? 'card-teaser-image card-flex no-after rounded shadow-sm border border-light mb-0' : 'no-after rounded shadow-sm mb-0 border border-light' }}">
                            @if ($hasImage) <div class="card-image-wrapper with-read-more"> @endif
                            <div class="card-body {{ $hasImage ? 'p-3 pb-5' : 'pb-5' }}">
                                <div class="category-top"><span class="title-xsmall-semi-bold fw-semibold">{{ $item['eyebrow'] ?? '' }}</span></div>
                                <h3 class="card-title text-paragraph-medium u-grey-light">{{ $item['title'] ?? '' }}</h3>
                                <p class="text-paragraph-card u-grey-light m-0">{{ $item['description'] ?? '' }}</p>
                            </div>
                            @if ($hasImage)
                                <div class="card-image card-image-rounded pb-5"><img src="{{ $item['image']['src'] }}" alt="{{ $item['image']['alt'] ?? '' }}"></div>
                                </div>
                            @endif
                            <a @class(['read-more', 'ps-3' => $hasImage]) href="{{ $item['url'] ?? '#' }}">
                                <span class="text">{{ $item['cta'] ?? '' }}</span>
                                <svg @class(['icon', 'ms-0' => ! $hasImage])><use xlink:href="{{ $icon('it-arrow-right') }}"></use></svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row row-title pt-5 pt-lg-60 pb-3">
                <div class="col-12 d-lg-flex justify-content-between"><h2>{{ $d('events.title') }}</h2></div>
            </div>
            <div class="row row-calendar">
                <div class="it-carousel-wrapper it-carousel-landscape-abstract-four-cols it-calendar-wrapper splide" data-bs-carousel-splide>
                    <div class="it-header-block">
                        <div class="it-header-block-title"><h3 class="mb-0 text-center home-carousel-title">{{ $d('events.month') }}</h3></div>
                    </div>
                    <div class="splide__track">
                        <ul class="splide__list it-carousel-all">
                            @foreach ($d('events.days', []) as $day)
                                <li class="splide__slide">
                                    <div @class(['it-single-slide-wrapper', 'h-100' => $loop->index < 6])>
                                        <div @class(['card-wrapper', 'h-100' => $loop->index < 6])>
                                            <div class="card card-bg">
                                                <div class="card-body">
                                                    <h4 @class(['card-title', 'pb-4', 'mb-10', 'text-secondary' => $loop->index < 4])>{{ $day['number'] ?? '' }}
                                                        <span>{{ $day['weekday'] ?? '' }}</span></h4>
                                                    @foreach ($day['items'] ?? [] as $event)
                                                        @php($img = data_get($event, 'image.src'))
                                                        <p @class(['card-text', 'px-2', 'pb-10 mb-10' => ! ($loop->last && $loop->count > 3), 'd-flex' => $img])>
                                                            @if ($img)<img src="{{ $img }}" alt="{{ $event['image']['alt'] ?? '' }}" class="me-3 rounded">@endif
                                                            <a href="{{ $event['url'] ?? '#' }}">{{ $event['title'] ?? '' }}</a></p>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="evidence-section">
    <div class="section py-5 pb-lg-80 px-lg-5 position-relative" style="background-image: url({{ $assets }}/images/evidenza-header.png)">
        <div class="container">
            <div class="row"><h2 class="text-white">{{ $d('topics.title') }}</h2></div>
            <div>
                <div class="card-wrapper card-teaser-wrapper card-teaser-wrapper-equal card-teaser-block-3">
                    @foreach ($d('topics.items', []) as $topic)
                        <div class="card card-teaser no-after rounded shadow-sm border border-light">
                            <div class="card-body pb-5">
                                <h3 class="card-title">{{ $topic['title'] ?? '' }}</h3>
                                <p @class(['card-text', 'pb-3' => filled($topic['site'] ?? null)])>{{ $topic['description'] ?? '' }}</p>
                                @if (filled($topic['site'] ?? null))
                                    <p class="mb-10 text-paragraph-small-semi">{{ $topic['site_intro'] ?? '' }}</p>
                                    <a href="{{ $topic['site']['url'] ?? '#' }}" class="card card-teaser card-bg-blue no-after rounded mt-0 p-3">
                                        <div class="avatar size-lg me-3"><img src="{{ $topic['site']['image'] ?? '' }}" alt="{{ $topic['site']['alt'] ?? '' }}"></div>
                                        <div class="card-body">
                                            <h4 class="card-title text-white mb-1">{{ $topic['site']['label'] ?? '' }}</h4>
                                            <p class="card-text text-sans-serif text-white">{{ $topic['site']['description'] ?? '' }}</p>
                                        </div>
                                    </a>
                                @endif
                                @if (filled($topic['links'] ?? null))
                                    <div class="link-list-wrapper mt-4">
                                        <ul class="link-list">
                                            @foreach ($topic['links'] as $link)
                                                <li>
                                                    <a @class(['list-item', 'active', 'icon-left', 'mb-2' => ! $loop->last]) href="{{ $link['url'] ?? '#' }}">
                                                        @if (($link['style'] ?? '') === 'success' && ! $loop->last)
                                                            <span class="list-item-title-icon-wrapper"><span class="text-success">{{ $link['label'] ?? '' }}</span></span>
                                                        @else
                                                            <span class="{{ ($link['style'] ?? '') === 'success' ? 'text-success' : 'text-underline' }}">{{ $link['label'] ?? '' }}</span>
                                                        @endif
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            <a class="read-more pt-0" href="{{ $topic['url'] ?? '#' }}">
                                @if ($topic['cta_wrapped'] ?? false)<span class="list-item-title-icon-wrapper">@endif
                                <span class="text">{{ $d('topics.cta') }}</span>
                                <svg @class(['icon', 'ms-0' => filled($topic['site'] ?? null)])><use xlink:href="{{ $icon('it-arrow-right') }}"></use></svg>
                                @if ($topic['cta_wrapped'] ?? false)</span>@endif
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row pt-30">
                <div class="col-lg-10 col-xl-6 offset-lg-1 offset-xl-2">
                    <div class="row d-lg-inline-flex">
                        <div class="col-lg-3"><h3 class="text-uppercase mb-3 title-xsmall-bold text text-secondary">{{ $d('topics.other.title') }}</h3></div>
                        <div class="col-lg-9">
                            <ul class="d-flex flex-wrap gap-1">
                                @foreach ($d('topics.other.items', []) as $chip)
                                    <li><a class="chip chip-simple" href="{{ $chip['url'] ?? '#' }}"><span class="chip-label">{{ $chip['label'] ?? '' }}</span></a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-10 col-xl-8 offset-lg-1 offset-xl-2 text-center">
                    <a href="{{ $d('topics.other.all.url', '#') }}" class="btn btn-primary mt-40">{{ $d('topics.other.all.label') }}</a>
                </div>
            </div>
            <div class="row pt-5"><h2>{{ $d('theme_sites.title') }}</h2></div>
            <div class="pt-4 pt-lg-30">
                <div class="card-wrapper card-teaser-wrapper card-teaser-wrapper-equal card-teaser-block-3 pb-0">
                    @foreach ($d('theme_sites.items', []) as $site)
                        @php($tone = $site['tone'] ?? 'dark')
                        <a href="{{ $site['url'] ?? '#' }}" class="card card-teaser card-bg-{{ $tone }} rounded {{ $tone === 'dark' ? 'p-3 mt-0' : 'mt-0 p-3' }}">
                            <div class="avatar size-lg me-3"><img src="{{ $site['image'] ?? '' }}" alt="{{ $site['alt'] ?? '' }}"></div>
                            <div class="card-body">
                                <h3 @class(['card-title', 'text-white' => $tone === 'blue', 'sito-tematico'])>{{ $site['label'] ?? '' }}</h3>
                                <p @class(['card-text', 'text-sans-serif', 'text-white' => $tone === 'blue'])>{{ $site['description'] ?? '' }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="useful-links-section">
    <div class="section section-muted p-0 py-5">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-lg-6">
                    <div class="cmp-input-search">
                        <div class="form-group autocomplete-wrapper mb-2 mb-lg-4">
                            <div class="input-group">
                                <label for="autocomplete-autocomplete-three" class="visually-hidden">{{ $d('search.label') }}</label>
                                <input type="search" class="autocomplete form-control" placeholder="{{ $d('search.label') }}" id="autocomplete-autocomplete-three" name="autocomplete-three" data-bs-autocomplete="[]">
                                <div class="input-group-append"><button class="btn btn-primary" type="button" id="button-3">{{ $d('search.button') }}</button></div>
                                <span class="autocomplete-icon" aria-hidden="true"><svg class="icon icon-sm icon-primary"><use href="{{ $icon('it-search') }}"></use></svg></span>
                            </div>
                        </div>
                    </div>
                    <div class="link-list-wrapper">
                        <div class="link-list-heading text-uppercase mb-3 ps-0 text-secondary">{{ $d('links.title') }}</div>
                        <ul class="link-list">
                            @foreach ($d('links.items', []) as $link)
                                <li><a @class(['list-item', 'mb-3' => ! $loop->last, 'active', 'ps-0']) href="{{ $link['url'] ?? '#' }}"><span class="text-button-normal">{{ $link['label'] ?? '' }}</span></a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('pub_theme::components.blocks.partials.dc-rating', ['data' => $data])
@include('pub_theme::components.blocks.partials.dc-contacts', ['data' => $data])
