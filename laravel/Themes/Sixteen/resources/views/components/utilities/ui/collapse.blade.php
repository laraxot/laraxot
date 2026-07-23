{{-- Bootstrap Italia Collapse Component --}}
{{-- 
    Componente collapse per contenuto espandibile conforme a Bootstrap Italia
    Supporta animazioni, trigger multipli e accessibilità WCAG 2.1
--}}
@props([
    'id' => null,
    'trigger' => null, // Testo del trigger button
    'triggerIcon' => 'heroicon-o-chevron-down',
    'variant' => 'default', // 'default', 'card', 'minimal'
    'size' => 'md', // 'sm', 'md', 'lg'
    'expanded' => false,
    'disabled' => false,
    'headerClass' => null,
    'bodyClass' => null,
])

@php
$collapseId = $id ?? 'collapse-' . uniqid();
$triggerId = $collapseId . '-trigger';

// Classi per varianti
$variantClasses = [
    'default' => [
        'container' => 'border border-gray-200 rounded-lg',
        'header' => 'px-4 py-3 bg-gray-50 border-b border-gray-200 rounded-t-lg',
        'body' => 'px-4 py-3',
        'trigger' => 'text-left font-medium text-gray-900 hover:text-italia-blue-600'
    ],
    'card' => [
        'container' => 'bg-white shadow rounded-lg overflow-hidden',
        'header' => 'px-6 py-4 bg-white border-b border-gray-200',
        'body' => 'px-6 py-4',
        'trigger' => 'text-left font-semibold text-gray-900 hover:text-italia-blue-600'
    ],
    'minimal' => [
        'container' => '',
        'header' => 'py-2',
        'body' => 'pt-2',
        'trigger' => 'text-left font-medium text-gray-700 hover:text-italia-blue-600'
    ]
];

// Classi per dimensioni
$sizeClasses = [
    'sm' => [
        'header' => 'text-sm',
        'body' => 'text-sm',
        'icon' => 'w-4 h-4'
    ],
    'md' => [
        'header' => 'text-base',
        'body' => 'text-sm',
        'icon' => 'w-5 h-5'
    ],
    'lg' => [
        'header' => 'text-lg',
        'body' => 'text-base',
        'icon' => 'w-6 h-6'
    ]
];
@endphp

<div 
    @class([
        $variantClasses[$variant]['container']
    ])
    x-data="{ 
        expanded: {{ $expanded ? 'true' : 'false' }},
        toggle() {
            this.expanded = !this.expanded;
            this.$dispatch('collapse-toggled', { 
                id: '{{ $collapseId }}', 
                expanded: this.expanded 
            });
        }
    }"
>
    @if($trigger)
        {{-- Trigger Button --}}
        <div @class([
            $variantClasses[$variant]['header'],
            $headerClass
        ])>
            <button
                type="button"
                @click="toggle()"
                @keydown.enter="toggle()"
                @keydown.space.prevent="toggle()"
                :aria-expanded="expanded"
                aria-controls="{{ $collapseId }}"
                id="{{ $triggerId }}"
                @class([
                    'flex',
                    'items-center',
                    'justify-between',
                    'w-full',
                    'focus:outline-none',
                    'focus:ring-2',
                    'focus:ring-italia-blue-500',
                    'focus:ring-offset-2',
                    'rounded',
                    'transition-colors',
                    'duration-200',
                    $variantClasses[$variant]['trigger'],
                    $sizeClasses[$size]['header'],
                    $disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                ])
                @if($disabled) disabled @endif
            >
                <span>{{ $trigger }}</span>
                
                @if($triggerIcon)
                    <x-filament::icon 
                        :name="$triggerIcon" 
                        @class([
                            'transform',
                            'transition-transform',
                            'duration-200',
                            'text-gray-500',
                            $sizeClasses[$size]['icon']
                        ])
                        x-bind:class="{ 'rotate-180': expanded }"
                        aria-hidden="true"
                    />
                @endif
            </button>
        </div>
    @endif
    
    {{-- Collapsible Content --}}
    <div
        x-show="expanded"
        x-collapse
        id="{{ $collapseId }}"
        role="region"
        @if($trigger) aria-labelledby="{{ $triggerId }}" @endif
        @class([
            $variantClasses[$variant]['body'],
            $sizeClasses[$size]['body'],
            $bodyClass
        ])
    >
        {{ $slot }}
    </div>
