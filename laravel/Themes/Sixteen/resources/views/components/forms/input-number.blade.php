{{-- Bootstrap Italia Input Number Component --}}
{{-- 
    Componente input numerico conforme alle specifiche Bootstrap Italia
    Supporta incremento/decremento, validazione e formattazione
--}}
@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'value' => null,
    'placeholder' => '0',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'min' => null,
    'max' => null,
    'step' => 1,
    'precision' => 0, // Numero di decimali (0 per interi)
    'showButtons' => true,
    'autocomplete' => 'off',
    'size' => 'md', // 'sm', 'md', 'lg'
    'variant' => 'default', // 'default', 'inline'
])

@php
$id = $id ?? $name ?? 'input-number-' . uniqid();
$inputId = $id . '-input';

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
    $sizeClasses[$size],
    $showButtons ? 'pr-12' : ''
];

// Classi per i pulsanti
$buttonClasses = [
    'absolute',
    'right-1',
    'flex',
    'flex-col',
    'h-full',
    'justify-center',
    'w-10'
];

// Classi per i bottoni incremento/decremento
$btnClasses = [
    'flex',
    'items-center',
    'justify-center',
    'w-6',
    'h-6',
    'text-gray-400',
    'hover:text-italia-blue-600',
    'hover:bg-gray-100',
    'rounded',
    'transition-colors',
    'duration-200',
    'focus:outline-none',
    'focus:ring-2',
    'focus:ring-italia-blue-500',
    'focus:ring-offset-2'
];
@endphp

<div class="{{ implode(' ', $wrapperClasses) }}" x-data="inputNumber()" x-init="initInputNumber('{{ $id }}')">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-italia-red-600" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <input
            type="text"
            name="{{ $name }}"
            id="{{ $inputId }}"
            x-ref="input"
            x-model="displayValue"
            @input="formatNumber($event)"
            @blur="validateNumber($event)"
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

        @if($showButtons && !$readonly && !$disabled)
            <div class="{{ implode(' ', $buttonClasses) }}">
                <button
                    type="button"
                    @click="increment()"
                    @keydown.enter="increment()"
                    @keydown.space="increment()"
                    :disabled="isMaxReached"
                    :class="{ 'opacity-50 cursor-not-allowed': isMaxReached, 'hover:bg-gray-100': !isMaxReached }"
                    class="{{ implode(' ', $btnClasses) }} mb-px"
                    aria-label="Incrementa"
                >
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                
                <button
                    type="button"
                    @click="decrement()"
                    @keydown.enter="decrement()"
                    @keydown.space="decrement()"
                    :disabled="isMinReached"
                    :class="{ 'opacity-50 cursor-not-allowed': isMinReached, 'hover:bg-gray-100': !isMinReached }"
                    class="{{ implode(' ', $btnClasses) }}"
                    aria-label="Decrementa"
                >
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif
    </div>

    {{-- Help text e error messages --}}
    @if($required || $min !== null || $max !== null)
        <div id="{{ $id }}-help" class="mt-1 text-sm text-gray-500">
            @if($required)
                <span>Campo obbligatorio</span>
            @endif
            @if($min !== null)
                <span x-show="min !== null">Valore minimo: <span x-text="formatForDisplay(min)"></span></span>
            @endif
            @if($max !== null)
                <span x-show="max !== null">Valore massimo: <span x-text="formatForDisplay(max)"></span></span>
            @endif
        </div>
    @endif

    <div x-show="hasError" class="mt-1 text-sm text-italia-red-600" x-text="errorMessage"></div>
</div>

