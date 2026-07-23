@props(['data' => []])

{{-- 
    Stats Block
    Usage: <x-blocks.stats.stats :data="$statsData" />
    
    Data structure:
    - title: string (optional)
    - stats: array of ['label' => string, 'value' => string, 'icon' => string (optional), 'change' => string (optional)]
    - layout: 'grid' | 'horizontal' (default: 'grid')
    - columns: 2 | 3 | 4 (default: 3)
--}}

@php
    $layout = $data['layout'] ?? 'grid';
    $columns = $data['columns'] ?? 3;
    $title = $data['title'] ?? '';
    $stats = $data['stats'] ?? [];
@endphp

<div class="stats-block stats-{{ $layout }}">
    @if($title)
    <h3 class="stats-title text-2xl font-bold text-gray-900 mb-6">
        {{ $title }}
    </h3>
    @endif
    
    <div class="stats-wrapper grid grid-cols-1 {{ $columns === 2 ? 'md:grid-cols-2' : ($columns === 3 ? 'md:grid-cols-2 lg:grid-cols-3' : 'md:grid-cols-2 lg:grid-cols-4') }} gap-6">
        @foreach($stats as $stat)
        <div class="stat-item bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
            {{-- Icon --}}
            @if(isset($stat['icon']))
            <div class="stat-icon mb-4">
            <x-filament::icon 
                :icon="$stat['icon']" 
                class="icon-lg icon-primary w-10 h-10" 
                aria-hidden="true" 
            />
            </div>
            @endif
            
            {{-- Value --}}
            @if(isset($stat['value']))
            <p class="stat-value text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                {{ $stat['value'] }}
            </p>
            @endif
            
            {{-- Label --}}
            @if(isset($stat['label']))
            <p class="stat-label text-sm text-gray-600 mb-2">
                {{ $stat['label'] }}
            </p>
            @endif
            
            {{-- Change (if provided) --}}
            @if(isset($stat['change']))
            <p class="stat-change text-sm {{ str_contains($stat['change'], '+') ? 'text-green-600' : 'text-red-600' }}">
                {{ $stat['change'] }}
            </p>
            @endif
        </div>
        @endforeach
    </div>
</div>
