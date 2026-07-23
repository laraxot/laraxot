{{--
/**
 * Service Booking Component
 * 
 * Interactive booking system for appointments and service requests
 * Calendar integration, time slot selection, and form validation
 * 
 * @param string $serviceTitle Title of the service being booked
 * @param array $availableDates Available dates for booking
 * @param array $timeSlots Available time slots
 * @param array $bookingForm Form fields for booking
 * @param bool $requiresPayment Whether payment is required
 * @param string $bookingApi API endpoint for booking submission
 */
--}}

@props([
    'serviceTitle' => 'Servizio',
    'availableDates' => [],
    'timeSlots' => [],
    'bookingForm' => [],
    'requiresPayment' => false,
    'bookingApi' => '/api/services/book'
])

@php
    $bookingId = 'service-booking-' . uniqid();
    $selectedDate = null;
    $selectedTime = null;
@endphp

<div class="service-booking bg-white rounded-lg shadow-lg border border-gray-200" id="{{ $bookingId }}">
    <!-- Booking Header -->
    <div class="bg-primary-600 text-white px-6 py-4 rounded-t-lg">
        <h2 class="text-xl font-semibold">Prenota {{ $serviceTitle }}</h2>
        <p class="text-primary-100 text-sm mt-1">Scegli data e ora per il tuo appuntamento</p>
    </div>
    
    <!-- Booking Form -->
    <form class="p-6" onsubmit="handleBooking(event, '{{ $bookingId }}')">
        <!-- Step Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div id="step-1-{{ $bookingId }}" class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                        1
                    </div>
                    <div class="w-16 h-1 bg-primary-600 mx-2"></div>
                    <div id="step-2-{{ $bookingId }}" class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">
                        2
                    </div>
                    <div class="w-16 h-1 bg-gray-300 mx-2"></div>
                    <div id="step-3-{{ $bookingId }}" class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">
                        3
                    </div>
                </div>
            </div>
            <div class="flex justify-between mt-2 text-xs text-gray-600">
                <span>Scegli data e ora</span>
                <span>Dati personali</span>
                <span>Conferma</span>
            </div>
        </div>
        
        <!-- Step 1: Date and Time Selection -->
        <div id="step-1-content-{{ $bookingId }}" class="booking-step">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Date Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Seleziona la data
                    </label>
                    <div class="grid grid-cols-3 gap-2" id="date-grid-{{ $bookingId }}">
                        @foreach($availableDates as $date)
                        <button 
                            type="button"
                            onclick="selectDate('{{ $bookingId }}', '{{ $date['date'] }}', this)"
                            class="date-option p-3 text-center border border-gray-300 rounded-lg hover:border-primary-500 hover:bg-primary-50 transition-colors focus:ring-2 focus:ring-primary-500"
                        >
                            <div class="text-sm font-medium text-gray-900">{{ $date['day_name'] }}</div>
                            <div class="text-lg font-bold text-primary-600">{{ $date['day'] }}</div>
                            <div class="text-xs text-gray-500">{{ $date['month'] }}</div>
                        </button>
                        @endforeach
                    </div>
                </div>
                
                <!-- Time Slot Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Seleziona l'orario
                    </label>
                    <div class="grid grid-cols-3 gap-2" id="time-grid-{{ $bookingId }}">
                        @foreach($timeSlots as $slot)
                        <button 
                            type="button"
                            onclick="selectTime('{{ $bookingId }}', '{{ $slot['time'] }}', this)"
                            class="time-option p-2 text-center border border-gray-300 rounded-lg hover:border-primary-500 hover:bg-primary-50 transition-colors focus:ring-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ $slot['available'] ? '' : 'disabled' }}
                        >
                            <div class="text-sm font-medium">{{ $slot['time'] }}</div>
                            @if(!$slot['available'])
                            <div class="text-xs text-red-600">Non disp.</div>
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Selected Appointment Summary -->
            <div id="appointment-summary-{{ $bookingId }}" class="hidden mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h4 class="font-medium text-blue-900 mb-2">Appuntamento selezionato:</h4>
                <div class="text-sm text-blue-800">
                    <span id="selected-date-{{ $bookingId }}"></span> alle <span id="selected-time-{{ $bookingId }}"></span>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <button 
                    type="button"
                    onclick="goToStep('{{ $bookingId }}', 2)"
                    class="bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    id="step-1-next-{{ $bookingId }}"
                    disabled
                >
                    Continua
                </button>
            </div>
        </div>
        
        <!-- Step 2: Personal Information -->
        <div id="step-2-content-{{ $bookingId }}" class="booking-step hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first-name-{{ $bookingId }}" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome *
                    </label>
                    <input 
                        type="text" 
                        id="first-name-{{ $bookingId }}" 
                        name="first_name"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    />
                </div>
                
                <div>
                    <label for="last-name-{{ $bookingId }}" class="block text-sm font-medium text-gray-700 mb-2">
                        Cognome *
                    </label>
                    <input 
                        type="text" 
                        id="last-name-{{ $bookingId }}" 
                        name="last_name"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    />
                </div>
                
                <div>
                    <label for="email-{{ $bookingId }}" class="block text-sm font-medium text-gray-700 mb-2">
                        Email *
                    </label>
                    <input 
                        type="email" 
                        id="email-{{ $bookingId }}" 
                        name="email"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    />
                </div>
                
                <div>
                    <label for="phone-{{ $bookingId }}" class="block text-sm font-medium text-gray-700 mb-2">
                        Telefono *
                    </label>
                    <input 
                        type="tel" 
                        id="phone-{{ $bookingId }}" 
                        name="phone"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    />
                </div>
                
                <div class="md:col-span-2">
                    <label for="fiscal-code-{{ $bookingId }}" class="block text-sm font-medium text-gray-700 mb-2">
                        Codice Fiscale
                    </label>
                    <input 
                        type="text" 
                        id="fiscal-code-{{ $bookingId }}" 
                        name="fiscal_code"
                        maxlength="16"
                        pattern="[A-Z0-9]{16}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 uppercase"
                    />
                </div>
                
                <div class="md:col-span-2">
                    <label for="notes-{{ $bookingId }}" class="block text-sm font-medium text-gray-700 mb-2">
                        Note aggiuntive
                    </label>
                    <textarea 
                        id="notes-{{ $bookingId }}" 
                        name="notes"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Inserisci eventuali note o richieste specifiche..."
                    ></textarea>
                </div>
            </div>
            
            <div class="mt-6 flex justify-between">
                <button 
                    type="button"
                    onclick="goToStep('{{ $bookingId }}', 1)"
                    class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300 transition-colors"
                >
                    Indietro
                </button>
                <button 
                    type="button"
                    onclick="goToStep('{{ $bookingId }}', 3)"
                    class="bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700 transition-colors"
                >
                    Continua
                </button>
            </div>
        </div>
        
        <!-- Step 3: Confirmation -->
        <div id="step-3-content-{{ $bookingId }}" class="booking-step hidden">
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Riepilogo prenotazione</h3>
                
                <!-- Appointment Details -->
                <div class="mb-6">
                    <h4 class="font-medium text-gray-900 mb-2">Dettagli appuntamento</h4>
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm text-gray-600">Servizio:</span>
                                <div class="font-medium">{{ $serviceTitle }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Data e ora:</span>
                                <div class="font-medium">
                                    <span id="confirm-date-{{ $bookingId }}"></span> alle <span id="confirm-time-{{ $bookingId }}"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Personal Information -->
                <div class="mb-6">
                    <h4 class="font-medium text-gray-900 mb-2">Dati personali</h4>
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm text-gray-600">Nome:</span>
                                <div class="font-medium" id="confirm-name-{{ $bookingId }}"></div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Email:</span>
                                <div class="font-medium" id="confirm-email-{{ $bookingId }}"></div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Telefono:</span>
                                <div class="font-medium" id="confirm-phone-{{ $bookingId }}"></div>
                            </div>
                            @if($requiresPayment)
                            <div>
                                <span class="text-sm text-gray-600">Costo:</span>
                                <div class="font-medium text-primary-600">€25.00</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Terms and Conditions -->
                <div class="mb-6">
                    <label class="flex items-start">
                        <input 
                            type="checkbox" 
                            id="terms-{{ $bookingId }}" 
                            name="terms"
                            required
                            class="mt-1 mr-3"
                        />
                        <span class="text-sm text-gray-600">
                            Accetto i <a href="/termini-servizio" class="text-primary-600 hover:text-primary-700">termini di servizio</a> e l'<a href="/privacy" class="text-primary-600 hover:text-primary-700">informativa sulla privacy</a> *
                        </span>
                    </label>
                </div>
                
                <!-- Privacy Notification -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <strong>Nota:</strong> Riceverai una conferma via email e SMS. Puoi cancellare la prenotazione fino a 24 ore prima dell'appuntamento.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between">
                <button 
                    type="button"
                    onclick="goToStep('{{ $bookingId }}', 2)"
                    class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300 transition-colors"
                >
                    Indietro
                </button>
                <button 
                    type="submit"
                    class="bg-primary-600 text-white px-8 py-2 rounded-md hover:bg-primary-700 transition-colors flex items-center"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Conferma prenotazione
                </button>
            </div>
        </div>
    </form>
    
    <!-- Loading Overlay -->
    <div id="loading-{{ $bookingId }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto mb-4"></div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Prenotazione in corso...</h3>
            <p class="text-gray-600">Stiamo elaborando la tua richiesta</p>
        </div>
    </div>
    
    <!-- Success Modal -->
    <div id="success-{{ $bookingId }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 text-center max-w-md">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Prenotazione confermata!</h3>
            <p class="text-gray-600 mb-6">Ti abbiamo inviato una email con tutti i dettagli dell'appuntamento.</p>
            <button 
                onclick="closeSuccessModal('{{ $bookingId }}')"
                class="bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700 transition-colors"
            >
                Chiudi
            </button>
        </div>
    </div>
</div>

<script>
// Global booking state
const bookingState = {};

// Initialize booking
document.addEventListener('DOMContentLoaded', function() {
    bookingState['{{ $bookingId }}'] = {
        currentStep: 1,
        selectedDate: null,
        selectedTime: null,
        formData: {}
    };
});

// Date selection
function selectDate(bookingId, date, element) {
    // Remove previous selection
    document.querySelectorAll(`#date-grid-${bookingId} .date-option`).forEach(btn => {
        btn.classList.remove('border-primary-500', 'bg-primary-50');
        btn.classList.add('border-gray-300');
    });
    
    // Add selection to clicked element
    element.classList.remove('border-gray-300');
    element.classList.add('border-primary-500', 'bg-primary-50');
    
    // Update state
    bookingState[bookingId].selectedDate = date;
    
    // Update summary
    updateAppointmentSummary(bookingId);
    
    // Enable time selection
    document.querySelectorAll(`#time-grid-${bookingId} .time-option`).forEach(btn => {
        if (!btn.disabled) {
            btn.classList.remove('opacity-50');
        }
    });
}

// Time selection
function selectTime(bookingId, time, element) {
    // Remove previous selection
    document.querySelectorAll(`#time-grid-${bookingId} .time-option`).forEach(btn => {
        btn.classList.remove('border-primary-500', 'bg-primary-50');
        btn.classList.add('border-gray-300');
    });
    
    // Add selection to clicked element
    element.classList.remove('border-gray-300');
    element.classList.add('border-primary-500', 'bg-primary-50');
    
    // Update state
    bookingState[bookingId].selectedTime = time;
    
    // Update summary
    updateAppointmentSummary(bookingId);
    
    // Enable continue button
    const nextBtn = document.getElementById(`step-1-next-${bookingId}`);
    nextBtn.disabled = false;
}

// Update appointment summary
function updateAppointmentSummary(bookingId) {
    const state = bookingState[bookingId];
    const summary = document.getElementById(`appointment-summary-${bookingId}`);
    const dateSpan = document.getElementById(`selected-date-${bookingId}`);
    const timeSpan = document.getElementById(`selected-time-${bookingId}`);
    
    if (state.selectedDate && state.selectedTime) {
        summary.classList.remove('hidden');
        dateSpan.textContent = state.selectedDate;
        timeSpan.textContent = state.selectedTime;
    } else {
        summary.classList.add('hidden');
    }
}

// Step navigation
function goToStep(bookingId, step) {
    const state = bookingState[bookingId];
    
    // Update step indicators
    for (let i = 1; i <= 3; i++) {
        const stepIndicator = document.getElementById(`step-${i}-${bookingId}`);
        if (i < step) {
            stepIndicator.className = 'w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-medium';
        } else if (i === step) {
            stepIndicator.className = 'w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm font-medium';
        } else {
            stepIndicator.className = 'w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium';
        }
    }
    
    // Hide all steps
    document.querySelectorAll(`.booking-step`).forEach(step => {
        step.classList.add('hidden');
    });
    
    // Show current step
    document.getElementById(`step-${step}-content-${bookingId}`).classList.remove('hidden');
    
    // Update confirmation data if going to step 3
    if (step === 3) {
        updateConfirmationData(bookingId);
    }
    
    state.currentStep = step;
}

// Update confirmation data
function updateConfirmationData(bookingId) {
    const state = bookingState[bookingId];
    
    // Appointment details
    document.getElementById(`confirm-date-${bookingId}`).textContent = state.selectedDate;
    document.getElementById(`confirm-time-${bookingId}`).textContent = state.selectedTime;
    
    // Personal information
    const firstName = document.getElementById(`first-name-${bookingId}`).value;
    const lastName = document.getElementById(`last-name-${bookingId}`).value;
    const email = document.getElementById(`email-${bookingId}`).value;
    const phone = document.getElementById(`phone-${bookingId}`).value;
    
    document.getElementById(`confirm-name-${bookingId}`).textContent = `${firstName} ${lastName}`;
    document.getElementById(`confirm-email-${bookingId}`).textContent = email;
    document.getElementById(`confirm-phone-${bookingId}`).textContent = phone;
}

// Handle booking submission
function handleBooking(event, bookingId) {
    event.preventDefault();
    
    const state = bookingState[bookingId];
    const formData = new FormData(event.target);
    
    // Show loading
    document.getElementById(`loading-${bookingId}`).classList.remove('hidden');
    
    // Prepare booking data
    const bookingData = {
        service: '{{ $serviceTitle }}',
        date: state.selectedDate,
        time: state.selectedTime,
        firstName: formData.get('first_name'),
        lastName: formData.get('last_name'),
        email: formData.get('email'),
        phone: formData.get('phone'),
        fiscalCode: formData.get('fiscal_code'),
        notes: formData.get('notes'),
        termsAccepted: formData.get('terms')
    };
    
    // Simulate API call
    setTimeout(() => {
        // Hide loading
        document.getElementById(`loading-${bookingId}`).classList.add('hidden');
        
        // Show success modal
        document.getElementById(`success-${bookingId}`).classList.remove('hidden');
        
        // Reset form
        event.target.reset();
        state.currentStep = 1;
        state.selectedDate = null;
        state.selectedTime = null;
        
        // Go back to step 1
        setTimeout(() => {
            goToStep(bookingId, 1);
        }, 2000);
    }, 2000);
}

// Close success modal
function closeSuccessModal(bookingId) {
    document.getElementById(`success-${bookingId}`).classList.add('hidden');
}

// Form validation
document.addEventListener('input', function(e) {
    if (e.target.type === 'email') {
        // Basic email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(e.target.value)) {
            e.target.setCustomValidity('Inserisci un\'email valida');
        } else {
            e.target.setCustomValidity('');
        }
    }
    
    if (e.target.pattern && e.target.id.includes('fiscal-code')) {
        if (e.target.value.length === 16) {
            e.target.setCustomValidity('');
        } else {
            e.target.setCustomValidity('Il codice fiscale deve avere 16 caratteri');
        }
    }
});
</script>

<style>
.service-booking {
    max-width: 100%;
    margin: 0 auto;
}

.date-option,
.time-option {
    cursor: pointer;
    transition: all 0.2s ease;
}

.date-option:hover,
.time-option:hover:not(:disabled) {
    transform: translateY(-1px);
}

.date-option.selected,
.time-option.selected {
    border-color: #3b82f6;
    background-color: #eff6ff;
}

.booking-step {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .service-booking {
        border-radius: 0;
        margin: 0 -1rem;
    }
    
    .grid-cols-3 {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .flex.justify-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .md\\:col-span-2 {
        grid-column: span 1;
    }
}

/* Print styles */
@media print {
    .service-booking {
        box-shadow: none;
        border: 1px solid #000;
    }
    
    .service-booking button {
        display: none;
    }
}

/* High contrast mode */
@media (prefers-contrast: high) {
    .service-booking {
        border-width: 2px;
        border-color: #000;
    }
    
    .date-option,
    .time-option {
        border-width: 2px;
    }
}
</style>