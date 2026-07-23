{{--
    EXACT Design Comuni Header - COPIED FROM view-source
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
    
    HTML STRUCTURE MUST BE IDENTICAL - Only Tailwind for colors!
--}}

{{-- SKIP LINKS - MUST BE FIRST --}}
<div class="skiplink">
    <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
    <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
</div><!-- /skiplink -->

{{-- HEADER WRAPPER - EXACT structure --}}
<header class="it-header-wrapper" data-bs-target="#header-nav-wrapper">
    
    {{-- TOP BAR (SLIM) - EXACT structure --}}
    <div class="it-header-slim-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="it-header-slim-wrapper-content">
                        {{-- Region Link - EXACT --}}
                        <a class="d-lg-block navbar-brand" 
                           target="_blank" 
                           href="#" 
                           aria-label="Vai al portale {Nome della Regione} - link esterno - apertura nuova scheda" 
                           title="Vai al portale {Nome della Regione}">
                            {{ $regionName ?? 'Nome della Regione' }}
                        </a>
                        
                        {{-- Right Zone - EXACT --}}
                        <div class="it-header-slim-right-zone" role="navigation">
                            
                            {{-- Language Dropdown - EXACT --}}
                            <div class="nav-item dropdown">
                                <button type="button" 
                                        class="nav-link dropdown-toggle" 
                                        data-bs-toggle="dropdown" 
                                        aria-expanded="false" 
                                        aria-controls="languages" 
                                        aria-haspopup="true">
                                    <span class="visually-hidden">Lingua attiva:</span>
                                    <span>ITA</span>
                                    <svg class="icon">
                                        <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-expand"></use>
                                    </svg>
                                </button>
                                <div class="dropdown-menu">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="link-list-wrapper">
                                                <ul class="link-list">
                                                    <li><a class="dropdown-item list-item" href="#"><span>ITA <span class="visually-hidden">selezionata</span></span></a></li>
                                                    <li><a class="dropdown-item list-item" href="#"><span>ENG</span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Login Button - EXACT --}}
                            <a class="btn btn-primary btn-icon btn-full" 
                               href="{{ route('login') }}"
                               data-element="personal-area-login">
                                <span class="rounded-icon" aria-hidden="true">
                                    <svg class="icon icon-primary">
                                        <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-user"></use>
                                    </svg>
                                </span>
                                <span class="d-none d-lg-block">Accedi all'area personale</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- MAIN HEADER - EXACT structure (it-nav-wrapper > it-header-center-wrapper) --}}
    <div class="it-nav-wrapper">
        <div class="it-header-center-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="it-header-center-content-wrapper">
                            
                            {{-- Brand Wrapper - EXACT --}}
                            <div class="it-brand-wrapper">
                                <a href="{{ route('home') }}">
                                    {{-- SVG Logo 82x82 - EXACT --}}
                                    <svg width="82" height="82" class="icon" aria-hidden="true">
                                        <image href="{{ $logoUrl ?? asset('themes/sixteen/images/logo-comune.svg') }}"/>
                                    </svg>
                                    <div class="it-brand-text">
                                        <div class="it-brand-title">{{ $cityName ?? 'Il mio Comune' }}</div>
                                        <div class="it-brand-tagline d-none d-md-block">{{ $tagline ?? 'Un comune da vivere' }}</div>
                                    </div>
                                </a>
                            </div>
                            
                            {{-- Right Zone with Socials - EXACT --}}
                            <div class="it-right-zone">
                                <div class="it-socials d-none d-lg-flex">
                                    <span>Seguici su</span>
                                    <ul>
                                        @foreach($socialLinks ?? [['platform'=>'twitter','url'=>'#','icon'=>'it-twitter','label'=>'Twitter']] as $social)
                                        <li>
                                            <a href="{{ $social['url'] }}" target="_blank">
                                                <svg class="icon icon-sm icon-white align-top">
                                                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#{{ $social['icon'] }}"></use>
                                                </svg>
                                                <span class="visually-hidden">{{ $social['label'] }}</span>
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- NAVIGATION - EXACT structure --}}
    <div class="it-nav-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="navbar navbar-expand-lg">
                        <button class="custom-navbar-toggler" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#mainNav"
                                aria-controls="mainNav"
                                aria-expanded="false"
                                aria-label="Toggle navigation">
                            <svg class="icon">
                                <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-burger"></use>
                            </svg>
                        </button>
                        <div class="collapse navbar-collapse" id="mainNav">
                            <div class="navbar-nav">
                                <div class="nav-item">
                                    <a class="nav-link" href="/it/tests/amministrazione">Amministrazione</a>
                                </div>
                                <div class="nav-item">
                                    <a class="nav-link" href="/it/tests/novita">Novità</a>
                                </div>
                                <div class="nav-item">
                                    <a class="nav-link" href="/it/tests/servizi">Servizi</a>
                                </div>
                                <div class="nav-item">
                                    <a class="nav-link" href="/it/tests/vivere">Vivere il Comune11</a>
                                </div>
                                <div class="nav-item highlighted">
                                    <a class="nav-link" href="/it/tests/argomenti">Tutti gli argomenti</a>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
