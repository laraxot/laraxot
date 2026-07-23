{{-- Bootstrap Italia Popover Component --}}
{{-- 
    Componente popover conforme alle specifiche Bootstrap Italia
    Supporta header, body, posizionamento e accessibilità WCAG 2.1
--}}
@props([
    'title' => null,
    'position' => 'top', // 'top', 'bottom', 'left', 'right'
    'trigger' => 'click', // 'click', 'hover', 'focus'
    'dismissible' => true,
    'theme' => 'light', // 'light', 'dark'
    'size' => 'md', // 'sm', 'md', 'lg'
    'width' => null, // Larghezza personalizzata
    'disabled' => false,
])

@php
$popoverId = 'popover-' . uniqid();

// Classi per posizione
$positionClasses = [
    'top' => 'bottom-full left-1/2 transform -translate-x-1/2 mb-3',
    'bottom' => 'top-full left-1/2 transform -translate-x-1/2 mt-3',
    'left' => 'right-full top-1/2 transform -translate-y-1/2 mr-3',
    'right' => 'left-full top-1/2 transform -translate-y-1/2 ml-3',
];

// Classi per la freccia
$arrowClasses = [
    'top' => 'top-full left-1/2 transform -translate-x-1/2 border-l-transparent border-r-transparent border-b-transparent',
    'bottom' => 'bottom-full left-1/2 transform -translate-x-1/2 border-l-transparent border-r-transparent border-t-transparent',
    'left' => 'left-full top-1/2 transform -translate-y-1/2 border-t-transparent border-b-transparent border-r-transparent',
    'right' => 'right-full top-1/2 transform -translate-y-1/2 border-t-transparent border-b-transparent border-l-transparent',
];

// Classi per tema
$themeClasses = [
    'light' => [
        'popover' => 'bg-white text-gray-900 ring-1 ring-black ring-opacity-5',
        'arrow' => 'border-white',
        'header' => 'bg-gray-50 border-b border-gray-200',
        'close' => 'text-gray-400 hover:text-gray-600'
    ],
    'dark' => [
        'popover' => 'bg-gray-800 text-white ring-1 ring-white ring-opacity-20',
        'arrow' => 'border-gray-800',
        'header' => 'bg-gray-700 border-b border-gray-600',
        'close' => 'text-gray-300 hover:text-white'
    ]
];

// Classi per dimensione
$sizeClasses = [
    'sm' => 'max-w-xs',
    'md' => 'max-w-sm',
    'lg' => 'max-w-md'
];

$popoverClasses = [
    'absolute',
    'z-50',
    'rounded-lg',
    'shadow-lg',
    $positionClasses[$position],
    $themeClasses[$theme]['popover'],
    $width ? '' : $sizeClasses[$size]
];

if ($width) {
    $popoverClasses[] = $width;
}
@endphp

<div class="relative inline-block" 
     x-data="{
         show: false,
         toggle() {
             this.show = !this.show;
         },
         open() {
             this.show = true;
         },
         close() {
             this.show = false;
         }
     }"
     @if(!$disabled)
         @if($trigger === 'click')
             @click="toggle()"
             @if($dismissible) @click.away="close()" @endif
         @elseif($trigger === 'hover')
             @mouseenter="open()"
             @mouseleave="close()"
         @elseif($trigger === 'focus')
             @focusin="open()"
             @focusout="close()"
         @endif
     @endif
     @keydown.escape="close()"
