@props(['data' => []])

@php
    $title = $data['title'] ?? 'Elenco segnalazioni';
    $subtitle = $data['subtitle'] ?? 'Negli ultimi 12 mesi sono state risolte 73 segnalazioni.';
    $resultsCount = $data['results_count'] ?? 645;
    $sprite = '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg';

    // Sample data for demonstration
    $categories = $data['categories'] ?? [
        ['label' => 'Verde pubblico', 'count' => 123],
        ['label' => 'Illuminazione', 'count' => 89],
        ['label' => 'Strade', 'count' => 156],
        ['label' => 'Rifiuti', 'count' => 201],
        ['label' => 'Arredo urbano', 'count' => 76],
    ];

    $segnalazioni = $data['segnalazioni'] ?? [
        [
            'titolo' => 'Buca in via Solferino',
            'tipologia' => 'Verde pubblico e arredo urbano',
            'data' => '15/03/2022',
            'stato' => 'In lavorazione',
        ],
        [
            'titolo' => 'Lampione spento via Roma',
            'tipologia' => 'Illuminazione pubblica',
            'data' => '12/03/2022',
            'stato' => 'Risolta',
        ],
        [
            'titolo' => 'Erba alta parco giochi',
            'tipologia' => 'Verde pubblico',
            'data' => '10/03/2022',
            'stato' => 'Da prendere in carico',
        ],
    ];
@endphp

