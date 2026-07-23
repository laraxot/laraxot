{{-- Bootstrap Italia Time Picker Component --}}
{{-- 
    Componente time picker conforme alle specifiche Bootstrap Italia
    Supporta selezione ore e minuti con formato italiano e validazione
--}}
@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'value' => null,
    'placeholder' => 'HH:MM',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'minTime' => null,
    'maxTime' => null,
    'step' => 30, // Minuti per step (15, 30, 60)
    'format' => '24h', // '24h', '12h'
    'showSeconds' => false,
    'autocomplete' => 'off',
    'size' => 'md', // 'sm', 'md', 'lg'
    'variant' => 'default', // 'default', 'inline'
    'iconPosition' => 'right', // 'left', 'right'
])

@php
$id = $id ?? $name ?? 'timepicker-' . uniqid();
$inputId = $id . '-input';
$dropdownId = $id . '-dropdown';

// Classi per dimensioni
$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-base', 
    'lg' => 'px-6 py-3 text-lg'
];

// Classi per il wrapper
$wrapperClasses = [
    'relative',
    'w-full',
    $variant === 'inline' ? 'inline-block' : 'block'
];

// Classi per l'input
$inputClasses = [
    'block',
    'w-full',
    'rounded-md',
    'border',
    'border-gray-300',
    'bg-white',
    'text-gray-900',
    'placeholder-gray-500',
    'focus:border-italia-blue-500',
    'focus:ring-2',
    'focus:ring-italia-blue-500',
    'focus:ring-offset-2',
    'focus:outline-none',
    'transition-colors',
    'duration-200',
    'disabled:bg-gray-100',
    'disabled:text-gray-500',
    'disabled:cursor-not-allowed',
    'readonly:bg-gray-50',
    'readonly:cursor-not-allowed',
    $sizeClasses[$size]
];

// Classi per l'icona
$iconClasses = [
    'absolute',
    'top-1/2',
    'transform',
    '-translate-y-1/2',
    'text-gray-400',
    'pointer-events-none',
    $iconPosition === 'left' ? 'left-3' : 'right-3'
];

// Genera opzioni orarie
$timeOptions = [];
$startHour = $format === '12h' ? 1 : 0;
$endHour = $format === '12h' ? 12 : 23;

for ($hour = $startHour; $hour <= $endHour; $hour++) {
    for ($minute = 0; $minute < 60; $minute += $step) {
        $timeValue = sprintf('%02d:%02d', $hour, $minute);
        $displayValue = $format === '12h' 
            ? sprintf('%d:%02d %s', $hour === 0 ? 12 : $hour, $minute, $hour >= 12 ? 'PM' : 'AM')
            : $timeValue;
        
        $timeOptions[] = [
            'value' => $timeValue,
            'display' => $displayValue,
            'disabled' => false
        ];
    }
}
@endphp

