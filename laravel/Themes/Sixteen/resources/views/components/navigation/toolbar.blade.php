{{--
/**
 * Toolbar Component - Bootstrap Italia Compliant
 * 
 * Contextual toolbar for actions, filters, and utility controls
 * Typically appears above content areas for quick access to functions
 * 
 * @param string $id Unique ID for the toolbar
 * @param array $leftItems Left-aligned toolbar items
 * @param array $rightItems Right-aligned toolbar items  
 * @param array $centerItems Center-aligned toolbar items
 * @param string $variant Style variant: 'default', 'condensed', 'prominent'
 * @param string $theme Theme variant: 'light' or 'dark'
 * @param bool $sticky Whether to use sticky positioning
 * @param bool $bordered Whether to show border
 */
--}}

@props([
    'id' => 'toolbar-' . uniqid(),
    'leftItems' => [],
    'rightItems' => [],
    'centerItems' => [],
    'variant' => 'default',
    'theme' => 'light',
    'sticky' => false,
    'bordered' => true,
])

@php
    $variantClasses = [
        'default' => 'py-3',
        'condensed' => 'py-2',
        'prominent' => 'py-4'
    ];
    
    $themeClasses = [
        'light' => 'bg-white text-gray-800',
        'dark' => 'bg-gray-800 text-white'
    ];
    
    $borderClass = $bordered ? 'border-b' : '';
    $stickyClass = $sticky ? 'sticky top-0 z-50' : '';
@endphp

<div 
    id="{{ $id }}"
    class="toolbar {{ $themeClasses[$theme] }} {{ $variantClasses[$variant] }} {{ $borderClass }} {{ $stickyClass }} px-4"
    role="toolbar"
    aria-label="Barra degli strumenti"
>
    <div class="flex items-center justify-between">
        {{-- Left section --}}
        <div class="flex items-center space-x-2">
            @foreach($leftItems as $item)
                @if($item['type'] === 'button')
                    <button
                        type="button"
                        class="toolbar-btn px-3 py-2 rounded text-sm font-medium transition-colors"
                        :class="{
                            'bg-blue-600 text-white': {{ $item['active'] ?? 'false' }},
                            'bg-gray-200 hover:bg-gray-300 text-gray-700': !({{ $item['active'] ?? 'false' }}) && theme === 'light',
                            'bg-gray-700 hover:bg-gray-600 text-gray-200': !({{ $item['active'] ?? 'false' }}) && theme === 'dark'
                        }"
                        @if(isset($item['click']))
                            @click="{{ $item['click'] }}"
                        @endif
                        aria-label="{{ $item['label'] }}"
                    >
                        @if(isset($item['icon']))
                            <x-dynamic-component :component="$item['icon']" class="w-4 h-4" />
                        @else
                            {{ $item['label'] }}
                        @endif
                    </button>
                
                @elseif($item['type'] === 'dropdown')
                    <div class="relative" x-data="{ open: false }">
                        <button
                            type="button"
                            class="toolbar-dropdown-btn px-3 py-2 rounded text-sm font-medium flex items-center space-x-1 transition-colors"
                            :class="{
                                'bg-blue-600 text-white': open,
                                'bg-gray-200 hover:bg-gray-300 text-gray-700': !open && theme === 'light',
                                'bg-gray-700 hover:bg-gray-600 text-gray-200': !open && theme === 'dark'
                            }"
                            @click="open = !open"
                            aria-haspopup="true"
                            :aria-expanded="open"
                        >
                            <span>{{ $item['label'] }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <div
                            x-show="open"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            class="absolute top-full left-0 mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                            style="display: none;"
                        >
                            <div class="py-1" role="menu" aria-orientation="vertical">
                                @foreach($item['options'] as $option)
                                    <button
                                        type="button"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        role="menuitem"
                                        @click="{{ $option['action'] }}; open = false;"
                                    >
                                        {{ $option['label'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                
                @elseif($item['type'] === 'separator')
                    <div class="w-px h-6 bg-gray-300 mx-2"></div>
                
                @elseif($item['type'] === 'text')
                    <span class="text-sm text-gray-600 px-2">
                        {{ $item['text'] }}
                    </span>
                @endif
            @endforeach
        </div>

        {{-- Center section --}}
        <div class="flex items-center space-x-2">
            @foreach($centerItems as $item)
                @if($item['type'] === 'search')
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="{{ $item['placeholder'] ?? 'Cerca...' }}"
                            class="toolbar-search pl-8 pr-3 py-2 rounded text-sm border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            @if(isset($item['model']))
                                x-model="{{ $item['model'] }}"
                            @endif
                        >
                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Right section --}}
        <div class="flex items-center space-x-2">
            @foreach($rightItems as $item)
                {{-- Reuse the same item rendering logic as left section --}}
                @if($item['type'] === 'button')
                    <button
                        type="button"
                        class="toolbar-btn px-3 py-2 rounded text-sm font-medium transition-colors"
                        :class="{
                            'bg-blue-600 text-white': {{ $item['active'] ?? 'false' }},
                            'bg-gray-200 hover:bg-gray-300 text-gray-700': !({{ $item['active'] ?? 'false' }}) && theme === 'light',
                            'bg-gray-700 hover:bg-gray-600 text-gray-200': !({{ $item['active'] ?? 'false' }}) && theme === 'dark'
                        }"
                        @if(isset($item['click']))
                            @click="{{ $item['click'] }}"
                        @endif
                        aria-label="{{ $item['label'] }}"
                    >
                        @if(isset($item['icon']))
                            <x-dynamic-component :component="$item['icon']" class="w-4 h-4" />
                        @else
                            {{ $item['label'] }}
                        @endif
                    </button>
                
                @elseif($item['type'] === 'dropdown')
                    <div class="relative" x-data="{ open: false }">
                        <button
                            type="button"
                            class="toolbar-dropdown-btn px-3 py-2 rounded text-sm font-medium flex items-center space-x-1 transition-colors"
                            :class="{
                                'bg-blue-600 text-white': open,
                                'bg-gray-200 hover:bg-gray-300 text-gray-700': !open && theme === 'light',
                                'bg-gray-700 hover:bg-gray-600 text-gray-200': !open && theme === 'dark'
                            }"
                            @click="open = !open"
                            aria-haspopup="true"
                            :aria-expanded="open"
                        >
                            <span>{{ $item['label'] }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <div
                            x-show="open"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            class="absolute top-full right-0 mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                            style="display: none;"
                        >
                            <div class="py-1" role="menu" aria-orientation="vertical">
                                @foreach($item['options'] as $option)
                                    <button
                                        type="button"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        role="menuitem"
                                        @click="{{ $option['action'] }}; open = false;"
                                    >
                                        {{ $option['label'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                
                @elseif($item['type'] === 'separator')
                    <div class="w-px h-6 bg-gray-300 mx-2"></div>
                
                @elseif($item['type'] === 'text')
                    <span class="text-sm text-gray-600 px-2">
                        {{ $item['text'] }}
                    </span>
                @endif
            @endforeach
        </div>
    </div>
</div>