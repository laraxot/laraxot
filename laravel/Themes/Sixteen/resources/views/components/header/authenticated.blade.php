{{--
  Header Authenticated Component - Logged-In Users
  
  Header with user dropdown for authenticated users.
  Shows user name and personal area menu.
  
  Based on: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-area-personale.html
  Part of Story 5.0: Header Logged-In State
  
  @props array $user - Current authenticated user
  @see .planning/stories/5.0-header-logged-in-state.story.md
--}}
@props(['user'])

<header class="it-header-wrapper">
    <div class="it-nav-wrapper">
        {{-- Utility Bar with User Info --}}
        <div class="it-header-slim-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="it-header-slim-content-wrapper">
                            {{-- Left: Welcome Message --}}
                            <div class="it-brand-slim-wrapper">
                                <span class="d-none d-md-inline">
                                    {{ __('sixteen::header.user.welcome', ['name' => $user->name]) }}
                                </span>
                            </div>
                            
                            {{-- Right: User Dropdown --}}
                            <div class="it-access-right-wrapper" x-data="{ open: false }" @click.away="open = false">
                                <button 
                                    @click="open = !open"
                                    class="btn btn-primary btn-sm btn-icon"
                                    aria-haspopup="true"
                                    :aria-expanded="open"
                                    aria-label="{{ __('sixteen::header.user.aria.toggle_menu') }}"
                                >
                                    <span class="d-none d-md-inline mr-2">{{ $user->name }}</span>
                                    <x-ui::icon name="user-circle" class="w-4 h-4" />
                                    <x-ui::icon name="chevron-down" class="w-4 h-4 ml-1" x-show="!open" />
                                    <x-ui::icon name="chevron-up" class="w-4 h-4 ml-1" x-show="open" />
                                </button>
                                
                                {{-- User Dropdown Menu --}}
                                <div 
                                    x-show="open"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 transform scale-95"
                                    x-transition:enter-end="opacity-100 transform scale-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 transform scale-100"
                                    x-transition:leave-end="opacity-0 transform scale-95"
                                    class="dropdown-menu dropdown-menu-right show"
                                    role="menu"
                                    aria-orientation="vertical"
                                    aria-labelledby="user-menu-button"
                                >
                                    {{-- Mobile Welcome (visible only on small screens) --}}
                                    <div class="d-md-none px-4 py-2 border-bottom">
                                        <span class="text-muted">{{ __('sixteen::header.user.welcome', ['name' => $user->name]) }}</span>
                                    </div>
                                    
                                    {{-- Menu Items — named route Folio (folio:list) + pub_theme::header.user.dropdown.* --}}
                                    <a href="{{ route('services.categories') }}" class="dropdown-item" role="menuitem">
                                        <x-ui::icon name="briefcase" class="w-4 h-4 mr-2" />
                                        {{ __('pub_theme::header.user.dropdown.my_services.label') }}
                                    </a>
                                    
                                    <a href="{{ route('dashboard') }}" class="dropdown-item" role="menuitem">
                                        <x-ui::icon name="document-text" class="w-4 h-4 mr-2" />
                                        {{ __('pub_theme::header.user.dropdown.my_practices.label') }}
                                    </a>
                                    
                                    <a href="{{ route('notifications') }}" class="dropdown-item" role="menuitem">
                                        <span class="d-flex align-items-center">
                                            <x-ui::icon name="bell" class="w-4 h-4 mr-2" />
                                            {{ __('pub_theme::header.user.dropdown.notifications.label') }}
                                            @if($user->unreadNotificationsCount > 0)
                                                <span class="badge badge-primary ml-auto">{{ $user->unreadNotificationsCount }}</span>
                                            @endif
                                        </span>
                                    </a>
                                    
                                    <div class="dropdown-divider"></div>
                                    
                                    <a href="{{ route('profile.edit') }}" class="dropdown-item" role="menuitem">
                                        <x-ui::icon name="cog-6-tooth" class="w-4 h-4 mr-2" />
                                        {{ __('pub_theme::header.user.dropdown.settings.label') }}
                                    </a>
                                    
                                    <div class="dropdown-divider"></div>
                                    
                                    <a href="{{ route('logout') }}" class="dropdown-item text-danger" role="menuitem">
                                        <x-ui::icon name="arrow-right-on-rectangle" class="w-4 h-4 mr-2" />
                                        {{ __('pub_theme::header.user.dropdown.logout.label') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Main Header --}}
        <div class="it-header-center-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="it-header-center-content-wrapper">
                            <div class="it-brand-wrapper">
                                <a href="{{ route('comune.homepage') }}">
                                    <img src="{{ theme_asset('images/logo-comune.png') }}" alt="Logo Comune" class="icon">
                                    <div class="it-brand-text">
                                        <h2>{{ config('comune.nome', 'Nome Comune') }}</h2>
                                        <h3>Comune di {{ config('comune.nome', 'Nome Comune') }}</h3>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Navigation --}}
        <div class="it-header-navbar-wrapper" x-data="mobileMenu()" @keydown.escape="close()">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav class="navbar navbar-expand-lg">
                            <button 
                                class="custom-navbar-toggler md:hidden" 
                                type="button"
                                @click="toggle()"
                                :aria-expanded="isOpen"
                                aria-controls="navbarNav"
                                aria-label="Toggle navigation"
                            >
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            
                            <div 
                                class="collapse navbar-collapse transition-all duration-300"
                                id="navbarNav"
                                x-show="isOpen || !isMobile()"
                                @click.outside="close()"
                            >
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a 
                                            class="nav-link {{ request()->routeIs('comune.homepage') ? 'active' : '' }}" 
                                            href="{{ route('comune.homepage') }}"
                                            @click="closeOnItemClick()"
                                        >Home</a>
                                    </li>
                                    <li class="nav-item">
                                        <a 
                                            class="nav-link {{ request()->routeIs('comune.servizi*') ? 'active' : '' }}" 
                                            href="{{ route('comune.servizi') }}"
                                            @click="closeOnItemClick()"
                                        >Servizi</a>
                                    </li>
                                    <li class="nav-item">
                                        <a 
                                            class="nav-link {{ request()->routeIs('comune.novita*') ? 'active' : '' }}" 
                                            href="{{ route('comune.novita') }}"
                                            @click="closeOnItemClick()"
                                        >Novità</a>
                                    </li>
                                    <li class="nav-item">
                                        <a 
                                            class="nav-link {{ request()->routeIs('comune.contatti') ? 'active' : '' }}" 
                                            href="{{ route('comune.contatti') }}"
                                            @click="closeOnItemClick()"
                                        >Contatti</a>
                                    </li>
                                    <li class="nav-item">
                                        <a 
                                            class="nav-link {{ request()->routeIs('fixcity.*') ? 'active' : '' }}" 
                                            href="{{ route('fixcity.tickets.index') }}"
                                            @click="closeOnItemClick()"
                                        >Segnalazioni</a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
