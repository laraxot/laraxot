{{-- Bootstrap Italia Autocomplete Component --}}
{{-- 
    Componente autocomplete conforme alle specifiche Bootstrap Italia
    Supporta ricerca live, suggerimenti e selezione da lista
--}}
@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'value' => null,
    'placeholder' => 'Cerca...',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'options' => [], // Array di opzioni o URL per fetch
    'minLength' => 2, // Lunghezza minima per ricerca
    'debounce' => 300, // Debounce in millisecondi
    'maxResults' => 10, // Numero massimo risultati
    'showClear' => true,
    'autocomplete' => 'off',
    'size' => 'md', // 'sm', 'md', 'lg'
    'variant' => 'default', // 'default', 'inline'
    'loadingText' => 'Caricamento...',
    'noResultsText' => 'Nessun risultato trovato',
    'remoteUrl' => null, // URL per fetch remoto
    'remoteMethod' => 'GET', // Metodo HTTP per fetch
    'valueField' => 'value', // Campo per valore
    'textField' => 'text', // Campo per testo visualizzato
])

@php
$id = $id ?? $name ?? 'autocomplete-' . uniqid();
$inputId = $id . '-input';
$dropdownId = $id . '-dropdown';
$hiddenId = $id . '-hidden';

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
    'right-3',
    'transform',
    '-translate-y-1/2',
    'text-gray-400',
    'pointer-events-none'
];

// Prepara opzioni locali
$localOptions = is_array($options) ? $options : [];
@endphp

<div class="{{ implode(' ', $wrapperClasses) }}" x-data="autocomplete()" x-init="initAutocomplete('{{ $id }}')">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-italia-red-600" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        {{-- Input nascosto per valore reale --}}
        <input type="hidden" name="{{ $name }}" x-model="selectedValue" id="{{ $hiddenId }}">
        
        {{-- Input visibile per ricerca --}}
        <input
            type="text"
            id="{{ $inputId }}"
            x-ref="input"
            x-model="searchQuery"
            @input.debounce.{{ $debounce }}ms="handleInput($event)"
            @focus="handleFocus"
            @blur="handleBlur"
            @keydown.arrow-down="handleArrowDown"
            @keydown.arrow-up="handleArrowUp"
            @keydown.enter="handleEnter"
            @keydown.escape="handleEscape"
            placeholder="{{ $placeholder }}"
            autocomplete="{{ $autocomplete }}"
            @class($inputClasses)
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            :class="{ 'border-italia-blue-500': dropdownOpen }"
            aria-describedby="{{ $id }}-help"
            aria-autocomplete="list"
            aria-haspopup="listbox"
            :aria-expanded="dropdownOpen"
            :aria-controls="'{{ $dropdownId }}'"
            :aria-activedescendant="activeDescendant"
        />

        {{-- Icona di ricerca --}}
        <div class="{{ implode(' ', $iconClasses) }}" :class="{ 'hidden': searchQuery && showClear }">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
        </div>

        {{-- Pulsante cancella --}}
        <button
            x-show="searchQuery && showClear && !disabled && !readonly"
            type="button"
            @click="clearSearch"
            class="absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-400 hover:text-italia-red-600 transition-colors"
            aria-label="Cancella ricerca"
        >
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>

        {{-- Indicatore caricamento --}}
        <div 
            x-show="isLoading" 
            class="absolute top-1/2 right-8 transform -translate-y-1/2"
            aria-live="polite"
            aria-busy="true"
        >
            <svg class="w-4 h-4 animate-spin text-italia-blue-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="sr-only">Caricamento...</span>
        </div>
    </div>

    {{-- Dropdown risultati --}}
    <div
        x-show="dropdownOpen && (filteredOptions.length > 0 || isLoading)"
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
        {{-- Risultati caricamento --}}
        <template x-if="isLoading">
            <div class="px-4 py-2 text-sm text-gray-500" role="status">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 animate-spin text-italia-blue-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ $loadingText }}
                </div>
            </div>
        </template>

        {{-- Risultati ricerca --}}
        <template x-if="!isLoading && filteredOptions.length > 0">
            <ul class="py-1 text-sm text-gray-700" role="listbox">
                <template x-for="(option, index) in filteredOptions" :key="option.{{ $valueField }}">
                    <li 
                        :id="'{{ $id }}-option-' + index"
                        role="option"
                        :aria-selected="isSelected(option)"
                        :class="{ 
                            'bg-italia-blue-100 text-italia-blue-900': isSelected(option),
                            'hover:bg-gray-100': !option.disabled,
                            'text-gray-400 cursor-not-allowed': option.disabled
                        }"
                        class="px-4 py-2 cursor-pointer select-none"
                        @click="selectOption(option)"
                        @mouseenter="activeIndex = index"
                        @keydown.enter="selectOption(option)"
                        @keydown.space="selectOption(option)"
                        :tabindex="option.disabled ? -1 : 0"
                        :aria-disabled="option.disabled"
                    >
                        <span x-html="highlightMatch(option.{{ $textField }})"></span>
                        
                        {{-- Descrizione aggiuntiva --}}
                        <template x-if="option.description">
                            <div class="text-xs text-gray-500 mt-1" x-text="option.description"></div>
                        </template>
                    </li>
                </template>
            </ul>
        </template>

        {{-- Nessun risultato --}}
        <template x-if="!isLoading && filteredOptions.length === 0 && searchQuery.length >= minLength">
            <div class="px-4 py-2 text-sm text-gray-500 italic" role="status">
                {{ $noResultsText }}
            </div>
        </template>
    </div>

    {{-- Help text --}}
    @if($required || $minLength > 0)
        <div id="{{ $id }}-help" class="mt-1 text-sm text-gray-500">
            @if($required)
                <span>Campo obbligatorio</span>
            @endif
            @if($minLength > 0)
                <span x-show="minLength">Inserisci almeno {{ $minLength }} caratteri</span>
            @endif
        </div>
    @endif
