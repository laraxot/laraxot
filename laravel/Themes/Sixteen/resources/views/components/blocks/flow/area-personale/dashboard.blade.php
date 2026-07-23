@props([
    'title' => 'Area Personale',
    'description' => 'Benvenuto nella tua area personale',
    'user' => [],
    'messaggi' => [],
    'attivita' => [],
    'pratiche' => [],
])

@php
    $user = array_merge([
        'nome' => 'Giulia',
        'cognome' => 'Rossi',
        'email' => '',
        'codice_fiscale' => 'GLARSS72H25H501Y',
    ], $user);
    
    $messaggi = $messaggi ?: [
        ['titolo' => 'Richiesta servizio mensa scolastica', 'data' => '05/04/2022', 'stato' => 'approvato'],
        ['titolo' => 'Richiesta servizio mensa scolastica', 'data' => '20/03/2022', 'stato' => 'ricevuto'],
        ['titolo' => 'Iscrizione alla scuola dell\'infanzia', 'data' => '15/02/2022', 'stato' => 'ricevuto'],
    ];
    
    $attivita = $attivita ?: [
        ['titolo' => 'Segnalazione disservizio', 'data' => '15/04/2022', 'tipo' => 'files'],
        ['titolo' => 'Pagamento contravvenzione', 'data' => '23/01/2022', 'tipo' => 'files'],
        ['titolo' => 'Richiesta assegno maternità', 'data' => '01/10/2021', 'tipo' => 'files'],
    ];
    
    $pratiche = $pratiche ?: [
        ['titolo' => 'Segnalazione disservizio', 'data' => '20/03/2022', 'stato' => 'in_attesa'],
        ['titolo' => 'Iscrizione corso di formazione', 'data' => '24/06/2021', 'stato' => 'conclusa'],
        ['titolo' => 'Richiesta permesso ZTL', 'data' => '10/05/2021', 'stato' => 'conclusa'],
    ];
@endphp

