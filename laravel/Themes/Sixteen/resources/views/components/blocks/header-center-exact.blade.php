@props(['data' => []])

{{--
    Header Center Component - EXACT Design Comuni HTML Structure
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
    
    EXACT Structure Required:
    - it-nav-wrapper (OUTER)
    - it-header-center-wrapper (INNER)
    - it-header-center-content-wrapper
    - it-brand-wrapper with SVG logo
    - it-brand-title, it-brand-tagline
    - it-right-zone
    - it-socials
--}}

@php
    $cityName = $data['city_name'] ?? 'Il mio Comune';
    $tagline = $data['tagline'] ?? 'Un comune da vivere';
    $logoUrl = $data['logo_url'] ?? asset('themes/sixteen/images/logo-comune.svg');
    $homeUrl = $data['home_url'] ?? '/it/tests/homepage';
    $socialLinks = $data['social_links'] ?? [
        ['platform' => 'twitter', 'url' => '#', 'label' => 'Twitter'],
        ['platform' => 'facebook', 'url' => '#', 'label' => 'Facebook'],
        ['platform' => 'youtube', 'url' => '#', 'label' => 'YouTube'],
        ['platform' => 'telegram', 'url' => '#', 'label' => 'Telegram'],
        ['platform' => 'whatsapp', 'url' => '#', 'label' => 'Whatsapp'],
    ];
@endphp

{{-- EXACT Design Comuni structure - ORDER MATTERS: it-nav-wrapper > it-header-center-wrapper --}}
<div class="it-nav-wrapper">
    <div class="it-header-center-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="it-header-center-content-wrapper">
                        {{-- Brand Wrapper --}}
                        <div class="it-brand-wrapper">
                            <a href="{{ $homeUrl }}">
                                {{-- SVG Logo - EXACT structure --}}
                                <svg width="82" height="82" class="icon" aria-hidden="true">
                                    <image href="{{ $logoUrl }}"/>
                                </svg>
                                <div class="it-brand-text">
                                    {{-- EXACT classes: it-brand-title, it-brand-tagline --}}
                                    <div class="it-brand-title">{{ $cityName }}</div>
                                    <div class="it-brand-tagline d-none d-md-block">{{ $tagline }}</div>
                                </div>
                            </a>
                        </div>
                        
                        {{-- Right Zone with Socials --}}
                        <div class="it-right-zone">
                            {{-- Social Links - EXACT structure --}}
                            <div class="it-socials d-none d-lg-flex">
                                <span>Seguici su</span>
                                <ul>
                                    @foreach($socialLinks as $social)
                                    <li>
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
        </div>
    </div>
</div>
