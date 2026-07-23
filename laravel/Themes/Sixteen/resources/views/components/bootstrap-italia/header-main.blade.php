{{-- Bootstrap Italia Header Main Component --}}
@props([
    'brandName' => config('app.name', 'Comune di '),
    'brandLogo' => null,
    'brandLink' => '/',
    'tagline' => 'Portale istituzionale',
    'searchEnabled' => true,
    'searchPlaceholder' => 'Cerca nel sito...',
    'menuItems' => [],
    'ctaButton' => null
])

<div class="it-header-wrapper bg-primary-600 text-white">
    <div class="container-italia">
        <div class="flex items-center justify-between py-4">
            {{-- Brand Section --}}
            <div class="flex items-center">
                @if($brandLogo)
                <div class="mr-4">
                    <img src="{{ $brandLogo }}" alt="{{ $brandName }}" class="h-12 w-auto">
                </div>
                @endif
                
                <div>
                    <a href="{{ $brandLink }}" class="text-white hover:text-primary-100 transition-colors">
                        <h1 class="text-xl font-bold mb-0">{{ $brandName }}</h1>
                        @if($tagline)
                        <p class="text-sm text-primary-100 mb-0">{{ $tagline }}</p>
                        @endif
                    </a>
                </div>
            </div>

            {{-- Search and Actions --}}
            <div class="flex items-center space-x-4">
                {{-- Search --}}
                @if($searchEnabled)
                <div class="relative hidden md:block">
                    <form method="GET" action="/ricerca" class="flex">
                        <input type="search" 
                               name="q"
                               placeholder="{{ $searchPlaceholder }}"
                               class="w-64 px-4 py-2 text-italia-gray-900 bg-white rounded-l-md border border-r-0 border-primary-200 focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent">
                        <button type="submit" 
                                class="px-4 py-2 bg-primary-700 hover:bg-primary-800 border border-primary-700 rounded-r-md transition-colors focus:outline-none focus:ring-2 focus:ring-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="sr-only">Cerca</span>
                        </button>
                    </form>
                </div>
                @endif

                {{-- CTA Button --}}
                @if($ctaButton)
                <a href="{{ $ctaButton['url'] ?? '#' }}" 
                   class="btn-italia bg-white text-primary-600 hover:bg-primary-50 border-white hidden lg:inline-flex">
                    {{ $ctaButton['label'] }}
                </a>
                @endif

                {{-- Mobile Menu Toggle --}}
                <button type="button" 
                        class="md:hidden p-2 rounded-md hover:bg-primary-700 transition-colors"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        x-data="{ mobileMenuOpen: false }">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <span class="sr-only">Menu</span>
                </button>
            </div>
        </div>

        {{-- Mobile Search --}}
        @if($searchEnabled)
        <div class="md:hidden pb-4">
            <form method="GET" action="/ricerca" class="flex">
                <input type="search" 
                       name="q"
                       placeholder="{{ $searchPlaceholder }}"
                       class="flex-1 px-4 py-2 text-italia-gray-900 bg-white rounded-l-md border border-r-0 border-primary-200 focus:outline-none focus:ring-2 focus:ring-white">
                <button type="submit" 
                        class="px-4 py-2 bg-primary-700 hover:bg-primary-800 border border-primary-700 rounded-r-md transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

{{-- Navigation Bar --}}
@if(!empty($menuItems))
<nav class="bg-white border-b border-italia-gray-200 shadow-sm">
    <div class="container-italia">
        <div class="flex items-center justify-between">
            <div class="hidden md:flex space-x-8">
                @foreach($menuItems as $item)
                <a href="{{ $item['url'] ?? '#' }}" 
                   class="nav-item-italia {{ ($item['active'] ?? false) ? 'nav-item-active-italia' : '' }}">
                    {{ $item['label'] }}
                    @if($item['badge'] ?? false)
                    <span class="badge-primary-italia ml-2">{{ $item['badge'] }}</span>
                    @endif
                </a>
                @endforeach
            </div>

            {{-- Mobile Menu --}}
            <div x-data="{ mobileMenuOpen: false }" class="md:hidden w-full">
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="flex items-center justify-between w-full px-4 py-3 text-left">
                    <span class="font-medium text-italia-gray-900">Menu</span>
                    <svg class="w-5 h-5 transition-transform" 
                         :class="{ 'rotate-180': mobileMenuOpen }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div x-show="mobileMenuOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="border-t border-italia-gray-200">
                    @foreach($menuItems as $item)
                    <a href="{{ $item['url'] ?? '#' }}" 
                       class="block px-4 py-3 text-italia-gray-900 hover:bg-italia-gray-50 border-b border-italia-gray-100 {{ ($item['active'] ?? false) ? 'bg-primary-50 text-primary-700' : '' }}">
                        {{ $item['label'] }}
                        @if($item['badge'] ?? false)
                        <span class="badge-primary-italia ml-2">{{ $item['badge'] }}</span>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</nav>
@endif