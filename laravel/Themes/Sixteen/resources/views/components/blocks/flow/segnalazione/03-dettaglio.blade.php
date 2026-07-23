@props([
    'title' => 'Dettagli segnalazione',
    'description' => 'Descrivi il disservizio riscontrato',
    'categorie' => [],
])

@php
    $categorie = collect($categorie)->map(function ($cat) {
        if (is_string($cat)) {
            return ['id' => $cat, 'name' => $cat];
        }
        return $cat;
    });
    
    if ($categorie->isEmpty()) {
        $categorie = collect([
            ['id' => 'illuminazione', 'name' => 'Illuminazione pubblica'],
            ['id' => 'strade', 'name' => 'Strade e marciapiedi'],
            ['id' => 'verde', 'name' => 'Verde pubblico'],
            ['id' => 'rifiuti', 'name' => 'Rifiuti e pulizia'],
            ['id' => 'trasporti', 'name' => 'Trasporti pubblici'],
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
            <label for="categoria" class="block text-sm font-medium text-gray-700 mb-2">
                Categoria <span class="text-red-500">*</span>
            </label>
            <select 
                id="categoria" 
                name="categoria"
                x-model="categoria"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                data-element="categoria-select"
            >
                <option value="">Seleziona una categoria</option>
                @foreach($categorie as $cat)
                    <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="titolo" class="block text-sm font-medium text-gray-700 mb-2">
                Titolo segnalazione <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="titolo" 
                name="titolo"
                x-model="titolo"
                required
                maxlength="100"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Breve descrizione del problema"
                data-element="titolo-input"
            >
            <p class="mt-1 text-xs text-gray-500">Max 100 caratteri</p>
        </div>

        <div>
            <label for="descrizione" class="block text-sm font-medium text-gray-700 mb-2">
                Descrizione dettagliata <span class="text-red-500">*</span>
            </label>
            <textarea 
                id="descrizione" 
                name="descrizione"
                x-model="descrizione"
                required
                rows="5"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Descrivi il problema riscontrato con il maggior dettaglio possibile..."
                data-element="descrizione-textarea"
            ></textarea>
        </div>

        <div>
            <label for="luogo" class="block text-sm font-medium text-gray-700 mb-2">
                Luogo del disservizio
            </label>
            <input 
                type="text" 
                id="luogo" 
                name="luogo"
                x-model="luogo"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Indirizzo o descrizione del luogo"
                data-element="luogo-input"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Allega foto (opzionale)
            </label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 cursor-pointer transition-colors">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="mt-2 text-sm text-gray-600">
                    <span class="font-medium text-blue-600">Clicca per caricare</span> o trascina file
                </p>
                <p class="mt-1 text-xs text-gray-500">PNG, JPG fino a 10MB</p>
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
            :disabled="!categoria || !titolo || !descrizione"
            @click="currentStep++"
            data-element="step-next"
        >
            Continua
        </button>
    </div>
</div>