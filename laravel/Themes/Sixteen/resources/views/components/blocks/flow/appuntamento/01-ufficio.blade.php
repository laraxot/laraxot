@props([
    'offices' => [],
    'selectedOffice' => null,
    'title' => 'Seleziona l\'ufficio',
    'description' => 'Scegli l\'ufficio presso cui presentarti',
])

@php
    $offices = collect($offices)->map(function ($office) {
        if (is_string($office)) {
            return ['id' => $office, 'name' => $office, 'address' => '', 'phone' => ''];
        }
        return array_merge(['id' => '', 'name' => '', 'address' => '', 'phone' => ''], $office);
    });
@endphp

<div class="step-content mt-8">
    @if($title)
        <h3 class="text-lg font-semibold text-gray-900 mb-4" data-element="step-title">{{ $title }}</h3>
    @endif
    
    @if($description)
        <p class="text-gray-600 mb-6" data-element="step-description">{{ $description }}</p>
    @endif

    <div class="space-y-4" role="radiogroup" aria-label="Selezione ufficio">
        @foreach($offices as $office)
            <label 
                class="block p-4 border-2 rounded-lg cursor-pointer transition-all duration-200 hover:border-blue-300 hover:bg-blue-50
                    {{ $selectedOffice === $office['id'] ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white' }}"
                role="radio"
                aria-checked="{{ $selectedOffice === $office['id'] ? 'true' : 'false' }}"
            >
                <input 
                    type="radio" 
                    name="office" 
                    value="{{ $office['id'] }}"
                    x-model="selectedOffice"
                    class="sr-only"
                >
                <div class="flex items-start">
                    <div class="flex-shrink-0 mt-1">
                        <span 
                            class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                {{ $selectedOffice === $office['id'] ? 'border-blue-500 bg-blue-500' : 'border-gray-300' }}"
                        >
                            @if($selectedOffice === $office['id'])
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </span>
                    </div>
                    <div class="ml-3 flex-1">
                        <div class="font-medium text-gray-900" data-element="office-name">{{ $office['name'] }}</div>
                        @if(!empty($office['address']))
                            <div class="text-sm text-gray-500 mt-1" data-element="office-address">{{ $office['address'] }}</div>
                        @endif
                        @if(!empty($office['phone']))
                            <div class="text-sm text-gray-500 mt-1" data-element="office-phone">{{ $office['phone'] }}</div>
                        @endif
                    </div>
                </div>
            </label>
        @endforeach

        @if($offices->isEmpty())
            <div class="text-center py-8 text-gray-500">
                <p>Nessun ufficio disponibile</p>
            </div>
        @endif
    </div>

    <div class="mt-6 flex justify-end">
        <button 
            type="button"
            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="!selectedOffice"
            @click="currentStep++"
            data-element="step-next"
        >
            Continua
        </button>
    </div>
</div>