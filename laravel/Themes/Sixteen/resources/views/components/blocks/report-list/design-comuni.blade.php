@props(['data' => []])
{{--
    Elenco segnalazioni Design Comuni (riferimento sito/segnalazioni-elenco): titolo, filtro per categoria (colonna desktop), tab Mappa / Elenco,
    mappa statica con pin, schede segnalazione apribili, invito a segnalare, valutazione, contatti e modale di dettaglio. Testi, categorie,
    segnalazioni e modale arrivano dal JSON (tests.segnalazioni-elenco.json). Stili: resources/css/design-comuni/. Comportamenti: tab
    `#tabDisservizio` (initTicketTabs), accordion e modali in app.js. La mappa e' l'immagine del riferimento (map-placeholder.svg), non Leaflet:
    una mappa reale e' un blocco a parte (vedi story DCP-23).
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $parts = 'pub_theme::components.blocks.report-partials';
    $modalId = $d('modal.id');
@endphp
<div class="container" id="main-container">
    <div class="row justify-content-center mb-md-40 mb-lg-80">
        <div class="col-12 col-lg-10">
            @include($parts.'.breadcrumb')
            <div class="cmp-heading p-0">
                <h1 class="title-xxxlarge">{{ $d('title') }}</h1>
                <p class="subtitle-small">{{ $d('subtitle') }}</p>
            </div>
        </div>
        <hr class="d-none d-lg-block mt-30 mb-2">
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-3 d-none d-lg-block">
            <fieldset>
                <legend class="h6 text-uppercase category-list__title">{{ $d('filters.title') }}</legend>
                <div class="categoy-list pb-4">
                    <ul>
                        @foreach ($d('filters.items', []) as $category)
                            <li>
                                <div class="form-check">
                                    <div class="checkbox-body border-light py-1">
                                        <input type="checkbox" id="{{ $category['id'] ?? '' }}" name="category" value="{{ $category['value'] ?? '' }}">
                                        <label for="{{ $category['id'] ?? '' }}" class="subtitle-small_semi-bold mb-0 category-list__list">{{ $category['label'] ?? '' }}</label>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </fieldset>
        </div>
        <div class="col-lg-8 offset-lg-1">
            <div class="d-flex justify-content-between border-bottom border-light pb-3 mt-5">
                <span class="search-results">{{ $d('results') }}</span>
                <button type="button" data-bs-toggle="modal" data-bs-target="#modal-categories" class="btn p-0 pe-2 d-lg-none">
                    <span class="rounded-icon"><svg class="icon icon-primary icon-xs" aria-hidden="true"><use href="{{ $icon('it-funnel') }}"></use></svg></span>
                    <span class="t-primary title-xsmall-semi-bold ms-1">{{ $d('filters.open_label') }}</span></button>
                <button type="button" class="btn p-0 pe-2 d-none d-lg-block"><span class="title-xsmall-semi-bold ms-1">{{ $d('filters.reset_label') }}</span></button>
            </div>
            <ul class="nav nav-tabs w-100 flex-nowrap border-bottom border-light mb-40 mt-3 shadow-none" id="tabDisservizio" role="tablist">
                @foreach ($d('tabs', []) as $tab)
                    {{-- ARIA valida (li presentation, a role=tab, aria-controls sul pannello): il riferimento ha li role=tab con a role=button e aria-controls su un id che non esiste (axe: nested-interactive, aria-allowed-attr, aria-valid-attr-value) --}}
                    <li class="nav-item w-100" role="presentation">
                        <a @class(['nav-link', 'active' => $loop->first, 'title-medium-semi-bold pt-0']) href="#data-ex-disservizio{{ $loop->iteration }}" data-bs-toggle="tab" role="tab" aria-controls="data-ex-disservizio{{ $loop->iteration }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}"> {{ $tab }} </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="data-ex-disservizio1" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="map-box">
                                <img src="{{ $assets }}/images/{{ $d('map.image') }}" alt="{{ $d('map.alt') }}" class="w-100">
                                <button type="button" class="pin" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
                                    <img src="{{ $assets }}/images/{{ $d('map.pin.image') }}" alt="{{ $d('map.pin.alt') }}" title="{{ $d('map.pin.alt') }}"></button>
                            </div>
                        </div>
                        @include('pub_theme::components.blocks.report-list.invite')
                    </div>
                </div>
                <div class="tab-pane fade" id="data-ex-disservizio2" role="tabpanel">
                    <div class="row">
                        @foreach ($d('items', []) as $item)
                            <div class="cmp-card mb-4 mb-lg-30">
                                <div class="card has-bkg-grey shadow-sm">
                                    <div class="card-body p-0">
                                        @include($parts.'.info-card', ['card' => $item])
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn btn-outline-primary mobile-full py-3 mt-10 mx-auto"><span class="">{{ $d('load_more') }}</span></button>
                        </div>
                        @include('pub_theme::components.blocks.report-list.invite')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include($parts.'.rating')
@include($parts.'.contacts')
@include('pub_theme::components.blocks.report-list.modal')
