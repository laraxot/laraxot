{{--
/**
 * Modulo Servizi Online Component - Bootstrap Italia Compliant
 * 
 * Componente per la gestione di moduli per servizi online comunali
 * Supporta flussi multi-step, validazione e invio dati
 * Conforme alle linee guida AGID per servizi digitali PA
 * 
 * @param string $serviceId ID univoco del servizio
 * @param string $title Titolo del servizio
 * @param string $description Descrizione del servizio
 * @param array $steps Array di step del flusso
 * @param string $submitUrl URL per l'invio del form
 * @param string $successMessage Messaggio di successo
 * @param string $errorMessage Messaggio di errore
 * @param bool $showProgress Mostra barra di progresso
 */
--}}

@props([
    'serviceId' => 'service-' . uniqid(),
    'title' => 'Servizio Online',
    'description' => null,
    'steps' => [
        ['id' => 'step1', 'title' => 'Dati richiedente', 'completed' => false],
        ['id' => 'step2', 'title' => 'Dettagli richiesta', 'completed' => false],
        ['id' => 'step3', 'title' => 'Conferma', 'completed' => false]
    ],
    'submitUrl' => '#',
    'successMessage' => 'Richiesta inviata con successo',
    'errorMessage' => 'Si è verificato un errore',
    'showProgress' => true
])

