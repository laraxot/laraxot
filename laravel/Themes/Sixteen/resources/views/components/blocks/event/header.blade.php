{{-- Event Header Component --}}
@props([
    'title' => '',
    'date' => '',
    'time' => '',
    'location' => '',
    'category' => '',
    'image' => '',
    'image_alt' => 'Event image',
])

<div class="event-header">
    @if($image)
    <div class="figure img-fluid mb-4">
        <img src="{{ $image }}" alt="{{ $image_alt }}" class="img-fluid rounded shadow-sm w-100" />
    </div>
    @endif
    
    <div class="cmp-heading mt-2 mb-4">
        <div class="d-flex flex-wrap gap-2 mb-4">
            @if($category)
            <span class="badge badge-pill badge-primary">{{ $category }}</span>
            @endif
            
            @if($date)
            <span class="text-muted">
                <x-filament::icon icon="heroicon-o-calendar" class="w-4 h-4 inline me-1" />
                {{ $date }}
            </span>
            @endif
            
            @if($time)
            <span class="text-muted">
                <x-filament::icon icon="heroicon-o-clock" class="w-4 h-4 inline me-1" />
                {{ $time }}
            </span>
            @endif
        </div>
        
        <h1 class="title-xxxlarge mb-2">{{ $title }}</h1>
        
        @if($location)
        <p class="lead text-muted">
            <x-filament::icon icon="heroicon-o-map-pin" class="w-5 h-5 inline me-1" />
            {{ $location }}
        </p>
        @endif
    </div>
</div>
