@props(['data' => []])

{{-- 
    Hero Block
    Usage: <x-blocks.hero.hero :data="$heroData" />
    
    Data structure:
    - title: string
    - subtitle: string
    - description: string (optional)
    - cta_text: string (optional)
    - cta_url: string (optional)
    - cta_secondary_text: string (optional)
    - cta_secondary_url: string (optional)
    - background: 'white' | 'gray' | 'image' | 'gradient'
    - background_image: string (url, optional)
    - alignment: 'left' | 'center' | 'right'
--}}

@php
    $background = $data['background'] ?? 'white';
    $alignment = $data['alignment'] ?? 'left';
    $title = $data['title'] ?? '';
    $subtitle = $data['subtitle'] ?? '';
    $description = $data['description'] ?? '';
    $cta_text = $data['cta_text'] ?? '';
    $cta_url = $data['cta_url'] ?? '';
    $cta_secondary_text = $data['cta_secondary_text'] ?? '';
    $cta_secondary_url = $data['cta_secondary_url'] ?? '';
    $background_image = $data['background_image'] ?? '';
@endphp

<section class="hero-block hero-{{ $background }} alignment-{{ $alignment }} py-16 md:py-24 relative overflow-hidden">
    {{-- Background --}}
    @if($background === 'image' && $background_image)
    <div class="hero-background absolute inset-0 z-0">
        <img src="{{ $background_image }}" alt="" class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-black/50"></div>
    </div>
    @elseif($background === 'gradient')
    <div class="hero-background absolute inset-0 z-0 bg-gradient-to-r from-primary to-primary-dark"></div>
    @elseif($background === 'gray')
    <div class="hero-background absolute inset-0 z-0 bg-gray-100"></div>
    @endif
    
    {{-- Content --}}
    <div class="container relative z-10">
        <div class="row">
            <div class="col-lg-8 {{ $alignment === 'center' ? 'mx-auto text-center' : ($alignment === 'right' ? 'ml-auto text-right' : 'text-left') }}">
                {{-- Subtitle --}}
                @if($subtitle)
                <p class="hero-subtitle text-sm font-semibold uppercase tracking-wide text-primary mb-4">
                    {{ $subtitle }}
                </p>
                @endif
                
                {{-- Title --}}
                @if($title)
                <h1 class="hero-title text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 {{ $background === 'image' || $background === 'gradient' ? 'text-white' : '' }} mb-6 leading-tight">
                    {{ $title }}
                </h1>
                @endif
                
                {{-- Description --}}
                @if($description)
                <p class="hero-description text-lg md:text-xl {{ $background === 'image' || $background === 'gradient' ? 'text-white/90' : 'text-gray-600' }} mb-8 max-w-2xl {{ $alignment === 'center' ? 'mx-auto' : '' }}">
                    {{ $description }}
                </p>
                @endif
                
                {{-- CTA Buttons --}}
                @if($cta_text || $cta_secondary_text)
                <div class="hero-cta flex flex-wrap gap-4 {{ $alignment === 'center' ? 'justify-center' : ($alignment === 'right' ? 'justify-end' : 'justify-start') }}">
                    @if($cta_text)
                    <a href="{{ $cta_url }}" class="btn btn-primary inline-flex items-center justify-center px-8 py-3 text-base font-semibold rounded-lg bg-primary text-white hover:bg-primary-dark transition-colors duration-200">
                        {{ $cta_text }}
                        <x-filament::icon 
                            icon="heroicon-o-arrow-right" 
                            class="icon-sm ml-2" 
                            aria-hidden="true" 
                        />
                    </a>
                    @endif
                    
                    @if($cta_secondary_text)
                    <a href="{{ $cta_secondary_url }}" class="btn btn-secondary inline-flex items-center justify-center px-8 py-3 text-base font-semibold rounded-lg border-2 border-primary text-primary hover:bg-primary hover:text-white transition-colors duration-200">
                        {{ $cta_secondary_text }}
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
