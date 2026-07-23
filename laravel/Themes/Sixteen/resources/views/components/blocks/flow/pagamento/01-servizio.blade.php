@props([
    'title' => 'Seleziona servizio',
    'description' => 'Scegli il servizio per cui effettuare il pagamento',
    'servizi' => [],
])

@php
    $servizi = collect($servizi)->map(function ($servizio) {
        if (is_string($servizio)) {
            return ['id' => $servizio, 'name' => $servizio, 'amount' => '', 'description' => ''];
        }
        return array_merge(['id' => '', 'name' => '', 'amount' => '', 'description' => ''], $servizio);
    });
    
    if ($servizi->isEmpty()) {
        $servizi = collect([
            ['id' => ' Tari', 'name' => 'Tassa Rifiuti (TARI)', 'amount' => '€ 150,00', 'description' => 'Anno 2025'],
            ['id' => 'imposta-servizi', 'name' => 'Imposta di Servizio', 'amount' => '€ 45,00', 'description' => 'Servizio anno corrente'],
            ['id' => 'pagamento-documenti', 'name' => 'Rilascio documenti', 'amount' => '€ 25,00', 'description' => 'Copia conforme'],
            ['id' => 'permesso-sosta', 'name' => 'Permesso Sosta', 'amount' => '€ 80,00', 'description' => 'Zona centrale'],
        ]);
    }
@endphp

<div class="step-content mt-8">
    @if($title)
        <h3 class="text-lg font-semibold text-gray-900 mb-4" data-element="step-title">{{ $title }}</h3>
    @endif
    
    @if($description)
        <p class="text-gray-600 mb-6" data-element="step-description">{{ $description }}</p>
    @endif

    <div class="space-y-4" role="radiogroup" aria-label="Selezione servizio">
        @foreach($servizi as $servizio)
            <label 
                class="block p-4 border-2 rounded-lg cursor-pointer transition-all duration-200 hover:border-blue-300 hover:bg-blue-50
                    {{ in_array($servizio['id'], ['TARI']) ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white' }}"
                role="radio"
                aria-checked="{{ in_array($servizio['id'], ['TARI']) ? 'true' : 'false' }}"
            >
                <input 
                    type="radio" 
                    name="servizio" 
                    value="{{ $servizio['id'] }}"
                    x-model="selectedServizio"
                    class="sr-only"
                >
                <div class="flex items-start justify-between">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <span 
                                class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                    {{ in_array($servizio['id'], ['TARI']) ? 'border-blue-500 bg-blue-500' : 'border-gray-300' }}"
                            >
                                @if(in_array($servizio['id'], ['TARI']))
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </span>
                        </div>
                        <div class="ml-3">
                            <div class="font-medium text-gray-900" data-element="servizio-name">{{ $servizio['name'] }}</div>
                            @if(!empty($servizio['description']))
                                <div class="text-sm text-gray-500 mt-1" data-element="servizio-description">{{ $servizio['description'] }}</div>
                            @endif
                        </div>
                    </div>
                    @if(!empty($servizio['amount']))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-blue-100 text-blue-800">
                            {{ $servizio['amount'] }}
                        </span>
                    @endif
                </div>
            </label>
        @endforeach

        @if($servizi->isEmpty())
            <div class="text-center py-8 text-gray-500">
                <p>Nessun servizio disponibile</p>
            </div>
        @endif
    </div>

    <div class="mt-6 flex justify-end">
        <button 
            type="button"
            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="!selectedServizio"
            @click="currentStep++"
            data-element="step-next"
        >
            Continua
        </button>
    </div>
</div>