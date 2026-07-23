@props([
    'tabs' => [],
    'activeTab' => 0,
    'variant' => 'default', // default, pills, underline
    'justify' => 'start', // start, center, end
])

@php
    $variants = [
        'default' => 'border-b border-gray-200',
        'pills' => 'bg-gray-100 p-1 rounded-lg',
        'underline' => 'border-b border-gray-200',
    ];
    
    $justifyClasses = [
        'start' => 'justify-start',
        'center' => 'justify-center',
        'end' => 'justify-end',
    ];
    
    $variantClass = $variants[$variant] ?? $variants['default'];
    $justifyClass = $justifyClasses[$justify] ?? $justifyClasses['start'];
@endphp

<div class="ui-tabs">
    <!-- Tab Headers -->
    <div class="flex {{ $variantClass }} {{ $justifyClass }} space-x-1">
        @foreach($tabs as $index => $tab)
            <button
                type="button"
                @click="$wire.setActiveTab({{ $index }})"
                class="px-4 py-2 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 rounded-lg
                    {{ $variant === 'pills' 
                        ? ($index === $activeTab 
                            ? 'bg-white text-gray-900 shadow' 
                            : 'text-gray-600 hover:text-gray-900') 
                        : ($variant === 'underline' 
                            ? ($index === $activeTab 
                                ? 'text-primary-600 border-b-2 border-primary-600' 
                                : 'text-gray-600 hover:text-gray-900 border-b-2 border-transparent') 
                            : ($index === $activeTab 
                                ? 'text-primary-600 border-b-2 border-primary-600' 
                                : 'text-gray-600 hover:text-gray-900 border-b-2 border-transparent')) }}"
                aria-selected="{{ $index === $activeTab ? 'true' : 'false' }}"
                role="tab"
            >
                @if(isset($tab['icon']))
                    <span class="inline-flex items-center">
                        {{ $tab['icon'] }}
                        <span class="ml-2">{{ $tab['label'] ?? '' }}</span>
                    </span>
                @else
                    {{ $tab['label'] ?? '' }}
                @endif
            </button>
        @endforeach
    </div>
    
    <!-- Tab Content -->
    <div class="mt-6">
        @if(isset($tabs[$activeTab]['content']))
            <div 
                class="tab-content"
                role="tabpanel"
                aria-labelledby="tab-{{ $activeTab }}"
            >
                {{ $tabs[$activeTab]['content'] }}
            </div>
        @endif
        
        @if(isset($tabs[$activeTab]['view']))
            <div 
                class="tab-content"
                role="tabpanel"
                aria-labelledby="tab-{{ $activeTab }}"
            >
                @include($tabs[$activeTab]['view'], $tabs[$activeTab]['data'] ?? [])
            </div>
        @endif
    </div>
</div>

<style>
.ui-tabs button:focus-visible {
    outline: 2px solid theme('colors.primary.500');
    outline-offset: 2px;
}

@media (max-width: 768px) {
    .ui-tabs .flex {
        flex-wrap: wrap;
    }
    
    .ui-tabs button {
        flex: 1 0 auto;
        min-width: 120px;
    }
}
</style>