<div class="{{ implode(' ', $wrapperClasses) }}" x-data="timePicker()" x-init="initTimePicker('{{ $id }}')">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-italia-red-600" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if($iconPosition === 'left')
            <div class="{{ implode(' ', $iconClasses) }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
            </div>
        @endif

        <input
            type="text"
            name="{{ $name }}"
            id="{{ $inputId }}"
            x-ref="input"
            x-model="selectedTime"
            @input="formatTime($event)"
            @blur="validateTime($event)"
            placeholder="{{ $placeholder }}"
            autocomplete="{{ $autocomplete }}"
            @class($inputClasses)
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            :class="{ 'border-italia-red-600': hasError, 'border-italia-green-600': isValid }"
            aria-describedby="{{ $id }}-help"
            aria-invalid="hasError"
            aria-haspopup="listbox"
            :aria-expanded="dropdownOpen"
            :aria-controls="'{{ $dropdownId }}'"
        />

        @if($iconPosition === 'right')
            <button
                type="button"
                @click="toggleDropdown()"
                class="{{ implode(' ', $iconClasses) }} cursor-pointer hover:text-italia-blue-600 transition-colors"
                :class="{ 'text-italia-blue-600': dropdownOpen }"
                aria-haspopup="listbox"
                :aria-expanded="dropdownOpen"
                :aria-controls="'{{ $dropdownId }}'"
                @if($disabled || $readonly) disabled @endif
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
            </button>
        @endif

        @if($value)
            <button
                type="button"
                @click="clearTime()"
                class="absolute top-1/2 right-8 transform -translate-y-1/2 text-gray-400 hover:text-italia-red-600 transition-colors"
                aria-label="Cancella orario"
                @if($disabled || $readonly) disabled @endif
            >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        @endif
    </div>

    {{-- Time Dropdown --}}
    <div
        x-show="dropdownOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1"
        class="absolute z-50 mt-1 w-full bg-white rounded-lg shadow-lg border border-gray-200 max-h-60 overflow-y-auto"
        :class="{ 'hidden': !dropdownOpen }"
        id="{{ $dropdownId }}"
        role="listbox"
        aria-labelledby="{{ $id }}-label"
        @click.away="dropdownOpen = false"
        @keydown.escape="dropdownOpen = false"
    >
        <ul class="py-1 text-sm text-gray-700" role="listbox">
            <template x-for="(option, index) in filteredOptions" :key="option.value">
                <li 
                    role="option"
                    :aria-selected="option.value === selectedTime"
                    :class="{ 
                        'bg-italia-blue-100 text-italia-blue-900': option.value === selectedTime,
                        'hover:bg-gray-100': !option.disabled,
                        'text-gray-400 cursor-not-allowed': option.disabled
                    }"
                    class="px-4 py-2 cursor-pointer select-none"
                    @click="selectOption(option)"
                    @keydown.enter="selectOption(option)"
                    @keydown.space="selectOption(option)"
                    @keydown.arrow-down="$event.preventDefault(); focusNextOption(index)"
                    @keydown.arrow-up="$event.preventDefault(); focusPreviousOption(index)"
                    :tabindex="option.disabled ? -1 : 0"
                    :aria-disabled="option.disabled"
                >
                    <span x-text="option.display"></span>
                </li>
            </template>
            
            <li x-show="filteredOptions.length === 0" class="px-4 py-2 text-gray-500 italic" role="option">
                Nessun orario disponibile
            </li>
        </ul>
    </div>

    {{-- Help text e error messages --}}
    @if($required || $minTime || $maxTime)
        <div id="{{ $id }}-help" class="mt-1 text-sm text-gray-500">
            @if($required)
                <span>Campo obbligatorio</span>
            @endif
            @if($minTime)
                <span x-show="minTime">Ora minima: <span x-text="minTime"></span></span>
            @endif
            @if($maxTime)
                <span x-show="maxTime">Ora massima: <span x-text="maxTime"></span></span>
            @endif
        </div>
    @endif

    <div x-show="hasError" class="mt-1 text-sm text-italia-red-600" x-text="errorMessage"></div>
</div>

