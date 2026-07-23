@props([
    'title' => 'Riepilogo pagamento',
    'pagamento' => [],
])

@php
    $pagamento = array_merge([
        'servizio' => '',
        'importo' => '',
        'metodo' => '',
    ], $pagamento);
@endphp

<div class="step-content mt-8">
    @if($title)
        <h3 class="text-lg font-semibold text-gray-900 mb-4" data-element="step-title">{{ $title }}</h3>
    @endif

    <div class="bg-gray-50 rounded-lg p-6 mb-6">
        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Dettagli pagamento</h4>
        
        <dl class="space-y-4">
            <div class="flex justify-between">
                <dt class="text-gray-500">Servizio</dt>
                <dd class="text-gray-900 font-medium" data-element="summary-servizio">{{ $pagamento['servizio'] ?: 'Tassa Rifiuti (TARI)' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Metodo di pagamento</dt>
                <dd class="text-gray-900 font-medium" data-element="summary-metodo">{{ $pagamento['metodo'] ?: 'Carta di credito' }}</dd>
            </div>
            <div class="border-t border-gray-200 pt-4 flex justify-between">
                <dt class="text-gray-700 font-medium">Totale da pagare</dt>
                <dd class="text-blue-600 font-bold text-xl" data-element="summary-importo">{{ $pagamento['importo'] ?: '€ 150,00' }}</dd>
            </div>
        </dl>
    </div>

    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
        <div class="flex">
            <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    <strong>Attenzione:</strong> Il pagamento è irrevocabile. Assicurati che tutti i dati siano corretti prima di confermare.
                </p>
            </div>
        </div>
    </div>

    <div class="flex items-start mb-6">
        <input 
            type="checkbox" 
            id="accetto_condizioni" 
            x-model="accettoCondizioni"
            class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
        >
        <label for="accetto_condizioni" class="ml-2 text-sm text-gray-700">
            Ho letto e accetto le <a href="#" class="text-blue-600 hover:underline">condizioni di servizio</a> e il <a href="#" class="text-blue-600 hover:underline">regolamento di pagamento</a>
        </label>
    </div>

    <div class="mt-6 flex justify-between">
        <button 
            type="button"
            class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
            @click="currentStep--"
            data-element="step-prev"
        >
            Indietro
        </button>
        <button 
            type="button"
            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            :disabled="!accettoCondizioni"
            @click="currentStep++"
            data-element="step-next"
        >
            Effettua pagamento
        </button>
    </div>
</div>