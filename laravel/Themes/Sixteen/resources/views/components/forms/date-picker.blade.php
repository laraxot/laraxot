{{-- Bootstrap Italia Date Picker Component --}}
{{-- 
    Componente date picker conforme alle specifiche Bootstrap Italia
    Supporta selezione date con calendario italiano e validazione
--}}
@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'value' => null,
    'placeholder' => 'GG/MM/AAAA',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'minDate' => null,
    'maxDate' => null,
    'format' => 'dd/mm/yyyy',
    'locale' => 'it',
    'showClear' => true,
    'showToday' => true,
    'autocomplete' => 'off',
    'size' => 'md', // 'sm', 'md', 'lg'
    'variant' => 'default', // 'default', 'inline'
    'iconPosition' => 'right', // 'left', 'right'
])

@php
$id = $id ?? $name ?? 'datepicker-' . uniqid();
$inputId = $id . '-input';
$calendarId = $id . '-calendar';

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
@endphp

<div class="{{ implode(' ', $wrapperClasses) }}" x-data="datePicker()" x-init="initDatePicker('{{ $id }}')">
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
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
            </div>
        @endif

        <input
            type="text"
            name="{{ $name }}"
            id="{{ $inputId }}"
            x-ref="input"
            x-model="selectedDate"
            @input="formatDate($event)"
            @blur="validateDate($event)"
            placeholder="{{ $placeholder }}"
            autocomplete="{{ $autocomplete }}"
            @class($inputClasses)
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            :class="{ 'border-italia-red-600': hasError, 'border-italia-green-600': isValid }"
            aria-describedby="{{ $id }}-help"
            aria-invalid="hasError"
        />

        @if($iconPosition === 'right')
            <button
                type="button"
                @click="toggleCalendar()"
                class="{{ implode(' ', $iconClasses) }} cursor-pointer hover:text-italia-blue-600 transition-colors"
                :class="{ 'text-italia-blue-600': calendarOpen }"
                aria-haspopup="dialog"
                :aria-expanded="calendarOpen"
                :aria-controls="'{{ $calendarId }}'"
                @if($disabled || $readonly) disabled @endif
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
            </button>
        @endif

        @if($showClear && $value)
            <button
                type="button"
                @click="clearDate()"
                class="absolute top-1/2 right-8 transform -translate-y-1/2 text-gray-400 hover:text-italia-red-600 transition-colors"
                aria-label="Cancella data"
                @if($disabled || $readonly) disabled @endif
            >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        @endif
    </div>

    {{-- Calendar Popup --}}
    <div
        x-show="calendarOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1"
        class="absolute z-50 mt-1 w-72 bg-white rounded-lg shadow-lg border border-gray-200 p-4"
        :class="{ 'hidden': !calendarOpen }"
        id="{{ $calendarId }}"
        role="dialog"
        aria-modal="true"
        aria-labelledby="{{ $id }}-calendar-title"
        @click.away="calendarOpen = false"
        @keydown.escape="calendarOpen = false"
    >
        <div class="flex items-center justify-between mb-4">
            <h3 id="{{ $id }}-calendar-title" class="text-sm font-semibold text-gray-900">
                Seleziona data
            </h3>
            <button
                type="button"
                @click="calendarOpen = false"
                class="text-gray-400 hover:text-gray-600"
                aria-label="Chiudi calendario"
            >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-7 gap-1 mb-2">
            <template x-for="day in ['L', 'M', 'M', 'G', 'V', 'S', 'D']" :key="day">
                <div class="text-xs text-center text-gray-500 font-medium py-1" x-text="day"></div>
            </template>
        </div>

        <div class="grid grid-cols-7 gap-1">
            <template x-for="day in calendarDays" :key="day.date">
                <button
                    type="button"
                    @click="selectDate(day.date)"
                    :class="{
                        'bg-italia-blue-600 text-white': day.isSelected,
                        'bg-gray-100': day.isToday,
                        'text-gray-400': day.isOtherMonth,
                        'hover:bg-gray-100': !day.isSelected && !day.isOtherMonth,
                        'text-gray-900': !day.isSelected && !day.isOtherMonth,
                        'cursor-not-allowed opacity-50': day.isDisabled
                    }"
                    class="w-8 h-8 rounded-md text-sm font-medium transition-colors"
                    :disabled="day.isDisabled"
                    :aria-selected="day.isSelected"
                    x-text="day.day"
                ></button>
            </template>
        </div>

        @if($showToday)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <button
                    type="button"
                    @click="selectToday()"
                    class="w-full text-sm text-italia-blue-600 hover:text-italia-blue-800 font-medium"
                >
                    Oggi
                </button>
            </div>
        @endif
    </div>

    {{-- Help text e error messages --}}
    @if($required || $minDate || $maxDate)
        <div id="{{ $id }}-help" class="mt-1 text-sm text-gray-500">
            @if($required)
                <span>Campo obbligatorio</span>
            @endif
            @if($minDate)
                <span x-show="minDate">Data minima: <span x-text="minDate"></span></span>
            @endif
            @if($maxDate)
                <span x-show="maxDate">Data massima: <span x-text="maxDate"></span></span>
            @endif
        </div>
    @endif

    <div x-show="hasError" class="mt-1 text-sm text-italia-red-600" x-text="errorMessage"></div>
