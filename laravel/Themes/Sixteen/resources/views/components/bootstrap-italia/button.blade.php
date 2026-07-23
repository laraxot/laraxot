{{-- Bootstrap Italia Button Component --}}
@props([
    'variant' => 'primary', // primary, secondary, success, danger, warning, outline-primary
    'size' => 'md', // sm, md, lg
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
    'href' => null,
    'target' => null,
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'fullWidth' => false
])

@php
$baseClasses = 'btn-italia';

$variantClasses = match($variant) {
    'secondary' => 'btn-secondary-italia',
    'success' => 'btn-success-italia',
    'danger' => 'btn-danger-italia',
    'warning' => 'btn-warning-italia',
    'outline-primary' => 'btn-outline-primary-italia',
    default => 'btn-primary-italia',
};

$sizeClasses = match($size) {
    'sm' => 'px-3 py-1.5 text-xs',
    'lg' => 'px-6 py-3 text-base',
    default => 'px-4 py-2 text-sm',
};

$classes = $baseClasses . ' ' . $variantClasses . ' ' . $sizeClasses;

if ($fullWidth) {
    $classes .= ' w-full';
}

if ($disabled || $loading) {
    $classes .= ' opacity-50 cursor-not-allowed';
}

$tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }} 
    @if($href) href="{{ $href }}" @endif
    @if($target) target="{{ $target }}" @endif
    @if(!$href) type="{{ $type }}" @endif
    @if($disabled) disabled @endif
    {{ $attributes->merge(['class' => $classes]) }}
    @if($loading) aria-busy="true" @endif>
    
    @if($loading)
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Caricamento...
    @else
        @if($icon && $iconPosition === 'left')
            <span class="mr-2">{!! $icon !!}</span>
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right')
            <span class="ml-2">{!! $icon !!}</span>
        @endif
    @endif
</{{ $tag }}>