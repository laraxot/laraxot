# Form Components - Bootstrap Italia to Tailwind

## Panoramica

Questa documentazione copre la migrazione dei componenti form da Bootstrap Italia a Tailwind CSS, mantenendo la conformità alle Linee Guida di Design PA e garantendo piena accessibilità.

## Input Base

### Bootstrap Italia
```html
<div class="form-group">
  <label for="input-text">Etichetta del campo</label>
  <input type="text" class="form-control" id="input-text" placeholder="Testo di esempio">
  <small class="form-text text-muted">Testo di aiuto</small>
</div>
```

### Tailwind CSS (Sixteen)
```html
<div class="space-y-2">
  <label for="input-text" class="block text-sm font-medium text-gray-700">
    Etichetta del campo
  </label>
  <input 
    type="text" 
    id="input-text" 
    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors placeholder-gray-400"
    placeholder="Testo di esempio"
  >
  <p class="text-sm text-gray-600">Testo di aiuto</p>
</div>
```

## Input con Validazione

### Stati di Validazione

#### Input Valido
```html
<div class="space-y-2">
  <label for="input-valid" class="block text-sm font-medium text-gray-700">
    Campo valido
  </label>
  <input 
    type="text" 
    id="input-valid" 
    class="w-full px-4 py-3 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
    value="Valore valido"
  >
  <p class="text-sm text-green-600 flex items-center">
    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
    </svg>
    Campo compilato correttamente
  </p>
</div>
```

#### Input con Errore
```html
<div class="space-y-2">
  <label for="input-error" class="block text-sm font-medium text-gray-700">
    Campo obbligatorio
  </label>
  <input 
    type="text" 
    id="input-error" 
    class="w-full px-4 py-3 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
    aria-invalid="true"
    aria-describedby="input-error-message"
  >
  <p id="input-error-message" class="text-sm text-red-600 flex items-center">
    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
    </svg>
    Questo campo è obbligatorio
  </p>
</div>
```

## Select

### Bootstrap Italia
```html
<div class="form-group">
  <label for="select-example">Scegli un'opzione</label>
  <select class="form-control" id="select-example">
    <option value="">Seleziona...</option>
    <option value="1">Opzione 1</option>
    <option value="2">Opzione 2</option>
  </select>
</div>
```

### Tailwind CSS (Sixteen)
```html
<div class="space-y-2">
  <label for="select-example" class="block text-sm font-medium text-gray-700">
    Scegli un'opzione
  </label>
  <select 
    id="select-example" 
    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white"
  >
    <option value="">Seleziona...</option>
    <option value="1">Opzione 1</option>
    <option value="2">Opzione 2</option>
  </select>
</div>
```

## Textarea

### Bootstrap Italia
```html
<div class="form-group">
  <label for="textarea-example">Messaggio</label>
  <textarea class="form-control" id="textarea-example" rows="3" placeholder="Scrivi qui il tuo messaggio"></textarea>
</div>
```

### Tailwind CSS (Sixteen)
```html
<div class="space-y-2">
  <label for="textarea-example" class="block text-sm font-medium text-gray-700">
    Messaggio
  </label>
  <textarea 
    id="textarea-example" 
    rows="3" 
    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors placeholder-gray-400 resize-vertical"
    placeholder="Scrivi qui il tuo messaggio"
  ></textarea>
</div>
```

## Checkbox

### Bootstrap Italia
```html
<div class="form-check">
  <input class="form-check-input" type="checkbox" id="checkbox-example">
  <label class="form-check-label" for="checkbox-example">
    Accetto i termini e condizioni
  </label>
</div>
```

### Tailwind CSS (Sixteen)
```html
<div class="flex items-start space-x-3">
  <input 
    type="checkbox" 
    id="checkbox-example" 
    class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
  >
  <label for="checkbox-example" class="text-sm text-gray-700 leading-5">
    Accetto i termini e condizioni
  </label>
</div>
```

## Radio Button

### Bootstrap Italia
```html
<div class="form-check">
  <input class="form-check-input" type="radio" name="radio-example" id="radio1" value="option1">
  <label class="form-check-label" for="radio1">Opzione 1</label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" name="radio-example" id="radio2" value="option2">
  <label class="form-check-label" for="radio2">Opzione 2</label>
</div>
```

