@props([
    'title' => 'Dati pagamento',
    'description' => 'Inserisci i dati per il pagamento',
])

<div class="step-content mt-8">
    @if($title)
        <h3 class="text-lg font-semibold text-gray-900 mb-4" data-element="step-title">{{ $title }}</h3>
    @endif
    
    @if($description)
        <p class="text-gray-600 mb-6" data-element="step-description">{{ $description }}</p>
    @endif

    <div class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">
                Metodo di pagamento <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <label class="relative flex items-start p-4 border-2 rounded-lg cursor-pointer hover:border-blue-300 transition-colors
                    {{ in_array('carta', ['carta']) ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                    <input type="radio" name="metodo_pagamento" value="carta" x-model="metodoPagamento" class="sr-only">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-gray-900">Carta di credito</span>
                            @if(in_array('carta', ['carta']))
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <svg class="w-8 h-5 text-gray-400" viewBox="0 0 32 20" fill="currentColor"><rect width="32" height="20" rx="2" fill="#1A1F71"/><text x="4" y="14" font-size="8" fill="white">VISA</text></svg>
                            <svg class="w-8 h-5 text-gray-400" viewBox="0 0 32 20" fill="currentColor"><rect width="32" height="20" rx="2" fill="#EB001B"/><circle cx="12" cy="10" r="6" fill="#EB001B"/><circle cx="20" cy="10" r="6" fill="#F79E1B"/><path d="M16 5.5a6 6 0 0 0 0 9" fill="#FF5F00"/></svg>
                        </div>
                    </div>
                </label>

                <label class="relative flex items-start p-4 border-2 rounded-lg cursor-pointer hover:border-blue-300 transition-colors
                    {{ in_array('banca', ['banca']) ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                    <input type="radio" name="metodo_pagamento" value="banca" x-model="metodoPagamento" class="sr-only">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-gray-900">Bonifico</span>
                            @if(in_array('banca', ['banca']))
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500">Pagamento tramite home banking</p>
                    </div>
                </label>

                <label class="relative flex items-start p-4 border-2 rounded-lg cursor-pointer hover:border-blue-300 transition-colors
                    {{ in_array('pa', ['pa']) ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                    <input type="radio" name="metodo_pagamento" value="pa" x-model="metodoPagamento" class="sr-only">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-gray-900"> PagoPA</span>
                            @if(in_array('pa', ['pa']))
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500">Pagamento con pagoPA</p>
                    </div>
                </label>
            </div>
        </div>

        <div x-show="metodoPagamento === 'carta'" class="space-y-4 p-4 bg-gray-50 rounded-lg">
            <div>
                <label for="numero_carta" class="block text-sm font-medium text-gray-700 mb-2">
                    Numero carta <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="numero_carta" 
                    name="numero_carta"
                    x-model="numeroCarta"
                    maxlength="19"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="1234 5678 9012 3456"
                >
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="scadenza" class="block text-sm font-medium text-gray-700 mb-2">
                        Scadenza <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="scadenza" 
                        name="scadenza"
                        x-model="scadenza"
                        maxlength="5"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="MM/YY"
                    >
                </div>
                <div>
                    <label for="cvv" class="block text-sm font-medium text-gray-700 mb-2">
                        CVV <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="cvv" 
                        name="cvv"
                        x-model="cvv"
                        maxlength="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="123"
                    >
                </div>
            </div>
            <div>
                <label for="titolare_carta" class="block text-sm font-medium text-gray-700 mb-2">
                    Titolare carta <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="titolare_carta" 
                    name="titolare_carta"
                    x-model="titolareCarta"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Nome cognome"
                >
            </div>
        </div>

        <div x-show="metodoPagamento === 'banca'" class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600 mb-4">
                Riceverai via email le coordinate bancarie per effettuare il bonifico. Il pagamento dovrà essere effettuato entro 5 giorni lavorativi.
            </p>
            <div class="bg-white p-3 rounded border border-gray-200 text-sm">
                <p><strong>IBAN:</strong> IT12 A123 4567 8901 2345 6789 012</p>
                <p><strong>BIC:</strong> BANCITMM</p>
                <p><strong>Causale:</strong> PAG-2025-001234-TARI</p>
            </div>
        </div>

        <div x-show="metodoPagamento === 'pa'" class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600 mb-4">
                Verrai reindirizzato sul sistema di pagamento pagoPA per completare la transazione in modo sicuro.
            </p>
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
            :disabled="!metodoPagamento || (metodoPagamento === 'carta' && (!numeroCarta || !scadenza || !cvv || !titolareCarta))"
            @click="currentStep++"
            data-element="step-next"
        >
            Continua
        </button>
    </div>
</div>