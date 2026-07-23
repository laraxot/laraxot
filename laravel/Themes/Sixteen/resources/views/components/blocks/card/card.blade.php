@props(['data' => []])

{{-- 
    Card Block
    Usage: <x-blocks.card.card :data="$cardData" />
    
    Data structure:
    - title: string
    - description: string
    - icon: string (optional)
    - image: string (url, optional)
    - url: string (optional)
    - url_text: string (optional, default: 'Leggi di più')
    - date: string (optional, for news cards)
    - category: string (optional)
    - layout: 'vertical' | 'horizontal' | 'compact'
--}}

@php
    $layout = $data['layout'] ?? 'vertical';
    $title = $data['title'] ?? '';
    $description = $data['description'] ?? '';
    $icon = $data['icon'] ?? '';
    $image = $data['image'] ?? '';
    $url = $data['url'] ?? '';
    $url_text = $data['url_text'] ?? 'Leggi di più';
    $date = $data['date'] ?? '';
    $category = $data['category'] ?? '';
@endphp

<article class="card-block card-{{ $layout }} bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
    {{-- Image (if provided) --}}
    @if($image)
    <div class="card-image relative h-48 overflow-hidden">
        <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
        @if($category)
        <span class="card-category absolute top-4 left-4 bg-primary text-white text-xs font-semibold px-3 py-1 rounded-full">
            {{ $category }}
        </span>
        @endif
    </div>
    @endif
    
    {{-- Card Body --}}
    <div class="card-body p-6">
        {{-- Icon (if provided and no image) --}}
        @if($icon && !$image)
        <div class="card-icon mb-4">
            <x-filament::icon 
                :icon="$icon" 
                class="icon-lg icon-primary w-12 h-12" 
                aria-hidden="true" 
            />
        </div>
        @endif
        
        {{-- Date (for news cards) --}}
        @if($date)
        <span class="card-date text-sm text-gray-500 block mb-2">
            {{ $date }}
        </span>
        @endif
        
        {{-- Title --}}
        @if($title)
        <h3 class="card-title text-xl font-semibold text-gray-900 mb-3">
            @if($url)
            <a href="{{ $url }}" class="hover:text-primary transition-colors duration-200">
                {{ $title }}
            </a>
            @else
            {{ $title }}
            @endif
        </h3>
        @endif
        
        {{-- Description --}}
        @if($description)
        <p class="card-description text-gray-600 mb-4 line-clamp-3">
            {{ $description }}
        </p>
        @endif
        
        {{-- Read More Link --}}
        @if($url)
        <a href="{{ $url }}" class="card-link inline-flex items-center text-primary font-semibold hover:text-primary-dark transition-colors duration-200">
            <span class="text">{{ $url_text }}</span>
            <x-filament::icon 
                icon="heroicon-o-arrow-right" 
                class="icon-sm ml-1" 
                aria-hidden="true" 
            />
        </a>
        @endif
    </div>
</article>
