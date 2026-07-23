{{-- Bootstrap Italia Callout Component --}}
{{-- 
    Componente callout per informazioni evidenziate conforme a Bootstrap Italia
    Supporta diversi tipi, icone e dismissibilità
--}}
@props([
    'type' => 'info', // 'info', 'success', 'warning', 'danger', 'note'
    'title' => null,
    'icon' => null,
    'dismissible' => false,
    'size' => 'md', // 'sm', 'md', 'lg'
    'variant' => 'default', // 'default', 'outline', 'minimal'
])

@php
// Icone di default per tipo
$defaultIcons = [
    'info' => 'heroicon-o-information-circle',
    'success' => 'heroicon-o-check-circle',
    'warning' => 'heroicon-o-exclamation-triangle',
    'danger' => 'heroicon-o-x-circle',
    'note' => 'heroicon-o-light-bulb'
];

$icon = $icon ?: $defaultIcons[$type] ?? null;

// Classi per tipo
$typeClasses = [
    'info' => [
        'default' => 'bg-italia-blue-50 border-italia-blue-200 text-italia-blue-900',
        'outline' => 'border-italia-blue-300 text-italia-blue-700 bg-white',
        'minimal' => 'border-l-italia-blue-500 text-italia-blue-900 bg-italia-blue-50/30',
        'icon' => 'text-italia-blue-500',
        'title' => 'text-italia-blue-900',
        'dismiss' => 'text-italia-blue-400 hover:text-italia-blue-600'
    ],
    'success' => [
        'default' => 'bg-italia-green-50 border-italia-green-200 text-italia-green-900',
        'outline' => 'border-italia-green-300 text-italia-green-700 bg-white',
        'minimal' => 'border-l-italia-green-500 text-italia-green-900 bg-italia-green-50/30',
        'icon' => 'text-italia-green-500',
        'title' => 'text-italia-green-900',
        'dismiss' => 'text-italia-green-400 hover:text-italia-green-600'
    ],
    'warning' => [
        'default' => 'bg-italia-yellow-50 border-italia-yellow-200 text-italia-yellow-900',
        'outline' => 'border-italia-yellow-300 text-italia-yellow-700 bg-white',
        'minimal' => 'border-l-italia-yellow-500 text-italia-yellow-900 bg-italia-yellow-50/30',
        'icon' => 'text-italia-yellow-500',
        'title' => 'text-italia-yellow-900',
        'dismiss' => 'text-italia-yellow-400 hover:text-italia-yellow-600'
    ],
    'danger' => [
        'default' => 'bg-italia-red-50 border-italia-red-200 text-italia-red-900',
        'outline' => 'border-italia-red-300 text-italia-red-700 bg-white',
        'minimal' => 'border-l-italia-red-500 text-italia-red-900 bg-italia-red-50/30',
        'icon' => 'text-italia-red-500',
        'title' => 'text-italia-red-900',
        'dismiss' => 'text-italia-red-400 hover:text-italia-red-600'
    ],
    'note' => [
        'default' => 'bg-gray-50 border-gray-200 text-gray-900',
        'outline' => 'border-gray-300 text-gray-700 bg-white',
        'minimal' => 'border-l-gray-500 text-gray-900 bg-gray-50/30',
        'icon' => 'text-gray-500',
        'title' => 'text-gray-900',
        'dismiss' => 'text-gray-400 hover:text-gray-600'
    ]
];

// Classi per dimensione
$sizeClasses = [
    'sm' => [
        'padding' => 'p-3',
        'icon' => 'w-4 h-4',
        'title' => 'text-sm font-medium',
        'content' => 'text-sm',
        'dismiss' => 'w-6 h-6'
    ],
    'md' => [
        'padding' => 'p-4',
        'icon' => 'w-5 h-5',
        'title' => 'text-base font-semibold',
        'content' => 'text-sm',
        'dismiss' => 'w-8 h-8'
    ],
    'lg' => [
        'padding' => 'p-6',
        'icon' => 'w-6 h-6',
        'title' => 'text-lg font-semibold',
        'content' => 'text-base',
        'dismiss' => 'w-10 h-10'
    ]
];

// Classi base
$baseClasses = [
    'rounded-lg',
    'border',
    $sizeClasses[$size]['padding']
];

if ($variant === 'minimal') {
    $baseClasses[] = 'border-l-4';
    $baseClasses[] = 'border-t-0';
    $baseClasses[] = 'border-r-0';
    $baseClasses[] = 'border-b-0';
} else {
    $baseClasses[] = 'border';
}

$containerClasses = array_merge(
    $baseClasses,
    [$typeClasses[$type][$variant]]
);

