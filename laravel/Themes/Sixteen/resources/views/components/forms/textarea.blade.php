{{-- Bootstrap Italia Textarea Component --}}
{{-- 
    Componente textarea conforme alle specifiche Bootstrap Italia
    Supporta validazione, resize e accessibilità completa
--}}
@props([
    'name' => null,
    'id' => null,
    'value' => null,
    'placeholder' => null,
    'label' => null,
    'description' => null,
    'error' => null,
    'help' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autofocus' => false,
    'rows' => 3,
    'cols' => null,
    'maxlength' => null,
    'minlength' => null,
    'size' => 'md', // 'sm', 'md', 'lg'
    'variant' => 'default', // 'default', 'success', 'warning', 'danger'
    'resize' => 'vertical', // 'none', 'both', 'horizontal', 'vertical'
    'autosize' => false,
    'class' => null,
    'textareaClass' => null,
    'labelClass' => null,
])

@php
$textareaId = $id ?? $name ?? 'textarea-' . uniqid();

// Classi per dimensioni
$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-base',
    'lg' => 'px-6 py-3 text-lg',
];

// Classi per varianti
$variantClasses = [
    'default' => 'border-gray-300 focus:border-italia-blue-500 focus:ring-italia-blue-500',
    'success' => 'border-green-500 focus:border-green-500 focus:ring-green-500',
    'warning' => 'border-yellow-500 focus:border-yellow-500 focus:ring-yellow-500',
    'danger' => 'border-red-500 focus:border-red-500 focus:ring-red-500',
];

// Classi per resize
$resizeClasses = [
    'none' => 'resize-none',
    'both' => 'resize',
    'horizontal' => 'resize-x',
    'vertical' => 'resize-y',
];

$textareaClasses = [
    'block',
    'w-full',
    'border',
    'rounded-md',
    'shadow-sm',
    'transition-colors',
    'duration-200',
    'focus:outline-none',
    'focus:ring-1',
    'focus:ring-offset-0',
    'disabled:bg-gray-50',
    'disabled:text-gray-500',
    'disabled:cursor-not-allowed',
    'read-only:bg-gray-50',
    'read-only:cursor-default',
    $sizeClasses[$size],
    $variantClasses[$variant],
    $resizeClasses[$resize],
    $error ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '',
    $textareaClass
];

$labelClasses = [
    'block',
    'text-sm',
    'font-medium',
    'text-gray-700',
    'mb-1',
    $error ? 'text-red-700' : '',
    $labelClass
];
@endphp

<div class="space-y-1" x-data="{ 
    value: @js($value ?? ''),
    autoResize() {
        if (@js($autosize)) {
            this.$refs.textarea.style.height = 'auto';
            this.$refs.textarea.style.height = this.$refs.textarea.scrollHeight + 'px';
        }
    }
}" x-init="autoResize()">
    @if($label)
        <label 
            for="{{ $textareaId }}" 
            @class($labelClasses)
        >
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-1" aria-label="Campo obbligatorio">*</span>
            @endif
        </label>
    @endif

    @if($description)
        <p class="text-sm text-gray-600 mb-2">
            {{ $description }}
        </p>
    @endif

    <textarea
        name="{{ $name }}"
        id="{{ $textareaId }}"
        rows="{{ $rows }}"
        @if($cols) cols="{{ $cols }}" @endif
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        @if($autofocus) autofocus @endif
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        @if($minlength) minlength="{{ $minlength }}" @endif
        @class($textareaClasses)
        x-model="value"
        x-ref="textarea"
        @input="autoResize()"
        aria-describedby="{{ $textareaId }}-description {{ $textareaId }}-help {{ $textareaId }}-error"
        @if($error) aria-invalid="true" @endif
    >{{ $value }}</textarea>

    @if($maxlength)
        <div class="flex justify-between items-center mt-1">
            <div></div>
            <span class="text-xs text-gray-500" x-text="`${value.length}/{{ $maxlength }}`"></span>
        </div>
    @endif

    @if($help && !$error)
        <p id="{{ $textareaId }}-help" class="text-sm text-gray-500">
            {{ $help }}
        </p>
    @endif

    @if($error)
        <p id="{{ $textareaId }}-error" class="text-sm text-red-600" role="alert">
            {{ $error }}
        </p>
    @endif
