# Implementazione Design Comuni nel Tema Sixteen

## Panoramica

Questo documento descrive l'implementazione dei template Design Comuni nel tema Sixteen, garantendo la conformità alle linee guida AGID e l'integrazione con il sistema Fixcity.

## Struttura del Tema

### Layout Principale
```blade
{{-- themes/sixteen/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Comune di ' . config('comune.nome'))</title>
    
    <!-- Bootstrap Italia CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-italia@2.0.0/dist/css/bootstrap-italia.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ theme_asset('css/design-comuni.css') }}" rel="stylesheet">
    <link href="{{ theme_asset('css/comune-custom.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    @include('sixteen::components.header-comune')
    
    <main>
        @yield('content')
    </main>
    
    @include('sixteen::components.footer-comune')
    
    <!-- Bootstrap Italia JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-italia@2.0.0/dist/js/bootstrap-italia.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="{{ theme_asset('js/design-comuni.js') }}"></script>
    <script src="{{ theme_asset('js/comune-functions.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
```

### Header Comunale
```blade
{{-- themes/sixteen/components/header-comune.blade.php --}}
@php
    $nav_items = [
        ['label' => 'Home', 'route' => 'comune.homepage', 'active' => request()->routeIs('comune.homepage')],
        ['label' => 'Servizi', 'route' => 'comune.servizi', 'active' => request()->routeIs('comune.servizi*')],
        ['label' => 'Novità', 'route' => 'comune.novita', 'active' => request()->routeIs('comune.novita*')],
        ['label' => 'Contatti', 'route' => 'comune.contatti', 'active' => request()->routeIs('comune.contatti')],
        ['label' => 'Segnalazioni', 'route' => 'fixcity.tickets.index', 'active' => request()->routeIs('fixcity.*')],
    ];
@endphp
<header class="it-header-wrapper" data-bs-toggle="sticky" data-bs-sticky-limit="0" data-bs-target="#navbarNav">
    <div class="it-header-slim-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="it-header-slim-wrapper-content">
                        <a class="d-none d-lg-block navbar-brand" href="#">{{ config('comune.ente_appartenenza', 'Ente di Appartenenza') }}</a>
                        <div class="it-header-slim-right-side">
                            <div class="it-access-top-wrapper">
                                @guest
                                    <a class="btn btn-primary btn-sm" href="{{ route('login') }}">Accedi</a>
                                @else
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ Auth::user()->name }}
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profilo</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">Esci</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                @endguest
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="it-header-center-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="it-header-center-content-wrapper">
                        <div class="it-brand-wrapper">
                            <a href="{{ route('it.homepage') }}">
                                <img src="{{ theme_asset('images/logo-comune.png') }}" alt="Logo Comune" class="icon" onerror="this.src='https://placehold.co/40x40?text=Comune'">
                                <div class="it-brand-text">
                                    <div class="it-brand-title">{{ config('comune.nome', 'Nome Comune') }}</div>
                                    <div class="it-brand-tagline d-none d-md-block">Comune di {{ config('comune.nome', 'Nome Comune') }}</div>
                                </div>
                            </a>
                        </div>
                        <div class="it-right-zone">
                            <div class="it-socials d-none d-md-flex">
                                <span>Seguici su</span>
                                <ul>
                                    <li><a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a></li>
                                </ul>
                            </div>
                            <div class="it-search-wrapper">
                                <a class="search-link" href="#" aria-label="Cerca nel sito">
                                    <i class="fas fa-search"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="it-header-navbar-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="navbar navbar-expand-lg has-megamenu">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <div class="navbar-wrapper">
                                <ul class="navbar-nav">
                                    @foreach($nav_items as $item)
                                        <li class="nav-item">
                                            <a class="nav-link {{ $item['active'] ? 'active' : '' }}" href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}">
                                                <span>{{ $item['label'] }}</span>
                                                @if($item['active']) <span class="visually-hidden">(corrente)</span> @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
```