<div class="service-form-container bg-white rounded-lg shadow-md overflow-hidden"
     x-data="{
        currentStep: 1,
        totalSteps: {{ count($steps) }},
        formData: {},
        isLoading: false,
        isSubmitted: false,
        errors: {},
        
        init() {
            // Inizializza dati form
            this.formData = {
                service: '{{ $serviceId }}',
                timestamp: new Date().toISOString()
            };
        },
        
        get progress() {
            return ((this.currentStep - 1) / (this.totalSteps - 1)) * 100;
        },
        
        nextStep() {
            if (this.validateStep(this.currentStep)) {
                this.currentStep++;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        
        prevStep() {
            this.currentStep--;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        
        validateStep(step) {
            this.errors = {};
            
            // Validazione specifica per step
            switch(step) {
                case 1:
                    if (!this.formData.name) {
                        this.errors.name = 'Il nome è obbligatorio';
                    }
                    if (!this.formData.surname) {
                        this.errors.surname = 'Il cognome è obbligatorio';
                    }
                    if (!this.formData.email || !this.isValidEmail(this.formData.email)) {
                        this.errors.email = 'Email non valida';
                    }
                    break;
                
                case 2:
                    if (!this.formData.request_type) {
                        this.errors.request_type = 'Seleziona un tipo di richiesta';
                    }
                    if (!this.formData.description) {
                        this.errors.description = 'La descrizione è obbligatoria';
                    }
                    break;
            }
            
            return Object.keys(this.errors).length === 0;
        },
        
        isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },
        
        async submitForm() {
            if (!this.validateStep(this.currentStep)) return;
            
            this.isLoading = true;
            
            try {
                const response = await fetch('{{ $submitUrl }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(this.formData)
                });
                
                if (response.ok) {
                    this.isSubmitted = true;
                    this.$dispatch('service-submitted', this.formData);
                } else {
                    throw new Error('Errore nel server');
                }
            } catch (error) {
                this.errors.submit = '{{ $errorMessage }}';
                console.error('Errore invio form:', error);
            } finally {
                this.isLoading = false;
            }
        },
        
        resetForm() {
            this.currentStep = 1;
            this.formData = {
                service: '{{ $serviceId }}',
                timestamp: new Date().toISOString()
            };
            this.errors = {};
            this.isSubmitted = false;
        }
     }">

    {{-- Header --}}
    <div class="bg-primary-800 text-white px-6 py-4">
        <div class="text-center">
            <h2 class="text-xl font-bold">{{ $title }}</h2>
            @if($description)
            <p class="text-primary-100 text-sm mt-1">{{ $description }}</p>
            @endif
        </div>
    </div>

    {{-- Progress Bar --}}
    @if($showProgress)
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm font-medium text-gray-700">
                    Step <span x-text="currentStep"></span> di <span x-text="totalSteps"></span>
                </span>
                <span class="text-sm text-gray-500" x-text="Math.round(progress) + '%'"></span>
            </div>
            
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                     :style="'width: ' + progress + '%'"></div>
            </div>
            
            {{-- Step Labels --}}
            <div class="flex justify-between mt-2">
                <template x-for="(step, index) in {{ json_encode($steps) }}" :key="step.id">
                    <div class="text-center" :class="{ 'text-blue-600 font-medium': currentStep > index + 1 }">
                        <div class="text-xs" x-text="step.title"></div>
                    </div>
                </template>
            </div>
        </div>
    </div>
    @endif

    {{-- Form Content --}}
    <div class="p-6">
        <template x-if="!isSubmitted">
            <form @submit.prevent="currentStep === totalSteps ? submitForm() : nextStep()"
                  class="max-w-2xl mx-auto">
                
                {{-- Step 1: Dati richiedente --}}
                <div x-show="currentStep === 1" x-transition>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Dati del richiedente</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        {{-- Nome --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                            <input type="text" 
                                   x-model="formData.name"
                                   :class="{ 'border-red-500': errors.name }"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            <p class="text-red-500 text-xs mt-1" x-show="errors.name" x-text="errors.name"></p>
                        </div>

                        {{-- Cognome --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cognome *</label>
                            <input type="text" 
                                   x-model="formData.surname"
                                   :class="{ 'border-red-500': errors.surname }"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            <p class="text-red-500 text-xs mt-1" x-show="errors.surname" x-text="errors.surname"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" 
                                   x-model="formData.email"
                                   :class="{ 'border-red-500': errors.email }"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            <p class="text-red-500 text-xs mt-1" x-show="errors.email" x-text="errors.email"></p>
                        </div>

                        {{-- Telefono --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Telefono</label>
                            <input type="tel" 
                                   x-model="formData.phone"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    {{-- Indirizzo --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Indirizzo</label>
                        <input type="text" 
                               x-model="formData.address"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                {{-- Step 2: Dettagli richiesta --}}
                <div x-show="currentStep === 2" x-transition>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Dettagli della richiesta</h3>
                    
                    {{-- Tipo richiesta --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo richiesta *</label>
                        <select x-model="formData.request_type"
                                :class="{ 'border-red-500': errors.request_type }"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                            <option value="">Seleziona tipo...</option>
                            <option value="info">Informazioni</option>
                            <option value="appointment">Prenotazione appuntamento</option>
                            <option value="issue">Segnalazione problema</option>
                            <option value="document">Richiesta documento</option>
                        </select>
                        <p class="text-red-500 text-xs mt-1" x-show="errors.request_type" x-text="errors.request_type"></p>
                    </div>

                    {{-- Descrizione --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descrizione *</label>
                        <textarea x-model="formData.description"
                                  :class="{ 'border-red-500': errors.description }"
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Descrivi la tua richiesta in dettaglio..."
                                  required></textarea>
                        <p class="text-red-500 text-xs mt-1" x-show="errors.description" x-text="errors.description"></p>
                    </div>

                    {{-- Allegati --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Allegati (opzionale)</label>
                        <input type="file" 
                               @change="formData.attachments = $event.target.files"
                               multiple
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-gray-500 text-xs mt-1">Max 5 file, 10MB totali</p>
                    </div>
                </div>

                {{-- Step 3: Conferma --}}
                <div x-show="currentStep === 3" x-transition>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Riepilogo e conferma</h3>
                    
                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <h4 class="font-semibold text-gray-900 mb-2">Dati inseriti:</h4>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nome e cognome:</span>
                                <span class="font-medium" x-text="formData.name + ' ' + formData.surname"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium" x-text="formData.email"></span>
                            </div>
                            <div class="flex justify-between" x-show="formData.phone">
                                <span class="text-gray-600">Telefono:</span>
                                <span class="font-medium" x-text="formData.phone"></span>
                            </div>
                            <div class="flex justify-between" x-show="formData.request_type">
                                <span class="text-gray-600">Tipo richiesta:</span>
                                <span class="font-medium" x-text="formData.request_type"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Privacy --}}
                    <div class="mb-4">
                        <label class="flex items-start">
                            <input type="checkbox" 
                                   x-model="formData.privacy_accepted"
                                   class="mt-1 mr-2"
                                   required>
                            <span class="text-sm text-gray-700">
                                Acconsento al trattamento dei dati personali ai sensi del GDPR e della normativa privacy.
                                <a href="/privacy" class="text-blue-600 hover:text-blue-800" target="_blank">
                                    Leggi l'informativa privacy
                                </a>
                            </span>
                        </label>
                    </div>
                </div>

                {{-- Navigation Buttons --}}
                <div class="flex justify-between mt-8">
                    <button type="button"
                            @click="prevStep()"
                            x-show="currentStep > 1"
                            class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Indietro
                    </button>

                    <div class="flex space-x-2 ml-auto">
                        <button type="button"
                                @click="resetForm()"
                                class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            Annulla
                        </button>

                        <button type="submit"
                                :disabled="isLoading"
                                :class="{ 'opacity-50 cursor-not-allowed': isLoading }"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <template x-if="!isLoading">
                                <span x-text="currentStep === totalSteps ? 'Invia richiesta' : 'Avanti'"></span>
                            </template>
                            <template x-if="isLoading">
                                <span class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Invio in corso...
                                </span>
                            </template>
                        </button>
                    </div>
                </div>

                {{-- Errori generali --}}
                <p class="text-red-500 text-sm mt-4" x-show="errors.submit" x-text="errors.submit"></p>
            </form>
        </template>

        {{-- Success Message --}}
        <template x-if="isSubmitted">
            <div class="max-w-2xl mx-auto text-center">
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <svg class="w-12 h-12 text-green-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    
                    <h3 class="text-lg font-semibold text-green-800 mb-2">{{ $successMessage }}</h3>
                    <p class="text-green-700 mb-4">
                        La tua richiesta è stata inviata con successo. Riceverai una email di conferma a breve.
                    </p>
                    
                    <div class="bg-white p-4 rounded border border-green-100 mb-4">
                        <p class="text-sm text-gray-600">
                            Numero pratica: <span class="font-mono text-green-800" x-text="'PR-' + Date.now().toString().slice(-6)"></span>
                        </p>
                    </div>
                    
                    <button @click="resetForm()"
                            class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        Nuova richiesta
                    </button>
                </div>
            </div>
        </template>
    </div>

    {{-- Footer --}}
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        <div class="text-center text-sm text-gray-600">
            <p>Per assistenza contatta: <a href="mailto:servizi@comune.example.it" class="text-blue-600 hover:text-blue-800">servizi@comune.example.it</a></p>
        </div>
    </div>
</div>

{{-- Stili specifici --}}
@pushOnce('styles')
<style>
.service-form-container {
    min-height: 600px;
}

.service-form-container input,
.service-form-container select,
.service-form-container textarea {
    transition: all 0.2s;
}

.service-form-container input:focus,
.service-form-container select:focus,
.service-form-container textarea:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

@media (max-width: 768px) {
    .service-form-container {
        margin: 0 -1rem;
        border-radius: 0;
        border: none;
    }
    
    .service-form-container > div {
        padding: 1rem;
    }
}
</style>
@endPushOnce

{{--
Usage Examples:

1. Form base:
<x-pub_theme::municipal.service-form 
    title="Prenotazione Appuntamento"
    description="Prenota un appuntamento presso gli uffici comunali"
    submit-url="/api/services/appointment" />

2. Con step personalizzati:
<x-pub_theme::municipal.service-form 
    :steps="[
        ['id' => 'dati', 'title' => 'Dati personali'],
        ['id' => 'appuntamento', 'title' => 'Data e ora'],
        ['id' => 'conferma', 'title' => 'Conferma']
    ]"
    success-message="Appuntamento prenotato con successo!" />

3. Senza barra di progresso:
<x-pub_theme::municipal.service-form 
    :show-progress="false"
    title="Richiesta informazioni" />
--}}