# Confirmation Block - Appointment Confirmation

> *"La conferma è l'ultimo passo. Deve essere chiaro, rassicurante, completo."*

## 🎯 Panoramica

Blocco per confermare appuntamenti, prenotazioni, o operazioni completate.

**Tipo**: `confirmation`  
**Blade**: `with-details`, `simple`, `with-steps`, `with-actions`

---

## 📁 Struttura File

```
components/blocks/confirmation/
├── simple.blade.php           # Messaggio base
├── with-details.blade.php     # Con dettagli
├── with-steps.blade.php       # Con stepper
├── with-actions.blade.php     # Con azioni
└── success-card.blade.php     # Card successo
```

---

## 🎨 Componenti di Riferimento

### Flowbite
- Alert components
- Cards
- Steps indicators

### Tailwind Plus
- Marketing confirmation
- E-commerce order confirmation
- Application success states

### DaisyUI
- Alert (success, info)
- Steps (vertical, horizontal)
- Card with actions

### Bootstrap Italia
- Alert con icona
- Stepper verticale
- Callout box
- Notification

---

## 📋 Block Types

### 1. Simple Confirmation

**File**: `confirmation/simple.blade.php`

```blade
@props([
    'title' => 'Operazione Completata',
    'message' => 'La tua richiesta è stata elaborata con successo.',
    'icon' => 'check',
])

<div class="bg-white rounded-lg shadow-lg p-8 max-w-lg mx-auto text-center">
    {{-- Icon --}}
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
        @if($icon === 'check')
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        @elseif($icon === 'info')
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        @endif
    </div>
    
    {{-- Title --}}
    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $title }}</h2>
    
    {{-- Message --}}
    <p class="text-gray-600">{{ $message }}</p>
</div>
```

**Usage**:
```blade
<x-blocks.confirmation.simple
    title="Appuntamento Confermato"
    message="Riceverai una email di conferma a breve."
    icon="check"
/>
```

---

### 2. With Details

**File**: `confirmation/with-details.blade.php`

```blade
@props([
    'title' => 'Appuntamento Confermato',
    'message' => 'Il tuo appuntamento è stato confermato.',
    'details' => [],
    'icon' => 'check',
])

<div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl mx-auto">
    {{-- Icon + Title --}}
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">{{ $title }}</h2>
        <p class="text-gray-600 mt-2">{{ $message }}</p>
    </div>
    
    {{-- Details Box --}}
    @if($details)
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Dettagli Appuntamento</h3>
        <dl class="space-y-3">
            @foreach($details as $label => $value)
                <div class="flex justify-between">
                    <dt class="text-gray-600">{{ $label }}</dt>
                    <dd class="text-gray-900 font-medium">{{ $value }}</dd>
                </div>
            @endforeach
        </dl>
    </div>
    @endif
</div>
```

**Usage**:
```blade
<x-blocks.confirmation.with-details
    title="Appuntamento Confermato"
    message="Il tuo appuntamento è stato confermato con successo."
    :details="[
        'Data' => '30 Marzo 2026',
        'Ora' => '10:00',
        'Luogo' => 'Ufficio Anagrafe, Via Roma 1',
        'Operatore' => 'Dott. Rossi',
        'Note' => 'Portare un documento di identità'
    ]"
/>
```

---

### 3. With Steps

**File**: `confirmation/with-steps.blade.php`

```blade
@props([
    'title' => 'Stato della Richiesta',
    'steps' => [],
    'currentStep' => 0,
])

<div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl mx-auto">
    {{-- Title --}}
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-6">{{ $title }}</h2>
    
    {{-- Steps --}}
    <ul class="steps steps-vertical w-full">
        @foreach($steps as $index => $step)
            <li class="step {{ $index <= $currentStep ? 'step-primary' : '' }}">
                <div class="flex items-center gap-3">
                    @if($index < $currentStep)
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @elseif($index === $currentStep)
                        <span class="w-5 h-5 text-current font-bold">{{ $index + 1 }}</span>
                    @else
                        <span class="w-5 h-5 text-gray-400">{{ $index + 1 }}</span>
                    @endif
                    <span>{{ $step }}</span>
                </div>
            </li>
        @endforeach
    </ul>
</div>
```

**Usage**:
```blade
<x-blocks.confirmation.with-steps
    title="Stato della Pratica"
    :steps="[
        'Richiesta inviata',
        'In lavorazione',
        'In attesa di documentazione',
        'Completata'
    ]"
    :currentStep="1"
/>
```

---

### 4. With Actions

**File**: `confirmation/with-actions.blade.php`

