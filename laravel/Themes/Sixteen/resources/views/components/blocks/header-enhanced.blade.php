@props(['data' => []])

{{--
    Enhanced Header Component - Design Comuni Style
    Usage: <x-blocks.header-enhanced :data="$headerData" />
    
    EXACT Design Comuni Colors:
    - Background: #FFFFFF (White)
- City Name: #007A52 (Comune Green)
    - Social Icons: #007A52 (Comune Green)
    - Login Button: #007A52 (Comune Green)
--}}

@php
    $cityName = $data['city_name'] ?? 'Nome del Comune';
    $tagline = $data['tagline'] ?? 'Un comune da vivere';
    $logoUrl = $data['logo_url'] ?? asset('themes/Sixteen/images/logo-comune.svg');
    $socialLinks = $data['social_links'] ?? [
        ['platform' => 'twitter', 'url' => '#', 'icon' => 'ui-brands.twitter'],
        ['platform' => 'facebook', 'url' => '#', 'icon' => 'ui-brands.facebook'],
        ['platform' => 'youtube', 'url' => '#', 'icon' => 'ui-brands.youtube'],
        ['platform' => 'whatsapp', 'url' => '#', 'icon' => 'ui-brands.whatsapp'],
    ];
    $searchAction = $data['search_action'] ?? '/it/tests/risultati-ricerca';
    $loginUrl = $data['login_url'] ?? '/it/tests/auth/login';
@endphp

{{-- Center Header - EXACT Design Comuni colors --}}
<div class="it-header-center-wrapper" style="background-color: #FFFFFF; border-bottom: 1px solid #F2F2F2;">
    <div class="container">
        <div class="row align-items-center py-4">
            {{-- Logo & City Info --}}
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ $logoUrl }}" alt="{{ $cityName }}" class="h-16 w-auto" />
                    <div>
                        {{-- City Name - EXACT color #0066CC (Blu Italia) --}}
                        <h1 class="h5 font-weight-bold mb-0" style="color: #0066CC;">{{ $cityName }}</h1>
                        {{-- Tagline - EXACT color #666666 (Grigio Medio) --}}
                        <p class="text-sm mb-0" style="color: #666666;">{{ $tagline }}</p>
                    </div>
                </div>
            </div>

            {{-- Search Bar --}}
            <div class="col-lg-4 col-md-6">
                <form action="{{ $searchAction }}" method="GET" class="search-form">
                    <div class="input-group">
                        <input
                            type="text"
                            name="q"
                            class="form-control"
                            placeholder="Cerca nel sito..."
                            aria-label="Cerca"
                            style="border: 1px solid #CCCCCC;"
                        />
                        <button class="btn btn-primary" type="submit" style="background-color: #007A52; border-color: #007A52;">
                            <x-filament::icon icon="heroicon-m-magnifying-glass" class="w-5 h-5" />
                        </button>
                    </div>
                </form>
            </div>

            {{-- Social & Login --}}
            <div class="col-lg-4 col-md-12 mt-3 mt-lg-0">
                <div class="d-flex justify-content-end align-items-center gap-3">
{{-- Social Links - Comune Green (#007A52) --}}
                    <div class="social-links d-flex gap-2">
                        @foreach($socialLinks as $social)
                        <a
                            href="{{ $social['url'] }}"
                            style="color: #007A52;"
                            class="hover:text-green-800 transition-colors"
                            aria-label="{{ ucfirst($social['platform']) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <x-filament::icon :icon="$social['icon']" class="w-5 h-5" />

                        </a>
                        @endforeach
                        <a href="#" style="color: #007A52;" class="hover:text-green-800 transition-colors" aria-label="RSS">
                            <x-filament::icon icon="heroicon-o-rss" class="w-5 h-5" />
                        </a>
                    </div>

                    {{-- Login Button - Comune Green (#007A52) --}}
                    <a href="{{ $loginUrl }}" class="btn btn-primary btn-sm px-4" style="background-color: #007A52; border-color: #007A52; color: #FFFFFF;">
                        <x-filament::icon icon="heroicon-m-user" class="w-4 h-4 mr-2" />
                        Accedi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