### Tailwind CSS (Sixteen)
```html
<div class="space-y-3">
  <div class="flex items-center space-x-3">
    <input 
      type="radio" 
      name="radio-example" 
      id="radio1" 
      value="option1" 
      class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-2 focus:ring-blue-500"
    >
    <label for="radio1" class="text-sm text-gray-700">Opzione 1</label>
  </div>
  <div class="flex items-center space-x-3">
    <input 
      type="radio" 
      name="radio-example" 
      id="radio2" 
      value="option2" 
      class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-2 focus:ring-blue-500"
    >
    <label for="radio2" class="text-sm text-gray-700">Opzione 2</label>
  </div>
</div>
```

## File Upload

### Bootstrap Italia
```html
<div class="form-group">
  <label for="file-upload">Carica file</label>
  <input type="file" class="form-control-file" id="file-upload">
  <small class="form-text text-muted">Formati supportati: PDF, DOC, DOCX</small>
</div>
```

### Tailwind CSS (Sixteen)
```html
<div class="space-y-2">
  <label for="file-upload" class="block text-sm font-medium text-gray-700">
    Carica file
  </label>
  <div class="relative">
    <input 
      type="file" 
      id="file-upload" 
      class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 file:cursor-pointer cursor-pointer border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
    >
  </div>
  <p class="text-sm text-gray-600">Formati supportati: PDF, DOC, DOCX</p>
</div>
```

## Input con Icone

### Input con Icona a Sinistra
```html
<div class="space-y-2">
  <label for="input-icon-left" class="block text-sm font-medium text-gray-700">
    Email
  </label>
  <div class="relative">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
      </svg>
    </div>
    <input 
      type="email" 
      id="input-icon-left" 
      class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
      placeholder="nome@esempio.it"
    >
  </div>
</div>
```

### Input con Icona a Destra
```html
<div class="space-y-2">
  <label for="input-icon-right" class="block text-sm font-medium text-gray-700">
    Cerca
  </label>
  <div class="relative">
    <input 
      type="search" 
      id="input-icon-right" 
      class="w-full pl-4 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
      placeholder="Inserisci termine di ricerca"
    >
    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
      <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
      </svg>
    </div>
  </div>
</div>
```

## Input Numerico

### Bootstrap Italia
```html
<div class="form-group">
  <label for="input-number">Età</label>
  <input type="number" class="form-control" id="input-number" min="18" max="100" step="1">
</div>
```

### Tailwind CSS (Sixteen)
```html
<div class="space-y-2">
  <label for="input-number" class="block text-sm font-medium text-gray-700">
    Età
  </label>
  <input 
    type="number" 
    id="input-number" 
    min="18" 
    max="100" 
    step="1" 
    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
  >
</div>
```

## Input Data

### Bootstrap Italia
```html
<div class="form-group">
  <label for="input-date">Data di nascita</label>
  <input type="date" class="form-control" id="input-date">
</div>
```

### Tailwind CSS (Sixteen)
```html
<div class="space-y-2">
  <label for="input-date" class="block text-sm font-medium text-gray-700">
    Data di nascita
  </label>
  <input 
    type="date" 
    id="input-date" 
    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
  >
</div>
```

## Form Layout

### Form a Colonne
```html
<form class="space-y-6">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="space-y-2">
      <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
      <input type="text" id="nome" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
    </div>
    <div class="space-y-2">
      <label for="cognome" class="block text-sm font-medium text-gray-700">Cognome</label>
      <input type="text" id="cognome" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
    </div>
  </div>
  
  <div class="space-y-2">
    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
    <input type="email" id="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
  </div>
  
  <div class="flex justify-end space-x-4">
    <button type="button" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
      Annulla
    </button>
    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
      Invia
    </button>
  </div>
</form>
```

## Componenti Blade per Form

