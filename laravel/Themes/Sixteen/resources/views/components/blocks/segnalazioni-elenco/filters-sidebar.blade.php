{{--
    Filters Sidebar - Desktop
    Alpine.js + TailwindCSS + DaisyUI
    No Bootstrap
--}}

@php
    $filters = $filters ?? [];
    $filterItems = $filters['items'] ?? [];
    $legend = $filters['title'] ?? __('fixcity::ticket.filters.legend.label');
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sticky top-4">
    
    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-italia-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            {{ $legend }}
        </h3>
        
        {{-- Reset Button --}}
        <button @click="selectedTypes = []"
                x-show="selectedTypes.length > 0"
                x-transition
                class="text-sm text-italia-blue hover:text-italia-blue-dark underline">
            {{ __('fixcity::ticket.filters.reset') }}
        </button>
    </div>
    
    {{-- Filter Items --}}
    <div class="space-y-3">
        @foreach ($filterItems as $item)
            <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer group">
                <div class="relative flex items-center">
                    <input type="checkbox" 
                           :value="'{{ $item['value'] }}'"
                           :checked="isTypeSelected('{{ $item['value'] }}')"
                           @change="toggleType('{{ $item['value'] }}')"
                           class="checkbox checkbox-sm checkbox-primary"
                           style="--chkbg: {{ $item['color'] ?? '#007A52' }}; --chkfg: white;">
                </div>
                
                <div class="flex items-center gap-2 min-w-0 flex-1">
                    @if (!empty($item['iconUrl']))
                        <img src="{{ $item['iconUrl'] }}" alt="" class="w-5 h-5 object-contain flex-shrink-0" loading="lazy">
                    @endif
                    <span class="block text-sm font-medium text-gray-900 truncate">
                        {{ $item['label'] }}
                    </span>
                </div>
                
                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 group-hover:bg-gray-200 transition-colors">
                    {{ $item['count'] }}
                </span>
            </label>
        @endforeach
    </div>
    
    {{-- Selected Filters Summary --}}
    <div x-show="selectedTypes.length > 0" 
         x-transition
         class="mt-4 pt-4 border-t border-gray-200">
        <p class="text-sm text-gray-600 mb-2">
            {{ __('fixcity::ticket.filters.selected') }}: <span x-text="selectedTypes.length"></span>
        </p>
        <div class="flex flex-wrap gap-2">
            <template x-for="type in selectedTypes" :key="type">
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-xs font-medium bg-italia-blue/10 text-italia-blue">
                    <span x-text="type"></span>
                    <button @click="toggleType(type)" class="hover:text-italia-blue-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </span>
            </template>
        </div>
    </div>
</div>
