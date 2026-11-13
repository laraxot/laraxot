@props(['data' => []])
{{--
    Area personale Design Comuni (riferimento sito/segnalazione-area-personale): titolo con codice fiscale, tab (Scrivania, Messaggi, Attivita,
    Servizi) e pannelli con indice laterale e sezioni scelte per `type` (messages, activities, practices): un nuovo tipo = un nuovo file
    `section-<tipo>`. Testi, tab, messaggi, attivita e pratiche arrivano dal JSON (tests.segnalazione-area-personale.json). Stili:
    resources/css/design-comuni/. Comportamenti: accordion e modale in app.js; scroll-spy `dcNavscroll`. Il cambio di tab (data-bs-toggle="tab" su
    #myTab) richiede una generalizzazione di `initTicketTabs` in app.js, oggi limitata a #tabDisservizio: vedi story DCP-23.
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
            <div class="cmp-heading pb-3 pb-lg-4">
                <h1 class="title-xxxlarge">{{ $d('title') }}</h1>
                <p class="subtitle-small">{{ $d('subtitle') }}</p>
            </div>
        </div>
        <div class="col-12 p-0">
            <div class="cmp-nav-tab mb-4 mb-lg-5 mt-lg-4">
                <ul class="nav nav-tabs nav-tabs-icon-text w-100 flex-nowrap" id="myTab" role="tablist">
                    @foreach ($d('tabs', []) as $tab)
                        <li class="nav-item w-100" role="tab">
                            <a @class(['nav-link justify-content-start text-tab', 'active' => $loop->first]) href="#data-ex-tab{{ $loop->iteration }}" aria-current="page" aria-controls="tab{{ $loop->iteration }}" aria-selected="false" data-bs-toggle="tab" role="button" data-focus-mouse="false"><svg class="icon me-1 mr-lg-10" aria-hidden="true"><use href="{{ $icon($tab['icon'] ?? '') }}"></use></svg> {{ $tab['label'] ?? '' }} </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="it-page-sections-container">
        <div class="tab-content">
            @foreach ($d('panes', []) as $pane)
                @php($isList = ($pane['variant'] ?? 'cards') === 'list')
                <div @class(['tab-pane', 'fade show active' => $loop->first]) id="data-ex-tab{{ $loop->iteration }}" role="tabpanel">
                    @if (filled($pane['sections'] ?? null))
                        <div class="row">
                            <div @class(['col-12 col-lg-3 d-lg-block mb-4 d-none' => ! $isList, 'd-none d-sm-none d-lg-block col-lg-3' => $isList])>
                                @include($parts.'.navscroll', ['nav' => $pane['nav'] ?? []])
                            </div>
                            <div @class(['col-12 col-lg-8 offset-lg-1', 'px-0 px-sm-3' => $isList])>
                                @foreach ($pane['sections'] as $section)
                                    @include('pub_theme::components.blocks.report-area.section-'.($section['type'] ?? 'messages'), ['section' => $section])
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@include($parts.'.contacts')
@include('pub_theme::components.blocks.report-area.modal-message')
