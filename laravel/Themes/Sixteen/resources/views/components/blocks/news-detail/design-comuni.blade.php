@props(['data' => []])
{{--
    Dettaglio notizia/comunicato/avviso Design Comuni (riferimento sito/novita-dettaglio.html): titolo con data e tempo di lettura,
    azioni (condividi, vedi azioni), argomenti, immagine a tutta larghezza, indice laterale con scroll-spy, descrizione, "a cura di"
    (ufficio e persone) e ultimo aggiornamento. Testi, immagini e link arrivano dal JSON (tests.novita-dettaglio.json); l'immagine
    e' un URL del JSON (nel riferimento e' un segnaposto esterno). Stili: resources/css/design-comuni/. Comportamenti: menu a tendina
    e accordion del tema, scroll-spy `dcNavscroll` (resources/js/theme/design-comuni-home.js). Rating e contatti: partial condivisi.
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $sprite = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
@endphp
<div class="container" id="main-container">
    <div class="row">
        <div class="col px-lg-4">
            <div class="cmp-breadcrumbs" role="navigation">
                <nav class="breadcrumb-container" aria-label="{{ __('pub_theme::components_common.a11y.breadcrumb_trail.label') }}"><ol class="breadcrumb p-0" data-element="breadcrumb">
                    @foreach ($d('breadcrumb.items', []) as $crumb)
                        <li class="breadcrumb-item">
                            @if (filled($crumb['url'] ?? null) && ($crumb['url'] ?? '') !== '#')
                                <a href="{{ $crumb['url'] }}">{{ $crumb['label'] ?? '' }}</a>
                            @else
                                <span>{{ $crumb['label'] ?? '' }}</span>
                            @endif
                            <span class="separator">/</span>
                        </li>
                    @endforeach
                    <li class="breadcrumb-item active" aria-current="page">{{ $d('breadcrumb.current') }}</li>
                </ol></nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 px-lg-4 py-lg-2">
            <h1 data-audio>{{ $d('title') }}</h1>
            <h2 class="visually-hidden">{{ $d('details_label') }}</h2>
            <p data-audio>{{ $d('lead') }}</p>
            <div class="row mt-5 mb-4">
                @foreach ($d('meta', []) as $meta)
                    <div class="col-6">
                        <small>{{ $meta['label'] ?? '' }}</small>
                        <p @class(['fw-semibold', 'font-monospace' => ($meta['format'] ?? '') === 'date'])>{{ $meta['value'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-lg-3 offset-lg-1">
            @foreach ($d('actions', []) as $action)
                <div class="dropdown d-inline">
                    <button aria-label="{{ $action['aria_label'] ?? ($action['label'] ?? __('pub_theme::a11y.actions')) }}" class="btn btn-dropdown dropdown-toggle text-decoration-underline d-inline-flex align-items-center fs-0" type="button" id="{{ $action['id'] ?? '' }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><svg class="icon" aria-hidden="true"><use xlink:href="{{ $sprite($action['icon'] ?? '') }}"></use></svg>
                        <small>{{ $action['label'] ?? '' }}</small></button>
                    <div class="dropdown-menu shadow-lg" aria-labelledby="{{ $action['id'] ?? '' }}">
                        <div class="link-list-wrapper">
                            <ul class="link-list" role="menu">
                                @foreach ($action['items'] ?? [] as $entry)
                                    <li role="none">
                                        <a class="list-item"@if (filled($entry['url'] ?? null)) href="{{ $entry['url'] }}"@endif role="menuitem"><svg class="icon" aria-hidden="true"><use xlink:href="{{ $sprite($entry['icon'] ?? '') }}"></use></svg>
                                            <span>{{ $entry['label'] ?? '' }}</span></a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="mt-4 mb-4">
                <div class="row"><span class="mb-2 small">{{ $d('topics.title') }}</span></div>
                <ul class="d-flex flex-wrap gap-1">
                    @foreach ($d('topics.items', []) as $topic)
                        <li><span class="chip chip-simple"><span class="chip-label">{{ $topic }}</span></span></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid my-3">
    <div class="row">
        <figure class="figure px-0 img-full">
            <img src="{{ $d('hero.src') }}" class="figure-img img-fluid" alt="{{ $d('hero.alt') }}">
            <figcaption class="figure-caption text-center pt-3">{{ $d('hero.caption') }}</figcaption>
        </figure>
    </div>
</div>
<div class="container">
    <div class="row border-top border-light row-column-border row-column-menu-left"><aside class="col-lg-3">
        <div class="cmp-navscroll sticky-top" aria-labelledby="accordion-title-one">
            <nav class="navbar it-navscroll-wrapper navbar-expand-lg" aria-label="{{ $d('side_nav.aria_label') }}" data-bs-navscroll>
                <div class="navbar-custom" id="navbarNavProgress">
                    <div class="menu-wrapper">
                        <div class="link-list-wrapper">
                            <div class="accordion">
                                <div class="accordion-item">
                                    <span class="accordion-header" id="accordion-title-one">
                                        <button class="accordion-button pb-10 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-one" aria-expanded="true" aria-controls="collapse-one"> {{ $d('side_nav.title') }} <svg class="icon icon-xs right"><use href="{{ $sprite('it-expand') }}"></use></svg></button>
                                    </span>
                                    <div class="progress">
                                        <div class="progress-bar it-navscroll-progressbar" role="progressbar" aria-label="{{ __('pub_theme::a11y.reading_progress') }}" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div id="collapse-one" class="accordion-collapse collapse show" role="region" aria-labelledby="accordion-title-one">
                                        <div class="accordion-body">
                                            <ul class="link-list" data-element="page-index">
                                                @foreach ($d('side_nav.items', []) as $item)
                                                    <li class="nav-item"><a class="nav-link" href="#{{ $item['anchor'] ?? '' }}"><span>{{ $item['label'] ?? '' }}</span></a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </aside>
    <section class="col-lg-9 it-page-sections-container border-light">
        <article id="descrizione" class="it-page-section anchor-offset" data-audio>
            @foreach ($d('description.paragraphs', []) as $paragraph)
                <p class="font-serif"> {{ $paragraph }} </p>
            @endforeach
        </article>
        <article id="a-cura-di" class="it-page-section anchor-offset mt-5">
            <h2 class="mb-3">{{ $d('curator.title') }}</h2>
            <div class="row">
                <div class="col-12 col-sm-8 col-xl-6">
                    <div class="card-wrapper rounded shadow-sm h-auto">
                        <div class="card card-teaser card-teaser-info rounded shadow-sm p-4">
                            <div class="card-body pe-3">
                        <h5 class="card-title">{{ $d('curator.office.name') }}</h5>
                                <div class="card-text">
                                    @foreach ($d('curator.office.lines', []) as $line)
                                        <p>{{ $line }}</p>
                                    @endforeach
                                </div>
                            </div>
                            <div class="avatar size-xl"><img src="{{ $d('curator.office.avatar.src') }}" alt="{{ $d('curator.office.avatar.alt') }}"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4 col-xl-6">
                    <h6><small>{{ $d('curator.people.title') }}</small></h6>
                    <ul class="d-flex flex-wrap gap-1 mt-2">
                        @foreach ($d('curator.people.items', []) as $person)
                            <li><span class="chip chip-simple"><span class="chip-label">{{ $person }}</span></span></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </article>
        <article id="ultimo-aggiornamento" class="anchor-offset mt-5">
            <h4 class="h6">{{ $d('updated.label') }} <span class="h6 fw-normal">{{ $d('updated.value') }}</span></h4>
        </article>
    </section>
    </div>
</div>
@include('pub_theme::components.blocks.partials.dc-rating', ['data' => $data])
@include('pub_theme::components.blocks.partials.dc-contacts', ['data' => $data])
