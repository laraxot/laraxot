@props([
    'logoUrl' => '/',
    'showAuth' => true,
    'showSearch' => true,
])

<header class="header-main bg-white shadow-md sticky top-0 z-50" role="banner">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ $logoUrl }}" class="flex items-center space-x-3" aria-label="{{ config('app.name') }} - Home">
                    <x-pub_theme::ui.logo class="h-10 w-auto" />
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center space-x-8" role="navigation" aria-label="Main navigation">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary-600 font-medium transition-colors">
                    {{ __('Home') }}
                </a>
                <a href="{{ route('pages.services') }}" class="text-gray-700 hover:text-primary-600 font-medium transition-colors">
                    {{ __('Servizi') }}
                </a>
                <a href="{{ route('pages.about') }}" class="text-gray-700 hover:text-primary-600 font-medium transition-colors">
                    {{ __('Chi Siamo') }}
                </a>
                <a href="{{ route('pages.blog') }}" class="text-gray-700 hover:text-primary-600 font-medium transition-colors">
                    {{ __('Blog') }}
                </a>
                <a href="{{ route('pages.contacts') }}" class="text-gray-700 hover:text-primary-600 font-medium transition-colors">
                    {{ __('Contatti') }}
                </a>
            </nav>
            
            <!-- Right Side Actions -->
            <div class="flex items-center space-x-4">
                <!-- Search -->
                @if($showSearch)
                    <button type="button" class="p-2 text-gray-700 hover:text-primary-600 transition-colors" aria-label="{{ __('Search') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                @endif
                
                <!-- Auth Links -->
                @if($showAuth)
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-600 font-medium transition-colors">
                            {{ __('Login') }}
                        </a>
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-primary-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-primary-700 transition-colors">
                                {{ __('Registrati') }}
                            </a>
                        @endif
                    @else
                        <a href="{{ route('profile.index') }}" class="flex items-center space-x-2 text-gray-700 hover:text-primary-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="hidden md:inline">{{ __('Profilo') }}</span>
                        </a>
                    @endguest
                @endif
                
                <!-- Mobile Menu Button -->
                <button type="button" class="lg:hidden p-2 text-gray-700 hover:text-primary-600 transition-colors" aria-label="{{ __('Open menu') }}" aria-expanded="false">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>