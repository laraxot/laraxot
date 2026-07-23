{{--
    Mobile Filters Modal
    Alpine.js + TailwindCSS + DaisyUI
    No Bootstrap modal
--}}

@php
    $items = $filters['items'] ?? [];
    $legend = $filters['title'] ?? __('fixcity::ticket.filters.legend.label');
@endphp

{{-- Backdrop --}}
<div x-show="mobileFiltersOpen"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black/50 z-40 lg:hidden"
     @click="mobileFiltersOpen = false"
     aria-hidden="true">
</div>

{{-- Modal Panel --}}
<div x-show="mobileFiltersOpen"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-full"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-full"
     class="fixed bottom-0 left-0 right-0 bg-white rounded-t-2xl shadow-2xl z-50 lg:hidden max-h-[80vh] overflow-hidden"
     role="dialog"
     aria-modal="true"
     aria-labelledby="mobile-filters-title">
    
    {{-- Header --}}
    <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <h3 id="mobile-filters-title" class="text-lg font-semibold text-gray-900">
            {{ $legend }}
        </h3>
        <button @click="mobileFiltersOpen = false" 
                class="p-2 rounded-full hover:bg-gray-100 transition-colors"
                aria-label="{{ __('pub_theme::accessibility.close') }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    {{-- Filters List --}}
    <div class="p-4 overflow-y-auto" style="max-height: calc(80vh - 140px)">
        <div class="space-y-3">
            @foreach ($items as $item)
                <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                    <input type="checkbox" 
                           :value="'{{ $item['value'] }}'"
                           :checked="isTypeSelected('{{ $item['value'] }}')"
                           @change="toggleType('{{ $item['value'] }}')"
                           class="checkbox checkbox-primary"
                           style="--chkbg: {{ $item['color'] ?? '#007A52' }}; --chkfg: white;">
                    
                    <div class="flex-1">
                        <span class="block text-sm font-medium text-gray-900">{{ $item['label'] }}</span>
                    </div>
                    
                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                        {{ $item['count'] }}
                    </span>
                </label>
            @endforeach
        </div>
    </div>
    
    {{-- Footer Actions --}}
    <div class="p-4 border-t border-gray-200 bg-gray-50 flex gap-3">
        <button @click="selectedTypes = []" 
                x-show="selectedTypes.length > 0"
                x-transition
                class="flex-1 btn btn-ghost btn-sm">
            {{ __('fixcity::ticket.filters.reset') }}
        </button>
        <button @click="mobileFiltersOpen = false" 
                class="flex-1 btn btn-primary btn-sm">
            {{ __('fixcity::ticket.filters.apply') }}
            <span x-show="selectedTypes.length > 0" 
                  x-text="'(' + selectedTypes.length + ')'"
                  class="ml-1"></span>
        </button>
    </div>
</div>
