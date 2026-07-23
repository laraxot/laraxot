# Blocks Structure Convention

> *"La struttura segue il pattern. Il tipo determina il percorso. La vista segue il tipo."*

## 🎯 Prototipo Corretto

```
laravel/Themes/Sixteen/resources/views/components/blocks/<tipo>/<blade>.blade.php
```

### Esempi

```
✅ CORRETTO:
components/blocks/confirmation/details.blade.php
components/blocks/confirmation/simple.blade.php
components/blocks/confirmation/with-steps.blade.php

components/blocks/hero/homepage.blade.php
components/blocks/hero/landing.blade.php

components/blocks/card/basic.blade.php
components/blocks/card/featured.blade.php
components/blocks/card/event.blade.php

❌ SBAGLIATO:
components/blocks/tests/appuntamento-conferma/details.blade.php  (troppo specifico)
components/blocks/fixcity/ticket-form.blade.php  (project-specific)
```

---

## 📚 Pattern da Framework UI

### 1. Flowbite Blocks

**URL**: https://flowbite.com/blocks/

**Categorie Principali**:

| Category | Block Types | Usage |
|----------|-------------|-------|
| **Hero** | `hero`, `banner` | Landing pages, headers |
| **Features** | `features`, `benefits` | Service listings |
| **Content** | `content`, `text` | Article blocks |
| **CTA** | `cta`, `action` | Call-to-action sections |
| **Card** | `card`, `product` | Product/service cards |
| **Form** | `form`, `contact` | Contact forms |
| **Footer** | `footer` | Site footers |
| **Header** | `header`, `nav` | Navigation headers |
| **Section** | `section`, `team` | Team sections |
| **Testimonial** | `testimonial`, `review` | Reviews |
| **Pricing** | `pricing` | Pricing tables |
| **Stats** | `stats`, `numbers` | Statistics |

**Pattern Naming**:
```blade
components/blocks/
├── hero/
│   ├── basic.blade.php
│   ├── with-image.blade.php
│   └── centered.blade.php
├── features/
│   ├── grid.blade.php
│   ├── list.blade.php
│   └── alternating.blade.php
├── card/
│   ├── basic.blade.php
│   ├── product.blade.php
│   └── article.blade.php
└── confirmation/
    ├── simple.blade.php
    ├── with-steps.blade.php
    └── with-actions.blade.php
```

---

### 2. Tailwind Plus UI Blocks

**URL**: https://tailwindcss.com/plus/ui-blocks

**Pattern Categories**:

| Type | Examples | Tailwind Classes |
|------|----------|-----------------|
| **Marketing** | Hero, Features, CTA, Stats | `py-24`, `mx-auto`, `max-w-7xl` |
| **E-commerce** | Product cards, Grids | `grid-cols-4`, `gap-x-6`, `aspect-w-1` |
| **Content** | Article, Blog, FAQ | `prose`, `prose-lg`, `max-w-prose` |
| **Application** | Dashboard, Stats, Tables | `divide-y`, `divide-gray-200` |
| **Overlay** | Modal, Banner, Alert | `fixed`, `inset-0`, `z-50` |

**Pattern Structure**:
```blade
{{-- Marketing Hero --}}
<div class="relative isolate overflow-hidden bg-white py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">
                Hero Title
            </h1>
        </div>
    </div>
</div>

{{-- E-commerce Product Grid --}}
<div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
    <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
        <!-- Product cards -->
    </div>
</div>
```

---

### 3. DaisyUI Components

**URL**: https://daisyui.com/components/

**Component List**:

| Component | DaisyUI Class | Use Case |
|-----------|--------------|----------|
| **Alert** | `alert`, `alert-info`, `alert-success` | Messages, notifications |
| **Card** | `card`, `card-body`, `card-actions` | Content cards |
| **Steps** | `steps`, `step`, `step-primary` | Progress indicators |
| **Timeline** | `timeline`, `timeline-item` | Timelines |
| **Toast** | `toast` | Notifications |
| **Modal** | `modal`, `modal-box` | Dialogs |
| **Accordion** | `collapse`, `collapse-title` | Expandable content |
| **Stats** | `stats`, `stat` | Statistics display |

**Example - Confirmation with Steps**:
```blade
{{-- Steps Indicator --}}
<ul class="steps steps-vertical">
    <li class="step step-primary">Register</li>
    <li class="step step-primary">Choose product</li>
    <li class="step step-primary">Purchase</li>
    <li class="step">Receive product</li>
</ul>

{{-- Success Alert --}}
<div role="alert" class="alert alert-success">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
    </svg>
    <span>Appointment confirmed!</span>
</div>

{{-- Card with Actions --}}
<div class="card w-96 bg-base-100 shadow-xl">
    <div class="card-body">
        <h2 class="card-title">Appointment Details</h2>
        <p>Date: 2026-03-30 10:00</p>
        <div class="card-actions justify-end">
            <button class="btn btn-primary">Confirm</button>
            <button class="btn btn-outline">Reschedule</button>
        </div>
    </div>
</div>
```