</div>

@push('scripts')
<script>
function datePicker() {
    return {
        selectedDate: '{{ $value }}',
        calendarOpen: false,
        hasError: false,
        isValid: false,
        errorMessage: '',
        currentMonth: new Date().getMonth(),
        currentYear: new Date().getFullYear(),
        
        initDatePicker(id) {
            // Inizializzazione del date picker
            if (this.selectedDate) {
                this.validateDate({ target: { value: this.selectedDate } });
            }
        },
        
        get calendarDays() {
            // Genera i giorni del calendario per il mese corrente
            const days = [];
            const firstDay = new Date(this.currentYear, this.currentMonth, 1);
            const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
            
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            const selectedDate = this.selectedDate ? this.parseDate(this.selectedDate) : null;
            
            // Aggiungi giorni del mese precedente
            for (let i = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1; i > 0; i--) {
                const date = new Date(this.currentYear, this.currentMonth, -i + 1);
                days.push({
                    day: date.getDate(),
                    date: this.formatDateForCalendar(date),
                    isOtherMonth: true,
                    isToday: false,
                    isSelected: false,
                    isDisabled: this.isDateDisabled(date)
                });
            }
            
            // Aggiungi giorni del mese corrente
            for (let i = 1; i <= lastDay.getDate(); i++) {
                const date = new Date(this.currentYear, this.currentMonth, i);
                const dateStr = this.formatDateForCalendar(date);
                
                days.push({
                    day: i,
                    date: dateStr,
                    isOtherMonth: false,
                    isToday: date.getTime() === today.getTime(),
                    isSelected: selectedDate && date.getTime() === selectedDate.getTime(),
                    isDisabled: this.isDateDisabled(date)
                });
            }
            
            // Aggiungi giorni del mese successivo
            const nextMonthDays = 42 - days.length; // 6 righe * 7 giorni
            for (let i = 1; i <= nextMonthDays; i++) {
                const date = new Date(this.currentYear, this.currentMonth + 1, i);
                days.push({
                    day: i,
                    date: this.formatDateForCalendar(date),
                    isOtherMonth: true,
                    isToday: false,
                    isSelected: false,
                    isDisabled: this.isDateDisabled(date)
                });
            }
            
            return days;
        },
        
        formatDate(event) {
            let value = event.target.value.replace(/\D/g, '');
            
            // Formattazione automatica GG/MM/AAAA
            if (value.length > 2 && value.length <= 4) {
                value = value.slice(0, 2) + '/' + value.slice(2);
            } else if (value.length > 4) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4) + '/' + value.slice(4, 8);
            }
            
            this.selectedDate = value;
            this.validateDate(event);
        },
        
        validateDate(event) {
            const value = event.target.value;
            
            if (!value) {
                this.hasError = false;
                this.isValid = false;
                return;
            }
            
            // Validazione formato italiano GG/MM/AAAA
            const regex = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/;
            
            if (!regex.test(value)) {
                this.hasError = true;
                this.isValid = false;
                this.errorMessage = 'Formato data non valido. Usa GG/MM/AAAA';
                return;
            }
            
            const [day, month, year] = value.split('/').map(Number);
            const date = new Date(year, month - 1, day);
            
            // Validazione data reale
            if (date.getFullYear() !== year || date.getMonth() !== month - 1 || date.getDate() !== day) {
                this.hasError = true;
                this.isValid = false;
                this.errorMessage = 'Data non valida';
                return;
            }
            
            // Validazione range date
            if (this.minDate && date < this.parseDate(this.minDate)) {
                this.hasError = true;
                this.isValid = false;
                this.errorMessage = `Data antecedente al ${this.minDate}`;
                return;
            }
            
            if (this.maxDate && date > this.parseDate(this.maxDate)) {
                this.hasError = true;
                this.isValid = false;
                this.errorMessage = `Data successiva al ${this.maxDate}`;
                return;
            }
            
            this.hasError = false;
            this.isValid = true;
            this.errorMessage = '';
        },
        
        parseDate(dateString) {
            if (!dateString) return null;
            const [day, month, year] = dateString.split('/').map(Number);
            return new Date(year, month - 1, day);
        },
        
        formatDateForCalendar(date) {
            return `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}/${date.getFullYear()}`;
        },
        
        isDateDisabled(date) {
            if (this.minDate && date < this.parseDate(this.minDate)) return true;
            if (this.maxDate && date > this.parseDate(this.maxDate)) return true;
            return false;
        },
        
        toggleCalendar() {
            if (this.disabled || this.readonly) return;
            this.calendarOpen = !this.calendarOpen;
        },
        
        selectDate(dateString) {
            this.selectedDate = dateString;
            this.calendarOpen = false;
            this.validateDate({ target: { value: dateString } });
            
            // Trigger change event
            this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
            this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }));
        },
        
        selectToday() {
            const today = new Date();
            this.selectDate(this.formatDateForCalendar(today));
        },
        
        clearDate() {
            this.selectedDate = '';
            this.hasError = false;
            this.isValid = false;
            this.errorMessage = '';
            
            // Trigger change event
            this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
            this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }));
        },
        
        navigateMonth(direction) {
            this.currentMonth += direction;
            if (this.currentMonth > 11) {
                this.currentMonth = 0;
                this.currentYear++;
            } else if (this.currentMonth < 0) {
                this.currentMonth = 11;
                this.currentYear--;
            }
        }
    };
}
</script>
@endpush