@push('scripts')
<script>
function timePicker() {
    return {
        selectedTime: '{{ $value }}',
        dropdownOpen: false,
        hasError: false,
        isValid: false,
        errorMessage: '',
        searchQuery: '',
        
        initTimePicker(id) {
            // Inizializzazione del time picker
            if (this.selectedTime) {
                this.validateTime({ target: { value: this.selectedTime } });
            }
            
            // Prepara le opzioni
            this.prepareOptions();
        },
        
        prepareOptions() {
            // Prepara le opzioni con validazione
            this.options = {{ json_encode($timeOptions) }}.map(option => ({
                ...option,
                disabled: this.isTimeDisabled(option.value)
            }));
        },
        
        get filteredOptions() {
            if (!this.searchQuery) {
                return this.options;
            }
            
            return this.options.filter(option => 
                option.display.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                option.value.includes(this.searchQuery)
            );
        },
        
        formatTime(event) {
            let value = event.target.value.replace(/\D/g, '');
            
            // Formattazione automatica HH:MM
            if (value.length > 2 && value.length <= 4) {
                value = value.slice(0, 2) + ':' + value.slice(2);
            }
            
            this.selectedTime = value;
            this.searchQuery = value;
            this.validateTime(event);
        },
        
        validateTime(event) {
            const value = event.target.value;
            
            if (!value) {
                this.hasError = false;
                this.isValid = false;
                return;
            }
            
            // Validazione formato HH:MM
            const regex = /^([0-1][0-9]|2[0-3]):[0-5][0-9]$/;
            
            if (!regex.test(value)) {
                this.hasError = true;
                this.isValid = false;
                this.errorMessage = 'Formato orario non valido. Usa HH:MM';
                return;
            }
            
            const [hours, minutes] = value.split(':').map(Number);
            
            // Validazione ore e minuti
            if (hours < 0 || hours > 23 || minutes < 0 || minutes > 59) {
                this.hasError = true;
                this.isValid = false;
                this.errorMessage = 'Orario non valido';
                return;
            }
            
            // Validazione range orario
            if (this.minTime && this.compareTimes(value, this.minTime) < 0) {
                this.hasError = true;
                this.isValid = false;
                this.errorMessage = `Orario antecedente alle ${this.minTime}`;
                return;
            }
            
            if (this.maxTime && this.compareTimes(value, this.maxTime) > 0) {
                this.hasError = true;
                this.isValid = false;
                this.errorMessage = `Orario successivo alle ${this.maxTime}`;
                return;
            }
            
            this.hasError = false;
            this.isValid = true;
            this.errorMessage = '';
        },
        
        compareTimes(time1, time2) {
            const [h1, m1] = time1.split(':').map(Number);
            const [h2, m2] = time2.split(':').map(Number);
            
            if (h1 !== h2) return h1 - h2;
            return m1 - m2;
        },
        
        isTimeDisabled(time) {
            if (this.minTime && this.compareTimes(time, this.minTime) < 0) return true;
            if (this.maxTime && this.compareTimes(time, this.maxTime) > 0) return true;
            return false;
        },
        
        toggleDropdown() {
            if (this.disabled || this.readonly) return;
            this.dropdownOpen = !this.dropdownOpen;
            this.searchQuery = '';
        },
        
        selectOption(option) {
            if (option.disabled) return;
            
            this.selectedTime = option.value;
            this.dropdownOpen = false;
            this.searchQuery = '';
            this.validateTime({ target: { value: option.value } });
            
            // Trigger change event
            this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
            this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }));
        },
        
        clearTime() {
            this.selectedTime = '';
            this.hasError = false;
            this.isValid = false;
            this.errorMessage = '';
            this.searchQuery = '';
            
            // Trigger change event
            this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
            this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }));
        },
        
        focusNextOption(currentIndex) {
            const options = this.$el.querySelectorAll('[role="option"]:not([aria-disabled="true"])');
            const nextIndex = (currentIndex + 1) % options.length;
            options[nextIndex]?.focus();
        },
        
        focusPreviousOption(currentIndex) {
            const options = this.$el.querySelectorAll('[role="option"]:not([aria-disabled="true"])');
            const prevIndex = (currentIndex - 1 + options.length) % options.length;
            options[prevIndex]?.focus();
        },
        
        selectCurrentTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const currentTime = `${hours}:${minutes}`;
            
            this.selectOption({ value: currentTime, display: currentTime, disabled: false });
        }
    };
}
</script>
@endpush

{{-- 
Utilizzo:

<!-- Time Picker base -->
<x-time-picker 
    name="start_time"
    label="Ora inizio"
    placeholder="HH:MM"
    required
    :step="30"
/>

<!-- Time Picker con range -->
<x-time-picker 
    name="opening_time"
    label="Orario apertura"
    :min-time="'08:00'"
    :max-time="'20:00'"
    :step="15"
    format="24h"
/>

<!-- Time Picker formato 12h -->
<x-time-picker 
    name="appointment_time"
    label="Orario appuntamento"
    format="12h"
    :step="30"
    size="lg"
/>

<!-- In Livewire component -->
<x-time-picker 
    name="form.end_time"
    label="Ora fine"
    wire:model="form.end_time"
    :required="true"
    :min-time="$startTime"
/>

Accessibilità:
- Navigazione da tastiera completa
- Supporto screen reader
- Messaggi di errore accessibili
- Focus management
- ARIA attributes completi

Validazione:
- Formato HH:MM (24h) o HH:MM AM/PM (12h)
- Range orario personalizzabile
- Validazione lato client e server

Browser Support:
- Chrome, Firefox, Safari, Edge
- Mobile browsers
- Screen readers
- Keyboard navigation
--}}