</div>

{{-- 
Utilizzo:

<!-- Collapse semplice con trigger -->
<x-collapse trigger="Mostra dettagli">
    <p>Questo contenuto è nascosto di default e può essere espanso cliccando sul trigger.</p>
    <p>Supporta qualsiasi tipo di contenuto HTML.</p>
</x-collapse>

<!-- Collapse con variante card -->
<x-collapse 
    trigger="Informazioni account" 
    variant="card"
    size="lg"
>
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Nome utente</label>
            <p class="mt-1 text-sm text-gray-900">mario.rossi@example.com</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Ultimo accesso</label>
            <p class="mt-1 text-sm text-gray-900">15 settembre 2025, 14:30</p>
        </div>
    </div>
</x-collapse>

<!-- Collapse minimale -->
<x-collapse 
    trigger="FAQ: Come posso recuperare la password?" 
    variant="minimal"
    trigger-icon="heroicon-o-plus"
>
    <div class="prose prose-sm">
        <p>Per recuperare la password segui questi passaggi:</p>
        <ol>
            <li>Vai alla pagina di login</li>
            <li>Clicca su "Password dimenticata?"</li>
            <li>Inserisci il tuo indirizzo email</li>
            <li>Controlla la tua casella di posta</li>
        </ol>
    </div>
</x-collapse>

<!-- Collapse aperto di default -->
<x-collapse 
    trigger="Impostazioni avanzate"
    expanded
    variant="default"
>
    <form class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Notifiche email
            </label>
            <select class="mt-1 block w-full border-gray-300 rounded-md">
                <option>Tutte</option>
                <option>Solo importanti</option>
                <option>Nessuna</option>
            </select>
        </div>
        
        <div class="flex items-center">
            <input type="checkbox" id="two-factor" class="mr-2">
            <label for="two-factor" class="text-sm text-gray-700">
                Abilita autenticazione a due fattori
            </label>
        </div>
    </form>
</x-collapse>

<!-- Collapse senza trigger (controllo esterno) -->
<div x-data="{ showDetails: false }">
    <button @click="showDetails = !showDetails" class="btn-primary mb-4">
        Toggle Details
    </button>
    
    <x-collapse x-bind:expanded="showDetails" variant="card">
        <p>Questo collapse è controllato da un pulsante esterno.</p>
        <p>Utile quando il trigger è separato dal contenuto.</p>
    </x-collapse>
</div>

<!-- Collapse con evento personalizzato -->
<x-collapse 
    trigger="Statistiche utilizzo"
    x-on:collapse-toggled="console.log('Collapse toggled:', $event.detail)"
>
    <div class="grid grid-cols-2 gap-4">
        <div class="text-center">
            <div class="text-2xl font-bold text-italia-blue-600">1,234</div>
            <div class="text-sm text-gray-500">Visualizzazioni</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-italia-green-600">98%</div>
            <div class="text-sm text-gray-500">Uptime</div>
        </div>
    </div>
</x-collapse>

<!-- Gruppo di collapse (accordion-like) -->
<div class="space-y-2">
    <x-collapse 
        trigger="Sezione 1" 
        variant="default"
        id="section-1"
    >
        Contenuto della prima sezione.
    </x-collapse>
    
    <x-collapse 
        trigger="Sezione 2" 
        variant="default"
        id="section-2"
    >
        Contenuto della seconda sezione.
    </x-collapse>
    
    <x-collapse 
        trigger="Sezione 3" 
        variant="default"
        id="section-3"
    >
        Contenuto della terza sezione.
    </x-collapse>
</div>
--}}