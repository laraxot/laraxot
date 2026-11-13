@props(['data' => []])
{{--
    Pagina "argomento" Design Comuni (riferimento sito/argomento): hero con immagine e scheda sovrapposta (breadcrumb, titolo, testo, chi gestisce
    l'argomento), sezioni `news` (card notizia su fascia grigia) e `teaser` (card teaser) e i partial condivisi rating e contatti. Tutto arriva dal
    JSON (tests.argomento.json). `sections[].wrapper_class` = spaziatura del contenitore (py-5 / pb-5), `grid_gap` = `g-4` sulla griglia teaser.
    Il breadcrumb e' il partial di `content-page`. Stili: resources/css/design-comuni/ (scope body:has(page-content tests.*)).
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $cx = static fn (?string ...$parts): string => implode(' ', array_filter($parts, 'filled'));
    $managers = $d('hero.managers', []);
@endphp
<div class="it-hero-wrapper it-wrapped-container" id="main-container">
    <div class="img-responsive-wrapper">
        <div class="img-responsive">
            <div class="img-wrapper"><img src="{{ $d('hero.image.src') }}" title="{{ $d('hero.image.title') }}" alt="{{ $d('hero.image.alt') }}"></div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12 drop-shadow">
                <div class="it-hero-card it-hero-bottom-overlapping rounded px-lg-5 py-4 py-lg-5">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            @include('pub_theme::components.blocks.content-page.partials.breadcrumb', ['breadcrumb' => $d('breadcrumb', [])])
                        </div>
                    </div>
                    <div class="row justify-content-between mt-lg-2">
                        <div class="col-12 col-lg-5 pl-lg-5">
                            <h1 class="mb-3">{{ $d('title') }}</h1>
                            <h2 class="visually-hidden" id="{{ $d('hero.details_id') }}">{{ $d('hero.details') }}</h2>
                            <p class="text-secondary mb-5">{{ $d('hero.text') }}</p>
                        </div>
                        <div class="col-12 col-lg-5">
                            <div class="card-wrapper card-column">
                                <h3 class="title-xsmall-semi-bold text-secondary">{{ $d('hero.managers_title') }}</h3>
                                @foreach ($managers as $manager)
                                    <div class="{{ $cx('card card-teaser card-teaser-info shadow-sm rounded d-flex align-items-center', $loop->last ? 'mb-2 mb-lg-0' : 'p-3') }}">
                                        <div class="card-body">
                                            <h4 class="mb-1 title-small-semi-bold-medium"><a href="{{ $manager['url'] ?? '#' }}" class="text-decoration-none">{{ $manager['title'] ?? '' }}</a></h4>
                                            <div class="card-text"><p class="u-main-black">{{ $manager['text'] ?? '' }}</p></div>
                                        </div>
                                        <div class="avatar size-xl"><img src="{{ $manager['image']['src'] ?? '' }}" alt="{{ $manager['image']['alt'] ?? '' }}"></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@foreach ($d('sections', []) as $section)
    @php
        $isNews = ($section['type'] ?? '') === 'news';
        $buttonAttr = filled($section['button']['title'] ?? null)
            ? ' title="'.e($section['button']['title']).'"'
            : (filled($section['button']['aria_label'] ?? null) ? ' aria-label="'.e($section['button']['aria_label']).'"' : '');
    @endphp
    <section id="{{ $section['id'] ?? '' }}">
        <div class="{{ $section['wrapper_class'] ?? '' }}">
            <div class="container">
                <div class="{{ $cx('row row-title', $isNews ? 'pt-md-5' : null) }}">
                    <div class="col-12"><h2 class="{{ $cx('text-secondary border-bottom pb-2 pb-lg-3', $isNews ? 'mt-lg-5' : null) }}">{{ $section['title'] ?? '' }}</h2></div>
                </div>
                @if ($isNews)
                    <div class="row pt-4 mt-lg-2 pb-lg-4">
                        @foreach ($section['items'] ?? [] as $item)
                            @include('pub_theme::components.blocks.content-topic.news-card', ['item' => $item])
                        @endforeach
                    </div>
                @else
                    <div class="row mx-0">
                        <div class="{{ $cx('card-wrapper px-0 card-teaser-wrapper card-teaser-wrapper-equal card-teaser-block-3', ! empty($section['grid_gap']) ? 'g-4' : null) }}">
                            @foreach ($section['items'] ?? [] as $item)
                                @include('pub_theme::components.blocks.content-topic.teaser-card', ['item' => $item])
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="{{ $isNews ? 'row' : 'row mt-lg-4' }}">
                    <div class="col-12 col-lg-3 offset-lg-9">
                        <a href="{{ $section['button']['url'] ?? '#' }}" class="btn btn-primary text-button w-100"{!! $buttonAttr !!}>{{ $section['button']['label'] ?? '' }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endforeach
@include('pub_theme::components.blocks.partials.dc-rating', ['data' => $data])
@include('pub_theme::components.blocks.partials.dc-contacts', ['data' => $data])
