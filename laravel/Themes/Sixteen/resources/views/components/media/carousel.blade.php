{{--
Bootstrap Italia Carousel Component
Compliant image carousel with navigation controls and accessibility features
--}}

@props([
    'id' => 'carousel-' . uniqid(),
    'items' => [],
    'controls' => true,
    'indicators' => true,
    'autoplay' => false,
    'interval' => 5000,
    'pauseOnHover' => true,
    'fade' => false,
    'dark' => false
])

@php
    $itemsCount = count($items);
    $carouselClasses = [
        'carousel',
        'slide',
        $fade ? 'carousel-fade' : '',
        $dark ? 'carousel-dark' : ''
    ];
@endphp

<div 
    id="{{ $id }}"
    {{ $attributes->merge(['class' => implode(' ', array_filter($carouselClasses))]) }}
    data-bs-ride="{{ $autoplay ? 'carousel' : 'false' }}"
    @if($interval !== 5000) data-bs-interval="{{ $interval }}" @endif
    @if($pauseOnHover) data-bs-pause="hover" @endif
>
    {{-- Indicators --}}
    @if($indicators && $itemsCount > 1)
    <div class="carousel-indicators">
        @foreach($items as $index => $item)
        <button 
            type="button" 
            data-bs-target="#{{ $id }}" 
            data-bs-slide-to="{{ $index }}"
            @if($index === 0) class="active" aria-current="true" @endif
            aria-label="Slide {{ $index + 1 }}"
        ></button>
        @endforeach
    </div>
    @endif

    {{-- Carousel Inner --}}
    <div class="carousel-inner">
        @foreach($items as $index => $item)
        <div class="carousel-item @if($index === 0) active @endif">
            @if(is_array($item))
                {{-- Image with caption --}}
                @if(isset($item['image']))
                <img src="{{ $item['image'] }}" class="d-block w-100" alt="{{ $item['alt'] ?? '' }}">
                @endif
                
                @if(isset($item['caption']) || isset($item['title']))
                <div class="carousel-caption d-none d-md-block">
                    @if(isset($item['title']))
                    <h5>{{ $item['title'] }}</h5>
                    @endif
                    @if(isset($item['caption']))
                    <p>{{ $item['caption'] }}</p>
                    @endif
                </div>
                @endif
            @else
                {{-- Raw HTML content --}}
                {!! $item !!}
            @endif
        </div>
        @endforeach
    </div>

    {{-- Controls --}}
    @if($controls && $itemsCount > 1)
    <button class="carousel-control-prev" type="button" data-bs-target="#{{ $id }}" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#{{ $id }}" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
    @endif
</div>