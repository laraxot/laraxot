{{-- Bootstrap Italia Dropdown Item Component --}}
{{-- 
    Dropdown item component per menu dropdown
    Supporta link, bottoni, icone e stati
--}}
@props([
    'href' => null,
    'icon' => null,
    'variant' => 'default', // 'default', 'danger'
    'disabled' => false,
    'active' => false,
    'target' => null,
])

@php
// Classi base per dropdown item
$baseClasses = [
    'group',
    'flex',
    'items-center',
    'w-full',
    'px-4',
    'py-2',
    'text-sm',
    'font-medium',
    'transition-colors',
    'duration-200',
    'focus:outline-none',
    'focus:ring-2',
    'focus:ring-inset',
    'focus:ring-italia-blue-500',
];

// Classi per varianti
$variantClasses = [
    'default' => [
        'text-gray-700',
        'hover:bg-gray-50',
        'hover:text-gray-900',
    ],
    'danger' => [
        'text-italia-red-600',
        'hover:bg-italia-red-50',
        'hover:text-italia-red-700',
    ],
];

// Classi per stato attivo
$activeClasses = $active ? [
    'bg-gray-100',
    'text-gray-900',
] : [];

// Classi per stato disabilitato
$disabledClasses = $disabled ? [
    'opacity-50',
    'cursor-not-allowed',
    'pointer-events-none',
] : [];

$classes = array_merge(
    $baseClasses,
    $variantClasses[$variant],
    $activeClasses,
    $disabledClasses
);
@endphp

@if($href && !$disabled)
    {{-- Link --}}
    <a
        href="{{ $href }}"
        @if($target) target="{{ $target }}" @endif
        @class($classes)
        role="menuitem"
        @if($active) aria-current="true" @endif
        @if($disabled) aria-disabled="true" tabindex="-1" @endif
    >
        @if($icon)
            <x-filament::icon 
                :name="$icon" 
                @class([
                    'w-4',
                    'h-4',
                    'mr-3',
                    'text-gray-400' => $variant === 'default',
                    'text-italia-red-400' => $variant === 'danger',
                    'group-hover:text-gray-500' => $variant === 'default',
                    'group-hover:text-italia-red-500' => $variant === 'danger',
                ])
                aria-hidden="true"
            />
        @endif
        {{ $slot }}
    </a>
@else
    {{-- Button --}}
    <button
        type="button"
        @class($classes)
        role="menuitem"
        @if($active) aria-current="true" @endif
        @if($disabled) disabled aria-disabled="true" tabindex="-1" @endif
    >
        @if($icon)
            <x-filament::icon 
                :name="$icon" 
                @class([
                    'w-4',
                    'h-4',
                    'mr-3',
                    'text-gray-400' => $variant === 'default',
                    'text-italia-red-400' => $variant === 'danger',
                    'group-hover:text-gray-500' => $variant === 'default',
                    'group-hover:text-italia-red-500' => $variant === 'danger',
                ])
                aria-hidden="true"
            />
        @endif
        {{ $slot }}
    </button>
@endif

{{-- 
Utilizzo:

<!-- Link semplice -->
<x-dropdown-item href="/profilo">
<x-dropdown-item href="/profilo">
    Il mio profilo
</x-dropdown-item>

<!-- Link con icona -->
<x-dropdown-item href="/settings" icon="heroicon-o-cog-6-tooth">
<x-dropdown-item href="/settings" icon="heroicon-o-cog-6-tooth">
    Impostazioni
</x-dropdown-item>

<!-- Button con azione JavaScript -->
<x-dropdown-item icon="heroicon-o-arrow-right-on-rectangle" variant="danger">
<x-dropdown-item icon="heroicon-o-arrow-right-on-rectangle" variant="danger">
    Logout
</x-dropdown-item>

<!-- Item disabilitato -->
<x-dropdown-item disabled>
<x-dropdown-item disabled>
    Funzione non disponibile
</x-dropdown-item>

<!-- Item attivo -->
<x-dropdown-item href="/dashboard" active>
<x-dropdown-item href="/dashboard" active>
    Dashboard
</x-dropdown-item>

<!-- Link esterno -->
<x-dropdown-item href="https://example.com" target="_blank" icon="heroicon-o-arrow-top-right-on-square">
<x-dropdown-item href="https://example.com" target="_blank" icon="heroicon-o-arrow-top-right-on-square">
    Link esterno
</x-dropdown-item>
--}}
