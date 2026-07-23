{{-- Componente riutilizzabile per i pulsanti di azione dei predict --}}
@props([
    'predict',
    'showBetButton' => true
])

<div class="flex items-center space-x-1">
    {{-- Pulsante visualizza --}}
    <a href="{{ route('predict.view', ['lang' => app()->getLocale(), 'slug' => $predict->slug]) }}" 
       class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded hover:from-blue-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-105">
        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>
        {{ __('predict::common.view') }}
    </a>

    {{-- Pulsante scommetti (se applicabile) --}}
    @if($showBetButton && $predict->status === 'open' && $predict->is_bettable)
        <a href="#" class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-gradient-to-r from-green-600 to-emerald-600 rounded hover:from-green-700 hover:to-emerald-700 transition-all duration-200 transform hover:scale-105">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
            {{ __('predict::common.bet') }}
        </a>
    @endif
</div> 