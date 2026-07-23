{{-- Bootstrap Italia Avatar Component --}}
{{-- 
    Componente avatar conforme alle specifiche Bootstrap Italia
    Supporta immagini, iniziali, icone e stati
--}}
@props([
    'src' => null,
    'alt' => null,
    'initials' => null,
    'icon' => null,
    'size' => 'md', // 'xs', 'sm', 'md', 'lg', 'xl', 'xxl'
    'variant' => 'default', // 'default', 'primary', 'secondary', 'success', 'warning', 'danger', 'info'
    'shape' => 'circle', // 'circle', 'square', 'rounded'
    'status' => null, // 'online', 'offline', 'away', 'busy'
    'badge' => null,
    'badgeVariant' => 'primary',
    'badgePosition' => 'bottom-right', // 'top-right', 'top-left', 'bottom-right', 'bottom-left'
    'clickable' => false,
    'href' => null,
    'class' => null,
])

@php
// Classi per dimensioni
$sizeClasses = [
    'xs' => 'w-6 h-6 text-xs',
    'sm' => 'w-8 h-8 text-sm',
    'md' => 'w-10 h-10 text-base',
    'lg' => 'w-12 h-12 text-lg',
    'xl' => 'w-16 h-16 text-xl',
    'xxl' => 'w-20 h-20 text-2xl',
];

// Classi per varianti
$variantClasses = [
    'default' => 'bg-gray-200 text-gray-700',
    'primary' => 'bg-italia-blue-500 text-white',
    'secondary' => 'bg-gray-500 text-white',
    'success' => 'bg-green-500 text-white',
    'warning' => 'bg-yellow-500 text-white',
    'danger' => 'bg-red-500 text-white',
    'info' => 'bg-blue-500 text-white',
];

// Classi per forme
$shapeClasses = [
    'circle' => 'rounded-full',
    'square' => 'rounded-none',
    'rounded' => 'rounded-md',
];

// Classi per stati
$statusClasses = [
    'online' => 'bg-green-500',
    'offline' => 'bg-gray-400',
    'away' => 'bg-yellow-500',
    'busy' => 'bg-red-500',
];

// Classi per posizione badge
$badgePositionClasses = [
    'top-right' => 'top-0 right-0',
    'top-left' => 'top-0 left-0',
    'bottom-right' => 'bottom-0 right-0',
    'bottom-left' => 'bottom-0 left-0',
];

$avatarClasses = [
    'inline-flex',
    'items-center',
    'justify-center',
    'font-medium',
    'overflow-hidden',
    'relative',
    $sizeClasses[$size],
    $variantClasses[$variant],
    $shapeClasses[$shape],
    $clickable ? 'cursor-pointer hover:opacity-80 transition-opacity' : '',
    $class
];
@endphp

@if($href || $clickable)
    <a 
        href="{{ $href ?? '#' }}" 
        @class($avatarClasses)
        @if($clickable && !$href) role="button" tabindex="0" @endif
        aria-label="{{ $alt ?? 'Avatar' }}"
    >
@else
    <div @class($avatarClasses) role="img" aria-label="{{ $alt ?? 'Avatar' }}">
@endif

    @if($src)
        {{-- Avatar con immagine --}}
        <img 
            src="{{ $src }}" 
            alt="{{ $alt ?? 'Avatar' }}"
            class="w-full h-full object-cover"
            loading="lazy"
        >
    @elseif($initials)
        {{-- Avatar con iniziali --}}
        <span class="font-semibold">{{ $initials }}</span>
    @elseif($icon)
        {{-- Avatar con icona --}}
        <x-filament::icon icon="heroicon-$icon" class="w-1/2 h-1/2" aria-hidden="true" />
    @else
        {{-- Avatar placeholder --}}
        <svg class="w-1/2 h-1/2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
        </svg>
    @endif

    @if($status)
        {{-- Indicatore di stato --}}
        <span 
            class="absolute w-3 h-3 border-2 border-white rounded-full {{ $badgePositionClasses['bottom-right'] }} {{ $statusClasses[$status] }}"
            aria-label="Stato: {{ $status }}"
        ></span>
    @endif

    @if($badge)
        {{-- Badge --}}
        <span 
            class="absolute inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-medium text-white rounded-full {{ $badgePositionClasses[$badgePosition] }} {{ $variantClasses[$badgeVariant] }}"
            aria-label="Badge: {{ $badge }}"
        >
            {{ $badge }}
        </span>
    @endif

@if($href || $clickable)
    </a>
@else
    </div>
@endif

{{-- 
Utilizzo:

<!-- Avatar con immagine -->
<x-avatar 
    src="/images/user.jpg" 
    alt="Mario Rossi"
    size="lg"
    status="online"
/>

<!-- Avatar con iniziali -->
<x-avatar 
    initials="MR"
    variant="primary"
    size="md"
    shape="circle"
/>

<!-- Avatar con icona -->
<x-avatar 
    icon="heroicon-o-user"
    variant="secondary"
    size="xl"
    shape="rounded"
/>

<!-- Avatar con badge -->
<x-avatar 
    src="/images/user.jpg"
    badge="3"
    badge-variant="danger"
    badge-position="top-right"
    size="lg"
/>

<!-- Avatar cliccabile -->
<x-avatar 
    src="/images/user.jpg"
    clickable
    size="md"
    class="hover:scale-105 transition-transform"
/>

<!-- Avatar con link -->
<x-avatar 
    src="/images/user.jpg"
    href="/profile"
    size="lg"
    status="away"
/>

<!-- Avatar placeholder -->
<x-avatar 
    size="md"
    variant="default"
/>

Dimensioni disponibili:
- xs: 24px (w-6 h-6)
- sm: 32px (w-8 h-8)  
- md: 40px (w-10 h-10) - default
- lg: 48px (w-12 h-12)
- xl: 64px (w-16 h-16)
- xxl: 80px (w-20 h-20)

Varianti disponibili:
- default: grigio
- primary: blu Italia
- secondary: grigio scuro
- success: verde
- warning: giallo
- danger: rosso
- info: blu

Forme disponibili:
- circle: rotondo (default)
- square: quadrato
- rounded: arrotondato

Stati disponibili:
- online: verde
- offline: grigio
- away: giallo
- busy: rosso

Posizioni badge:
- top-right: alto destra (default)
- top-left: alto sinistra
- bottom-right: basso destra
- bottom-left: basso sinistra

Accessibilità:
- Supporto screen reader
- ARIA labels appropriati
- Navigazione da tastiera
- Contrasto colori WCAG AA
--}}
