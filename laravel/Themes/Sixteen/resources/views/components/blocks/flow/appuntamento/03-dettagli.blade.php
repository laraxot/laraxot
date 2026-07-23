@props([
    'title' => 'Dettagli appuntamento',
    'description' => 'Inserisci i dettagli della tua richiesta',
    'motivi' => [],
])

@php
    $motivi = collect($motivi)->map(function ($motivo) {
        if (is_string($motivo)) {
            return ['id' => $motivo, 'name' => $motivo];
        }
        return $motivo;
    });
    
    if ($motivi->isEmpty()) {
        $motivi = collect([
            ['id' => 'informazioni', 'name' => 'Informazioni generali'],
            ['id' => 'pratica', 'name' => 'Pratica amministrativa'],
            ['id' => 'certificato', 'name' => 'Richiesta certificato'],
            ['id' => 'pagamento', 'name' => 'Pagamento'],
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
            <label for="motivo" class="block text-sm font-medium text-gray-700 mb-2">
                Motivo della visita <span class="text-red-500">*</span>
            </label>
            <select 
                id="motivo" 
                name="motivo"
                x-model="motivo"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                data-element="motivo-select"
            >
                <option value="">Seleziona un motivo</option>
                @foreach($motivi as $m)
                    <option value="{{ $m['id'] }}">{{ $m['name'] }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="note" class="block text-sm font-medium text-gray-700 mb-2">
                Note aggiuntive (opzionale)
            </label>
            <textarea 
                id="note" 
                name="note"
                x-model="note"
                rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Inserisci eventuali informazioni utili..."
                data-element="note-textarea"
            ></textarea>
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
            :disabled="!motivo"
            @click="currentStep++"
            data-element="step-next"
        >
            Continua
        </button>
    </div>
</div>