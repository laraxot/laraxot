{{-- Multi-step Appointment Creation - AGID Compliant --}}

<div class="min-h-screen bg-gray-50 py-8" wire:loading.class="opacity-50">
    <div class="max-w-4xl mx-auto px-4">
        {{-- Progress Header --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('pub_theme::appointment_form.booking.title.label') }}</h1>
                    <p class="text-gray-600 mt-1">{{ $stepTitle }}</p>
                </div>
                
                <div class="text-right">
                    <div class="text-sm text-gray-500 mb-1">
                        {{ __('pub_theme::appointment_form.booking.progress.label', ['current' => $currentStep, 'total' => $totalSteps]) }}
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
                </div>
                
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('pub_theme::appointment_form.booking.confirmed.title') }}</h2>
                <p class="text-gray-600 mb-6">
                    {{ __('pub_theme::appointment_form.booking.confirmed.message') }}
                </p>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="text-sm text-gray-600 mb-2">{{ __('pub_theme::appointment_form.booking.confirmation_code.label') }}</div>
                    <div class="text-2xl font-mono font-bold text-blue-600">{{ $confirmationCode }}</div>
                </div>

                <p class="text-sm text-gray-500 mb-6">
                    {{ __('pub_theme::appointment_form.booking.confirmed.email_notice') }}
                    {{ __('pub_theme::appointment_form.booking.confirmed.keep_code') }}
                </p>

                <div class="flex justify-center space-x-4">
                    <x-button 
                        href="{{ route('appointments.index') }}"
                        variant="primary">
                        {{ __('pub_theme::appointment_form.booking.my_bookings.label') }}
                    </x-button>
                    
                    <x-button 
                        wire:click="restart"
                        variant="secondary">
                        {{ __('pub_theme::appointment_form.booking.new_booking.label') }}
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
                                {{ __('pub_theme::appointment_form.booking.select_service.title') }}
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
                                    {{ __('pub_theme::appointment_form.booking.select_office.title') }}
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
                                {{ __('pub_theme::appointment_form.booking.select_date.title') }}
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
                                    <span class="text-blue-800">
                                        {{ __('pub_theme::appointment_form.booking.selected_date.label', ['date' => $selectedDateFormatted]) }}
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
                                {{ __('pub_theme::appointment_form.booking.select_time.title') }}
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
                                            {{ $slot['duration'] }} {{ __('pub_theme::appointment_form.booking.minutes.label') }}
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        @if($selectedSlot)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <x-filament::icon icon="heroicon-o-clock" class="w-5 h-5 text-blue-600 mr-3" />
                                    <span class="text-blue-800">
                                        {{ __('pub_theme::appointment_form.booking.selected_time.label', ['time' => $selectedTimeFormatted]) }}
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
                                {{ __('pub_theme::appointment_form.booking.requester.title') }}
                            </label>
                            
                            <div class="grid md:grid-cols-2 gap-4 mb-6">
                                <button 
                                    type="button"
                                    wire:click="toggleSelfBooking"
                                    class="p-4 border rounded-lg text-center transition-colors
                                        {{ $isSelf ? 'border-blue-300 bg-blue-50 text-blue-800' : 'border-gray-200 hover:border-gray-300' }}">
                                    <x-filament::icon icon="heroicon-o-user" class="w-8 h-8 mx-auto mb-2" />
                                    <div class="font-medium">{{ __('pub_theme::appointment_form.booking.requester_self.label') }}</div>
                                </button>
                                
                                <button 
                                    type="button"
                                    wire:click="toggleSelfBooking"
                                    class="p-4 border rounded-lg text-center transition-colors
                                        {{ !$isSelf ? 'border-blue-300 bg-blue-50 text-blue-800' : 'border-gray-200 hover:border-gray-300' }}">
                                    <x-filament::icon icon="heroicon-o-users" class="w-8 h-8 mx-auto mb-2" />
                                    <div class="font-medium">{{ __('pub_theme::appointment_form.booking.requester_other.label') }}</div>
                                </button>
                            </div>
                        </div>

                        @if(!$isSelf)
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('pub_theme::appointment_form.booking.tax_code.label') }}
                                    </label>
                                    <div class="flex space-x-2">
                                        <x-input
                                            type="text"
                                            wire:model="citizenData.fiscal_code"
                                            placeholder="{{ __('pub_theme::appointment_form.booking.tax_code.placeholder') }}"
                                            class="flex-1"
                                            wire:keydown.enter="searchCitizen($event.target.value)" />
                                        <x-button
                                            type="button"
                                            wire:click="searchCitizen($citizenData['fiscal_code'] ?? '')"
                                            variant="secondary">
                                            {{ __('pub_theme::header.center.search.label') }}
                                        </x-button>
                                    </div>
                                </div>

                                @if(!empty($citizenData))
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="text-sm text-gray-600">{{ __('pub_theme::appointment_form.booking.person_found.label') }}</div>
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
                                {{ __('pub_theme::appointment_form.booking.required_documents.title') }}
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
                                {{ __('pub_theme::appointment_form.booking.notes.label') }}
                            </label>
                            <x-textarea
                                wire:model="notes"
                                placeholder="{{ __('pub_theme::appointment_form.booking.notes.placeholder') }}"
                                rows="3" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('pub_theme::appointment_form.booking.emergency_contact.label') }}
                            </label>
                            <x-input
                                type="text"
                                wire:model="emergencyContact"
                                placeholder="{{ __('pub_theme::appointment_form.booking.emergency_contact.placeholder') }}" />
                        </div>
                    </div>

                {{-- Step 6: Confirmation --}}
                @elseif($currentStep == 6)
                    <div class="space-y-6">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('pub_theme::appointment_form.booking.summary.title') }}</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('pub_theme::appointment_form.booking.summary.service.label') }}</span>
                                    <span class="font-medium">{{ $service->name }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('pub_theme::appointment_form.booking.summary.office.label') }}</span>
                                    <span class="font-medium">{{ $office->name }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('pub_theme::appointment_form.booking.summary.date.label') }}</span>
                                    <span class="font-medium">{{ $selectedDateFormatted }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('pub_theme::appointment_form.booking.summary.time.label') }}</span>
                                    <span class="font-medium">{{ $selectedTimeFormatted }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('pub_theme::appointment_form.booking.summary.requester.label') }}</span>
                                    <span class="font-medium">
                                        {{ $isSelf ? 'Me stesso' : ($citizenData['first_name'] ?? 'Altro') }}
                                    </span>
                                </div>
                                
                                @if(!empty($requiredDocuments))
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">{{ __('pub_theme::appointment_form.booking.summary.documents.label') }}</span>
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
                                <div class="text-sm text-yellow-800">
                                    <strong>{{ __('pub_theme::appointment_form.booking.important.label') }}</strong> {{ __('pub_theme::appointment_form.booking.important.message') }}
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-4">
                                {{ __('pub_theme::appointment_form.booking.consent.intro') }} <a href="{{ route('terms.show') }}" class="text-blue-600 hover:text-blue-800">{{ __('pub_theme::appointment_form.booking.consent.terms') }}</a>
                                {{ __('pub_theme::appointment_form.booking.consent.and_privacy') }}<a href="{{ route('policy.show') }}" class="text-blue-600 hover:text-blue-800">{{ __('pub_theme::appointment_form.booking.consent.privacy') }}</a>.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Navigation Buttons --}}
            <div class="flex justify-between">
                <x-button
                    wire:click="previousStep"
                    variant="secondary"
                    :disabled="$currentStep === 1">
                    <x-filament::icon icon="heroicon-o-arrow-left" class="w-4 h-4 mr-2" />
                    {{ __('pub_theme::common.actions.back.label') }}
                </x-button>

                @if($currentStep < $totalSteps)
                    <x-button 
                        wire:click="nextStep"
                        variant="primary">
                        {{ __('pub_theme::common.actions.next.label') }}
                        <x-filament::icon icon="heroicon-o-arrow-right" class="w-4 h-4 ml-2" />
                    </x-button>
                @else
                    <x-button
                        wire:click="confirmAppointment"
                        variant="primary"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ __('pub_theme::appointment_form.booking.confirm.label') }}</span>
                        <span wire:loading>{{ __('pub_theme::appointment_form.booking.confirm.loading') }}</span>
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
