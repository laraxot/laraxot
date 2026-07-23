{{-- Multi-step Appointment Creation - AGID Compliant --}}

<div class="min-h-screen bg-gray-50 py-8" wire:loading.class="opacity-50">
    <div class="max-w-4xl mx-auto px-4">
        {{-- Progress Header --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Prenotazione Appuntamento</h1>
                    <p class="text-gray-600 mt-1">{{ $stepTitle }}</p>
                </div>
                
                <div class="text-right">
                    <div class="text-sm text-gray-500 mb-1">
                        Step {{ $currentStep }} di {{ $totalSteps }}
                    </div>
                    <div class="w-32 bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                             style="width: {{ $stepProgress }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Breadcrumb Steps --}}
            <div class="flex justify-center space-x-4 text-sm">
                @foreach(range(1, $totalSteps) as $step)
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium
                            {{ $step < $currentStep ? 'bg-green-100 text-green-800' : '' }}
                            {{ $step == $currentStep ? 'bg-blue-600 text-white' : '' }}
                            {{ $step > $currentStep ? 'bg-gray-100 text-gray-400' : '' }}">
                            @if($step < $currentStep)
                                <x-filament::icon icon="heroicon-o-check" class="w-4 h-4" />
f7ac8eda (.)
                            @else
                                {{ $step }}
                            @endif
                        </div>
                        @if($step < $totalSteps)
                            <div class="w-12 h-0.5 bg-gray-200 mx-2"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Success Step --}}
        @if($currentStep > $totalSteps)
            <div class="bg-white rounded-lg shadow-sm border border-green-200 p-8 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <x-filament::icon icon="heroicon-o-check" class="w-8 h-8 text-green-600" />