</div>

{{-- 
Utilizzo:

<!-- Textarea base -->
<x-textarea 
<x-textarea 
    name="message"
    label="Messaggio"
    placeholder="Inserisci il tuo messaggio..."
    rows="4"
/>

<!-- Textarea con validazione -->
<x-textarea 
<x-textarea 
    name="description"
    label="Descrizione"
    placeholder="Descrivi il prodotto..."
    required
    :error="$errors->first('description')"
/>

<!-- Textarea con contatore caratteri -->
<x-textarea 
<x-textarea 
    name="comment"
    label="Commento"
    placeholder="Lascia un commento..."
    maxlength="500"
    rows="3"
/>

<!-- Textarea con autosize -->
<x-textarea 
<x-textarea 
    name="content"
    label="Contenuto"
    placeholder="Inizia a scrivere..."
    autosize
    rows="2"
/>

<!-- Textarea con descrizione -->
<x-textarea 
<x-textarea 
    name="feedback"
    label="Feedback"
    description="Condividi la tua opinione sui nostri servizi"
    help="Il feedback è anonimo e ci aiuta a migliorare"
    rows="5"
/>

<!-- Textarea disabilitato -->
<x-textarea 
<x-textarea 
    name="disabled"
    label="Campo disabilitato"
    value="Questo campo non può essere modificato"
    disabled
    rows="2"
/>

<!-- Textarea readonly -->
<x-textarea 
<x-textarea 
    name="readonly"
    label="Campo solo lettura"
    value="Questo contenuto è di sola lettura"
    readonly
    rows="2"
/>

<!-- Textarea con variante success -->
<x-textarea 
<x-textarea 
    name="valid"
    label="Campo valido"
    value="Contenuto corretto"
    variant="success"
    rows="2"
/>

<!-- Textarea con variante danger -->
<x-textarea 
<x-textarea 
    name="invalid"
    label="Campo non valido"
    value="Contenuto con errori"
    variant="danger"
    error="Questo campo contiene errori"
    rows="2"
/>

<!-- Textarea con resize personalizzato -->
<x-textarea 
<x-textarea 
    name="resize-none"
    label="Non ridimensionabile"
    resize="none"
    rows="3"
/>

<x-textarea 
<x-textarea 
    name="resize-both"
    label="Ridimensionabile in entrambe le direzioni"
    resize="both"
    rows="3"
/>

<x-textarea 
<x-textarea 
    name="resize-horizontal"
    label="Ridimensionabile orizzontalmente"
    resize="horizontal"
    rows="3"
/>

<!-- Textarea con dimensioni -->
<x-textarea 
<x-textarea 
    name="small"
    label="Piccolo"
    size="sm"
    rows="2"
/>

<x-textarea 
<x-textarea 
    name="large"
    label="Grande"
    size="lg"
    rows="4"
/>

<!-- Textarea per form di contatto -->
<x-textarea 
<x-textarea 
    name="contact_message"
    label="Messaggio"
    placeholder="Descrivi la tua richiesta..."
    required
    rows="6"
    maxlength="1000"
    help="Massimo 1000 caratteri"
/>

<!-- Textarea per recensioni -->
<x-textarea 
<x-textarea 
    name="review"
    label="Recensione"
    placeholder="Condividi la tua esperienza..."
    rows="4"
    minlength="10"
    help="Scrivi almeno 10 caratteri per una recensione valida"
/>

Opzioni di resize:
- none: non ridimensionabile
- both: ridimensionabile in entrambe le direzioni
- horizontal: ridimensionabile orizzontalmente
- vertical: ridimensionabile verticalmente (default)

Dimensioni disponibili:
- sm: piccolo
- md: medio (default)
- lg: grande

Varianti disponibili:
- default: normale (default)
- success: verde (valido)
- warning: giallo (attenzione)
- danger: rosso (errore)

Funzionalità:
- Autosize: si adatta automaticamente al contenuto
- Contatore caratteri: mostra caratteri utilizzati/massimi
- Validazione: supporto per errori e help text
- Accessibilità: ARIA attributes completi

Accessibilità:
- Supporto screen reader
- ARIA attributes completi
- Navigazione da tastiera
- Focus management
- Error handling
- Help text associato
- Label associati
--}}
