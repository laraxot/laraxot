@props([
    'title' => 'Seleziona sede',
    'description' => 'Scegli la sede più vicina a te',
    'sedi' => [],
])

@php
    $sedi = collect($sedi)->map(function ($sede) {
        if (is_string($sede)) {
            return ['id' => $sede, 'name' => $sede, 'address' => '', 'distance' => ''];
        }
        return array_merge(['id' => '', 'name' => '', 'address' => '', 'distance' => ''], $sede);
    });
    
    if ($sedi->isEmpty()) {
        $sedi = collect([
            ['id' => 'anagrafe-centro', 'name' => 'Ufficio Anagrafe - Centro', 'address' => 'Via Roma, 1 - 00100 Roma', 'distance' => '0.5 km'],
            ['id' => 'anagrafe-nord', 'name' => 'Ufficio Anagrafe - Nord', 'address' => 'Via Milano, 15 - 00100 Roma', 'distance' => '3.2 km'],
            ['id' => 'anagrafe-sud', 'name' => 'Ufficio Anagrafe - Sud', 'address' => 'Via Napoli, 22 - 00100 Roma', 'distance' => '5.1 km'],
            ['id' => 'ufficio-tecnico', 'name' => 'Ufficio Tecnico', 'address' => 'Via Engineering, 8 - 00100 Roma', 'distance' => '2.8 km'],
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

    <div class="space-y-4" role="radiogroup" aria-label="Selezione sede">
        @foreach($sedi as $sede)
            <label 
                class="block p-4 border-2 rounded-lg cursor-pointer transition-all duration-200 hover:border-blue-300 hover:bg-blue-50
                    {{ in_array($sede['id'], ['anagrafe-centro']) ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white' }}"
                role="radio"
                aria-checked="{{ in_array($sede['id'], ['anagrafe-centro']) ? 'true' : 'false' }}"
            >
                <input 
                    type="radio" 
                    name="sede" 
                    value="{{ $sede['id'] }}"
                    x-model="selectedSede"
                    class="sr-only"
                >
                <div class="flex items-start justify-between">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <span 
                                class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                    {{ in_array($sede['id'], ['anagrafe-centro']) ? 'border-blue-500 bg-blue-500' : 'border-gray-300' }}"
                            >
                                @if(in_array($sede['id'], ['anagrafe-centro']))
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </span>
                        </div>
                        <div class="ml-3">
                            <div class="font-medium text-gray-900" data-element="sede-name">{{ $sede['name'] }}</div>
                            @if(!empty($sede['address']))
                                <div class="text-sm text-gray-500 mt-1" data-element="sede-address">{{ $sede['address'] }}</div>
                            @endif
                        </div>
                    </div>
                    @if(!empty($sede['distance']))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            {{ $sede['distance'] }}
                        </span>
                    @endif
                </div>
            </label>
        @endforeach

        @if($sedi->isEmpty())
            <div class="text-center py-8 text-gray-500">
                <p>Nessuna sede disponibile</p>
            </div>
        @endif
    </div>

    <div class="mt-6 flex justify-end">
        <button 
            type="button"
            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="!selectedSede"
            @click="currentStep++"
            data-element="step-next"
        >
            Continua
        </button>
    </div>
</div>