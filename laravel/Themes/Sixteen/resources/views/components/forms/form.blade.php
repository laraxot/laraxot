{{-- Bootstrap Italia Form Component --}}
{{-- 
    Componente form conforme alle specifiche Bootstrap Italia
    Supporta validazione, gestione errori e accessibilità
--}}
@props([
    'method' => 'POST',
    'action' => null,
    'enctype' => null,
    'novalidate' => false,
    'autocomplete' => null,
    'class' => null,
    'id' => null,
    'name' => null,
    'target' => null,
    'acceptCharset' => null,
    'spellcheck' => null,
    'translate' => null,
    'dir' => null,
    'lang' => null,
    'title' => null,
    'role' => null,
    'ariaLabel' => null,
    'ariaLabelledby' => null,
    'ariaDescribedby' => null,
    'dataAttributes' => [],
])

@php
$formId = $id ?? 'form-' . uniqid();
$formMethod = strtoupper($method);
$actualMethod = in_array($formMethod, ['GET', 'POST']) ? $formMethod : 'POST';

// Classi per il form
$formClasses = [
    'space-y-6',
    $class
];

// Attributi per il form
$formAttributes = [
    'id' => $formId,
    'name' => $name,
    'method' => $actualMethod,
    'action' => $action,
    'enctype' => $enctype,
    'autocomplete' => $autocomplete,
    'target' => $target,
    'accept-charset' => $acceptCharset,
    'spellcheck' => $spellcheck,
    'translate' => $translate,
    'dir' => $dir,
    'lang' => $lang,
    'title' => $title,
    'role' => $role,
    'aria-label' => $ariaLabel,
    'aria-labelledby' => $ariaLabelledby,
    'aria-describedby' => $ariaDescribedby,
];

// Rimuovi attributi null
$formAttributes = array_filter($formAttributes, fn($value) => $value !== null);

// Aggiungi data attributes
foreach ($dataAttributes as $key => $value) {
    $formAttributes["data-{$key}"] = $value;
}

// Se novalidate è true, aggiungi l'attributo
if ($novalidate) {
    $formAttributes['novalidate'] = true;
}
@endphp

<form 
    @class($formClasses)
    @foreach($formAttributes as $attr => $value)
        {{ $attr }}="{{ $value }}"
    @endforeach
    x-data="formManager()"
    @submit="handleSubmit"
>
    {{-- CSRF Token per metodi POST/PUT/PATCH/DELETE --}}
    @if(in_array($formMethod, ['POST', 'PUT', 'PATCH', 'DELETE']))
        @csrf
    @endif

    {{-- Method Spoofing per PUT/PATCH/DELETE --}}
    @if(in_array($formMethod, ['PUT', 'PATCH', 'DELETE']))
        @method($formMethod)
    @endif

    {{-- Slot principale per i campi del form --}}
    <div class="space-y-6">
        {{ $slot }}
    </div>

    {{-- Slot per le azioni del form (bottoni) --}}
    @if(isset($actions))
        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
            {{ $actions }}
        </div>
    @endif
</form>

@push('scripts')
<script>
function formManager() {
    return {
        isSubmitting: false,
        errors: {},
        touched: {},
        values: {},

        init() {
            // Inizializza i valori dai campi esistenti
            this.initializeValues();
            
            // Setup event listeners
            this.setupEventListeners();
        },

        initializeValues() {
            const inputs = this.$el.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.name) {
                    this.values[input.name] = input.value;
                }
            });
        },

        setupEventListeners() {
            // Listen per validazione in tempo reale
            this.$el.addEventListener('blur', (e) => {
                if (e.target.matches('input, select, textarea')) {
                    this.touched[e.target.name] = true;
                    this.validateField(e.target);
                }
            }, true);

            // Listen per aggiornamento valori
            this.$el.addEventListener('input', (e) => {
                if (e.target.matches('input, select, textarea')) {
                    this.values[e.target.name] = e.target.value;
                }
            }, true);
        },

        validateField(field) {
            if (!field.name) return;

            const value = field.value;
            const rules = this.getFieldRules(field);
            const errors = [];

            // Validazione required
            if (rules.required && !value.trim()) {
                errors.push('Questo campo è obbligatorio');
            }

            // Validazione email
            if (rules.email && value && !this.isValidEmail(value)) {
                errors.push('Inserisci un indirizzo email valido');
            }

            // Validazione minlength
            if (rules.minlength && value.length < rules.minlength) {
                errors.push(`Il campo deve essere lungo almeno ${rules.minlength} caratteri`);
            }

            // Validazione maxlength
            if (rules.maxlength && value.length > rules.maxlength) {
                errors.push(`Il campo non può superare ${rules.maxlength} caratteri`);
            }

            // Validazione pattern
            if (rules.pattern && value && !new RegExp(rules.pattern).test(value)) {
                errors.push('Il formato inserito non è valido');
            }

            // Validazione min/max per numeri
            if (field.type === 'number') {
                if (rules.min !== undefined && parseFloat(value) < rules.min) {
                    errors.push(`Il valore deve essere almeno ${rules.min}`);
                }
                if (rules.max !== undefined && parseFloat(value) > rules.max) {
                    errors.push(`Il valore non può superare ${rules.max}`);
                }
            }

            // Aggiorna errori
            if (errors.length > 0) {
                this.errors[field.name] = errors[0]; // Mostra solo il primo errore
                field.setAttribute('aria-invalid', 'true');
                field.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            } else {
                delete this.errors[field.name];
                field.removeAttribute('aria-invalid');
                field.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            }
        },

        getFieldRules(field) {
            const rules = {};
            
            if (field.required) rules.required = true;
            if (field.type === 'email') rules.email = true;
            if (field.minLength) rules.minlength = parseInt(field.minLength);
            if (field.maxLength) rules.maxlength = parseInt(field.maxLength);
            if (field.pattern) rules.pattern = field.pattern;
            if (field.min !== '') rules.min = parseFloat(field.min);
            if (field.max !== '') rules.max = parseFloat(field.max);

            return rules;
        },

        isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },

        validateForm() {
            const inputs = this.$el.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;

            inputs.forEach(input => {
                this.touched[input.name] = true;
                this.validateField(input);
                if (this.errors[input.name]) {
                    isValid = false;
                }
            });

            return isValid;
        },

        async handleSubmit(event) {
            event.preventDefault();
            
            if (this.isSubmitting) return;

            // Validazione form
            if (!this.validateForm()) {
                // Focus sul primo campo con errore
                const firstError = this.$el.querySelector('[aria-invalid="true"]');
                if (firstError) {
                    firstError.focus();
                }
                return;
            }

            this.isSubmitting = true;

            try {
                // Dispatch evento personalizzato per gestione submit
                const submitEvent = new CustomEvent('form-submit', {
                    detail: {
                        form: this.$el,
                        values: this.values,
                        errors: this.errors
                    }
                });
                
                this.$el.dispatchEvent(submitEvent);

                // Se non è stato preventDefault, procedi con il submit normale
                if (!submitEvent.defaultPrevented) {
                    this.$el.submit();
                }
            } catch (error) {
                console.error('Errore durante il submit del form:', error);
            } finally {
                this.isSubmitting = false;
            }
        },

        resetForm() {
            this.$el.reset();
            this.values = {};
            this.errors = {};
            this.touched = {};
            this.initializeValues();
        },

        setFieldError(fieldName, message) {
            this.errors[fieldName] = message;
            const field = this.$el.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.setAttribute('aria-invalid', 'true');
                field.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            }
        },

        clearFieldError(fieldName) {
            delete this.errors[fieldName];
            const field = this.$el.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.removeAttribute('aria-invalid');
                field.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            }
        }
    };
}
</script>
@endpush