<div class="container" id="main-container">
    <div class="row justify-content-center mb-md-40 mb-lg-80">
        <div class="col-12 col-lg-10">
            <!-- Breadcrumbs -->
            <nav class="breadcrumb-container" aria-label="breadcrumb">
                <ol class="breadcrumb" data-element="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/it/tests/homepage">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="/it/tests/servizi">Servizi</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="#">Elenco segnalazioni</a>
                    </li>
                </ol>
            </nav>

            <!-- Heading -->
            <div class="cmp-heading p-0">
                <h1 class="title-xxxlarge" data-element="service-title">{{ $title }}</h1>
                @if($subtitle)
                    <p class="subtitle-small">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        <hr class="d-none d-lg-block mt-30 mb-2">
    </div>

    <div class="row justify-content-center">
        <!-- Sidebar categorie -->
        <div class="col-lg-3 d-none d-lg-block">
            <div class="cmp-category-list">
                <div class="card-wrapper bg-grey-card rounded h-auto">
                    <div class="card card-teaser bg-grey-card rounded shadow-sm p-3">
                        <div class="card-body">
                            <h2 class="title-medium-semi-bold mb-3">Categorie</h2>
                            <ul class="link-list">
                                @foreach($categories as $cat)
                                    <li>
                                        <a class="list-item" href="#">
                                            <span class="list-item-title">{{ $cat['label'] }}</span>
                                            <span class="badge bg-primary rounded-pill ms-2">{{ $cat['count'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="col-lg-8 offset-lg-1">
            <!-- Search results bar -->
            <div class="d-flex justify-content-between border-bottom border-light pb-3 mt-5">
                <span class="search-results">{{ $resultsCount }} Risultati</span>

                <button type="button" class="btn p-0 pe-2 d-lg-none" data-bs-toggle="modal" data-bs-target="#modal-categories">
                    <span class="t-primary title-xsmall-semi-bold ms-1">Filtra</span>
                    <svg class="icon icon-primary icon-sm" aria-hidden="true">
                        <use href="{{ $sprite }}#it-funnel"></use>
                    </svg>
                </button>
                <button type="button" class="btn p-0 pe-2 d-none d-lg-block">
                    <span class="title-xsmall-semi-bold ms-1">Rimuovi tutti i filtri</span>
                </button>
            </div>

            <!-- Tabs Mappa/Elenco -->
            <ul class="nav nav-tabs w-100 flex-nowrap border-bottom border-light mb-40 mt-3 shadow-none"
                id="tabDisservizio" role="tablist"
                x-data="{ activeTab: 'mappa' }">
                <li class="nav-item w-100" role="tab">
                    <a class="nav-link title-medium-semi-bold pt-0"
                       :class="{ 'active': activeTab === 'mappa' }"
                       @click.prevent="activeTab = 'mappa'"
                       href="#mappa"
                       aria-controls="mappa"
                       :aria-selected="activeTab === 'mappa'"
                       role="tab">
                        Mappa
                    </a>
                </li>
                <li class="nav-item w-100" role="tab">
                    <a class="nav-link title-medium-semi-bold pt-0"
                       :class="{ 'active': activeTab === 'elenco' }"
                       @click.prevent="activeTab = 'elenco'"
                       href="#elenco"
                       aria-controls="elenco"
                       :aria-selected="activeTab === 'elenco'"
                       role="tab">
                        Elenco
                    </a>
                </li>
            </ul>

            <!-- Tab content -->
            <div class="tab-content">
                <!-- Mappa tab -->
                <div class="tab-pane fade show active" id="mappa" role="tabpanel"
                     x-show="activeTab === 'mappa'">
                    <div class="row">
                        <div class="col-12">
                            <!-- Map placeholder -->
                            <div class="cmp-map mb-4">
                                <div class="card-wrapper bg-grey-card rounded h-auto">
                                    <div class="card card-teaser bg-grey-card rounded shadow-sm">
                                        <div class="card-body p-0">
                                            <div class="map-placeholder" style="height: 300px; background: #e8e8e8; display: flex; align-items: center; justify-content: center;">
                                                <span class="text-muted">Mappa delle segnalazioni</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-50 mb-4 mb-lg-0">
                            <div class="card-wrapper bg-grey-card rounded h-auto">
                                <div class="card card-teaser bg-grey-card rounded shadow-sm p-3">
                                    <div class="card-body">
                                        <h3 class="title-medium-semi-bold">Fai una segnalazione</h3>
                                        <p class="subtitle-small mt-3">Se vuoi aggiungere una segnalazione, puoi farlo dopo esserti autenticato con le tue credenziali SPID o CIE.</p>
                                        <a href="/it/tests/segnalazione-01-privacy" class="btn btn-primary mobile-full py-3 mt-2 mb-4 mb-lg-0">
                                            <span>Segnala disservizio</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Elenco tab -->
                <div class="tab-pane fade" id="elenco" role="tabpanel"
                     x-show="activeTab === 'elenco'">
                    <div class="row">
                        @foreach($segnalazioni as $index => $s)
                            <div class="col-12 mb-4 mb-lg-30">
                                <div class="card-wrapper bg-grey-card rounded h-auto">
                                    <div class="card card-teaser bg-grey-card rounded shadow-sm p-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h3 class="title-medium-semi-bold t-primary">{{ $s['titolo'] }}</h3>
                                                    <p class="subtitle-small mb-1">{{ $s['tipologia'] }}</p>
                                                    <p class="text-paragraph-small text-muted">{{ $s['data'] }}</p>
                                                </div>
                                                <span class="badge bg-{{ $s['stato'] === 'Risolta' ? 'success' : ($s['stato'] === 'In lavorazione' ? 'warning' : 'secondary') }}">
                                                    {{ $s['stato'] }}
                                                </span>
                                            </div>

                                            <!-- Collapsible details -->
                                            <div class="mt-3" x-data="{ open: false }">
                                                <button type="button" class="btn btn-link p-0 t-primary title-small-semi-bold"
                                                        @click="open = !open"
                                                        :aria-expanded="open">
                                                    <span x-text="open ? 'Chiudi dettagli' : 'Vedi dettagli'"></span>
                                                    <svg class="icon icon-primary icon-xs ms-1"
                                                         :class="{ 'rotate-180': open }"
                                                         aria-hidden="true">
                                                        <use href="{{ $sprite }}#it-expand"></use>
                                                    </svg>
                                                </button>
                                                <div x-show="open" x-collapse>
                                                    <div class="mt-3 p-3 bg-white rounded">
                                                        <p class="text-paragraph-small">Dettagli della segnalazione...</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Load more button -->
                    <div class="text-center mt-10">
                        <button type="button" class="btn btn-outline-primary mobile-full py-3 mx-auto">
                            <span>Carica altre segnalazioni</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rating section -->
<div class="bg-primary">
    <div class="container">
        <div class="row d-flex justify-content-center bg-primary">
            <div class="col-12 col-lg-6 p-lg-0 px-3">
                <div class="cmp-rating pt-lg-80 pb-lg-80" id="rating">
                    <div class="card shadow card-wrapper" data-element="feedback">
                        <div class="cmp-rating__card-first">
                            <div class="card-header border-0">
                                <h2 class="title-medium-2-semi-bold mb-0" data-element="feedback-title">Quanto è stato facile usare questo servizio?</h2>
                            </div>
                            <div class="card-body">
                                <fieldset class="rating">
                                    <legend class="visually-hidden">Valuta da 1 a 5 stelle il servizio</legend>
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}a" name="ratingA" value="{{ $i }}">
                                        <label class="full rating-star" for="star{{ $i }}a" data-element="feedback-rate-{{ $i }}">
                                            <svg class="icon icon-sm" role="img" aria-hidden="true" viewBox="0 0 24 24">
                                                <path d="M12 1.7L9.5 9.2H1.6L8 13.9l-2.4 7.6 6.4-4.7 6.4 4.7-2.4-7.6 6.4-4.7h-7.9L12 1.7z"/>
                                            </svg>
                                            <span class="visually-hidden">Valuta {{ $i }} stelle su 5</span>
                                        </label>
                                    @endfor
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contacts footer -->
<div class="bg-grey-card shadow-contacts">
    <div class="container">
        <div class="row d-flex justify-content-center p-contacts">
            <div class="col-12 col-lg-5">
                <div class="cmp-contacts">
                    <div class="card w-100">
                        <div class="card-body">
                            <h2 class="title-medium-2-semi-bold">Contatta il comune</h2>
                            <ul class="contact-list p-0">
                                <li>
                                    <a class="list-item" href="#">
                                        <span class="list-item-title-icon-wrapper">
                                            <svg class="icon icon-primary icon-sm" aria-hidden="true">
                                                <use href="{{ $sprite }}#it-help-circle"></use>
                                            </svg>
                                            <span>Leggi le domande frequenti</span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a class="list-item" href="#" data-element="contacts">
                                        <span class="list-item-title-icon-wrapper">
                                            <svg class="icon icon-primary icon-sm" aria-hidden="true">
                                                <use href="{{ $sprite }}#it-mail"></use>
                                            </svg>
                                            <span>Richiedi assistenza</span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a class="list-item" href="#">
                                        <span class="list-item-title-icon-wrapper">
                                            <svg class="icon icon-primary icon-sm" aria-hidden="true">
                                                <use href="{{ $sprite }}#it-hearing"></use>
                                            </svg>
                                            <span>Chiama il numero verde 05 0505</span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal categorie (mobile) -->
<div class="modal fade" id="modal-categories" tabindex="-1" aria-labelledby="modal-categories-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="title-medium-semi-bold" id="modal-categories-title">Filtra per categoria</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
            </div>
            <div class="modal-body">
                <ul class="link-list">
                    @foreach($categories as $cat)
                        <li>
                            <a class="list-item" href="#">
                                <span class="list-item-title">{{ $cat['label'] }}</span>
                                <span class="badge bg-primary rounded-pill ms-2">{{ $cat['count'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
