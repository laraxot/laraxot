{{--
/**
 * Navbar Component
 * 
 * Bootstrap Italia compliant navbar component with support for:
 * - Responsive navigation
 * - Brand logo and text
 * - Navigation links
 * - Dropdown menus
 * - Search functionality
 * - User menu
 * - Mobile hamburger menu
 * - Accessibility features
 * 
 * @param string $variant - Navbar variant (default, dark, light)
 * @param bool $fixed - Fixed positioning (top, bottom)
 * @param bool $sticky - Sticky positioning
 * @param string $brand - Brand text
 * @param string $brandHref - Brand link URL
 * @param array $navItems - Navigation items
 * @param bool $searchable - Enable search functionality
 * @param string $class - Additional CSS classes
 * 
 * @example
 * <x-navbar 
 *     brand="Mio Sito"
 *     brand-href="/"
 *     :nav-items="[
 *         ['text' => 'Home', 'href' => '/'],
 *         ['text' => 'Servizi', 'href' => '/servizi'],
 *         ['text' => 'Contatti', 'href' => '/contatti']
 *     ]"
 *     searchable
 * />
 */
--}}

@props([
    'variant' => 'default',
    'fixed' => false,
    'sticky' => false,
    'brand' => '',
    'brandHref' => '/',
    'navItems' => [],
    'searchable' => false,
    'class' => ''
])

@php
    $baseClasses = 'navbar navbar-expand-lg';
    
    // Variant classes
    $variantClasses = match($variant) {
        'dark' => 'navbar-dark bg-dark',
        'light' => 'navbar-light bg-light',
        default => 'navbar-light bg-white'
    };
    
    // Positioning classes
    $positionClasses = '';
    if ($fixed === 'top') {
        $positionClasses = 'fixed-top';
    } elseif ($fixed === 'bottom') {
        $positionClasses = 'fixed-bottom';
    } elseif ($sticky) {
        $positionClasses = 'sticky-top';
    }
    
    $classes = trim($baseClasses . ' ' . $variantClasses . ' ' . $positionClasses . ' ' . $class);
@endphp