```blade
@props([
    'title' => 'Appuntamento Confermato',
    'message' => '',
    'details' => [],
    'actions' => [],
])

<div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl mx-auto">
    {{-- Icon + Title --}}
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">{{ $title }}</h2>
        @if($message)
        <p class="text-gray-600 mt-2">{{ $message }}</p>
        @endif
    </div>
    
    {{-- Details --}}
    @if($details)
    <div class="bg-gray-50 rounded-lg p-6 mb-6">
        <dl class="space-y-3">
            @foreach($details as $label => $value)
                <div class="flex justify-between">
                    <dt class="text-gray-600">{{ $label }}</dt>
                    <dd class="text-gray-900 font-medium">{{ $value }}</dd>
                </div>
            @endforeach
        </dl>
    </div>
    @endif
    
    {{-- Actions --}}
    @if($actions)
    <div class="flex gap-4 justify-center flex-wrap">
        @foreach($actions as $action)
            <a href="{{ $action['url'] }}" 
               class="btn {{ $action['variant'] ?? 'btn-primary' }} px-6 py-2 rounded-lg font-semibold transition-colors">
                {{ $action['label'] }}
            </a>
        @endforeach
    </div>
    @endif
</div>
```

**Usage**:
```blade
<x-blocks.confirmation.with-actions
    title="Appuntamento Confermato"
    message="Riceverai una email di conferma."
    :details="[
        'Data' => '30 Marzo 2026',
        'Ora' => '10:00',
        'Luogo' => 'Ufficio Anagrafe'
    ]"
    :actions="[
        ['label' => 'Scarica PDF', 'url' => '/appuntamento/pdf', 'variant' => 'btn-primary'],
        ['label' => 'Aggiungi a Calendar', 'url' => '/appuntamento/calendar', 'variant' => 'btn-outline'],
        ['label' => 'Annulla', 'url' => '/appuntamento/annulla', 'variant' => 'btn-danger']
    ]"
/>
```

---

## 🎨 Tailwind Classes Reference

### Container
```css
bg-white              /* White background */
rounded-lg            /* Rounded corners */
shadow-lg             /* Large shadow */
p-8                   /* Padding: 2rem */
max-w-2xl             /* Max width */
mx-auto               /* Centered */
```

### Icon
```css
w-16 h-16             /* 4rem x 4rem */
rounded-full          /* Circle */
bg-green-100          /* Light green background */
text-green-600        /* Green icon */
```

### Typography
```css
text-2xl              /* 1.5rem */
font-bold             /* Bold */
text-gray-900         /* Dark gray */
text-gray-600         /* Medium gray */
```

### Details Box
```css
bg-gray-50            /* Light gray background */
rounded-lg            /* Rounded */
p-6                   /* Padding: 1.5rem */
```

---

## ✅ Bootstrap Italia Mapping

### Alert Success
```blade
{{-- Bootstrap Italia --}}
<div class="alert alert-success" role="alert">
    <svg class="icon icon-success">
        <use href="#it-check-circle"></use>
    </svg>
    <div class="alert-text">
        <div class="title">Operazione completata</div>
        <p>Messaggio di conferma.</p>
    </div>
</div>

{{-- Tailwind Equivalent --}}
<div class="bg-green-50 border-l-4 border-green-500 p-4">
    <div class="flex">
        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <div class="ml-3">
            <p class="text-sm text-green-800">Operazione completata</p>
            <p class="text-sm text-green-700 mt-1">Messaggio di conferma.</p>
        </div>
    </div>
</div>
```

### Stepper
```blade
{{-- Bootstrap Italia --}}
<div class="stepper stepper-vertical">
    <div class="stepper-item completed">
        <span class="stepper-number">1</span>
        <span class="stepper-title">Richiesta</span>
    </div>
    <div class="stepper-item active">
        <span class="stepper-number">2</span>
        <span class="stepper-title">Conferma</span>
    </div>
</div>

{{-- Tailwind Equivalent --}}
<ul class="steps steps-vertical">
    <li class="step step-primary">Richiesta</li>
    <li class="step step-primary">Conferma</li>
    <li class="step">Completamento</li>
</ul>
```

---

## 🔗 References

### External
- [Flowbite Alerts](https://flowbite.com/docs/components/alerts/)
- [Tailwind Plus Confirmation](https://tailwindcss.com/plus/ui-blocks/marketing)
- [DaisyUI Alert](https://daisyui.com/components/alert/)
- [DaisyUI Steps](https://daisyui.com/components/steps/)
- [Bootstrap Italia Alert](https://italia.github.io/bootstrap-italia/docs/componenti/alert/)
- [Bootstrap Italia Steppers](https://italia.github.io/bootstrap-italia/docs/componenti/steppers/)

### Internal
- [Blocks Structure Convention](./BLOCKS_STRUCTURE_CONVENTION.md)
- [Bootstrap Italia Tailwind Conversion](./BOOTSTRAP_ITALIA_TAILWIND_CONVERSION.md)

---

**Version**: 1.0  
**Date**: 2026-03-30  
**Status**: ✅ Ready to Use  
**OpenViking URI**: `viking://themes/sixteen/docs/blocks/confirmation`