---

### 4. Bootstrap Italia (Reference)

**URL**: https://italia.github.io/bootstrap-italia/docs/componenti/

**Componenti Rilevanti**:

| Componente | Classi Bootstrap Italia | Equivalente Tailwind |
|------------|------------------------|---------------------|
| **Alert** | `alert`, `alert-success`, `alert-info` | `alert`, `bg-success`, `bg-info` |
| **Stepper** | `stepper`, `stepper-item` | `steps`, `step` |
| **Callout** | `callout`, `callout-success` | `bg-callout`, `border-l-4` |
| **Notification** | `notification`, `notification-info` | `toast`, `fixed` |
| **Card** | `card`, `card-body`, `card-footer` | `card`, `card-body` |

**Pattern Confirmation**:
```blade
{{-- Alert con Icona --}}
<div class="alert alert-success" role="alert">
    <svg class="icon icon-success">
        <use href="/dist/svg/sprites.svg#it-check-circle"></use>
    </svg>
    <div class="alert-text">
        <div class="title">Operazione completata</div>
        <p>L'appuntamento è stato confermato con successo.</p>
    </div>
</div>

{{-- Stepper Verticale --}}
<div class="stepper stepper-vertical">
    <div class="stepper-item completed">
        <div class="stepper-info">
            <span class="stepper-number">1</span>
            <span class="stepper-title">Richiesta</span>
        </div>
    </div>
    <div class="stepper-item active">
        <div class="stepper-info">
            <span class="stepper-number">2</span>
            <span class="stepper-title">Conferma</span>
        </div>
    </div>
    <div class="stepper-item">
        <div class="stepper-info">
            <span class="stepper-number">3</span>
            <span class="stepper-title">Completamento</span>
        </div>
    </div>
</div>
```

---

## 🎨 Icon Convention

**Use `<x-filament::icon>` for all icon rendering in Filament projects**

### Syntax

```blade
<x-filament::icon icon="heroicon-o-check-circle" class="w-8 h-8 text-green-600" />
```

### Icon Sets

**Heroicons (default in Filament)**:
```blade
{{-- Outline icons --}}
<x-filament::icon icon="heroicon-o-check-circle" />
<x-filament::icon icon="heroicon-o-information-circle" />
<x-filament::icon icon="heroicon-o-exclamation-triangle" />
<x-filament::icon icon="heroicon-o-x-circle" />

{{-- Solid icons --}}
<x-filament::icon icon="heroicon-s-check-circle" />
<x-filament::icon icon="heroicon-s-information-circle" />
```

### Examples

```blade
{{-- Success Icon --}}
<x-filament::icon 
    icon="heroicon-o-check-circle" 
    class="w-8 h-8 text-green-600" 
    aria-hidden="true" 
/>

{{-- Info Icon --}}
<x-filament::icon 
    icon="heroicon-o-information-circle" 
    class="w-8 h-8 text-blue-600" 
/>

{{-- Warning Icon --}}
<x-filament::icon 
    icon="heroicon-o-exclamation-triangle" 
    class="w-8 h-8 text-yellow-600" 
/>

{{-- Error Icon --}}
<x-filament::icon 
    icon="heroicon-o-x-circle" 
    class="w-8 h-8 text-red-600" 
/>
```

### References