</div>

@push('scripts')
<script>
function autocomplete() {
    return {
        searchQuery: '{{ $value ? ($options["text"] ?? $value) : "" }}',
        selectedValue: '{{ $value }}',
        dropdownOpen: false,
        isLoading: false,
        activeIndex: -1,
        options: {{ json_encode($localOptions) }},
        minLength: {{ $minLength }},
        maxResults: {{ $maxResults }},
        
        initAutocomplete(id) {
            // Inizializzazione autocomplete
            if (this.selectedValue && this.options.length > 0) {
                const selectedOption = this.options.find(opt => opt.{{ $valueField }} == this.selectedValue);
                if (selectedOption) {
                    this.searchQuery = selectedOption.{{ $textField }};
                }
            }
        },
        
        get activeDescendant() {
            if (this.activeIndex >= 0 && this.filteredOptions[this.activeIndex]) {
                return `{{ $id }}-option-${this.activeIndex}`;
            }
            return '';
        },
        
        get filteredOptions() {
            if (!this.searchQuery || this.searchQuery.length < this.minLength) {
                return [];
            }
            
            const query = this.searchQuery.toLowerCase();
            return this.options
                .filter(option => 
                    option.{{ $textField }}.toLowerCase().includes(query) ||
                    (option.description && option.description.toLowerCase().includes(query))
                )
                .slice(0, this.maxResults);
        },
        
        async handleInput(event) {
            const query = event.target.value;
            
            if (query.length < this.minLength) {
                this.dropdownOpen = false;
                return;
            }
            
            // Fetch remoto se configurato
            if ('{{ $remoteUrl }}' && query.length >= this.minLength) {
                await this.fetchRemoteOptions(query);
            }
            
            this.dropdownOpen = this.filteredOptions.length > 0;
            this.activeIndex = -1;
        },
        
        async fetchRemoteOptions(query) {
            this.isLoading = true;
            
            try {
                const response = await fetch('{{ $remoteUrl }}'.replace('{query}', encodeURIComponent(query)), {
                    method: '{{ $remoteMethod }}',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.options = Array.isArray(data) ? data : [];
                } else {
                    console.error('Errore nel fetch opzioni remote:', response.status);
                    this.options = [];
                }
            } catch (error) {
                console.error('Errore nel fetch opzioni remote:', error);
                this.options = [];
            } finally {
                this.isLoading = false;
            }
        },
        
        handleFocus() {
            if (this.searchQuery.length >= this.minLength && this.filteredOptions.length > 0) {
                this.dropdownOpen = true;
            }
        },
        
        handleBlur() {
            // Chiudi dropdown dopo un breve delay per permettere click sugli item
            setTimeout(() => {
                this.dropdownOpen = false;
            }, 200);
        },
        
        handleArrowDown(event) {
            event.preventDefault();
            
            if (!this.dropdownOpen && this.filteredOptions.length > 0) {
                this.dropdownOpen = true;
                this.activeIndex = 0;
                return;
            }
            
            if (this.filteredOptions.length > 0) {
                this.activeIndex = (this.activeIndex + 1) % this.filteredOptions.length;
                this.scrollToActive();
            }
        },
        
        handleArrowUp(event) {
            event.preventDefault();
            
            if (this.filteredOptions.length > 0) {
                this.activeIndex = (this.activeIndex - 1 + this.filteredOptions.length) % this.filteredOptions.length;
                this.scrollToActive();
            }
        },
        
        handleEnter(event) {
            event.preventDefault();
            
            if (this.activeIndex >= 0 && this.filteredOptions[this.activeIndex]) {
                this.selectOption(this.filteredOptions[this.activeIndex]);
            } else if (this.filteredOptions.length === 1) {
                this.selectOption(this.filteredOptions[0]);
            }
        },
        
        handleEscape() {
            this.dropdownOpen = false;
            this.activeIndex = -1;
        },
        
        scrollToActive() {
            const activeElement = this.$el.querySelector(`#{{ $id }}-option-${this.activeIndex}`);
            if (activeElement) {
                activeElement.scrollIntoView({ block: 'nearest' });
            }
        },
        
        isSelected(option) {
            return option.{{ $valueField }} == this.selectedValue;
        },
        
        selectOption(option) {
            if (option.disabled) return;
            
            this.selectedValue = option.{{ $valueField }};
            this.searchQuery = option.{{ $textField }};
            this.dropdownOpen = false;
            this.activeIndex = -1;
            
            // Trigger change event
            this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
            this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }));
            
            // Evento personalizzato per autocomplete
            this.$el.dispatchEvent(new CustomEvent('autocomplete-select', {
                detail: { option, value: option.{{ $valueField }} }
            }));
        },
        
        clearSearch() {
            this.searchQuery = '';
            this.selectedValue = '';
            this.dropdownOpen = false;
            this.activeIndex = -1;
            
            // Trigger change event
            this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
            this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }));
        },
        
        highlightMatch(text) {
            if (!this.searchQuery) return text;
            
            const query = this.searchQuery.toLowerCase();
            const index = text.toLowerCase().indexOf(query);
            
            if (index === -1) return text;
            
            return text.substring(0, index) +
                   '<mark class="bg-yellow-100">' + 
                   text.substring(index, index + query.length) +
                   '</mark>' +
                   text.substring(index + query.length);
        }
    };
}
</script>
@endpush

