@props([
    'title' => 'Dettagli richiesta',
    'description' => 'Descrivi il problema per cui richiedi assistenza',
    'tipi_richiesta' => [],
])

@php
    $tipi_richiesta = collect($tipi_richiesta)->map(function ($tipo) {
        if (is_string($tipo)) {
            return ['id' => $tipo, 'name' => $tipo];
        }
        return $tipo;
    });
    
    if ($tipi_richiesta->isEmpty()) {
        $tipi_richiesta = collect([
            ['id' => 'supporto-tecnico', 'name' => 'Supporto tecnico'],
            ['id' => 'info-servizio', 'name' => 'Informazioni su un servizio'],
            ['id' => 'segnalazione-problema', 'name' => 'Segnalazione problema'],
            ['id' => 'richiesta-informazione', 'name' => 'Richiesta informazione'],
            ['id' => 'altro', 'name' => 'Altro'],
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

    <div class="space-y-6">
        <div>
            <label for="tipo_richiesta" class="block text-sm font-medium text-gray-700 mb-2">
                Tipo di richiesta <span class="text-red-500">*</span>
            </label>
            <select 
                id="tipo_richiesta" 
                name="tipo_richiesta"
                x-model="tipoRichiesta"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                data-element="tipo-select"
            >
                <option value="">Seleziona un tipo</option>
                @foreach($tipi_richiesta as $tipo)
                    <option value="{{ $tipo['id'] }}">{{ $tipo['name'] }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="oggetto" class="block text-sm font-medium text-gray-700 mb-2">
                Oggetto <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="oggetto" 
                name="oggetto"
                x-model="oggetto"
                required
                maxlength="150"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Oggetto della tua richiesta"
                data-element="oggetto-input"
            >
        </div>

        <div>
            <label for="messaggio" class="block text-sm font-medium text-gray-700 mb-2">
                Messaggio <span class="text-red-500">*</span>
            </label>
            <textarea 
                id="messaggio" 
                name="messaggio"
                x-model="messaggio"
                required
                rows="6"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Descrivi in dettaglio il tuo problema o la tua richiesta..."
                data-element="messaggio-textarea"
            ></textarea>
            <p class="mt-1 text-xs text-gray-500">Minimo 20 caratteri</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Allega documenti (opzionale)
            </label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 cursor-pointer transition-colors">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="mt-2 text-sm text-gray-600">
                    <span class="font-medium text-blue-600">Clicca per caricare</span> o trascina file
                </p>
                <p class="mt-1 text-xs text-gray-500">PDF, DOC, DOCX fino a 10MB</p>
            </div>
        </div>
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
            :disabled="!tipoRichiesta || !oggetto || !messaggio || messaggio.length < 20"
            @click="currentStep++"
            data-element="step-next"
        >
            Continua
        </button>
    </div>
</div>