### Footer Comunale
```blade
{{-- themes/sixteen/components/footer-comune.blade.php --}}
<footer class="it-footer">
    <div class="it-footer-main">
        <div class="container">
            <section>
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <div class="it-brand-wrapper">
                            <a href="{{ route('it.homepage') }}">
                                <img src="{{ theme_asset('images/logo-comune.png') }}" alt="Logo Comune" class="icon" onerror="this.src='https://placehold.co/40x40?text=Comune'">
                                <div class="it-brand-text">
                                    <h2>{{ config('comune.nome', 'Nome Comune') }}</h2>
                                    <h3>Comune di {{ config('comune.nome', 'Nome Comune') }}</h3>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-6 pb-2">
                        <h4>Amministrazione</h4>
                        <ul class="footer-list">
                            <li><a href="#">Organi di governo</a></li>
                            <li><a href="#">Aree amministrative</a></li>
                            <li><a href="#">Uffici</a></li>
                            <li><a href="#">Enti e fondazioni</a></li>
                            <li><a href="#">Amministrazione trasparente</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 pb-2">
                        <h4>Servizi</h4>
                        <ul class="footer-list">
                            <li><a href="{{ route('comune.servizi') }}">Tutti i servizi</a></li>
                            <li><a href="#">Anagrafe e stato civile</a></li>
                            <li><a href="#">Cultura e tempo libero</a></li>
                            <li><a href="#">Educazione e formazione</a></li>
                            <li><a href="#">Tasse e tributi</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 pb-2">
                        <h4>Novità</h4>
                        <ul class="footer-list">
                            <li><a href="{{ route('comune.novita') }}">Notizie</a></li>
                            <li><a href="#">Comunicati stampa</a></li>
                            <li><a href="#">Avvisi</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <h4>Contatti</h4>
                        <p><strong>Comune di {{ config('comune.nome', 'Nome Comune') }}</strong><br>
                        {{ config('comune.indirizzo', 'Via Roma, 1') }} - {{ config('comune.cap', '00100') }} {{ config('comune.nome', 'Città') }}<br>
                        Codice Fiscale / Partita IVA: {{ config('comune.cf', '00000000000') }}</p>
                        <ul class="footer-list">
                            <li><a href="mailto:{{ config('comune.email', 'info@comune.it') }}">Email: {{ config('comune.email', 'info@comune.it') }}</a></li>
                            <li><a href="mailto:{{ config('comune.pec', 'pec@comune.it') }}">PEC: {{ config('comune.pec', 'pec@comune.it') }}</a></li>
                            <li><a href="tel:{{ config('comune.telefono', '0000000') }}">Centralino: {{ config('comune.telefono', '0000000') }}</a></li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="it-footer-small-prints">
        <div class="container">
            <ul class="list-inline link-footer">
                <li class="list-inline-item"><a href="#" title="Note legali">Note legali</a></li>
                <li class="list-inline-item"><a href="#" title="Privacy policy">Privacy policy</a></li>
                <li class="list-inline-item"><a href="#" title="Mappa del sito">Mappa del sito</a></li>
            </ul>
        </div>
    </div>
</footer>
```