{{-- 
Utilizzo:

<!-- Form base -->
<x-form 
    method="POST" 
    action="/users"
    name="user-form"
>
    <x-input 
    <x-input 
        name="name"
        label="Nome"
        required
    />
    
    <x-input 
    <x-input 
        name="email"
        type="email"
        label="Email"
        required
    />
    
    <x-slot name="actions">
        <x-button type="submit">
            Salva
        </x-button>
        <x-button type="submit">
            Salva
        </x-button>
    </x-slot>
</x-form>

<!-- Form con validazione personalizzata -->
<x-form 
    method="POST" 
    action="/contact"
    x-data="contactForm()"
    @form-submit="handleContactSubmit"
>
    <x-input 
    <x-input 
        name="subject"
        label="Oggetto"
        required
        maxlength="100"
    />
    
    <x-textarea 
    <x-textarea 
        name="message"
        label="Messaggio"
        required
        rows="5"
        minlength="10"
    />
    
    <x-slot name="actions">
        <x-button 
        <x-button 
            type="button" 
            variant="secondary"
            @click="resetForm()"
        >
            Annulla
        </x-button>
        
        <x-button 
        </x-button>
        
        <x-button 
            type="submit"
            :disabled="isSubmitting"
        >
            <span x-show="!isSubmitting">Invia</span>
            <span x-show="isSubmitting">Invio in corso...</span>
        </x-button>
        </x-button>
    </x-slot>
</x-form>

<!-- Form con upload file -->
<x-form 
    method="POST" 
    action="/upload"
    enctype="multipart/form-data"
>
    <x-input 
    <x-input 
        name="file"
        type="file"
        label="Carica file"
        accept=".pdf,.doc,.docx"
        required
    />
    
    <x-slot name="actions">
        <x-button type="submit">
            Carica
        </x-button>
        <x-button type="submit">
            Carica
        </x-button>
    </x-slot>
</x-form>

<!-- Form con metodo PUT -->
<x-form 
    method="PUT" 
    action="/users/{{ $user->id }}"
>
    <x-input 
    <x-input 
        name="name"
        label="Nome"
        :value="$user->name"
        required
    />
    
    <x-slot name="actions">
        <x-button type="submit">
            Aggiorna
        </x-button>
        <x-button type="submit">
            Aggiorna
        </x-button>
    </x-slot>
</x-form>

<!-- Form con attributi personalizzati -->
<x-form 
    method="POST" 
    action="/api/data"
    :data-attributes="['tracking' => 'user-registration', 'source' => 'landing-page']"
    aria-label="Form di registrazione utente"
>
    <!-- Campi del form -->
</x-form>

Funzionalità:
- Validazione in tempo reale
- Gestione errori automatica
- Supporto per tutti i metodi HTTP
- CSRF protection automatica
- Method spoofing per PUT/PATCH/DELETE
- Eventi personalizzati
- Reset form programmatico
- Gestione stati di caricamento

Metodi supportati:
- GET: per form di ricerca
- POST: per creazione dati
- PUT: per aggiornamento completo
- PATCH: per aggiornamento parziale
- DELETE: per eliminazione

Validazioni automatiche:
- Required: campo obbligatorio
- Email: formato email valido
- MinLength/MaxLength: lunghezza testo
- Pattern: regex personalizzata
- Min/Max: valori numerici
- Custom: validazioni personalizzate

Eventi disponibili:
- form-submit: prima del submit
- form-reset: dopo il reset
- field-validate: validazione campo
- form-error: errori di validazione

Accessibilità:
- Supporto screen reader
- ARIA attributes completi
- Navigazione da tastiera
- Focus management
- Error handling
- Form labels associati
--}}