>
    {{-- Trigger element --}}
    <div 
        @if($trigger === 'click' || $trigger === 'focus') 
            tabindex="0"
            role="button"
        @endif
        aria-expanded="false"
        x-bind:aria-expanded="show"
        aria-haspopup="true"
        :aria-controls="$id('{{ $popoverId }}')"
        @if($disabled) 
            aria-disabled="true" 
        @endif
        class="cursor-pointer"
    >
        {{ $trigger }}
    </div>
    
    @if(!$disabled)
        {{-- Popover --}}
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            @class($popoverClasses)
            x-bind:id="$id('{{ $popoverId }}')"
            role="dialog"
            aria-modal="false"
            x-trap="show"
        >
            @if($title || $dismissible)
                {{-- Header --}}
                <div @class([
                    'flex',
                    'items-center',
                    'justify-between',
                    'px-4',
                    'py-3',
                    'rounded-t-lg',
                    $themeClasses[$theme]['header']
                ])>
                    @if($title)
                        <h3 class="text-lg font-medium" id="{{ $popoverId }}-title">
                            {{ $title }}
                        </h3>
                    @endif
                    
                    @if($dismissible)
                        <button
                            type="button"
                            @click="close()"
                            @class([
                                'ml-auto',
                                'inline-flex',
                                'items-center',
                                'justify-center',
                                'w-8',
                                'h-8',
                                'rounded-md',
                                'hover:bg-gray-200',
                                'focus:outline-none',
                                'focus:ring-2',
                                'focus:ring-italia-blue-500',
                                'transition-colors',
                                $themeClasses[$theme]['close']
                            ])
                            aria-label="Chiudi popover"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @endif
                </div>
            @endif
            
            {{-- Body --}}
            <div @class([
                'px-4',
                'py-3',
                $title || $dismissible ? '' : 'rounded-t-lg',
                'rounded-b-lg'
            ])>
                {{ $slot }}
            </div>
            
            {{-- Arrow --}}
            <div @class([
                'absolute',
                'w-0',
                'h-0',
                'border-8',
                $arrowClasses[$position],
                $themeClasses[$theme]['arrow']
            ])></div>
        </div>
    @endif
</div>

{{-- 
Utilizzo:

<!-- Popover semplice -->
<x-popover title="Informazioni">
    <x-slot name="trigger">
        <button class="btn-primary">Mostra informazioni</button>
    </x-slot>
    
    Questo è il contenuto del popover con informazioni dettagliate.
</x-popover>

<!-- Popover senza header -->
<x-popover>
    <x-slot name="trigger">
        <span class="text-blue-600 cursor-pointer">?</span>
    </x-slot>
    
    Contenuto del popover senza titolo.
</x-popover>

<!-- Popover con posizionamento -->
<x-popover title="Menu azioni" position="right" size="lg">
    <x-slot name="trigger">
        <button class="btn-outline">Azioni</button>
    </x-slot>
    
    <div class="space-y-2">
        <button class="w-full text-left p-2 hover:bg-gray-100 rounded">Modifica</button>
        <button class="w-full text-left p-2 hover:bg-gray-100 rounded">Duplica</button>
        <button class="w-full text-left p-2 hover:bg-gray-100 rounded text-red-600">Elimina</button>
    </div>
</x-popover>

<!-- Popover con hover trigger -->
<x-popover title="Dettagli utente" trigger="hover" theme="dark">
    <x-slot name="trigger">
        <img src="/avatar.jpg" alt="Avatar" class="w-8 h-8 rounded-full">
    </x-slot>
    
    <div class="text-sm">
        <p><strong>Mario Rossi</strong></p>
        <p>Sviluppatore Frontend</p>
        <p class="text-gray-300">mario.rossi@example.com</p>
    </div>
</x-popover>

<!-- Popover non dismissibile -->
<x-popover title="Importante" :dismissible="false">
    <x-slot name="trigger">
        <button class="btn-warning">Attenzione</button>
    </x-slot>
    
    <p>Questo popover rimane aperto fino a quando non si clicca altrove.</p>
    <button @click="$parent.close()" class="mt-2 btn-sm btn-primary">Chiudi</button>
</x-popover>

<!-- Popover con larghezza personalizzata -->
<x-popover title="Form rapido" width="w-80">
    <x-slot name="trigger">
        <button class="btn-secondary">Compila form</button>
    </x-slot>
    
    <form class="space-y-3">
        <div>
            <label class="block text-sm font-medium mb-1">Nome</label>
            <input type="text" class="w-full px-3 py-2 border rounded-md">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" class="w-full px-3 py-2 border rounded-md">
        </div>
        <button type="submit" class="w-full btn-primary">Invia</button>
    </form>
</x-popover>
--}}