f7ac8eda (.)
                </div>
                
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Prenotazione Confermata!</h2>
                <p class="text-gray-600 mb-6">
                    La tua prenotazione è stata registrata con successo.
                </p>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="text-sm text-gray-600 mb-2">Codice di Conferma:</div>
                    <div class="text-2xl font-mono font-bold text-blue-600">{{ $confirmationCode }}</div>
                </div>

                <p class="text-sm text-gray-500 mb-6">
                    Riceverai una email di conferma con tutti i dettagli.
                    Conserva questo codice per riferimento futuro.
                </p>

                <div class="flex justify-center space-x-4">
                    <x-button 
                        href="{{ route('appointments.index') }}"
                        variant="primary">
                        Le Mie Prenotazioni
                    </x-button>
                    
                    <x-button 
                        wire:click="restart"
                        variant="secondary">
                        Nuova Prenotazione
                    </x-button>
                </div>
            </div>
        @else
            {{-- Step Content --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                {{-- Step 1: Service Selection --}}
                @if($currentStep == 1)
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Seleziona il Servizio
                            </label>
                            <div class="grid md:grid-cols-2 gap-4">
                                @foreach($services as $service)
                                    <button 
                                        type="button"
                                        wire:click="loadOffices('{{ $service->id }}')"
                                        class="p-4 border border-gray-200 rounded-lg text-left hover:border-blue-300 hover:bg-blue-50 transition-colors
                                            {{ $serviceId == $service->id ? 'border-blue-300 bg-blue-50' : '' }}"
                                        :class="{ 'border-blue-300 bg-blue-50': serviceId == {{ $service->id }} }">
                                        <div class="flex items-center">
                                            <div class="bg-blue-100 p-2 rounded-lg mr-4">
                                                <x-dynamic-component 
                                                    :component="$service->icon ?? 'heroicon-o-cog'" 
                                                    class="w-6 h-6 text-blue-600" />
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900">{{ $service->name }}</h3>
                                                <p class="text-sm text-gray-600 mt-1">{{ $service->description }}</p>
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        @if($serviceId)
                            <div x-data="{ selectedOffice: null }">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Seleziona l'Ufficio
                                </label>
                                <div class="grid gap-3">
                                    @foreach($offices as $office)
                                        <button 
                                            type="button"
                                            wire:click="selectOffice('{{ $office->id }}')"
                                            class="p-4 border border-gray-200 rounded-lg text-left hover:border-blue-300 hover:bg-blue-50 transition-colors
                                                {{ $officeId == $office->id ? 'border-blue-300 bg-blue-50' : '' }}">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h4 class="font-medium text-gray-900">{{ $office->name }}</h4>
                                                    <p class="text-sm text-gray-600 mt-1">{{ $office->address }}</p>
                                                    @if($office->phone)
                                                        <p class="text-sm text-gray-600">{{ $office->phone }}</p>
                                                    @endif
                                                </div>
                                                <x-filament::icon icon="heroicon-o-arrow-right" class="w-5 h-5 text-gray-400" />
f7ac8eda (.)
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                {{-- Step 2: Date Selection --}}
                @elseif($currentStep == 2)
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Seleziona la Data
                            </label>
                            <div class="grid grid-cols-7 gap-2 mb-4">
                                @foreach(['Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom'] as $day)
                                    <div class="text-center text-xs font-medium text-gray-500 py-2">
                                        {{ $day }}
                                    </div>
                                @endforeach
                                
                                @foreach($availableDates as $date)
                                    <button 
                                        type="button"
                                        wire:click="selectDate('{{ $date['date'] }}')"
                                        class="p-2 text-center rounded border transition-colors
                                            {{ $date['available'] ? 'hover:bg-blue-50 hover:border-blue-300' : 'bg-gray-50 text-gray-400 cursor-not-allowed' }}
                                            {{ $appointmentDate == $date['date'] ? 'bg-blue-100 border-blue-300 text-blue-800' : 'border-gray-200' }}"
                                        {{ !$date['available'] ? 'disabled' : '' }}>
                                        <div class="text-sm font-medium">{{ $date['day'] }}</div>
                                        <div class="text-xs">{{ $date['month_short'] }}</div>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        @if($appointmentDate)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <x-filament::icon icon="heroicon-o-calendar" class="w-5 h-5 text-blue-600 mr-3" />
f7ac8eda (.)
                                    <span class="text-blue-800">
                                        Data selezionata: {{ $selectedDateFormatted }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                {{-- Step 3: Time Slot Selection --}}
                @elseif($currentStep == 3)
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Seleziona l'Orario
                            </label>
                            <div class="grid md:grid-cols-3 gap-3">
                                @foreach($availableSlots as $slot)
                                    <button 
                                        type="button"
                                        wire:click="selectSlot(@js($slot))"
                                        class="p-3 border border-gray-200 rounded-lg text-center hover:border-blue-300 hover:bg-blue-50 transition-colors
                                            {{ $selectedSlot && $selectedSlot['start'] == $slot['start'] ? 'border-blue-300 bg-blue-50' : '' }}"
                                        :class="{ 'border-blue-300 bg-blue-50': selectedSlot && selectedSlot.start === '{{ $slot['start'] }}' }">
                                        <div class="font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($slot['start'])->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($slot['end'])->format('H:i') }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $slot['duration'] }} minuti
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        @if($selectedSlot)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <x-filament::icon icon="heroicon-o-clock" class="w-5 h-5 text-blue-600 mr-3" />
f7ac8eda (.)
                                    <span class="text-blue-800">
                                        Orario selezionato: {{ $selectedTimeFormatted }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                {{-- Step 4: Citizen Data --}}
                @elseif($currentStep == 4)
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-4">
                                Chi richiede l'appuntamento?
                            </label>
                            
                            <div class="grid md:grid-cols-2 gap-4 mb-6">
                                <button 
                                    type="button"
                                    wire:click="toggleSelfBooking"
                                    class="p-4 border rounded-lg text-center transition-colors
                                        {{ $isSelf ? 'border-blue-300 bg-blue-50 text-blue-800' : 'border-gray-200 hover:border-gray-300' }}">
                                    <x-filament::icon icon="heroicon-o-user" class="w-8 h-8 mx-auto mb-2" />
f7ac8eda (.)
                                    <div class="font-medium">Per me stesso</div>
                                </button>
                                
                                <button 
                                    type="button"
                                    wire:click="toggleSelfBooking"
                                    class="p-4 border rounded-lg text-center transition-colors
                                        {{ !$isSelf ? 'border-blue-300 bg-blue-50 text-blue-800' : 'border-gray-200 hover:border-gray-300' }}">
                                    <x-filament::icon icon="heroicon-o-users" class="w-8 h-8 mx-auto mb-2" />
f7ac8eda (.)
                                    <div class="font-medium">Per altra persona</div>
                                </button>
                            </div>
                        </div>

                        @if(!$isSelf)
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Codice Fiscale della persona
                                    </label>
                                    <div class="flex space-x-2">
                                        <x-input 
                                        <x-input 
                                            type="text"
                                            wire:model="citizenData.fiscal_code"
                                            placeholder="Inserisci codice fiscale"
                                            class="flex-1"
                                            wire:keydown.enter="searchCitizen($event.target.value)" />
                                        <x-button 
                                        <x-button 
                                            type="button"
                                            wire:click="searchCitizen($citizenData['fiscal_code'] ?? '')"
                                            variant="secondary">
                                            Cerca
                                        </x-button>
                                        </x-button>
                                    </div>
                                </div>

                                @if(!empty($citizenData))
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="text-sm text-gray-600">Dati trovati:</div>
                                        <div class="font-medium text-gray-900">
                                            {{ $citizenData['first_name'] }} {{ $citizenData['last_name'] }}
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            CF: {{ $citizenData['fiscal_code'] }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                {{-- Step 5: Additional Details --}}
                @elseif($currentStep == 5)
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Documenti Richiesti
                            </label>
                            <div class="grid md:grid-cols-2 gap-3">
                                @foreach($availableDocuments as $key => $label)
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-gray-300">
                                        <input 
                                            type="checkbox"
                                            wire:model="requiredDocuments"
                                            value="{{ $key }}"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-3" />
                                        <span class="text-sm text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Note aggiuntive (opzionale)
                            </label>
                            <x-textarea 
                            <x-textarea 
                                wire:model="notes"
                                placeholder="Eventuali note o informazioni aggiuntive..."
                                rows="3" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Contatto di emergenza (opzionale)
                            </label>
                            <x-input 
                            <x-input 
                                type="text"
                                wire:model="emergencyContact"
                                placeholder="Nome e telefono di riferimento" />
                        </div>
                    </div>

                {{-- Step 6: Confirmation --}}
                @elseif($currentStep == 6)
                    <div class="space-y-6">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Riepilogo Prenotazione</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Servizio:</span>
                                    <span class="font-medium">{{ $service->name }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Ufficio:</span>
                                    <span class="font-medium">{{ $office->name }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Data:</span>
                                    <span class="font-medium">{{ $selectedDateFormatted }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Orario:</span>
                                    <span class="font-medium">{{ $selectedTimeFormatted }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Richiedente:</span>
                                    <span class="font-medium">
                                        {{ $isSelf ? 'Me stesso' : ($citizenData['first_name'] ?? 'Altro') }}
                                    </span>
                                </div>
                                
                                @if(!empty($requiredDocuments))
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Documenti:</span>
                                        <span class="font-medium text-right">
                                            @foreach($requiredDocuments as $doc)
                                                {{ $availableDocuments[$doc] }}
                                                @if(!$loop->last), @endif
                                            @endforeach
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex">
                                <x-filament::icon icon="heroicon-o-exclamation-triangle" class="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0" />
f7ac8eda (.)
                                <div class="text-sm text-yellow-800">
                                    <strong>Importante:</strong> Porta con te i documenti richiesti e presenta il codice di conferma all'ufficio.
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-4">
                                Confermando, accetti i <a href="#" class="text-blue-600 hover:text-blue-800">termini di servizio</a>
                                e l'<a href="#" class="text-blue-600 hover:text-blue-800">informativa privacy</a>.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Navigation Buttons --}}
            <div class="flex justify-between">
                <x-button 
                <x-button 
                    wire:click="previousStep"
                    variant="secondary"
                    {{ $isFirstStep ? 'disabled' : '' }}>
                    <x-filament::icon icon="heroicon-o-arrow-left" class="w-4 h-4 mr-2" />
f7ac8eda (.)
                    Indietro
                </x-button>

                @if($currentStep < $totalSteps)
                    <x-button 
                        wire:click="nextStep"
                        variant="primary">
                        Avanti
                        <x-filament::icon icon="heroicon-o-arrow-right" class="w-4 h-4 ml-2" />
f7ac8eda (.)
                    </x-button>
                @else
                    <x-button 
                    </x-button>
                @else
                    <x-button 
                        wire:click="confirmAppointment"
                        variant="primary"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>Conferma Prenotazione</span>
                        <span wire:loading>Conferma in corso...</span>
                    </x-button>
                    </x-button>
                @endif
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .appointment-calendar {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
    }
    
    .time-slot {
        transition: all 0.2s ease;
    }
    
    .time-slot:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('livewire:load', function() {
        // Gestione navigazione con tastiera
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft' && !e.ctrlKey && !e.metaKey) {
                Livewire.emit('previousStep');
            } else if (e.key === 'ArrowRight' && !e.ctrlKey && !e.metaKey) {
                Livewire.emit('nextStep');
            }
        });
        
        // Scroll to top on step change
        Livewire.on('stepChanged', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>
@endpush
