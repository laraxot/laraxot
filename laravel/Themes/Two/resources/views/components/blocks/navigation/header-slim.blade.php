@props([
    'logoUrl' => '/',
    'showAuth' => true,
])

<header class="header-slim bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ $logoUrl }}" class="flex items-center">
                    <x-pub_theme::ui.logo class="h-8 w-auto" />
                </a>
            </div>
            
            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary-600 transition-colors">
                    {{ __('Home') }}
                </a>
                <a href="{{ route('pages.services') }}" class="text-gray-700 hover:text-primary-600 transition-colors">
                    {{ __('Servizi') }}
                </a>
                <a href="{{ route('pages.blog') }}" class="text-gray-700 hover:text-primary-600 transition-colors">
                    {{ __('Blog') }}
                </a>
            </div>
            
            <!-- Auth Links -->
            @if($showAuth)
                <div class="flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-600 transition-colors">
                            {{ __('Login') }}
                        </a>
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                                {{ __('Registrati') }}
                            </a>
                        @endif
                    @else
                        <a href="{{ route('profile.index') }}" class="text-gray-700 hover:text-primary-600 transition-colors">
                            {{ __('Profilo') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-primary-600 transition-colors">
                                {{ __('Logout') }}
                            </button>
                        </form>
                    @endguest
                </div>
            @endif
        </div>
    </div>
</header>