$calloutId = 'callout-' . uniqid();
@endphp

<div 
    x-data="{ show: true }"
    x-show="show"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-95"
    @class($containerClasses)
    role="alert"
    aria-live="polite"
    id="{{ $calloutId }}"
>
    <div class="flex items-start {{ $dismissible ? 'justify-between' : '' }}">
        <div class="flex items-start space-x-3 flex-1">
            @if($icon)
                <div class="flex-shrink-0 {{ $sizeClasses[$size]['icon'] === 'w-4 h-4' ? 'mt-0.5' : 'mt-1' }}">
                    <x-filament::icon 
                        :name="$icon" 
                        @class([
                            $sizeClasses[$size]['icon'],
                            $typeClasses[$type]['icon']
                        ])
                        aria-hidden="true"
                    />
                </div>
            @endif
            
            <div class="flex-1 min-w-0">
                @if($title)
                    <h3 @class([
                        'mb-2',
                        $sizeClasses[$size]['title'],
                        $typeClasses[$type]['title']
                    ])>
                        {{ $title }}
                    </h3>
                @endif
                
                <div @class([
                    $sizeClasses[$size]['content'],
                    $title ? '' : 'mt-0'
                ])>
                    {{ $slot }}
                </div>
            </div>
        </div>
        
        @if($dismissible)
            <div class="flex-shrink-0 {{ $icon ? '' : 'ml-3' }}">
                <button
                    type="button"
                    @click="show = false"
                    @class([
                        'inline-flex',
                        'rounded-md',
                        'focus:outline-none',
                        'focus:ring-2',
                        'focus:ring-offset-2',
                        'focus:ring-italia-blue-500',
                        'transition-colors',
                        'duration-200',
                        $typeClasses[$type]['dismiss']
                    ])
                    aria-label="Chiudi callout"
                    :aria-controls="$id('{{ $calloutId }}')"
                >
                    <span class="sr-only">Chiudi</span>
                    <svg @class([$sizeClasses[$size]['dismiss']]) fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif
    </div>
</div>

{{-- 
Utilizzo:

<!-- Callout informativo semplice -->
<x-callout type="info">
    Questa è un'informazione importante per gli utenti.
</x-callout>

<!-- Callout con titolo e icona personalizzata -->
<x-callout 
    type="success" 
    title="Operazione completata"
    icon="heroicon-o-check-badge"
>
    La tua richiesta è stata elaborata con successo. Riceverai una email di conferma a breve.
</x-callout>

<!-- Callout di avvertimento dismissibile -->
<x-callout 
    type="warning"
    title="Attenzione"
    dismissible
    size="lg"
>
    <p>Il servizio sarà temporaneamente non disponibile per manutenzione dalle 14:00 alle 16:00.</p>
    <p class="mt-2">
        <a href="/info-manutenzione" class="font-medium underline">
            Maggiori informazioni &rarr;
        </a>
    </p>
</x-callout>

<!-- Callout con variante outline -->
<x-callout 
    type="danger"
    title="Errore di validazione"
    variant="outline"
>
    <ul class="list-disc list-inside space-y-1">
        <li>Il campo email è obbligatorio</li>
        <li>La password deve contenere almeno 8 caratteri</li>
    </ul>
</x-callout>

<!-- Callout minimale -->
<x-callout 
    type="note"
    variant="minimal"
    size="sm"
>
    <strong>Nota:</strong> I documenti caricati devono essere in formato PDF e non superare i 10MB.
</x-callout>

<!-- Callout senza icona -->
<x-callout 
    type="info"
    title="Informazioni di servizio"
    :icon="false"
>
    Per questioni urgenti contatta il numero verde 800.123.456 attivo dal lunedì al venerdì dalle 9:00 alle 18:00.
</x-callout>

<!-- Callout con contenuto complesso -->
<x-callout 
    type="success"
    title="Registrazione completata"
    size="lg"
>
    <div class="space-y-3">
        <p>Il tuo account è stato creato con successo. Ora puoi accedere ai seguenti servizi:</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div class="p-3 bg-white rounded-md">
                <h4 class="font-medium">Servizi Online</h4>
                <p class="text-sm text-gray-600">Certificati, pagamenti, pratiche</p>
            </div>
            <div class="p-3 bg-white rounded-md">
                <h4 class="font-medium">Area Personale</h4>
                <p class="text-sm text-gray-600">Gestione dati e preferenze</p>
            </div>
        </div>
        
        <div class="pt-3">
            <button class="btn-primary">Accedi ai servizi</button>
        </div>
    </div>
</x-callout>
--}}