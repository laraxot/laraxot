{{-- News Header Component --}}
@props([
    'title' => '',
    'date' => '',
    'category' => '',
    'author' => '',
    'reading_time' => '',
])

<div class="cmp-heading mt-2 mb-8">
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                @if($category)
                <span class="badge badge-pill badge-primary">{{ $category }}</span>
                @endif
                
                @if($date)
                <span class="text-muted small">
                    <x-filament::icon icon="heroicon-o-calendar" class="w-4 h-4 inline me-1" />
                    {{ $date }}
                </span>
                @endif
                
                @if($reading_time)
                <span class="text-muted small">
                    <x-filament::icon icon="heroicon-o-clock" class="w-4 h-4 inline me-1" />
                    {{ $reading_time }} lettura
                </span>
                @endif
            </div>
        </div>
    </div>
    
    <h1 class="title-xxxlarge mb-4">{{ $title }}</h1>
    
    @if($author)
    <div class="author-info">
        <p class="text-muted mb-0">
            <x-filament::icon icon="heroicon-o-user" class="w-4 h-4 inline me-1" />
            Di {{ $author }}
        </p>
    </div>
    @endif
</div>
