@props(['data' => []])
{{--
    Pagina "Novita" Design Comuni (riferimento sito/novita.html): breadcrumb, hero, card in evidenza, ricerca con elenco paginato,
    categorie e i partial condivisi rating e contatti. Testi, immagini, card, paginazione e categorie arrivano dal JSON
    (tests.novita.json); la card e' il partial `card` di questa cartella (immagine opzionale). Le immagini stanno in
    public/design-comuni/assets/images. Stili: resources/css/design-comuni/ (scope body:has(page-content tests.*)).
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
@endphp
<div class="container" id="main-container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="cmp-breadcrumbs" role="navigation">
                <nav class="breadcrumb-container" aria-label="{{ __('pub_theme::components_common.a11y.breadcrumb_trail.label') }}"><ol class="breadcrumb p-0" data-element="breadcrumb">
                    @foreach ($d('breadcrumb.items', []) as $crumb)
                        <li class="breadcrumb-item"><a href="{{ $crumb['url'] ?? '#' }}">{{ $crumb['label'] ?? '' }}</a><span class="separator">/</span></li>
                    @endforeach
                    <li class="breadcrumb-item active" aria-current="page">{{ $d('breadcrumb.current') }}</li>
                </ol></nav>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center row-shadow">
        <div class="col-12 col-lg-10">
            <div class="cmp-hero">
                <section class="it-hero-wrapper bg-white align-items-start">
                    <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
                        <h1 class="text-black" data-element="page-name">{{ $d('title') }}</h1>
                        <div class="hero-text"><p>{{ $d('hero.text') }}</p></div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<div class="container py-5">
    <h2 class="text-secondary mb-4">{{ $d('featured.title') }}</h2>
    <div class="row g-4">
        @foreach ($d('featured.items', []) as $item)
            @include('pub_theme::components.blocks.news-page.card', ['item' => $item, 'assets' => $assets])
        @endforeach
    </div>
</div>
<div class="bg-grey-card py-5">
    <div class="container">
        <h2 class="text-secondary mb-4">{{ $d('explore.title') }}</h2>
        <div class="cmp-input-search">
            <div class="form-group autocomplete-wrapper">
                <div class="input-group">
                    <label for="{{ $d('explore.search.id') }}" class="visually-hidden">{{ $d('explore.search.label') }}</label>
                    <input type="search" class="autocomplete form-control" placeholder="{{ $d('explore.search.placeholder') }}" id="{{ $d('explore.search.id') }}" name="{{ $d('explore.search.name') }}" data-bs-autocomplete="[]">
                    <div class="input-group-append"><button class="btn btn-primary" type="button" id="{{ $d('explore.search.button_id') }}">{{ $d('explore.search.button') }}</button></div>
                    <span class="autocomplete-icon" aria-hidden="true"><svg class="icon icon-sm icon-primary"><use href="{{ $icon('it-search') }}"></use></svg></span>
                </div>
            </div>
        </div>
        <div class="row g-4">
            @foreach ($d('explore.items', []) as $item)
                @include('pub_theme::components.blocks.news-page.card', ['item' => $item, 'assets' => $assets])
            @endforeach
        </div>
        <div class="row my-4">
            <nav class="pagination-wrapper justify-content-center" aria-label="{{ $d('explore.pagination.aria_label') }}">
                <ul class="pagination">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-hidden="true"><svg class="icon icon-primary"><use href="{{ $icon('it-chevron-left') }}"></use></svg>
                            <span class="visually-hidden">{{ $d('explore.pagination.previous') }}</span></a>
                    </li>
                    @foreach ($d('explore.pagination.pages', []) as $page)
                        <li class="page-item">
                            @if ($loop->first)
                                <a class="page-link" href="#" aria-current="page"><span class="d-inline-block d-sm-none">{{ $d('explore.pagination.page_label') }} </span>{{ $page }} </a>
                            @else
                                <a class="page-link" href="#">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                    <li class="page-item">
                        <a class="page-link" href="#"><span class="visually-hidden">{{ $d('explore.pagination.next') }}</span>
                            <svg class="icon icon-primary"><use href="{{ $icon('it-chevron-right') }}"></use></svg></a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
<div class="container py-5" id="argomento">
    <h2 class="text-secondary mb-4">{{ $d('categories.title') }}</h2>
    <div class="row g-4">
        @foreach ($d('categories.items', []) as $category)
            <div class="col-md-6 col-xl-4">
                <div class="cmp-card-simple card-wrapper pb-0 rounded border border-light">
                    <div class="card shadow-sm rounded">
                        <div class="card-body">
                            <a href="{{ $category['url'] ?? '#' }}" class="text-decoration-none" data-element="news-category-link"><h3 class="card-title t-primary title-xlarge">{{ $category['title'] ?? '' }}</h3></a>
                            <p class="text-secondary mb-0">{{ $category['text'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@include('pub_theme::components.blocks.partials.dc-rating', ['data' => $data])
@include('pub_theme::components.blocks.partials.dc-contacts', ['data' => $data])
