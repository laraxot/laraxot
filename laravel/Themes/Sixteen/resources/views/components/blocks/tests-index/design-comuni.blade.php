@props(['data' => []])
{{--
    Catalogo dei template Design Comuni (riferimento: index.html, scheda "Sito"): titolo con versione, schede, indice laterale e
    sezioni con l'elenco delle pagine (nome, descrizione, file). Testi, link e sezioni arrivano dal JSON (tests.index.json); i link
    portano alle pagine di prova /it/tests/<slug>. Stessa struttura per la scheda "Flussi di servizio" (servizi/index.html, dove e' commentato
    il pannello "Sito"): il JSON indica `pane_id` (tab1 | tab2) e, per sezione, `intro` e `note` (esempio realizzato). Stili: resources/css/design-comuni/ (scope body:has(page-content tests.*)). Indice mobile: comportamento del tema
    (`custom-navbar-toggler`, `navbarcollapsible`).
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
@endphp
<div class="container px-4 my-4">
    <div class="row">
        <div class="col-12 px-lg-4 py-lg-2">
            <h1>{{ $d('title') }}</h1>
            <span class="fw-semibold">
                <a class="text-muted text-decoration-none" href="{{ $d('release.url') }}" target="_blank" alt="{{ $d('release.alt') }}" title="{{ $d('release.title') }}"><svg class="icon icon-sm icon-secondary"><use href="{{ $icon('it-github') }}"></use></svg> {{ $d('release.label') }}</a>
            </span>
        </div>
        <div class="col-12">
            {{-- Collegamenti tra pagine, non schede con pannelli: role=tablist senza role=tab farebbe fallire axe (aria-required-children, listitem) --}}
            <ul id="myTab" class="nav nav-tabs auto mt-3">
                @foreach ($d('tabs', []) as $tab)
                    <li class="nav-item"><a @class(['nav-link', 'active' => $tab['active'] ?? false]) href="{{ $tab['url'] ?? '#' }}">{{ $tab['label'] ?? '' }}</a></li>
                @endforeach
            </ul>
            <div id="myTabContent" class="tab-content">
                <div id="{{ $d('pane_id', 'tab1') }}" class="tab-pane fade show active" role="tabpanel" aria-labelledby="{{ $d('pane_id', 'tab1') }}-tab">
                    <div class="row">
                        <div class="col-12 col-md-4 px-lg-2 py-lg-2 sticky-top">
                            <nav class="navbar it-navscroll-wrapper navbar-expand-lg it-top-navscroll" data-bs-navscroll>
                                <button class="custom-navbar-toggler" type="button" aria-controls="navbarNavB" aria-expanded="false" aria-label="{{ $d('nav.toggle_aria_label') }}" data-bs-toggle="navbarcollapsible" data-bs-target="#navbarNavB"><span class="it-list"></span>{{ $d('nav.toggle_label') }}
                                </button>
                                <div class="navbar-collapsable" id="navbarNavB">
                                    <div class="overlay"></div>
                                    <div class="close-div visually-hidden">
                                        <button class="btn close-menu" type="button"><span class="it-close"></span>{{ $d('nav.close_label') }}
                                        </button>
                                    </div>
                                    <a class="it-back-button" href="#"><svg class="icon icon-sm icon-primary align-top"><use href="{{ $icon('it-chevron-left') }}"></use></svg>
                                        <span>{{ $d('nav.back_label') }}
                                        </span></a>
                                    <div class="menu-wrapper">
                                        <div class="link-list-wrapper">
                                            <h3>{{ $d('nav.heading') }}</h3>
                                            <ul class="link-list">
                                                @foreach ($d('nav.items', []) as $item)
                                                    <li @class(['nav-item', 'active' => $item['active'] ?? false])><a @class(['nav-link', 'active' => $item['active'] ?? false]) href="#{{ $item['anchor'] ?? '' }}"><span>{{ $item['label'] ?? '' }}</span></a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <div class="col-12 col-md-8 px-lg-2 py-lg-2">
                            <div class="my-5">
                                <div class="it-list-wrapper it-page-sections-container">
                                    @foreach ($d('sections', []) as $section)
                                        <section id="{{ $section['id'] ?? '' }}" class="it-page-section">
                                            <h2{!! ($section['heading_id'] ?? false) ? ' id="'.e($section['id'] ?? '').'"' : '' !!} class="text-secondary">{{ $section['title'] ?? '' }}</h2>
                                            @if (filled($section['intro'] ?? null))<p>{{ $section['intro'] }}</p>@endif
                                            @if (filled($section['note'] ?? null))<p><strong>{{ $section['note'] }}</strong></p>@endif
                                            <ul class="it-list mb-5">
                                                @foreach ($section['pages'] ?? [] as $page)
                                                    <li>
                                                        <a href="{{ $page['url'] ?? '#' }}" target="_blank" class="list-item" data-focus-mouse="false">
                                                            <div class="it-right-zone">
                                                                <span class="text">{{ $page['name'] ?? '' }}
                                                                    <em>{{ $page['description'] ?? '' }}</em>
                                                                </span>
                                                                <span class="it-multiple"><span class="metadata">{{ $page['file'] ?? '' }}</span></span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </section>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