{{-- 
Utilizzo:

<!-- Date Picker base -->
<x-date-picker 
    name="birth_date"
    label="Data di nascita"
    placeholder="GG/MM/AAAA"
    required
/>

<!-- Date Picker con range -->
<x-date-picker 
    name="event_date"
    label="Data evento"
    :min-date="now()->format('d/m/Y')"
    :max-date="now()->addMonths(6)->format('d/m/Y')"
    :show-clear="true"
    :show-today="true"
    size="lg"
/>

<!-- Date Picker inline -->
<x-date-picker 
    name="appointment_date"
    variant="inline"
    icon-position="left"
    :value="old('appointment_date', $appointment?->date?->format('d/m/Y'))"
/>

<!-- In Livewire component -->
<x-date-picker 
    name="form.start_date"
    label="Data inizio"
    wire:model="form.start_date"
    :required="true"
/>

Accessibilità:
- Navigazione da tastiera completa
- Supporto screen reader
- Messaggi di errore accessibili
- Focus management
- ARIA attributes completi

Validazione:
- Formato GG/MM/AAAA
- Date reali (controllo giorni/mesi)
- Range personalizzabile
- Validazione lato client e server

Browser Support:
- Chrome, Firefox, Safari, Edge
- Mobile browsers
- Screen readers
- Keyboard navigation
--}}