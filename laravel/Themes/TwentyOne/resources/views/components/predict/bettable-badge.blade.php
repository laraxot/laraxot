{{-- Componente riutilizzabile per il badge di scommettibilità --}}
@props([
    'isBettable' => false
])

@if($isBettable)
    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        {{ __('predict::common.bettable') }}
    </span>
@endif 