{{--
Component: CIE Button
Description: Carta Identità Elettronica authentication button
Documentation: https://www.agid.gov.it/it/piattaforme/cie
--}}

@props([
    'size' => 'md',
    'variant' => 'primary',
    'disabled' => false,
    'loading' => false,
    'label' => 'Entra con CIE',
    'icon' => true,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg',
        default => 'px-4 py-2 text-base'
    };
    
    $variantClasses = match($variant) {
        'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
        'secondary' => 'bg-gray-200 text-gray-900 hover:bg-gray-300 focus:ring-gray-500',
        'outline' => 'border border-blue-600 text-blue-600 hover:bg-blue-50 focus:ring-blue-500',
        default => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500'
    };
    
    $loadingClasses = $loading ? 'opacity-70 cursor-wait' : '';
    $disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed' : '';
    
    $classes = implode(' ', [$baseClasses, $sizeClasses, $variantClasses, $loadingClasses, $disabledClasses]);
@endphp

<button 
    {{ $attributes->merge(['class' => $classes]) }}
    type="button"
    @disabled($disabled || $loading)
    aria-label="Accedi con Carta Identità Elettronica"
    data-testid="cie-button"
>
    @if($icon)
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2V7zm0 8h2v2h-2v-2z"/>
        </svg>
    @endif
    
    @if($loading)
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Caricamento...
    @else
        {{ $label }}
    @endif
</button>