- [Filament Icons Documentation](https://filamentphp.com/docs/5.x/forms/fields/icon-picker)
- [Heroicons](https://heroicons.com/)

---

## 🎨 Block Types Recommendations

### Per Appuntamento/Conferma

**Tipo**: `confirmation`

**Blade Files**:
```blade
components/blocks/confirmation/
├── simple.blade.php           # Messaggio semplice
├── with-details.blade.php     # Con dettagli appuntamento
├── with-steps.blade.php       # Con stepper di progresso
├── with-actions.blade.php     # Con bottoni azione
├── timeline.blade.php         # Timeline verticale
└── success-card.blade.php     # Card con icona successo
```

### Esempio: `confirmation/with-details.blade.php`

```blade
@props([
    'title' => 'Appuntamento Confermato',
    'message' => 'Il tuo appuntamento è stato confermato.',
    'details' => [],
    'actions' => [],
    'steps' => [],
])

<div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl mx-auto">
    {{-- Success Icon --}}
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">{{ $title }}</h2>
        <p class="text-gray-600 mt-2">{{ $message }}</p>
    </div>
    
    {{-- Details --}}
    @if($details)
    <div class="bg-gray-50 rounded-lg p-6 mb-6">
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
    
    {{-- Steps Progress --}}
    @if($steps)
    <div class="mb-6">
        <ul class="steps steps-vertical w-full">
            @foreach($steps as $index => $step)
                <li class="step {{ $index < count($steps) - 1 ? 'step-primary' : '' }}">
                    {{ $step }}
                </li>
            @endforeach
        </ul>
    </div>
    @endif
    
    {{-- Actions --}}
    @if($actions)
    <div class="flex gap-4 justify-center">
        @foreach($actions as $action)
            <a href="{{ $action['url'] }}" class="btn {{ $action['variant'] ?? 'btn-primary' }}">
                {{ $action['label'] }}
            </a>
        @endforeach
    </div>
    @endif
</div>
```

---

## 📋 Block Type Categories

### Primary Types (Generic, Reusable)

| Type | Purpose | Example Blades |
|------|---------|---------------|
| `hero` | Hero sections | `basic`, `with-image`, `centered`, `video` |
| `card` | Content cards | `basic`, `featured`, `product`, `event`, `article` |
| `features` | Feature lists | `grid`, `list`, `alternating`, `with-icons` |
| `cta` | Call-to-action | `simple`, `with-form`, `centered`, `banner` |
| `content` | Text content | `single-column`, `two-column`, `with-sidebar` |
| `gallery` | Image galleries | `grid`, `carousel`, `masonry`, `lightbox` |
| `form` | Forms | `contact`, `newsletter`, `search`, `booking` |
| `confirmation` | Confirmations | `simple`, `with-details`, `with-steps`, `success-card` |
| `steps` | Progress indicators | `horizontal`, `vertical`, `with-icons` |
| `timeline` | Timelines | `simple`, `with-icons`, `alternating` |
| `stats` | Statistics | `grid`, `horizontal`, `with-icons` |
| `testimonial` | Testimonials | `single`, `grid`, `carousel`, `with-rating` |
| `pricing` | Pricing tables | `single`, `comparison`, `with-features` |
| `faq` | FAQs | `accordion`, `list`, `with-search` |
| `team` | Team members | `grid`, `list`, `with-social` |
| `contact` | Contact info | `with-map`, `with-form`, `cards` |
| `footer` | Footers | `simple`, `multi-column`, `with-social` |
| `header` | Headers | `basic`, `with-nav`, `mega-menu` |
| `nav` | Navigation | `horizontal`, `vertical`, `breadcrumb` |

---

## ✅ Naming Rules

### DO ✅

```blade
components/blocks/confirmation/with-details.blade.php
components/blocks/hero/centered.blade.php
components/blocks/card/featured.blade.php
components/blocks/form/contact.blade.php
```

### DON'T ❌

```blade
components/blocks/tests/appuntamento-conferma/details.blade.php  (troppo specifico)
components/blocks/fixcity/appuntamento-conferma.blade.php  (project-specific)
components/blocks/appuntamento_conferma_details.blade.php  (snake_case nel nome)
components/blocks/confirmation-details.blade.php  (ibrido tipo-blade)
```

---

## 🔧 Usage Examples

### In CMS JSON

```json
{
    "content_blocks": {
        "it": [
            {
                "type": "confirmation",
                "data": {
                    "view": "pub_theme::components.blocks.confirmation.with-details",
                    "title": "Appuntamento Confermato",
                    "details": {
                        "Data": "30 Marzo 2026",
                        "Ora": "10:00",
                        "Luogo": "Ufficio Anagrafe"
                    },
                    "actions": [
                        {"label": "Scarica PDF", "url": "/pdf", "variant": "btn-primary"},
                        {"label": "Annulla", "url": "/annulla", "variant": "btn-outline"}
                    ]
                }
            }
        ]
    }
}
```

### In Blade Template

```blade
<x-blocks.confirmation.with-details
    title="Appuntamento Confermato"
    :details="[
        'Data' => '30 Marzo 2026',
        'Ora' => '10:00',
        'Luogo' => 'Ufficio Anagrafe'
    ]"
    :actions="[
        ['label' => 'Scarica PDF', 'url' => '/pdf', 'variant' => 'btn-primary'],
        ['label' => 'Annulla', 'url' => '/annulla', 'variant' => 'btn-outline']
    ]"
/>
```

---

## 🧘 Developer Mantra

> *"Il tipo è generico. La vista è specifica. Il blocco è riutilizzabile."*

> *"Flowbite, Tailwind, DaisyUI sono ispirazione. Bootstrap Italia è compliance."*

> *"La struttura segue il pattern. Il pattern segue lo scopo."*

---

## 🔗 References

### External
- [Flowbite Blocks](https://flowbite.com/blocks/)
- [Tailwind Plus UI Blocks](https://tailwindcss.com/plus/ui-blocks)
- [DaisyUI Components](https://daisyui.com/components/)
- [Bootstrap Italia](https://italia.github.io/bootstrap-italia/docs/componenti/)

### Internal
- [Bootstrap Italia Tailwind Conversion](./BOOTSTRAP_ITALIA_TAILWIND_CONVERSION.md)
- [Complete Implementation Guide](./COMPLETE_IMPLEMENTATION_GUIDE.md)
- [Footer Implementation](./FOOTER_IMPLEMENTATION.md)

---

**Version**: 1.0  
**Date**: 2026-03-30  
**Status**: ✅ Documentation Complete  
**OpenViking URI**: `viking://themes/sixteen/docs/blocks-structure-convention`
