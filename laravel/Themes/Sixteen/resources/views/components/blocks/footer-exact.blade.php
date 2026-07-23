@props(['data' => []])

{{--
    Footer Component - EXACT Design Comuni HTML Structure
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
    
    EXACT Structure Required:
    - it-footer
    - it-footer-main
    - footer-items-wrapper (on all columns)
    - logo-wrapper (first column)
    - ue-logo (EU flag)
    - it-brand-wrapper with SVG
    - footer-heading-title
    - footer-list (on all ul)
--}}

@php
    $cityName = $data['city_name'] ?? 'Nome del Comune';
    $ueLogoUrl = $data['ue_logo_url'] ?? asset('themes/sixteen/images/logo-eu-inverted.svg');
    $homeUrl = $data['home_url'] ?? '/it/tests/homepage';
    
    $adminLinks = $data['admin_links'] ?? [
        ['label' => 'Organi di governo', 'url' => '#'],
        ['label' => 'Aree amministrative', 'url' => '#'],
        ['label' => 'Uffici', 'url' => '#'],
        ['label' => 'Enti e fondazioni', 'url' => '#'],
        ['label' => 'Politici', 'url' => '#'],
        ['label' => 'Personale amministrativo', 'url' => '#'],
        ['label' => 'Documenti e dati', 'url' => '#'],
    ];
    
    $serviceCategories = $data['service_categories'] ?? [
        ['label' => 'Anagrafe e stato civile', 'url' => '#'],
        ['label' => 'Cultura e tempo libero', 'url' => '#'],
        ['label' => 'Vita lavorativa', 'url' => '#'],
        ['label' => 'Imprese e commercio', 'url' => '#'],
        ['label' => 'Appalti pubblici', 'url' => '#'],
        ['label' => 'Catasto e urbanistica', 'url' => '#'],
        ['label' => 'Turismo', 'url' => '#'],
        ['label' => 'Mobilità e trasporti', 'url' => '#'],
        ['label' => 'Educazione e formazione', 'url' => '#'],
        ['label' => 'Giustizia e sicurezza pubblica', 'url' => '#'],
        ['label' => 'Tributi, finanze e contravvenzioni', 'url' => '#'],
        ['label' => 'Ambiente', 'url' => '#'],
        ['label' => 'Salute, benessere e assistenza', 'url' => '#'],
        ['label' => 'Autorizzazioni', 'url' => '#'],
        ['label' => 'Agricoltura e pesca', 'url' => '#'],
    ];
    
    $newsLinks = $data['news_links'] ?? [
        ['label' => 'Notizie', 'url' => '/it/tests/novita'],
        ['label' => 'Comunicati', 'url' => '#'],
        ['label' => 'Avvisi', 'url' => '#'],
    ];
    
    $liveLinks = $data['live_links'] ?? [
        ['label' => 'Luoghi', 'url' => '#'],
        ['label' => 'Eventi', 'url' => '/it/tests/eventi'],
    ];
    
    $contactLinks = $data['contact_links'] ?? [
        ['label' => 'Leggi le FAQ', 'url' => '/it/tests/domande-frequenti'],
        ['label' => 'Prenotazione appuntamento', 'url' => '/it/tests/appuntamento-01-ufficio'],
        ['label' => 'Segnalazione disservizio', 'url' => '#'],
        ['label' => 'Richiesta d\'assistenza', 'url' => '/it/tests/assistenza-01-dati'],
    ];
    
    $legalLinks = $data['legal_links'] ?? [
        ['label' => 'Amministrazione trasparente', 'url' => '#'],
        ['label' => 'Informativa privacy', 'url' => '#'],
        ['label' => 'Note legali', 'url' => '#'],
        ['label' => 'Dichiarazione di accessibilità', 'url' => '#'],
    ];
    
    $socialLinks = $data['social_links'] ?? [
        ['platform' => 'twitter', 'url' => '#', 'label' => 'Twitter'],
        ['platform' => 'facebook', 'url' => '#', 'label' => 'Facebook'],
        ['platform' => 'youtube', 'url' => '#', 'label' => 'YouTube'],
        ['platform' => 'telegram', 'url' => '#', 'label' => 'Telegram'],
        ['platform' => 'whatsapp', 'url' => '#', 'label' => 'Whatsapp'],
        ['platform' => 'rss', 'url' => '#', 'label' => 'RSS'],
    ];
@endphp

{{-- EXACT Design Comuni footer structure --}}
<footer class="it-footer" id="footer">
    <div class="it-footer-main">
        <div class="container">
            <div class="row">
                {{-- Logo and Brand Section --}}
                <div class="col-12 footer-items-wrapper logo-wrapper">
                    {{-- EU Logo - REQUIRED --}}
                    <img class="ue-logo" 
                         src="{{ $ueLogoUrl }}" 
                         alt="logo Unione Europea">
                    
                    {{-- Brand with SVG --}}
                    <div class="it-brand-wrapper">
                        <a href="{{ $homeUrl }}">
                            <svg class="icon" aria-hidden="true">
                                <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-pa"></use>
                            </svg>
                            <div class="it-brand-text">
                                <h2 class="no_toc">{{ $cityName }}</h2>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="row">
                {{-- Administration Column --}}
                <div class="col-md-3 footer-items-wrapper">
                    <h4 class="footer-heading-title">Amministrazione</h4>
                    <ul class="footer-list">
                        @foreach($adminLinks as $link)
                        <li>
                            <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                
                {{-- Service Categories Column (wider) --}}
                <div class="col-md-6 footer-items-wrapper">
                    <h4 class="footer-heading-title">Categorie di servizio</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="footer-list">
                                @foreach(array_slice($serviceCategories, 0, 7) as $link)
                                <li>
                                    <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="footer-list">
                                @foreach(array_slice($serviceCategories, 7) as $link)
                                <li>
                                    <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                
                {{-- News Column --}}
                <div class="col-md-3 footer-items-wrapper">
                    <h4 class="footer-heading-title">Novità</h4>
                    <ul class="footer-list">
                        @foreach($newsLinks as $link)
                        <li>
                            <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <div class="row">
                {{-- Live Column --}}
                <div class="col-md-3 footer-items-wrapper">
                    <h4 class="footer-heading-title">Vivere il comune3</h4>
                    <ul class="footer-list">
                        @foreach($liveLinks as $link)
                        <li>
                            <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                
                {{-- Contact Column --}}
                <div class="col-md-6 footer-items-wrapper">
                    <h4 class="footer-heading-title">Contatti</h4>
                    <ul class="footer-list">
                        @foreach($contactLinks as $link)
                        <li>
                            <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                
                {{-- Legal Column --}}
                <div class="col-md-3 footer-items-wrapper">
                    <h4 class="footer-heading-title">Amministrazione trasparente</h4>
                    <ul class="footer-list">
                        @foreach($legalLinks as $link)
                        <li>
                            <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            {{-- Social Links Section --}}
            <div class="row">
                <div class="col-12 footer-items-wrapper">
                    <div class="it-social-list">
                        <h4 class="footer-heading-title">Seguici su</h4>
                        <ul class="list-inline">
                            @foreach($socialLinks as $social)
                            <li class="list-inline-item">
                                <a href="{{ $social['url'] }}" 
                                   target="_blank"
                                   rel="noopener"
                                   aria-label="{{ $social['platform'] }} - link esterno - apertura nuova scheda"
                                   title="{{ $social['platform'] }}">
                                    {{ $social['label'] }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
