@props(['data' => []])
{{--
    Dettaglio evento Design Comuni (riferimento sito/evento-dettaglio.html): titolo con azioni (condividi, vedi azioni), argomenti,
    immagine a tutta larghezza, indice laterale con scroll-spy e sezioni scelte per `type` (rich, text, place, schedule, costs,
    attachments, related, contacts, links). Testi, immagini, link e struttura delle sezioni arrivano dal JSON della pagina
    (tests.evento-dettaglio.json); un nuovo tipo di sezione = un nuovo `@case`. Le immagini sono URL del JSON (nel riferimento sono
    segnaposto esterni). Stili: resources/css/design-comuni/. Comportamenti: menu a tendina e accordion del tema, carosello `dcCarousel`,
    scroll-spy `dcNavscroll` (resources/js/theme/design-comuni-home.js). Rating e contatti: partial condivisi.
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $sprite = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
@endphp
<div class="container" id="main-container">
    <div class="row">
        <div class="col px-lg-4">
            <div class="cmp-breadcrumbs" role="navigation">
                <nav class="breadcrumb-container" aria-label="{{ __('pub_theme::blocks_common.shared.breadcrumb.label') }}"><ol class="breadcrumb p-0" data-element="breadcrumb">
                    @foreach ($d('breadcrumb.items', []) as $crumb)
                        <li class="breadcrumb-item"><a href="{{ $crumb['url'] ?? '#' }}">{{ $crumb['label'] ?? '' }}</a><span class="separator">/</span></li>
                    @endforeach
                    <li class="breadcrumb-item active" aria-current="page">{{ $d('breadcrumb.current') }}</li>
                </ol></nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 px-lg-4 py-lg-2">
            <h1 data-audio>{{ $d('title') }}</h1>
            <h2 class="h4 py-2" data-audio>{{ $d('subtitle') }}</h2>
            <p data-audio>{{ $d('lead') }}</p>
        </div>
        <div class="col-lg-3 offset-lg-1">
            @foreach ($d('actions', []) as $action)
                <div class="dropdown d-inline">
                    <button aria-label="{{ $action['aria_label'] ?? '' }}" class="btn btn-dropdown dropdown-toggle text-decoration-underline d-inline-flex align-items-center fs-0" type="button" id="{{ $action['id'] ?? '' }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><svg class="icon" aria-hidden="true"><use xlink:href="{{ $sprite($action['icon'] ?? '') }}"></use></svg>
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
                <h6 class="text-secondary">{{ $d('topics.title') }}</h6>
                <ul class="d-flex flex-wrap gap-1 mt-2">
                    @foreach ($d('topics.items', []) as $topic)
                        <li><a class="chip chip-simple" href="#"><span class="chip-label">{{ $topic }}</span></a></li>
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
    <div class="row border-top border-light row-column-border row-column-menu-left"><aside class="col-lg-4">
        <div class="cmp-navscroll sticky-top" aria-labelledby="accordion-title-one">
            <nav class="navbar it-navscroll-wrapper navbar-expand-lg" aria-label="{{ $d('side_nav.aria_label') }}" data-bs-navscroll>
                <div class="navbar-custom" id="navbarNavProgress">
                    <div class="menu-wrapper">
                        <div class="link-list-wrapper">
                            <div class="accordion">
                                <div class="accordion-item">
                                    <span class="accordion-header" id="accordion-title-one">
                                        <button class="accordion-button pb-10 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-one" aria-expanded="true" aria-controls="collapse-one"> {{ $d('side_nav.title') }} <svg class="icon icon-xs right"><use href="{{ $icon('it-expand') }}"></use></svg></button>
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
    <section class="col-lg-8 it-page-sections-container border-light">
        @foreach ($d('sections', []) as $section)
            <article id="{{ $section['id'] ?? '' }}" class="it-page-section mb-5"@if ($section['audio'] ?? false) data-audio @endif>
                <h2 class="mb-3">{{ $section['title'] ?? '' }}</h2>
                @switch($section['type'] ?? '')
                    @case('rich')
                        @foreach ($section['paragraphs'] ?? [] as $paragraph)
                            <p class="font-serif"> {{ $paragraph }} </p>
                        @endforeach
                        @if (filled($section['chips'] ?? null))
                            <div class="pt-3 mb-4">
                                <h3 class="h4">{{ $section['chips']['title'] ?? '' }}</h3>
                                <ul class="d-flex flex-wrap gap-1 mt-2">
                                    @foreach ($section['chips']['items'] ?? [] as $chip)
                                        <li><a class="chip chip-simple" href="#"><span class="chip-label">{{ $chip }}</span></a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (filled($section['gallery'] ?? null))
                            <div class="it-carousel-wrapper it-carousel-landscape-abstract-three-cols splide" data-bs-carousel-splide>
                                <div class="it-header-block">
                                    <div class="it-header-block-title"><h3 class="h4">{{ $section['gallery']['title'] ?? '' }} </h3></div>
                                </div>
                                <div class="splide__track">
                                    <ul class="splide__list it-carousel-all">
                                        @foreach ($section['gallery']['items'] ?? [] as $photo)
                                            <li class="splide__slide">
                                                <div class="it-single-slide-wrapper">
                                                    <figure>
                                                        <img src="{{ $photo['src'] ?? '' }}" alt="{{ $photo['alt'] ?? '' }}" class="img-fluid">
                                                        <figcaption class="figure-caption mt-2">{{ $photo['caption'] ?? '' }}</figcaption>
                                                    </figure>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                        @if (filled($section['video'] ?? null))
                            <div class="mb-4">
                                <h3 class="h4">{{ $section['video']['title'] ?? '' }}</h3>
                                <div class="ratio ratio-16x9 my-4">
                                    <iframe class="embed-responsive-item" title="{{ $section['video']['iframe_title'] ?? '' }}" src="{{ $section['video']['src'] ?? '' }}" allowfullscreen></iframe>
                                </div>
                                <div class="accordion border-0 mb-4" id="videoTranscription">
                                    <div class="accordion-item">
                                        <div class="accordion-header" id="heading1c">
                                            <button class="accordion-button collapsed title-snall-semi-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1c" aria-expanded="false" aria-controls="collapse1c">
                                                <div class="button-wrapper d-flex"> {{ $section['video']['transcript']['title'] ?? '' }}
                                                    <div class="icon-wrapper"><svg class="icon icon-sm me-1 icon-primary"><use href="{{ $icon('it-expand') }}"></use></svg></div>
                                                </div>
                                            </button>
                                        </div>
                                        <div id="collapse1c" class="accordion-collapse collapse p-0" data-bs-parent="#videoTranscription" role="region" aria-labelledby="heading1c">
                                            <div class="accordion-body ps-0 pb-4"><p class="font-serif mb-0">{{ $section['video']['transcript']['text'] ?? '' }}</p></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @break
                    @case('text')
                        <p>{{ $section['text'] ?? '' }}</p>
                        @break
                    @case('place')
                        <div class="card-wrapper card-teaser-wrapper">
                            <div class="card card-teaser shadow mt-3 rounded"><svg class="icon icon-success" aria-hidden="true"><use xlink:href="{{ $sprite($section['icon'] ?? '') }}"></use></svg>
                                <div class="card-body">
                                    <h3 class="card-title h5"><a href="#" class="text-decoration-none"> {{ $section['name'] ?? '' }} </a></h3>
                                    <div class="card-text">
                                        <p>{{ $section['address'] ?? '' }}</p>
                                        <p class="mt-3">{{ $section['details'] ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="map-wrapper map-column mt-4">
                            <img src="{{ $section['map']['src'] ?? '' }}" alt="{{ $section['map']['alt'] ?? '' }}" class="img-fluid" style="object-fit:cover;width:100%;">
                        </div>
                        @break
                    @case('schedule')
                        <div class="point-list-wrapper my-4">
                            @foreach ($section['items'] ?? [] as $slot)
                                @php($tag = ($slot['tag'] ?? 'span') === 'div' ? 'div' : 'span')
                                <div class="point-list">
                                    <h3 class="point-list-aside point-list-primary fw-normal">
                                        <{{ $tag }} class="point-date font-monospace">{{ $slot['day'] ?? '' }}</{{ $tag }}>
                                        <{{ $tag }} class="point-month font-monospace">{{ $slot['month'] ?? '' }}</{{ $tag }}>
                                    </h3>
                                    <div class="point-list-content">
                                        <div class="card card-teaser shadow rounded">
                                            <div class="card-body"><h3 class="card-title h5 m-0"> {{ $slot['text'] ?? '' }} </h3></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="font-serif"> {{ $section['note'] ?? '' }} </p>
                        @break
                    @case('costs')
                        @foreach ($section['items'] ?? [] as $cost)
                            <div @class(['card', 'no-after', 'border-start', 'mt-3' => ! $loop->first])>
                                <div class="card-body">
                                    <h5><span> {{ $cost['name'] ?? '' }} </span><p class="card-title big-heading">{{ $cost['price'] ?? '' }}</p></h5>
                                    <p class="mt-4">{{ $cost['text'] ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                        @break
                    @case('attachments')
                        @foreach ($section['items'] ?? [] as $file)
                            <div class="card card-teaser shadow rounded">
                                <div class="card-body">
                                    <h3 class="card-title h5 m-0"><svg class="icon" aria-hidden="true"><use xlink:href="{{ $sprite($section['icon'] ?? '') }}"></use></svg>
                                        <a href="#" class="text-decoration-none" title="{{ $file['title'] ?? '' }}" aria-label="{{ $file['aria'] ?? '' }}">{{ $file['label'] ?? '' }}</a></h3>
                                </div>
                            </div>
                        @endforeach
                        @break
                    @case('related')
                        <div class="card-wrapper card-teaser-wrapper card-teaser-wrapper-equal">
                            @foreach ($section['items'] ?? [] as $card)
                                <div class="card-wrapper card-teaser">
                                    <div class="card card-img no-after">
                                        <div class="img-responsive-wrapper">
                                            <div class="img-responsive">
                                                <figure class="img-wrapper"><img src="{{ $card['image']['src'] ?? '' }}" title="{{ $card['image']['title'] ?? '' }}" alt="{{ $card['image']['alt'] ?? '' }}"></figure>
                                                <div class="card-calendar d-flex flex-column justify-content-center">
                                                    <span class="card-date">{{ $card['date'] ?? '' }}</span>
                                                    <span class="card-day">{{ $card['month'] ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title"><a href="#" class="text-decoration-none">{{ $card['title'] ?? '' }}</a></h5>
                                            <p class="card-text"></p>
                                            <a class="read-more" href="#" aria-label="{{ $card['aria'] ?? '' }}">
                                                <span class="text">{{ $section['read_more'] ?? '' }}</span>
                                                <span class="visually-hidden">{{ $section['read_more_hidden'] ?? '' }}</span><svg class="icon"><use href="{{ $icon('it-arrow-right') }}"></use></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @break
                    @case('contacts')
                        @foreach ($section['items'] ?? [] as $contact)
                            @if ($loop->index === 1)<h4 class="h5">{{ $section['supported_by'] ?? '' }}</h4>@endif
                            @if ($loop->first)<div class="mb-4">@endif
                                <div @class(['card', 'card-teaser', 'shadow', 'rounded', 'mt-3' => ! $loop->first])><svg class="icon" aria-hidden="true"><use xlink:href="{{ $sprite($contact['icon'] ?? '') }}"></use></svg>
                                    <div class="card-body">
                                        <h3 class="card-title h5"><a href="#" class="text-decoration-none"> {{ $contact['name'] ?? '' }} </a></h3>
                                        <div class="card-text">
                                            @foreach ($contact['lines'] ?? [] as $line)
                                                <p @class(['mt-3' => $loop->index === 1])>{{ $line }}</p>
                                            @endforeach
                                            @foreach ($contact['links'] ?? [] as $link)
                                                <p><a @if (filled($link['aria'] ?? null)) aria-label="{{ $link['aria'] }}"@endif @if ($link['external'] ?? false) target="_blank"@endif @if (filled($link['title'] ?? null)) title="{{ $link['title'] }}"@endif href="{{ $link['url'] ?? '#' }}">{{ $link['label'] ?? '' }}</a></p>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @if ($loop->first)</div>@endif
                        @endforeach
                        @break
                    @case('links')
                        <div @class(['link-list-wrapper', 'mb-3' => $section['id'] === 'patrocinio'])>
                            <ul class="link-list">
                                @foreach ($section['items'] ?? [] as $label)
                                    <li><a class="list-item px-0" href="#"><span>{{ $label }}</span></a></li>
                                @endforeach
                            </ul>
                        </div>
                        @break
                @endswitch
            </article>
        @endforeach
        <article id="ultimo-aggiornamento" class="it-page-section mt-5">
            <h4 class="h6">{{ $d('updated.label') }} <span class="h6 fw-normal">{{ $d('updated.value') }}</span></h4>
        </article>
    </section>
</div>
@include('pub_theme::components.blocks.partials.dc-rating', ['data' => $data])
@include('pub_theme::components.blocks.partials.dc-contacts', ['data' => $data])
