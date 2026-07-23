<header class="it-header-wrapper" id="header" data-bs-toggle="sticky" data-bs-sticky-limit="0" data-bs-sticky-offset="0">
    <!-- Slim Header -->
    <div class="it-header-slim-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="it-header-slim-wrapper-content">
                        <a class="d-none d-lg-block navbar-brand" href="#">Regione {{ config('comune.regione', 'Sicilia') }}</a>
                        <div class="it-header-slim-wrapper-device">
                            <span class="navbar-brand">Regione {{ config('comune.regione', 'Sicilia') }}</span>
                        </div>
                        <div class="it-header-slim-right-zone">
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span>ITA</span>
                                    <svg class="icon d-none d-lg-block">
                                        <use href="{{ asset('themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg') }}#it-expand"></use>
                                    </svg>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="link-list-wrapper">
                                        <ul class="link-list">
                                            <li><a class="dropdown-item list-item" href="#"><span>ITA</span></a></li>
                                            <li><a class="dropdown-item list-item" href="#"><span>ENG</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="it-access-wrapper">
    @guest
        <a class="btn btn-primary btn-sm" href="{{ route('login') }}">Accedi all'area personale</a>
    @else
        <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                @if (Auth::user()->avatar && Auth::user()->avatar->isNotEmpty())
                    <img src="{{ Auth::user()->avatar->first()->getUrl() }}" alt="Avatar" class="avatar-small me-1">
                @else
                    <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(Auth::user()->email)) }}?s=32&d=identicon" alt="Avatar" class="avatar-small me-1">
                @endif
                <span class="d-none d-lg-inline">{{ Auth::user()->first_name ?? explode(' ', Auth::user()->name)['0'] }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <ul class="list-unstyled">
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="it-user me-1"></i> {{ __('user::profile.edit') }}
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('comune.tickets.my') ?? '/'.app()->getLocale().'/tickets/my' }}">
                            <i class="it-list me-1"></i> {{ __('fixcity::tickets.my_tickets') }}
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button class="dropdown-item" type="submit">
                                <i class="it-logout-circle-outline me-1"></i> {{ __('user::auth.logout') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    @endguest
</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Center Header -->
    <div class="it-header-center-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="it-header-center-content-wrapper">
                        <div class="it-brand-wrapper">
                            <a href="{{ route('comune.homepage') }}">
                                <img src="{{ theme_asset('images/logo-comune.png') }}" alt="Logo Comune" class="icon">
                                <div class="it-brand-text">
                                    <div class="it-brand-title">Comune di {{ config('comune.nome', 'Nome Comune') }}</div>
                                    <div class="it-brand-tagline d-none d-md-block">{{ config('comune.tagline', 'Sito istituzionale') }}</div>
                                </div>
                            </a>
                        </div>
                        <div class="it-right-zone">
                            <div class="it-socials d-none d-md-flex">
                                <span>Seguici su</span>
                                <ul>
                                    <li>
                                        <a href="#" aria-label="Facebook" target="_blank">
                                            <svg class="icon">
                                                <use href="{{ asset('themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg') }}#it-facebook"></use>
                                            </svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" aria-label="Twitter" target="_blank">
                                            <svg class="icon">
                                                <use href="{{ asset('themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg') }}#it-twitter"></use>
                                            </svg>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="it-search-wrapper">
                                <span class="d-none d-md-block">Cerca</span>
                                <a class="search-link" href="#" aria-label="Cerca nel sito">
                                    <svg class="icon">
                                        <use href="{{ asset('themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg') }}#it-search"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar Header -->
    <div class="it-header-navbar-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="navbar navbar-expand-lg has-megamenu">
                        <button class="custom-navbar-toggler" type="button" aria-controls="nav10" aria-expanded="false" aria-label="Mostra/Nascondi la navigazione" data-bs-target="#nav10" data-bs-toggle="navbarcollapsible">
                            <svg class="icon">
                                <use href="{{ asset('themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg') }}#it-burger"></use>
                            </svg>
                        </button>
                        <div class="navbar-collapsible" id="nav10">
                            <div class="overlay"></div>
                            <div class="close-div">
                                <button class="btn close-menu" type="button">
                                    <span class="visually-hidden">Chiudi navigazione</span>
                                    <svg class="icon">
                                        <use href="{{ asset('themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg') }}#it-close-big"></use>
                                    </svg>
                                </button>
                            </div>
                            <div class="menu-wrapper">
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('comune.homepage') ? 'active' : '' }}" href="{{ route('comune.homepage') }}"><span>Home</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('comune.servizi*') ? 'active' : '' }}" href="{{ route('comune.servizi') }}"><span>Servizi</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('comune.novita*') ? 'active' : '' }}" href="{{ route('comune.novita') }}"><span>Novità</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('comune.contatti') ? 'active' : '' }}" href="{{ route('comune.contatti') }}"><span>Contatti</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('fixcity.*') ? 'active' : '' }}" href="{{ route('fixcity.tickets.index') }}"><span>Segnalazioni</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>



