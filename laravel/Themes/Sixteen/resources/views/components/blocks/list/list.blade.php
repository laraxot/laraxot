@props(['data' => []])

{{-- 
    List Block
    Usage: <x-blocks.list.list :data="$listData" />
    
    Data structure:
    - title: string (optional)
    - items: array of ['text' => string, 'url' => string (optional), 'icon' => string (optional)]
    - style: 'plain' | 'bordered' | 'icon' (default: 'plain')
    - ordered: boolean (default: false)
--}}

@php
    $style = $data['style'] ?? 'plain';
    $ordered = $data['ordered'] ?? false;
    $title = $data['title'] ?? '';
    $items = $data['items'] ?? [];
@endphp

<div class="list-block list-{{ $style }} {{ $ordered ? 'list-ordered' : 'list-unordered' }}">
    @if($title)
    <h3 class="list-title text-xl font-semibold text-gray-900 mb-4">
        {{ $title }}
    </h3>
    @endif
    
    @if($ordered)
    <ol class="list-wrapper space-y-2">
        @foreach($items as $index => $item)
        <li class="list-item {{ $style === 'bordered' ? 'py-2 border-b border-gray-200' : '' }}">
            <span class="list-marker font-semibold text-primary mr-2">{{ $index + 1 }}.</span>
            @if(isset($item['url']))
            <a href="{{ $item['url'] }}" class="list-text text-gray-900 hover:text-primary transition-colors duration-200">
                {{ $item['text'] }}
            </a>
            @else
            <span class="list-text text-gray-900">{{ $item['text'] }}</span>
            @endif
        </li>
        @endforeach
    </ol>
    @else
    <ul class="list-wrapper space-y-2">
        @foreach($items as $item)
        <li class="list-item {{ $style === 'bordered' ? 'py-2 border-b border-gray-200' : '' }}">
            @if($style === 'icon' && isset($item['icon']))
            <x-filament::icon 
                :icon="$item['icon']" 
                class="icon-sm icon-primary inline mr-2" 
                aria-hidden="true" 
            />
            @elseif($style === 'plain')
            <span class="list-marker text-primary mr-2">•</span>
            @endif
            
            @if(isset($item['url']))
            <a href="{{ $item['url'] }}" class="list-text text-gray-900 hover:text-primary transition-colors duration-200">
                {{ $item['text'] }}
            </a>
            @else
            <span class="list-text text-gray-900">{{ $item['text'] }}</span>
            @endif
        </li>
        @endforeach
    </ul>
    @endif
</div>
