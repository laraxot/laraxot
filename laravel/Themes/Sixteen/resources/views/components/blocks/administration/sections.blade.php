{{-- Administration Sections Component --}}
@props([
    'items' => [],
])

<div class="administration-sections py-8">
    <div class="row g-4">
        @foreach($items as $item)
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card card-bg shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    @if(isset($item['icon']))
                    <div class="mb-3">
                        <x-filament::icon 
                            icon="heroicon-o-{{ $item['icon'] }}" 
                            class="w-12 h-12 text-primary mx-auto" 
                        />
                    </div>
                    @endif
                    
                    <h3 class="h5 mb-2">{{ $item['title'] }}</h3>
                    
                    @if(isset($item['description']))
                    <p class="text-muted small mb-3">{{ $item['description'] }}</p>
                    @endif
                    
                    @if(isset($item['url']))
                    <a href="{{ $item['url'] }}" class="btn btn-outline-primary btn-sm">
                        Scopri di più
                        <x-filament::icon icon="heroicon-o-arrow-right" class="w-4 h-4 inline ms-1" />
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
