@props(['data' => []])

{{--
    Header Center Component - EXACT Design Comuni HTML & Tailwind CSS
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
    
    EXACT Structure Required:
    - it-nav-wrapper (OUTER wrapper)
    - it-header-center-wrapper (INNER wrapper)
    - it-header-center-content-wrapper
    - it-brand-wrapper with SVG logo (82x82px)
    - it-brand-title (text-xl, font-bold, #0066CC)
    - it-brand-tagline (text-sm, #666666, hidden on mobile)
    - it-right-zone
    - it-socials (hidden on mobile/tablet, visible on lg)
    
    Colors:
    - Background: #FFFFFF (white)
    - Border: #CCCCCC (light gray)
    - Brand Title: #0066CC (Blu Italia)
    - Tagline: #666666 (Grigio Medio)
    - Social Icons: #0066CC (Blu Italia)
--}}

@php
    $cityName = $data['city_name'] ?? 'Il mio Comune';
    $tagline = $data['tagline'] ?? 'Un comune da vivere';
    $logoUrl = $data['logo_url'] ?? asset('themes/sixteen/images/logo-comune.svg');
    $homeUrl = $data['home_url'] ?? '/it/tests/homepage';
    $socialLinks = $data['social_links'] ?? [
        ['platform' => 'twitter', 'url' => '#', 'label' => 'Twitter', 'icon' => 'it-twitter'],
        ['platform' => 'facebook', 'url' => '#', 'label' => 'Facebook', 'icon' => 'it-facebook'],
        ['platform' => 'youtube', 'url' => '#', 'label' => 'YouTube', 'icon' => 'it-youtube'],
        ['platform' => 'telegram', 'url' => '#', 'label' => 'Telegram', 'icon' => 'it-telegram'],
        ['platform' => 'whatsapp', 'url' => '#', 'label' => 'Whatsapp', 'icon' => 'it-whatsapp'],
        ['platform' => 'rss', 'url' => '#', 'label' => 'RSS', 'icon' => 'it-rss'],
    ];
@endphp

{{-- EXACT Design Comuni structure - ORDER MATTERS: it-nav-wrapper > it-header-center-wrapper --}}
<div class="it-nav-wrapper" style="background-color: #FFFFFF; border-bottom: 1px solid #CCCCCC;">
    <div class="it-header-center-wrapper">
        <div class="container mx-auto px-4">
            <div class="row">
                <div class="col-12">
                    <div class="it-header-center-content-wrapper" style="display: flex; align-items: center; justify-content: between; padding: 1rem 0;">
                        
                        {{-- Brand Wrapper - Logo + Text --}}
                        <div class="it-brand-wrapper" style="display: flex; align-items: center; gap: 0.75rem;">
                            <a href="{{ $homeUrl }}" class="no-underline hover:no-underline">
                                {{-- SVG Logo - EXACT size 82x82px --}}
                                <svg width="82" height="82" class="icon" aria-hidden="true" style="width: 82px; height: 82px;">
                                    <image href="{{ $logoUrl }}" style="width: 82px; height: 82px;"/>
                                </svg>
                                
                                {{-- Brand Text - Title + Tagline --}}
                                <div class="it-brand-text" style="display: flex; flex-direction: column;">
                                    {{-- Brand Title - EXACT color #0066CC (Blu Italia) --}}
                                    <div class="it-brand-title" 
                                         style="font-size: 1.25rem; font-weight: 700; color: #0066CC; margin: 0; line-height: 1.2;">
                                        {{ $cityName }}
                                    </div>
                                    
                                    {{-- Tagline - EXACT color #666666 (Grigio Medio), hidden on mobile --}}
                                    <div class="it-brand-tagline d-none d-md-block" 
                                         style="font-size: 0.875rem; color: #666666; margin: 0.25rem 0 0 0;"
                                         class="hidden md:block">
                                        {{ $tagline }}
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        {{-- Right Zone with Socials --}}
                        <div class="it-right-zone" style="display: flex; align-items: center;">
                            
                            {{-- Social Links - EXACT structure, hidden on mobile/tablet --}}
                            <div class="it-socials d-none d-lg-flex" 
                                 style="display: none; align-items: center; gap: 0.5rem;"
                                 class="hidden lg:flex">
                                <span style="font-size: 0.875rem; color: #666666; margin-right: 0.5rem;">Seguici su</span>
                                <ul style="display: flex; list-style: none; margin: 0; padding: 0; gap: 0.5rem;">
                                    @foreach($socialLinks as $social)
                                    <li style="margin: 0;">
                                        <a href="{{ $social['url'] }}" 
                                           target="_blank"
                                           rel="noopener"
                                           aria-label="{{ $social['platform'] }} - link esterno - apertura nuova scheda"
                                           title="{{ $social['platform'] }}"
                                           style="color: #0066CC; text-decoration: none; font-size: 0.875rem; transition: color 0.2s;"
                                           class="hover:text-[#003D73] hover:underline">
                                            <svg class="icon icon-sm" 
                                                 width="16" height="16" 
                                                 style="width: 16px; height: 16px; vertical-align: top;"
                                                 aria-hidden="true">
                                                <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#{{ $social['icon'] }}"></use>
                                            </svg>
                                            <span class="visually-hidden" style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0;">
                                                {{ $social['label'] }}
                                            </span>
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