<div class="container" id="main-container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="cmp-heading pb-3 pb-lg-4">
                <h1 class="title-xxxlarge">{{ $user['nome'] }} {{ $user['cognome'] }}</h1>
                <p class="subtitle-small">CF: {{ $user['codice_fiscale'] }}</p>
            </div>
        </div>
        
        <div class="col-12 p-0">
            <div class="cmp-nav-tab mb-4 mb-lg-5 mt-lg-4">
                <ul class="nav nav-tabs nav-tabs-icon-text w-100 flex-nowrap" id="myTab" role="tablist">
                    <li class="nav-item w-100" role="tab">
                        <a class="nav-link justify-content-start text-tab active" href="#data-ex-tab1" aria-current="page" aria-controls="tab1" aria-selected="false" data-bs-toggle="tab" role="button">
                            <svg class="icon me-1 mr-lg-10" aria-hidden="true">
                                <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-pa"></use>
                            </svg>
                            Scrivania
                        </a>
                    </li>
                    <li class="nav-item w-100" role="tab">
                        <a class="nav-link justify-content-start text-tab" href="#data-ex-tab2" aria-current="page" aria-controls="tab2" aria-selected="false" data-bs-toggle="tab" role="button">
                            <svg class="icon me-1 mr-lg-10" aria-hidden="true">
                                <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-mail"></use>
                            </svg>
                            Messaggi
                        </a>
                    </li>
                    <li class="nav-item w-100" role="tab">
                        <a class="nav-link justify-content-start text-tab" href="#data-ex-tab3" aria-current="page" aria-controls="tab3" aria-selected="false" data-bs-toggle="tab" role="button">
                            <svg class="icon me-1 mr-lg-10" aria-hidden="true">
                                <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-files"></use>
                            </svg>
                            Attività
                        </a>
                    </li>
                    <li class="nav-item w-100" role="tab">
                        <a class="nav-link justify-content-start text-tab" href="#data-ex-tab4" aria-current="page" aria-controls="tab4" aria-selected="false" data-bs-toggle="tab" role="button">
                            <svg class="icon me-1 mr-lg-10" aria-hidden="true">
                                <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-settings"></use>
                            </svg>
                            Servizi
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="it-page-sections-container">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="data-ex-tab1" role="tabpanel">
                <div class="row">
                    <div class="col-12 col-lg-3 d-lg-block mb-4 d-none">
                        <div class="cmp-navscroll sticky-top" aria-labelledby="accordion-title-one">
                            <nav class="navbar it-navscroll-wrapper navbar-expand-lg" aria-label="INDICE DELLA PAGINA" data-bs-navscroll>
                                <div class="navbar-custom" id="navbarNavProgress">
                                    <div class="menu-wrapper">
                                        <div class="link-list-wrapper">
                                            <div class="accordion">
                                                <div class="accordion-item">
                                                    <span class="accordion-header" id="accordion-title-one">
                                                        <button class="accordion-button pb-10 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-one" aria-expanded="true" aria-controls="collapse-one">
                                                            INDICE DELLA PAGINA
                                                            <svg class="icon icon-xs right">
                                                                <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-expand"></use>
                                                            </svg>
                                                        </button>
                                                    </span>
                                                    <div class="progress">
                                                        <div class="progress-bar it-navscroll-progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <div id="collapse-one" class="accordion-collapse collapse show" role="region" aria-labelledby="accordion-title-one">
                                                        <div class="accordion-body">
                                                            <ul class="link-list" data-element="page-index">
                                                                <li class="nav-item">
                                                                    <a class="nav-link" href="#latest-posts">
                                                                        <span>Ultimi messaggi</span>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" href="#latest-activities">
                                                                        <span>Ultime attività</span>
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
                            </nav>
                        </div>
                    </div>
                    
                    <div class="col-12 col-lg-8 offset-lg-1">
                        <div class="it-page-section mb-40 mb-lg-60" id="latest-posts">
                            <div class="cmp-card">
                                <div class="card">
                                    <div class="card-header border-0 p-0 mb-lg-20">
                                        <div class="d-flex">
                                            <h2 class="title-xxlarge">Ultimi messaggi</h2>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        @foreach($messaggi as $msg)
                                        <div class="cmp-card-latest-messages mb-3">
                                            <div class="card shadow-sm px-4 pt-4 pb-4">
                                                <div class="card-header border-0 p-0 m-0">
                                                    <date class="date-xsmall">{{ $msg['data'] }}</date>
                                                </div>
                                                <div class="card-body p-0 my-2">
                                                    <h3 class="title-small-semi-bold t-primary m-0 mb-1">
                                                        <a href="#" class="text-decoration-none">{{ $msg['titolo'] }}</a>
                                                    </h3>
                                                    <p class="text-paragraph text-truncate">La richiesta è stata {{ $msg['stato'] }}...</p>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        <button type="button" class="btn btn-xs btn-me btn-label t-primary px-0">
                                            <span class="">Vedi altri messaggi</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="it-page-section mb-50 mb-lg-90" id="latest-activities">
                            <div class="cmp-card">
                                <div class="card">
                                    <div class="card-header border-0 p-0 mb-lg-20 m-0">
                                        <div class="d-flex">
                                            <h2 class="title-xxlarge mb-3">Ultime attività</h2>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        @foreach($attivita as $att)
                                        <div class="cmp-icon-card mb-3">
                                            <div class="card pt-20 pb-4 ps-4 pr-30 drop-shadow">
                                                <div class="cmp-card-title d-flex">
                                                    <svg class="icon icon-sm me-2" aria-hidden="true">
                                                        <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-files"></use>
                                                    </svg>
                                                    <h3 class="t-primary mb-2 underline title-small-semi-bold">
                                                        <a href="#">{{ $att['titolo'] }}</a>
                                                    </h3>
                                                </div>
                                                <div class="cmp-icon-card__description">
                                                    <date class="date-xsmall ml-30">{{ $att['data'] }}</date>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        <button type="button" class="btn btn-xs btn-me btn-label t-primary px-0">
                                            <span class="">Vedi altre attività</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tab-pane" id="data-ex-tab3" role="tabpanel">
                <div class="row">
                    <div class="col-12 col-lg-8 offset-lg-1">
                        <section class="it-page-section mb-40 mb-lg-60" id="practices">
                            <div class="cmp-filter">
                                <div class="filter-section">
                                    <h2 class="cmp-filter__title title-xxlarge">Pratiche</h2>
                                    <div class="filter-wrapper d-flex align-items-center">
                                        <button type="button" class="btn p-0 pe-2 t-primary">
                                            <span class="rounded-icon">
                                                <svg class="icon icon-primary icon-xs me-1" aria-hidden="true">
                                                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-funnel"></use>
                                                </svg>
                                            </span>
                                            <span class="">Filtra</span>
                                        </button>
                                        <div class="dropdown">
                                            <button class="btn btn-dropdown dropdown-toggle" type="button" id="dropdownMenu-pratiche" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="dropdown__title">Ordina</span>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenu-pratiche">
                                                <div class="link-list-wrapper">
                                                    <ul class="link-list">
                                                        <li><a class="dropdown-item list-item" href="#"><span>Più recenti</span></a></li>
                                                        <li><a class="dropdown-item list-item" href="#"><span>Più vecchi</span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="cmp-accordion">
                                <div class="accordion" id="accordionPratiche">
                                    @foreach($pratiche as $idx => $pratica)
                                    <div class="accordion-item">
                                        <div class="accordion-header" id="heading{{ $idx }}">
                                            <button class="accordion-button collapsed title-snall-semi-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $idx }}" aria-expanded="true" aria-controls="collapse{{ $idx }}">
                                                <div class="button-wrapper">
                                                    {{ $pratica['titolo'] }}
                                                    <div class="icon-wrapper">
                                                        @if($pratica['stato'] === 'in_attesa')
                                                        <img class="icon-folder" src="/themes/Sixteen/design-comuni/assets/images/folder-waiting.svg" alt="folder In attesa" role="img">
                                                        <span class="u-main-black">In attesa</span>
                                                        @else
                                                        <img class="icon-folder" src="/themes/Sixteen/design-comuni/assets/images/folder-concluded.svg" alt="folder Conclusa" role="img">
                                                        <span class="">Conclusa</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </button>
                                            <p class="accordion-date title-xsmall-regular mb-0">{{ $pratica['data'] }}</p>
                                        </div>
                                        
                                        <div id="collapse{{ $idx }}" class="accordion-collapse collapse p-0" data-bs-parent="#accordionPratiche" role="region" aria-labelledby="heading{{ $idx }}">
                                            <div class="accordion-body">
                                                <p class="mb-2 fw-normal">Pratica: <span class="label">AN4059281</span></p>
                                                <a href="#" class="mb-2">
                                                    <span class="t-primary">Scheda servizio</span>
                                                </a>
                                                <a class="chip chip-simple" href="#">
                                                    <span class="chip-label">Servizio non digitale</span>
                                                </a>
                                                <button type="button" class="btn btn-primary justify-content-center my-3">
                                                    <span class="">Perfeziona la richiesta</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>