<nav {{ $attributes->merge(['class' => $classes]) }} 
     x-data="navbar()" 
     role="navigation" 
     aria-label="Navigazione principale">
    
    <div class="container-fluid">
        {{-- Brand --}}
        <a class="navbar-brand" href="{{ $brandHref }}" aria-label="{{ $brand }}">
            @if($slot->isNotEmpty())
                {{ $slot }}
            @else
                {{ $brand }}
            @endif
        </a>
        
        {{-- Mobile toggle button --}}
        <button 
            class="navbar-toggler" 
            type="button" 
            x-bind:aria-expanded="isOpen"
            aria-controls="navbarNav"
            x-on:click="toggle()"
            aria-label="Apri menu di navigazione"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        
        {{-- Navigation content --}}
        <div 
            class="collapse navbar-collapse" 
            id="navbarNav"
            x-show="isOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
        >
            {{-- Main navigation --}}
            <ul class="navbar-nav me-auto">
                @foreach($navItems as $item)
                    @if(!empty($item['dropdown']))
                        {{-- Dropdown item --}}
                        <li class="nav-item dropdown">
                            <a 
                                class="nav-link dropdown-toggle" 
                                href="#" 
                                role="button" 
                                data-bs-toggle="dropdown" 
                                aria-expanded="false"
                                aria-haspopup="true"
                            >
                                {{ $item['text'] }}
                            </a>
                            <ul class="dropdown-menu">
                                @foreach($item['dropdown'] as $dropdownItem)
                                    <li>
                                        <a class="dropdown-item" href="{{ $dropdownItem['href'] ?? '#' }}">
                                            @if(!empty($dropdownItem['icon']))
                                                <x-filament::icon icon="heroicon-o-{{" $dropdownItem['icon'] }} class="me-2" />
                                            @endif
                                            {{ $dropdownItem['text'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        {{-- Regular nav item --}}
                        <li class="nav-item">
                            <a 
                                class="nav-link {{ !empty($item['active']) && $item['active'] ? 'active' : '' }}" 
                                href="{{ $item['href'] ?? '#' }}"
                                @if(!empty($item['target']))
                                    target="{{ $item['target'] }}"
                                @endif
                                @if(!empty($item['rel']))
                                    rel="{{ $item['rel'] }}"
                                @endif
                            >
                                @if(!empty($item['icon']))
                                    <x-filament::icon icon="heroicon-o-{{" $item['icon'] }} class="me-1" />
                                @endif
                                {{ $item['text'] }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
            
            {{-- Right side content --}}
            <div class="navbar-nav ms-auto">
                {{-- Search --}}
                @if($searchable)
                    <div class="nav-item">
                        <form class="d-flex" role="search">
                            <div class="input-group">
                                <input 
                                    class="form-control" 
                                    type="search" 
                                    placeholder="Cerca..." 
                                    aria-label="Cerca"
                                    x-model="searchQuery"
                                    x-on:keyup.enter="performSearch()"
                                >
                                <button 
                                    class="btn btn-outline-secondary" 
                                    type="button"
                                    x-on:click="performSearch()"
                                    aria-label="Esegui ricerca"
                                >
                                    <x-filament::icon icon="heroicon-o-magnifying-glass" class="w-4 h-4" />
f7ac8eda (.)
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
                
                {{-- User menu --}}
                @if(!empty($userMenu))
                    <div class="nav-item dropdown">
                        <a 
                            class="nav-link dropdown-toggle d-flex align-items-center" 
                            href="#" 
                            role="button" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false"
                            aria-haspopup="true"
                        >
                            @if(!empty($userMenu['avatar']))
                                <img 
                                    src="{{ $userMenu['avatar'] }}" 
                                    alt="{{ $userMenu['name'] ?? 'Utente' }}"
                                    class="rounded-circle me-2"
                                    width="32"
                                    height="32"
                                >
                            @endif
                            <span class="d-none d-md-inline">{{ $userMenu['name'] ?? 'Utente' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @foreach($userMenu['items'] ?? [] as $userItem)
                                <li>
                                    <a class="dropdown-item" href="{{ $userItem['href'] ?? '#' }}">
                                        @if(!empty($userItem['icon']))
                                            <x-filament::icon icon="heroicon-o-{{" $userItem['icon'] }} class="me-2" />
                                        @endif
                                        {{ $userItem['text'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</nav>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('navbar', () => ({
        isOpen: false,
        searchQuery: '',
        
        init() {
            // Close navbar when clicking outside
            document.addEventListener('click', (e) => {
                if (!this.$el.contains(e.target)) {
                    this.isOpen = false;
                }
            });
            
            // Close navbar on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.isOpen = false;
                }
            });
            
            // Handle window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 992) {
                    this.isOpen = false;
                }
            });
        },
        
        toggle() {
            this.isOpen = !this.isOpen;
            
            // Update aria-expanded
            const toggleButton = this.$el.querySelector('.navbar-toggler');
            if (toggleButton) {
                toggleButton.setAttribute('aria-expanded', this.isOpen);
            }
            
            // Emit event
            this.$dispatch('navbar-toggled', { isOpen: this.isOpen });
        },
        
        close() {
            this.isOpen = false;
            
            // Update aria-expanded
            const toggleButton = this.$el.querySelector('.navbar-toggler');
            if (toggleButton) {
                toggleButton.setAttribute('aria-expanded', false);
            }
        },
        
        performSearch() {
            if (this.searchQuery.trim()) {
                this.$dispatch('navbar-search', { 
                    query: this.searchQuery.trim() 
                });
                
                // Reset search query
                this.searchQuery = '';
            }
        }
    }));
});
</script>
@endpush