### Homepage Comunale
```blade
{{-- themes/sixteen/pages/comune/homepage.blade.php --}}
@extends('sixteen::layouts.app')

@section('title', 'Homepage - Comune di ' . config('comune.nome'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="it-page-section">
                <h1>Benvenuti nel Comune di {{ config('comune.nome') }}</h1>
                <p class="lead">Il portale ufficiale per i servizi e le informazioni del comune</p>
            </div>
        </div>
    </div>
    
    <!-- Servizi Principali -->
    <div class="row">
        <div class="col-md-4">
            <div class="card-wrapper">
                <div class="card">
                    <div class="card-body">
                        <div class="it-header-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h5 class="card-title">Segnalazioni</h5>
                        <p class="card-text">Segnala problemi e disservizi nel territorio comunale</p>
                        <a href="{{ route('fixcity.tickets.create') }}" class="btn btn-primary">Segnala</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card-wrapper">
                <div class="card">
                    <div class="card-body">
                        <div class="it-header-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h5 class="card-title">Servizi</h5>
                        <p class="card-text">Accedi ai servizi online del comune</p>
                        <a href="{{ route('comune.servizi') }}" class="btn btn-primary">Vai ai Servizi</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card-wrapper">
                <div class="card">
                    <div class="card-body">
                        <div class="it-header-icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <h5 class="card-title">Novità</h5>
                        <p class="card-text">Scopri le ultime notizie e comunicati</p>
                        <a href="{{ route('comune.novita') }}" class="btn btn-primary">Leggi le Novità</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ultime Segnalazioni -->
    @if(isset($recentTickets) && $recentTickets->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <div class="it-page-section">
                <h2>Ultime Segnalazioni</h2>
                <div class="row">
                    @foreach($recentTickets as $ticket)
                    <div class="col-md-6 col-lg-4">
                        <div class="card-wrapper">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $ticket->name }}</h6>
                                    <p class="card-text">{{ Str::limit($ticket->description, 100) }}</p>
                                    <div class="card-meta">
                                        <span class="badge badge-{{ $ticket->priority }}">{{ $ticket->priority_label }}</span>
                                        <span class="badge badge-{{ $ticket->status }}">{{ $ticket->status_label }}</span>
                                    </div>
                                    <a href="{{ route('fixcity.tickets.show', $ticket) }}" class="btn btn-outline-primary btn-sm">Dettagli</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Ultime Novità -->
    @if(isset($recentNews) && $recentNews->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <div class="it-page-section">
                <h2>Ultime Novità</h2>
                <div class="row">
                    @foreach($recentNews as $news)
                    <div class="col-md-6 col-lg-4">
                        <div class="card-wrapper">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $news->title }}</h6>
                                    <p class="card-text">{{ Str::limit($news->excerpt, 100) }}</p>
                                    <div class="card-meta">
                                        <small class="text-muted">{{ $news->created_at->format('d/m/Y') }}</small>
                                    </div>
                                    <a href="{{ route('comune.novita.show', $news) }}" class="btn btn-outline-primary btn-sm">Leggi</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
```

### Pagina Servizi
```blade
{{-- themes/sixteen/pages/comune/servizi.blade.php --}}
@extends('sixteen::layouts.app')

@section('title', 'Servizi - Comune di ' . config('comune.nome'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="it-page-section">
                <h1>Servizi del Comune</h1>
                <p class="lead">Accedi ai servizi online e alle informazioni del comune</p>
            </div>
        </div>
    </div>
    
    <div class="row">
        @foreach($services as $service)
        <div class="col-md-6 col-lg-4">
            <div class="card-wrapper">
                <div class="card">
                    <div class="card-body">
                        <div class="it-header-icon">
                            <i class="fas fa-{{ $service['icona'] }}"></i>
                        </div>
                        <h5 class="card-title">{{ $service['nome'] }}</h5>
                        <p class="card-text">{{ $service['descrizione'] }}</p>
                        <a href="{{ $service['url'] }}" class="btn btn-primary">Accedi</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Categorie di Servizi -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="it-page-section">
                <h2>Categorie di Servizi</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Anagrafe</h5>
                                    <small>3 giorni fa</small>
                                </div>
                                <p class="mb-1">Servizi anagrafici e stato civile</p>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Tributi</h5>
                                    <small>1 settimana fa</small>
                                </div>
                                <p class="mb-1">Pagamento tasse e tributi comunali</p>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Urbanistica</h5>
                                    <small>2 settimane fa</small>
                                </div>
                                <p class="mb-1">Pratiche edilizie e urbanistiche</p>
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Sociale</h5>
                                    <small>3 giorni fa</small>
                                </div>
                                <p class="mb-1">Servizi sociali e assistenziali</p>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Cultura</h5>
                                    <small>1 settimana fa</small>
                                </div>
                                <p class="mb-1">Eventi culturali e biblioteca</p>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Ambiente</h5>
                                    <small>2 settimane fa</small>
                                </div>
                                <p class="mb-1">Servizi ambientali e rifiuti</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

### Pagina Novità
```blade
{{-- themes/sixteen/pages/comune/novita.blade.php --}}
@extends('sixteen::layouts.app')

