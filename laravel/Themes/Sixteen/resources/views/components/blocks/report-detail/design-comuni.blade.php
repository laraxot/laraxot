@props(['data' => []])
{{--
    Scheda servizio "Segnalazione disservizio" Design Comuni (riferimento sito/segnalazione-dettaglio): titolo con stato e pulsanti, menu
    Condividi / Vedi azioni, indice laterale con scroll-spy e sezioni scelte per `type` (text, list, text-attachment, cta, contacts), valutazione,
    carosello "Contenuti correlati" e contatti. Testi, sezioni, link e schede arrivano dal JSON (tests.segnalazione-dettaglio.json); un nuovo
    tipo di sezione = un nuovo `@case`. Stili: resources/css/design-comuni/. Comportamenti: menu a tendina e collapse in app.js, `dcNavscroll`,
    `dcRating`; il carosello `carousel-4-card` (4 schede, 3 sotto 1200 px, 2 sotto 992, 1 sotto 768) richiede la sua voce in `CAROUSELS` di
    resources/js/theme/design-comuni-home.js: vedi story DCP-23.
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $parts = 'pub_theme::components.blocks.report-partials';
@endphp
<div class="container" id="main-container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            @include($parts.'.breadcrumb')
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="cmp-heading pb-3 pb-lg-4">
                <div class="row">
                    <div class="col-lg-8">
                        <h1 class="title-xxxlarge" data-element="service-title">{{ $d('title') }}</h1>
                        <ul class="d-flex flex-wrap gap-1 my-3">
                            <li><div class="chip chip-simple text-button" data-element="service-status"><span class="chip-label">{{ $d('status') }}</span></div></li>
                        </ul>
                        <p class="subtitle-small mb-3">{{ $d('subtitle') }}</p>
                        <div class="d-lg-flex gap-30 mb-2">
                            <button type="button" class="btn fw-bold btn-primary mr-lg-30"><span class="">{{ $d('buttons.primary') }}</span></button>
                            <button type="button" class="btn fw-bold btn-outline-primary t-primary"><span class="">{{ $d('buttons.secondary') }}</span></button>
                        </div>
                    </div>
                    <div class="col-lg-3 offset-lg-1 mt-5 mt-lg-0">
                        @foreach ($d('actions', []) as $action)
                            <div class="dropdown">
                                <button aria-label="{{ $action['aria_label'] ?? '' }}" class="btn btn-dropdown dropdown-toggle text-decoration-underline d-inline-flex align-items-center fs-0" type="button" id="{{ $action['id'] ?? '' }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg class="icon" aria-hidden="true"><use xlink:href="{{ $icon($action['icon'] ?? '') }}"></use></svg>
                                    <small>{{ $action['label'] ?? '' }}</small></button>
                                <div class="dropdown-menu shadow-lg" aria-labelledby="{{ $action['id'] ?? '' }}">
                                    <div class="link-list-wrapper">
                                        <ul class="link-list" role="menu">
                                            @foreach ($action['items'] ?? [] as $entry)
                                                <li role="none">
                                                    <a class="list-item" href="#" role="menuitem">
                                                        <svg class="icon" aria-hidden="true"><use xlink:href="{{ $icon($entry['icon'] ?? '') }}"></use></svg>
                                                        <span>{{ $entry['label'] ?? '' }}</span></a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <hr class="d-none d-lg-block mb-0 mt-2">
    </div>
</div>
<div class="container">
    <div class="row row-column-menu-left mt-lg-80 mt-3">
        <div class="col-12 col-lg-3 mb-4 border-col">
            @include($parts.'.navscroll', ['nav' => $d('side_nav', [])])
        </div>
        <div class="col-12 col-lg-8 offset-lg-1">
            <div class="it-page-sections-container">
                @foreach ($d('sections', []) as $section)
                    @include('pub_theme::components.blocks.report-detail.section', ['section' => $section])
                @endforeach
            </div>
        </div>
    </div>
</div>
@include($parts.'.rating')
<div class="bg-grey-card shadow-contacts">
    <div class="container">
        <div class="row">
            <div class="col-12 pt-5">
                @include('pub_theme::components.blocks.report-detail.related')
            </div>
            <div class="col-12 col-lg-6 offset-lg-3 p-contacts">
                @include($parts.'.contacts-card')
            </div>
        </div>
    </div>
</div>