### Input Component
```blade
{{-- resources/views/components/ui/input.blade.php --}}
@props([
    'type' => 'text',
    'label' => null,
    'help' => null,
    'error' => null,
    'required' => false,
    'icon' => null,
    'iconPosition' => 'left'
])

@php
$inputClasses = collect([
    'w-full px-4 py-3 border rounded-lg transition-colors',
    $error ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500',
    $icon && $iconPosition === 'left' ? 'pl-10' : '',
    $icon && $iconPosition === 'right' ? 'pr-10' : '',
    'focus:ring-2'
])->filter()->implode(' ');
@endphp

<div class="space-y-2">
    @if($label)
        <label {{ $attributes->only('id')->mapWithKeys(fn($value, $key) => ['for' => $value]) }} class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        @if($icon && $iconPosition === 'left')
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                {!! $icon !!}
            </div>
        @endif
        
        <input 
            type="{{ $type }}" 
            {{ $attributes->merge(['class' => $inputClasses]) }}
            @if($error) aria-invalid="true" aria-describedby="{{ $attributes->get('id') }}-error" @endif
        >
        
        @if($icon && $iconPosition === 'right')
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                {!! $icon !!}
            </div>
        @endif
    </div>
    
    @if($help && !$error)
        <p class="text-sm text-gray-600">{{ $help }}</p>
    @endif
    
    @if($error)
        <p id="{{ $attributes->get('id') }}-error" class="text-sm text-red-600 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            {{ $error }}
        </p>
    @endif
</div>
```

### Select Component
```blade
{{-- resources/views/components/ui/select.blade.php --}}
@props([
    'label' => null,
    'help' => null,
    'error' => null,
    'required' => false,
    'options' => [],
    'placeholder' => null
])

@php
$selectClasses = collect([
    'w-full px-4 py-3 border rounded-lg transition-colors bg-white',
    $error ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500',
    'focus:ring-2'
])->filter()->implode(' ');
@endphp

<div class="space-y-2">
    @if($label)
        <label {{ $attributes->only('id')->mapWithKeys(fn($value, $key) => ['for' => $value]) }} class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <select {{ $attributes->merge(['class' => $selectClasses]) }}>
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $value => $text)
            <option value="{{ $value }}">{{ $text }}</option>
        @endforeach
        
        {{ $slot }}
    </select>
    
    @if($help && !$error)
        <p class="text-sm text-gray-600">{{ $help }}</p>
    @endif
    
    @if($error)
        <p class="text-sm text-red-600 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            {{ $error }}
        </p>
    @endif
</div>
```

## Accessibilità

### Attributi ARIA Essenziali
- `aria-invalid="true"` per campi con errori
- `aria-describedby` per collegare messaggi di aiuto/errore
- `aria-required="true"` per campi obbligatori
- `role="group"` per gruppi di radio/checkbox correlati

### Focus Management
```css
/* Stili focus personalizzati per accessibilità */
.focus-visible:focus {
  @apply ring-2 ring-blue-500 ring-offset-2;
}

/* Rimuovi outline di default solo se focus-visible è supportato */
.focus-visible:focus:not(.focus-visible) {
  outline: none;
}
```

### Screen Reader Support
```html
<!-- Label nascosta per screen reader -->
<label for="search" class="sr-only">Cerca nel sito</label>

<!-- Descrizione aggiuntiva per screen reader -->
<span class="sr-only">Campo obbligatorio</span>

<!-- Istruzioni per screen reader -->
<div id="password-help" class="sr-only">
  La password deve contenere almeno 8 caratteri, una maiuscola e un numero
</div>
<input type="password" aria-describedby="password-help">
```

## Testing e Validazione

### Checklist Accessibilità
- [ ] Tutti i campi hanno label associati
- [ ] Messaggi di errore sono collegati con aria-describedby
- [ ] Focus è visibile e logico
- [ ] Contrasto colori è conforme WCAG 2.1 AA
- [ ] Funziona con screen reader
- [ ] Navigazione da tastiera completa

### Test Automatici
```javascript
// Test con axe-core per accessibilità
import { axe, toHaveNoViolations } from 'jest-axe';

test('form should be accessible', async () => {
  const { container } = render(<FormComponent />);
  const results = await axe(container);
  expect(results).toHaveNoViolations();
});
```
