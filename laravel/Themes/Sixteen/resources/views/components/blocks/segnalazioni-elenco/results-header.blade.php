{{--
    Results Header
    Shows count and mobile filter toggle
    TailwindCSS + Alpine.js
--}}

@php
    $count = $resultsCount ?? 0;
@endphp

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    
    {{-- Results Count --}}
    <div class="flex items-center gap-2">
        <span class="text-2xl font-bold text-gray-900">{{ number_format($count) }}</span>
        <span class="text-gray-600">{{ __('fixcity::ticket.results.count_label') }}</span>
    </div>
    
    {{-- Mobile Filter Button --}}
    <button @click="mobileFiltersOpen = true"
            class="lg:hidden btn btn-outline btn-sm inline-flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
        </svg>
        {{ __('fixcity::ticket.filters.open') }}
        <span x-show="selectedTypes.length > 0" 
              x-text="selectedTypes.length"
              class="ml-1 px-1.5 py-0.5 rounded-full text-xs bg-italia-blue text-white"></span>
    </button>
    
</div>