@push('scripts')
<script>
function inputNumber() {
    return {
        displayValue: '{{ $value }}',
        numericValue: {{ $value ?? 'null' }},
        hasError: false,
        isValid: false,
        errorMessage: '',
        
        initInputNumber(id) {
            // Inizializzazione dell'input numerico
            if (this.displayValue) {
                this.validateNumber({ target: { value: this.displayValue } });
            }
        },
        
        get isMinReached() {
            if (this.min === null || this.numericValue === null) return false;
            return this.numericValue <= this.min;
        },
        
        get isMaxReached() {
            if (this.max === null || this.numericValue === null) return false;
            return this.numericValue >= this.max;
        },
        
        get min() {
            return {{ $min !== null ? $min : 'null' }};
        },
        
        get max() {
            return {{ $max !== null ? $max : 'null' }};
        },
        
        get step() {
            return {{ $step }};
        },
        
        get precision() {
            return {{ $precision }};
        },
        
        formatNumber(event) {
            let value = event.target.value;
            
            // Rimuovi tutto tranne numeri, punto e segno meno
            value = value.replace(/[^0-9.,-]/g, '');
            
            // Sostituisci virgola con punto per decimali
            value = value.replace(',', '.');
            
            // Gestisci multipli punti decimali
            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }
            
            // Gestisci segno meno multiplo
            if ((value.match(/-/g) || []).length > 1) {
                value = value.replace(/-/g, '');
                if (value !== '') {
                    value = '-' + value;
                }
            }
            
            // Posiziona il segno meno all'inizio
            if (value.includes('-') && !value.startsWith('-')) {
                value = '-' + value.replace(/-/g, '');
            }
            
            this.displayValue = value;
            this.validateNumber(event);
        },
        
        validateNumber(event) {
            const value = event.target.value;
            
            if (!value) {
                this.hasError = false;
                this.isValid = false;
                this.numericValue = null;
                return;
            }
            
            // Converti in numero
            const numericValue = parseFloat(value);
            
            if (isNaN(numericValue)) {
                this.hasError = true;
                this.isValid = false;
                this.numericValue = null;
                this.errorMessage = 'Valore numerico non valido';
                return;
            }
            
            // Controlla precisione decimali
            const decimalParts = value.split('.');
            if (decimalParts.length > 1 && decimalParts[1].length > this.precision) {
                this.hasError = true;
                this.isValid = false;
                this.numericValue = null;
                this.errorMessage = `Massimo ${this.precision} decimali consentiti`;
                return;
            }
            
            // Validazione range
            if (this.min !== null && numericValue < this.min) {
                this.hasError = true;
                this.isValid = false;
                this.numericValue = null;
                this.errorMessage = `Valore minimo: ${this.formatForDisplay(this.min)}`;
                return;
            }
            
            if (this.max !== null && numericValue > this.max) {
                this.hasError = true;
                this.isValid = false;
                this.numericValue = null;
                this.errorMessage = `Valore massimo: ${this.formatForDisplay(this.max)}`;
                return;
            }
            
            this.hasError = false;
            this.isValid = true;
            this.numericValue = numericValue;
            this.errorMessage = '';
            
            // Formatta il valore per la visualizzazione
            this.displayValue = this.formatForDisplay(numericValue);
        },
        
        formatForDisplay(value) {
            if (value === null || isNaN(value)) return '';
            
            if (this.precision === 0) {
                return Math.round(value).toString();
            }
            
            return value.toFixed(this.precision).replace('.', ',');
        },
        
        increment() {
            if (this.disabled || this.readonly) return;
            
            const currentValue = this.numericValue || 0;
            let newValue = currentValue + this.step;
            
            if (this.max !== null) {
                newValue = Math.min(newValue, this.max);
            }
            
            this.numericValue = newValue;
            this.displayValue = this.formatForDisplay(newValue);
            this.hasError = false;
            this.isValid = true;
            
            // Trigger change event
            this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
            this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }));
        },
        
        decrement() {
            if (this.disabled || this.readonly) return;
            
            const currentValue = this.numericValue || 0;
            let newValue = currentValue - this.step;
            
            if (this.min !== null) {
                newValue = Math.max(newValue, this.min);
            }
            
            this.numericValue = newValue;
            this.displayValue = this.formatForDisplay(newValue);
            this.hasError = false;
            this.isValid = true;
            
            // Trigger change event
            this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
            this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }));
        },
        
        clearValue() {
            this.displayValue = '';
            this.numericValue = null;
            this.hasError = false;
            this.isValid = false;
            this.errorMessage = '';
            
            // Trigger change event
            this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
            this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }));
        }
    };
}
</script>
@endpush

{{-- 
Utilizzo:

<!-- Input Number base -->
<x-input-number 
    name="quantity"
    label="Quantità"
    placeholder="0"
    required
    :min="1"
    :max="100"
    :step="1"
/>

<!-- Input Number con decimali -->
<x-input-number 
    name="price"
    label="Prezzo"
    :value="9.99"
    :min="0"
    :step="0.01"
    :precision="2"
    :show-buttons="false"
/>

<!-- Input Number senza pulsanti -->
<x-input-number 
    name="age"
    label="Età"
    :min="18"
    :max="120"
    :show-buttons="false"
    size="lg"
/>

<!-- In Livewire component -->
<x-input-number 
    name="form.quantity"
    label="Quantità"
    wire:model="form.quantity"
    :required="true"
    :min="0"
    :step="1"
/>

Accessibilità:
- Navigazione da tastiera completa
- Supporto screen reader
- Messaggi di errore accessibili
- Focus management
- ARIA attributes completi

Validazione:
- Valori numerici
- Range personalizzabile
- Precisione decimali
- Validazione lato client e server

Browser Support:
- Chrome, Firefox, Safari, Edge
- Mobile browsers
- Screen readers
- Keyboard navigation
--}}