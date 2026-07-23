{{-- Bootstrap Italia Breadcrumb Component --}}
@props([
    'items' => [],
    'separator' => '/',
    'homeLabel' => 'Home',
    'homeUrl' => '/',
    'showHome' => true
])

@if(!empty($items) || $showHome)
<nav aria-label="Breadcrumb" class="py-3">
    <div class="container-italia">
        <ol class="breadcrumb-italia">
            @if($showHome)
            <li>
                <a href="{{ $homeUrl }}" class="breadcrumb-item-italia">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    {{ $homeLabel }}
                </a>
            </li>
            @if(!empty($items))
            <li class="breadcrumb-separator-italia">{{ $separator }}</li>
            @endif
            @endif
            
            @foreach($items as $index => $item)
            <li class="flex items-center">
                @if($index > 0)
                <span class="breadcrumb-separator-italia mr-2">{{ $separator }}</span>
                @endif
                
                @if($loop->last || !isset($item['url']))
                <span class="text-italia-gray-900 font-medium" aria-current="page">
                    {{ $item['label'] ?? $item }}
                </span>
                @else
                <a href="{{ $item['url'] ?? '#' }}" class="breadcrumb-item-italia">
                    {{ $item['label'] ?? $item }}
                </a>
                @endif
            </li>
            @endforeach
        </ol>
    </div>
</nav>
@endif