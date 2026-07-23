@props([
    'title' => '',
    'subtitle' => '',
    'image' => null,
    'imageAlt' => '',
    'footer' => null,
    'variant' => 'default',
])

@php
    $cardClasses = [
        'default' => 'card',
        'primary' => 'card card-bg',
        'secondary' => 'card card-bg card-secondary',
    ][$variant] ?? 'card';
@endphp

<div class="{{ $cardClasses }}">
    @if($image)
        <div class="card-img-top">
            <img src="{{ $image }}" alt="{{ $imageAlt }}">
        </div>
    @endif
    
    <div class="card-body">
        @if($title)
            <h5 class="card-title">{{ $title }}</h5>
        @endif
        
        @if($subtitle)
            <h6 class="card-subtitle mb-2 text-muted">{{ $subtitle }}</h6>
        @endif
        
        <div class="card-text">
            {{ $slot }}
        </div>
    </div>
    
    @if($footer)
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>
