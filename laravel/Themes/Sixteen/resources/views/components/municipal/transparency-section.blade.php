{{--
/**
 * Sezione Trasparenza Amministrativa - Bootstrap Italia Compliant
 * 
 * Componente per la gestione della trasparenza amministrativa (D.Lgs. 33/2013)
 * Conforme agli obblighi di pubblicazione per PA italiana
 * 
 * @param string $title Titolo della sezione
 * @param string $description Descrizione della sezione
 * @param array $documents Array di documenti da pubblicare
 * @param string $icon Icona della sezione (optional)
 * @param string $variant Variante di stile (default, primary, warning)
 * @param bool $collapsible Se la sezione è espandibile
 * @param bool $initiallyOpen Se aperta inizialmente
 */
--}}

@props([
    'title' => 'Trasparenza Amministrativa',
    'description' => null,
    'documents' => [],
    'icon' => null,
    'variant' => 'default',
    'collapsible' => false,
    'initiallyOpen' => true
])

@php
$variants = [
    'default' => [
        'bg' => 'bg-white',
        'border' => 'border border-gray-200',
        'text' => 'text-gray-900',
        'icon' => 'text-blue-600'
    ],
    'primary' => [
        'bg' => 'bg-blue-50',
        'border' => 'border border-blue-200',
        'text' => 'text-blue-900',
        'icon' => 'text-blue-700'
    ],
    'warning' => [
        'bg' => 'bg-yellow-50',
        'border' => 'border border-yellow-200',
        'text' => 'text-yellow-900',
        'icon' => 'text-yellow-700'
    ]
];

$currentVariant = $variants[$variant] ?? $variants['default'];
@endphp

<div class="transparency-section {{ $currentVariant['bg'] }} {{ $currentVariant['border'] }} rounded-lg overflow-hidden mb-6"
     @if($collapsible)
     x-data="{ isOpen: {{ $initiallyOpen ? 'true' : 'false' }} }"
     @endif>
    
    {{-- Header --}}
    <div class="px-6 py-4 {{ $currentVariant['text'] }}">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                @if($icon)
                <div class="mr-3 {{ $currentVariant['icon'] }}">
                    {!! $icon !!}
                </div>
                @endif
                
                <div>
                    <h3 class="text-lg font-semibold {{ $currentVariant['text'] }}">
                        {{ $title }}
                    </h3>
                    
                    @if($description)
                    <p class="text-sm opacity-80 mt-1">
                        {{ $description }}
                    </p>
                    @endif
                </div>
            </div>
            
            @if($collapsible)
            <button @click="isOpen = !isOpen" 
                    class="p-1 rounded hover:bg-white hover:bg-opacity-20 transition-colors"
                    :aria-expanded="isOpen">
                <svg class="w-5 h-5 transform transition-transform" 
                     :class="{ 'rotate-180': isOpen }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            @endif
        </div>
    </div>

    {{-- Content --}}
    <div @if($collapsible) x-show="isOpen" x-transition @endif>
        <div class="px-6 pb-4">
            @if(count($documents) > 0)
            <div class="space-y-3">
                @foreach($documents as $document)
                <div class="flex items-center justify-between p-3 bg-white bg-opacity-50 rounded border">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">
                                {{ $document['title'] ?? 'Documento' }}
                            </h4>
                            
                            @if(isset($document['description']))
                            <p class="text-xs text-gray-600 mt-1">
                                {{ $document['description'] }}
                            </p>
                            @endif
                            
                            @if(isset($document['date']))
                            <p class="text-xs text-gray-500 mt-1">
                                Aggiornato: {{ $document['date'] }}
                            </p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        @if(isset($document['url']))
                        <a href="{{ $document['url'] }}" 
                           class="btn-italia btn-sm bg-blue-600 hover:bg-blue-700 text-white"
                           @if(isset($document['external']) && $document['external'])
                           target="_blank" rel="noopener noreferrer"
                           @endif>
                            Visualizza
                        </a>
                        @endif
                        
                        @if(isset($document['download_url']))
                        <a href="{{ $document['download_url'] }}" 
                           class="btn-italia btn-sm bg-gray-600 hover:bg-gray-700 text-white"
                           download>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 text-sm">
                    Nessun documento disponibile in questa sezione
                </p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Stili specifici --}}
@pushOnce('styles')
<style>
.transparency-section {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.transparency-section:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-italia.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.375rem;
}

@media (max-width: 768px) {
    .transparency-section {
        margin: 0 -1rem;
        border-radius: 0;
        border-left: none;
        border-right: none;
    }
    
    .transparency-section > div:first-child {
        padding: 1rem;
    }
    
    .transparency-section > div:last-child {
        padding: 0 1rem 1rem;
    }
}
</style>
@endPushOnce

{{--
Usage Examples:

1. Sezione base:
<x-pub_theme::municipal.transparency-section 
    title="Bilanci e Conti Consuntivi"
    description="Documenti contabili e finanziari del comune"
    :documents="[
        ['title' => 'Bilancio 2023', 'date' => '15/01/2023', 'url' => '/documenti/bilancio-2023'],
        ['title' => 'Conto Consuntivo 2022', 'date' => '20/03/2023', 'url' => '/documenti/conto-2022']
    ]" />

2. Sezione espandibile:
<x-pub_theme::municipal.transparency-section 
    title="Organi Politici"
    :collapsible="true"
    :initiallyOpen="false"
    :documents="$politicalDocs" />

3. Con variante e icona:
<x-pub_theme::municipal.transparency-section 
    title="Bandi di Gara"
    variant="warning"
    icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>'
    :documents="$tenderDocs" />
--}}