{{-- 
Utilizzo:

<!-- Autocomplete base con opzioni locali -->
<x-autocomplete 
    name="city"
    label="Città"
    :options="[
        ['value' => 'roma', 'text' => 'Roma'],
        ['value' => 'milano', 'text' => 'Milano'],
        ['value' => 'napoli', 'text' => 'Napoli']
    ]"
    placeholder="Cerca città..."
    :min-length="2"
    :max-results="5"
/>

<!-- Autocomplete con fetch remoto -->
<x-autocomplete 
    name="user_id"
    label="Utente"
    :remote-url="route('api.users.search', ['query' => '{query}'])"
    value-field="id"
    text-field="name"
    :debounce="500"
    :min-length="3"
    loading-text="Ricerca utenti..."
    no-results-text="Nessun utente trovato"
/>

<!-- Autocomplete con descrizioni -->
<x-autocomplete 
    name="service"
    label="Servizio"
    :options="[
        ['value' => 'spid', 'text' => 'SPID', 'description' => 'Sistema Pubblico Identità Digitale'],
        ['value' => 'cie', 'text' => 'CIE', 'description' => 'Carta Identità Elettronica'],
        ['value' => 'pago', 'text' => 'PagoPA', 'description' => 'Sistema pagamenti PA']
    ]"
    placeholder="Cerca servizio..."
    :show-clear="true"
    size="lg"
/>

<!-- In Livewire component -->
<x-autocomplete 
    name="form.category_id"
    label="Categoria"
    wire:model="form.category_id"
    :options="$categories"
    :required="true"
    value-field="id"
    text-field="name"
    @autocomplete-select="$wire.set('form.category_id', $event.detail.value)"
/>

Accessibilità:
- Navigazione da tastiera completa
- Supporto screen reader
- Focus management
- ARIA attributes completi
- Highlight risultati ricerca

Browser Support:
- Chrome, Firefox, Safari, Edge
- Mobile browsers
- Screen readers
- Keyboard navigation
--}}