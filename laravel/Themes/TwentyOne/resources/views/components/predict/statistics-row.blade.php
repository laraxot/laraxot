{{-- Componente riutilizzabile per le statistiche dei predict --}}
@props([
    'betsCount' => 0,
    'totalCredits' => 0
])

<div class="flex items-center justify-between mt-2">
    {{-- Numero di giocatori --}}
    <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
        <svg class="w-3 h-3 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
        </svg>
        <span class="font-medium">{{ number_format($betsCount) }}</span>
        <span class="ml-1">{{ __('predict::common.bets_count.label') }}</span>
    </div>

    {{-- Crediti totali giocati --}}
    <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
        <svg class="w-3 h-3 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
        </svg>
        <span class="font-medium">{{ number_format($totalCredits) }}</span>
        <span class="ml-1">{{ __('predict::common.credits.label') }}</span>
    </div>
</div> 