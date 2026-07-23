{{--
    Design Comuni - Homepage
    Template: Bootstrap Italia HTML-identical
    Source: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
    Body Length: 1331 righe HTML
--}}

@extends('pub_theme::layouts.base')

@section('title', 'Il mio Comune')

@section('body_class', 'cmp-homepage-page')

@section('content')

{{-- Skip Links --}}
<div class="skiplink">
    <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
    <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
</div>

{{-- Header Component --}}

{{-- Main Content --}}
<main>
    <h1 class="visually-hidden" id="main-container">Il mio Comune</h1>
    
    {{-- Hero Section --}}
    <section id="head-section">
        <h2 class="visually-hidden">Contenuti in evidenza</h2>
        <div class="container">
            <div class="row">
                {{-- News Card --}}
                <div class="col-lg-6 order-2 order-lg-1">
                    <div class="card mb-5">
                        <div class="card-body pb-5 px-0">
                            <div class="category-top">
                                <svg class="icon icon-sm" aria-hidden="true">
                                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-calendar"></use>
                                </svg>
                                <span class="title-xsmall-semi-bold fw-semibold">Notizie</span>
                                <span class="data fw-normal">18 mag 2026</span>
                            </div>
                            <a href="/it/tests/novita-dettaglio" class="text-decoration-none">
                                <h3 class="card-title text-success">
                                    Parte l'estate con oltre 300 eventi in centro e nei quartieri, tutti gli eventi previsti
                                </h3>
                            </a>
                            <p class="mb-4 pt-3 lora">
                                <strong>Inaugurazione lunedì 2 luglio</strong> con il concerto
                                gratuito in piazza XX Settembre degli Sweet Soul Music Revue. Sul palco 20 musicisti dal tutto il mondo
                            </p>
                            <a class="chip chip-simple chip-green" href="/it/tests/novita-dettaglio">
                                <span class="chip-label">Estate in città</span>
                            </a>
                            <a class="read-more read-more-green pb-3" href="/it/tests/novita">
                                <span class="text">Tutte le novità</span>
                                <svg class="icon">
                                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-arrow-right"></use>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                {{-- Hero Image --}}
                <div class="col-lg-6 order-1 order-lg-2 px-0 px-lg-3">
                    <img src="https://picsum.photos/800/600" title="titolo immagine" alt="descrizione immagine" class="img-fluid">
                </div>
            </div>
        </div>
    </section>
    {{-- Calendario Section - Organi di governo + Events Calendar (from reference) --}}
    @php
        $calendarioData = [
            'cards' => [
                [
                    'category' => 'Organi di governo',
                    'title' => 'Mario Rossi',
                    'role' => 'Il Sindaco della città',
                    'image' => 'https://picsum.photos/150/200',
                    'url' => '/it/tests/amministrazione#sindaco',
                ],
                [
                    'category' => 'Organi di governo',
                    'title' => 'La giunta comunale',
                    'description' => 'La giunta, nominata dal sindaco, esercita collegialmente le funzioni ad essa attribuite dalla legge.',
                    'url' => '/it/tests/amministrazione#giunta',
                ],
                [
                    'category' => 'Organi di governo',
                    'title' => 'Il consiglio comunale',
                    'description' => 'Il Consiglio è un organo collegiale ed elettivo che rimane in carica per 5 anni.',
                    'url' => '/it/tests/amministrazione#consiglio',
                ],
            ],
            'month' => 'LUGLIO 2026',
            'slides' => [
                ['day' => '1', 'weekday' => 'Lunedì', 'events' => [
                    ['title' => 'Estate in città - Inaugurazione', 'url' => '/it/tests/eventi/estate-citta'],
                ]],
                ['day' => '5', 'weekday' => 'Venerdì', 'events' => [
                    ['title' => 'Concerto in piazza', 'url' => '/it/tests/eventi/concerto', 'image' => 'https://picsum.photos/60/60'],
                ]],
                ['day' => '10', 'weekday' => 'Mercoledì', 'events' => [
                    ['title' => 'Mostra d\'arte moderna', 'url' => '/it/tests/eventi/mostra', 'image' => 'https://picsum.photos/60/61'],
                    ['title' => 'Incontro con l\'autore', 'url' => '/it/tests/eventi/incontro'],
                ]],
                ['day' => '15', 'weekday' => 'Lunedì', 'events' => [
                    ['title' => 'Festa di quartiere', 'url' => '/it/tests/eventi/festa'],
                ]],
                ['day' => '20', 'weekday' => 'Sabato', 'events' => [
                    ['title' => 'Mercato dell\'usato', 'url' => '/it/tests/eventi/mercato'],
                    ['title' => 'Laboratorio bambini', 'url' => '/it/tests/eventi/laboratorio'],
                ]],
                ['day' => '25', 'weekday' => 'Giovedì', 'events' => [
                    ['title' => 'Cinema all\'aperto', 'url' => '/it/tests/eventi/cinema', 'image' => 'https://picsum.photos/60/62'],
                ]],
                ['day' => '30', 'weekday' => 'Martedì', 'events' => [
                    ['title' => 'Torneo di scacchi', 'url' => '/it/tests/eventi/scacchi'],
                ]],
            ],
        ];
    @endphp
    <x-blocks.governance.cards :data="$calendarioData" />

    {{-- Services Section --}}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <h2 class="title-xxlarge mb-4">Servizi</h2>
                <div class="row g-4">
                    @foreach($servizi ?? [] as $servizio)
                    <div class="col-sm-6 col-lg-4">
                        <div class="it-grid-item-wrapper it-grid-item-overlay">
                            <a href="{{ $servizio['url'] ?? '#' }}" class="text-decoration-none">
                                <div class="card card-bg card-teaser shadow">
                                    <div class="card-body">
                                        <div class="category-top">
                                            <span class="text">Servizio</span>
                                        </div>
                                        <h3 class="card-title h5">{{ $servizio['nome'] ?? 'Servizio' }}</h3>
                                        <p class="card-text">{{ $servizio['descrizione'] ?? '' }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="text-center mt-4">
                    <a class="btn btn-outline-primary" href="/it/tests/servizi">
                        Tutti i servizi
                        <svg class="icon icon-sm">
                            <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-arrow-right"></use>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Administration Section --}}
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <h2 class="title-xxlarge mb-4">Amministrazione</h2>
                <div class="row g-4">
                    @foreach($amministrazione ?? [] as $item)
                    <div class="col-sm-6 col-lg-4">
                        <div class="it-grid-item-wrapper">
                            <a href="{{ $item['url'] ?? '#' }}" class="text-decoration-none">
                                <div class="card card-bg card-teaser shadow">
                                    <div class="card-body">
                                        <h3 class="card-title h5">{{ $item['titolo'] ?? 'Amministrazione' }}</h3>
                                        <p class="card-text">{{ $item['descrizione'] ?? '' }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="text-center mt-4">
                    <a class="btn btn-outline-primary" href="/it/tests/amministrazione">
                        Tutta l'amministrazione
                        <svg class="icon icon-sm">
                            <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-arrow-right"></use>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- News Section --}}
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <h2 class="title-xxlarge mb-4">Novità</h2>
                <div class="row g-4">
                    @foreach($novita ?? [] as $notizia)
                    <div class="col-sm-6 col-lg-4">
                        <div class="it-grid-item-wrapper">
                            <a href="{{ $notizia['url'] ?? '#' }}" class="text-decoration-none">
                                <div class="card card-bg card-teaser shadow">
                                    <div class="card-body">
                                        <div class="category-top">
                                            <svg class="icon icon-sm" aria-hidden="true">
                                                <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-calendar"></use>
                                            </svg>
                                            <span class="title-xsmall-semi-bold fw-semibold">{{ $notizia['categoria'] ?? 'Notizie' }}</span>
                                            <span class="data fw-normal">{{ $notizia['data'] ?? '' }}</span>
                                        </div>
                                        <h3 class="card-title h5">{{ $notizia['titolo'] ?? 'Notizia' }}</h3>
                                        <p class="card-text">{{ Str::limit($notizia['contenuto'] ?? '', 100) }}</p>
                                        <a class="read-more pb-3" href="{{ $notizia['url'] ?? '#' }}">
                                            <span class="text">Leggi tutto</span>
                                            <svg class="icon">
                                                <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-arrow-right"></use>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="text-center mt-4">
                    <a class="btn btn-outline-primary" href="/it/tests/novita">
                        Tutte le novità
                        <svg class="icon icon-sm">
                            <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-arrow-right"></use>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Events Section (Vivere il Comune) --}}
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <h2 class="title-xxlarge mb-4">Vivere il Comune19</h2>
                <div class="row g-4">
                    @foreach($eventi ?? [] as $evento)
                    <div class="col-sm-6 col-lg-4">
                        <div class="it-grid-item-wrapper">
                            <a href="{{ $evento['url'] ?? '#' }}" class="text-decoration-none">
                                <div class="card card-bg card-teaser shadow">
                                    <div class="card-body">
                                        <div class="category-top">
                                            <svg class="icon icon-sm" aria-hidden="true">
                                                <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-calendar"></use>
                                            </svg>
                                            <span class="title-xsmall-semi-bold fw-semibold">{{ $evento['categoria'] ?? 'Eventi' }}</span>
                                            <span class="data fw-normal">{{ $evento['data'] ?? '' }}</span>
                                        </div>
                                        <h3 class="card-title h5">{{ $evento['titolo'] ?? 'Evento' }}</h3>
                                        <p class="card-text">{{ Str::limit($evento['descrizione'] ?? '', 100) }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="text-center mt-4">
                    <a class="btn btn-outline-primary" href="/it/tests/eventi">
                        Tutti gli eventi
                        <svg class="icon icon-sm">
                            <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-arrow-right"></use>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- Argomenti in Evidenza Section --}}
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <h2 class="title-xxlarge mb-4">{{ __('navigation.homepage.featured_topics') }}</h2>
            <div class="row g-4">
                @php
                $featuredTopics = [
                    [
                        'title' => 'Trasporto pubblico',
                        'description' => 'Informazioni sui servizi di trasporto pubblico e servizi taxi',
                        'url' => '#',
                        'image' => 'https://picsum.photos/200/200',
                    ],
                    [
                        'title' => 'Animale domestico',
                        'description' => 'Informazioni sui servizi e le norme previste dal comune per gli animali domestici.',
                        'url' => '#',
                        'links' => [
                            'Come adottare un cane al Canile Municipale',
                            'Elenco delle aree per cani',
                            'Come segnalare una colonia felina',
                            'Come segnalare lo smarrimento del proprio animale',
                        ],
                    ],
                    [
                        'title' => 'Sport',
                        'description' => 'Tutto quello che c\'è da sapere sulle strutture sportive comunali.',
                        'url' => '#',
                        'links' => [
                            'Tutte le strutture sportive della città',
                            'Da lunedì 3 settembre chiudono le vasche della piscina comunale',
                            'Concessione di contributi ad enti, associazioni, società sportive',
                        ],
                    ],
                ];
                @endphp
                @foreach($featuredTopics as $topic)
                <div class="col-sm-6 col-lg-4">
                    <div class="card card-bg card-teaser shadow">
                        <div class="card-body">
                            <h3 class="card-title h5">{{ $topic['title'] }}</h3>
                            <p class="card-text">{{ $topic['description'] }}</p>
                            @if(isset($topic['links']))
                            <ul class="mb-3">
                                @foreach($topic['links'] as $link)
                                <li><a href="{{ $topic['url'] }}">{{ $link }}</a></li>
                                @endforeach
                            </ul>
                            @endif
                            <a href="{{ $topic['url'] }}" class="read-more">
                                <span class="text">Esplora argomento</span>
                                <svg class="icon"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-arrow-right"></use></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Altri Argomenti Section --}}
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <h2 class="title-xxlarge mb-4">{{ __('navigation.homepage.other_topics') }}</h2>
            <div class="d-flex flex-wrap gap-2">
                <a href="#" class="chip chip-simple"><span class="chip-label">Associazioni</span></a>
                <a href="#" class="chip chip-simple"><span class="chip-label">Concorsi</span></a>
                <a href="#" class="chip chip-simple"><span class="chip-label">Energie rinnovabili</span></a>
                <a href="#" class="chip chip-simple"><span class="chip-label">Gestione rifiuti</span></a>
                <a href="#" class="chip chip-simple"><span class="chip-label">Imposte</span></a>
                <a href="#" class="chip chip-simple"><span class="chip-label">Istruzione</span></a>
                <a href="#" class="chip chip-simple"><span class="chip-label">Pista ciclabile</span></a>
            </div>
            <a href="#" class="btn btn-outline-primary mt-3">{{ __('navigation.homepage.show_all') }}</a>
        </div>
    </div>
</div>

{{-- Siti Tematici Section --}}
@php
$thematicSites = [
    'title' => __('navigation.homepage.thematic_sites'),
    'sites' => [
        [
            'title' => 'Mobilità in Comune',
            'description' => 'Il sito del turismo del Comune e della Città Metropolitana',
            'url' => '#',
            'image' => 'https://picsum.photos/200/200',
        ],
        [
            'title' => 'Turismo',
            'description' => 'Il sito che offre informazioni sulle attività turistiche attive in città',
            'url' => '#',
            'image' => 'https://picsum.photos/200/201',
        ],
        [
            'title' => 'Musei Civici',
            'description' => 'Tutte le informazioni sui musei e gli eventi culturali della città',
            'url' => '#',
            'image' => 'https://picsum.photos/200/202',
        ],
    ],
];
@endphp
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <h2 class="title-xxlarge mb-4">{{ $thematicSites['title'] }}</h2>
            <div class="row g-4">
                @foreach($thematicSites['sites'] as $site)
                <div class="col-sm-6 col-lg-4">
                    <a href="{{ $site['url'] }}" class="text-decoration-none">
                        <div class="card card-bg shadow">
                            <img src="{{ $site['image'] }}" alt="{{ $site['title'] }}" class="card-img-top">
                            <div class="card-body">
                                <h3 class="card-title h5">{{ $site['title'] }}</h3>
                                <p class="card-text">{{ $site['description'] }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Useful Links Section --}}
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <h2 class="title-xxlarge mb-4">{{ __('navigation.homepage.useful_links') }}</h2>
            <ul class="list-unstyled">
                <li><a href="#">Rilascio Carta Identità Elettronica (CIE)</a></li>
                <li><a href="#">Cambio di residenza</a></li>
                <li><a href="#">Tributi online</a></li>
                <li><a href="#">Prenotazione appuntamenti</a></li>
                <li><a href="#">Rilascio tessera elettorale</a></li>
                <li><a href="#">Voucher connettività</a></li>
            </ul>
        </div>
    </div>
</div>

{{-- Rating Section --}}
<x-blocks.rating.default />

{{-- Contacts Section --}}
<div class="bg-grey-card shadow-contacts">
    <div class="container">
        <div class="row d-flex justify-content-center p-contacts">
            <div class="col-12 col-lg-6">
                <div class="cmp-contacts" data-element="contacts">
                    <div class="card card-wrapper bg-white p-3">
                        <div class="card-body">
                            <h2 class="title-medium-2-semi-bold mb-0">{{ __('navigation.homepage.contacts') }}</h2>
                            <div class="d-flex flex-column gap-3">
                                <a href="tel:+390512345678" class="d-flex align-items-center gap-2 text-decoration-none text-primary">
                                    <svg class="icon icon-sm"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-phone"></use></svg>
                                    <span>05 12345678</span>
                                </a>
                                <a href="mailto:urp@comune.it" class="d-flex align-items-center gap-2 text-decoration-none text-primary">
                                    <svg class="icon icon-sm"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-mail"></use></svg>
                                    <span>urp@comune.it</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Maybe You Were Looking For --}}
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <h2 class="title-xxlarge mb-4">{{ __('navigation.homepage.maybe_searching') }}</h2>
            <ul class="list-unstyled">
                <li><a href="#">Rilascio Carta Identità Elettronica (CIE)</a></li>
                <li><a href="#">Cambio di residenza</a></li>
                <li><a href="#">Tributi online</a></li>
                <li><a href="#">Prenotazione appuntamenti</a></li>
                <li><a href="#">Rilascio tessera elettorale</a></li>
                <li><a href="#">Voucher connettività</a></li>
            </ul>
        </div>
    </div>
</div>

{{-- Footer Component --}}
@include('pub_theme::footer-comune')

@endsection

@push('scripts')
{{-- Bootstrap Italia JS --}}
@endpush