@section('title', 'Novità - Comune di ' . config('comune.nome'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="it-page-section">
                <h1>Novità e Comunicati</h1>
                <p class="lead">Rimani aggiornato sulle ultime notizie del comune</p>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            @foreach($news as $article)
            <article class="it-page-section">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">
                            <a href="{{ route('comune.novita.show', $article) }}" class="text-decoration-none">
                                {{ $article->title }}
                            </a>
                        </h2>
                        <div class="card-meta">
                            <span class="badge badge-primary">{{ $article->category }}</span>
                            <small class="text-muted">{{ $article->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <p class="card-text">{{ $article->excerpt }}</p>
                        <a href="{{ route('comune.novita.show', $article) }}" class="btn btn-outline-primary">Leggi tutto</a>
                    </div>
                </div>
            </article>
            @endforeach
            
            <!-- Paginazione -->
            <div class="d-flex justify-content-center">
                {{ $news->links() }}
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="it-page-section">
                <h3>Filtra per Categoria</h3>
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action active">Tutte</a>
                    <a href="#" class="list-group-item list-group-item-action">Comunicati</a>
                    <a href="#" class="list-group-item list-group-item-action">Avvisi</a>
                    <a href="#" class="list-group-item list-group-item-action">Eventi</a>
                    <a href="#" class="list-group-item list-group-item-action">Bandi</a>
                </div>
            </div>
            
            <div class="it-page-section">
                <h3>Archivio</h3>
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action">Gennaio 2024</a>
                    <a href="#" class="list-group-item list-group-item-action">Dicembre 2023</a>
                    <a href="#" class="list-group-item list-group-item-action">Novembre 2023</a>
                    <a href="#" class="list-group-item list-group-item-action">Ottobre 2023</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

### Pagina Contatti
```blade
{{-- themes/sixteen/pages/comune/contatti.blade.php --}}
@extends('sixteen::layouts.app')

@section('title', 'Contatti - Comune di ' . config('comune.nome'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="it-page-section">
                <h1>Contatti</h1>
                <p class="lead">Come contattare il Comune di {{ config('comune.nome') }}</p>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-6">
            <div class="it-page-section">
                <h2>Informazioni di Contatto</h2>
                <div class="contact-info">
                    <div class="contact-item">
                        <h4>Indirizzo</h4>
                        <p>{{ config('comune.indirizzo') }}<br>
                        {{ config('comune.cap') }} {{ config('comune.nome') }} ({{ config('comune.provincia') }})</p>
                    </div>
                    
                    <div class="contact-item">
                        <h4>Telefono</h4>
                        <p>{{ config('comune.telefono') }}</p>
                    </div>
                    
                    <div class="contact-item">
                        <h4>Email</h4>
                        <p><a href="mailto:{{ config('comune.email') }}">{{ config('comune.email') }}</a></p>
                    </div>
                    
                    <div class="contact-item">
                        <h4>PEC</h4>
                        <p><a href="mailto:{{ config('comune.pec') }}">{{ config('comune.pec') }}</a></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="it-page-section">
                <h2>Orari di Apertura</h2>
                <div class="opening-hours">
                    <div class="opening-item">
                        <strong>Lunedì - Venerdì:</strong> 8:30 - 12:30
                    </div>
                    <div class="opening-item">
                        <strong>Martedì e Giovedì:</strong> 14:30 - 16:30
                    </div>
                    <div class="opening-item">
                        <strong>Sabato:</strong> 8:30 - 12:30
                    </div>
                    <div class="opening-item">
                        <strong>Domenica:</strong> Chiuso
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-12">
            <div class="it-page-section">
                <h2>Mappa</h2>
                <div id="map" style="height: 400px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Inizializza mappa
    var map = L.map('map').setView([{{ config('comune.lat') }}, {{ config('comune.lng') }}], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    L.marker([{{ config('comune.lat') }}, {{ config('comune.lng') }}]).addTo(map)
        .bindPopup('Comune di {{ config('comune.nome') }}')
        .openPopup();
</script>
@endpush
@endsection
```

### CSS Personalizzato
```css
/* themes/sixteen/assets/css/comune-custom.css */
:root {
    --comune-primary: #0066cc;
    --comune-secondary: #00cc66;
    --comune-accent: #ff6600;
    --comune-dark: #333333;
    --comune-light: #f8f9fa;
}

/* Header */
.it-header-wrapper {
    background: linear-gradient(135deg, var(--comune-primary), var(--comune-secondary));
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.it-brand-text h2 {
    color: white;
    font-weight: 700;
    margin-bottom: 0;
}

.it-brand-text h3 {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    margin-bottom: 0;
}

/* Navigation */
.navbar-nav .nav-link {
    color: white;
    font-weight: 500;
    padding: 0.5rem 1rem;
    transition: color 0.3s ease;
}

.navbar-nav .nav-link:hover,
.navbar-nav .nav-link.active {
    color: var(--comune-accent);
}

/* Cards */
.card-wrapper {
    margin-bottom: 2rem;
}

.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.card-body {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* Buttons */
.btn-primary {
    background-color: var(--comune-primary);
    border-color: var(--comune-primary);
    font-weight: 500;
}

.btn-primary:hover {
    background-color: var(--comune-secondary);
    border-color: var(--comune-secondary);
}

.btn-outline-primary {
    color: var(--comune-primary);
    border-color: var(--comune-primary);
}

.btn-outline-primary:hover {
    background-color: var(--comune-primary);
    border-color: var(--comune-primary);
}

/* Icons */
.it-header-icon {
    font-size: 2rem;
    color: var(--comune-primary);
    margin-bottom: 1rem;
}

/* Badges */
.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.badge-low { background-color: #10b981; }
.badge-medium { background-color: #f59e0b; }
.badge-high { background-color: #ef4444; }
.badge-urgent { background-color: #dc2626; }
.badge-open { background-color: #3b82f6; }
.badge-in_progress { background-color: #8b5cf6; }
.badge-resolved { background-color: #10b981; }

/* Page Sections */
.it-page-section {
    padding: 3rem 0;
}

.it-page-section h1 {
    color: var(--comune-primary);
    margin-bottom: 1rem;
    font-weight: 700;
}

.it-page-section h2 {
    color: var(--comune-primary);
    margin-bottom: 2rem;
    font-weight: 600;
}

.lead {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

/* Contact Info */
.contact-info .contact-item {
    margin-bottom: 2rem;
}

.contact-info .contact-item h4 {
    color: var(--comune-primary);
    margin-bottom: 0.5rem;
}

.contact-info .contact-item p {
    margin-bottom: 0;
}

/* Opening Hours */
.opening-hours .opening-item {
    margin-bottom: 0.5rem;
    padding: 0.5rem 0;
    border-bottom: 1px solid #eee;
}

.opening-hours .opening-item:last-child {
    border-bottom: none;
}

/* Footer */
.it-footer {
    background-color: var(--comune-dark);
    color: white;
    margin-top: 4rem;
}

.it-footer h4 {
    color: var(--comune-accent);
    margin-bottom: 1rem;
}

.it-footer a {
    color: white;
    text-decoration: none;
}

.it-footer a:hover {
    color: var(--comune-accent);
}

.it-footer-secondary {
    background-color: #222;
    padding: 1rem 0;
}

.it-footer-small-prints {
    text-align: center;
    font-size: 0.9rem;
    color: #ccc;
}

/* Responsive */
@media (max-width: 768px) {
    .it-page-section {
        padding: 2rem 0;
    }
    
    .card-wrapper {
        margin-bottom: 1rem;
    }
    
    .it-brand-text h2 {
        font-size: 1.5rem;
    }
    
    .it-brand-text h3 {
        font-size: 0.8rem;
    }
}

/* Accessibility */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* Focus styles */
.btn:focus,
.nav-link:focus,
.form-control:focus {
    outline: 2px solid var(--comune-accent);
    outline-offset: 2px;
}

/* Print styles */
@media print {
    .it-header-wrapper,
    .it-footer,
    .btn {
        display: none !important;
    }
    
    .container {
        max-width: none !important;
    }
}
```

### JavaScript Personalizzato
```javascript
// themes/sixteen/assets/js/comune-functions.js
document.addEventListener('DOMContentLoaded', function() {
    // Inizializza tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Inizializza popover
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Smooth scroll per i link interni
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Form validation personalizzata
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
    
    // Lazy loading per le immagini
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // Gestione del menu mobile
    const navbarToggler = document.querySelector('.custom-navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
        navbarToggler.addEventListener('click', function() {
            navbarCollapse.classList.toggle('show');
        });
        
        // Chiudi il menu quando si clicca su un link
        document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                navbarCollapse.classList.remove('show');
            });
        });
    }
    
    // Gestione dei filtri per le novità
    const filterButtons = document.querySelectorAll('.list-group-item[data-filter]');
    const newsItems = document.querySelectorAll('.news-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Rimuovi classe active da tutti i pulsanti
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Aggiungi classe active al pulsante cliccato
            this.classList.add('active');
            
            // Filtra le notizie
            const filter = this.dataset.filter;
            
            newsItems.forEach(item => {
                if (filter === 'all' || item.dataset.category === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    
    // Gestione del form di contatto
    const contactForm = document.querySelector('#contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Mostra messaggio di successo
            const successMessage = document.createElement('div');
            successMessage.className = 'alert alert-success';
            successMessage.textContent = 'Messaggio inviato con successo!';
            
            contactForm.parentNode.insertBefore(successMessage, contactForm);
            contactForm.reset();
            
            // Rimuovi il messaggio dopo 5 secondi
            setTimeout(() => {
                successMessage.remove();
            }, 5000);
        });
    }
});
```

## Configurazione del Tema

### Configurazione Comune
```php
// config/comune.php
<?php

return [
    'nome' => env('COMUNE_NOME', 'Nome Comune'),
    'codice_istat' => env('COMUNE_CODICE_ISTAT', '000000'),
    'cap' => env('COMUNE_CAP', '00000'),
    'provincia' => env('COMUNE_PROVINCIA', 'Provincia'),
    'regione' => env('COMUNE_REGIONE', 'Regione'),
    'sindaco' => env('COMUNE_SINDACO', 'Nome Sindaco'),
    'indirizzo' => env('COMUNE_INDIRIZZO', 'Via, 1'),
    'telefono' => env('COMUNE_TELEFONO', '000-0000000'),
    'email' => env('COMUNE_EMAIL', 'info@comune.it'),
    'pec' => env('COMUNE_PEC', 'comune@pec.it'),
    'piva' => env('COMUNE_PIVA', '00000000000'),
    'cf' => env('COMUNE_CF', '00000000000'),
    'lat' => env('COMUNE_LAT', '45.4642'),
    'lng' => env('COMUNE_LNG', '9.1900'),
    'logo' => env('COMUNE_LOGO', '/images/logo-comune.png'),
    'colori' => [
        'primario' => env('COMUNE_COLORE_PRIMARIO', '#0066cc'),
        'secondario' => env('COMUNE_COLORE_SECONDARIO', '#00cc66'),
        'accento' => env('COMUNE_COLORE_ACCENTO', '#ff6600'),
    ],
];
```

### Routes del Tema
```php
// themes/sixteen/routes/web.php
<?php

use Illuminate\Support\Facades\Route;
use Themes\Sixteen\Http\Controllers\ComuneController;

Route::prefix('comune')->name('comune.')->group(function () {
    Route::get('/', [ComuneController::class, 'homepage'])->name('homepage');
    Route::get('/servizi', [ComuneController::class, 'servizi'])->name('servizi');
    Route::get('/novita', [ComuneController::class, 'novita'])->name('novita');
    Route::get('/novita/{news}', [ComuneController::class, 'showNews'])->name('novita.show');
    Route::get('/contatti', [ComuneController::class, 'contatti'])->name('contatti');
    Route::get('/documenti', [ComuneController::class, 'documenti'])->name('documenti');
    Route::get('/eventi', [ComuneController::class, 'eventi'])->name('eventi');
    Route::post('/contatti', [ComuneController::class, 'sendContact'])->name('contatti.send');
});
```

## Benefici dell'Implementazione

### 1. Conformità AGID
- Design system ufficiale per la PA italiana
- Accessibilità WCAG 2.1 AA
- Responsive design per tutti i dispositivi
- Coerenza visiva con altri siti della PA

### 2. Miglioramento UX
- Navigazione intuitiva e familiare
- Interfaccia ottimizzata per cittadini
- Accesso rapido ai servizi principali
- Design professionale e affidabile

### 3. Integrazione Sistema
- Collegamento diretto con Fixcity
- API per dati dinamici
- Gestione centralizzata dei contenuti
- Sistema di autenticazione unificato

### 4. Manutenibilità
- Template standardizzati e documentati
- Codice pulito e ben strutturato
- Facile personalizzazione e aggiornamento
- Compatibilità con future versioni

## Prossimi Passi

1. **Implementazione Graduale**: Iniziare con i componenti principali
2. **Personalizzazione**: Adattare i template alle esigenze specifiche
3. **Testing**: Verificare funzionalità e accessibilità
4. **Deployment**: Pubblicare le nuove pagine
5. **Monitoraggio**: Raccogliere feedback e ottimizzare


