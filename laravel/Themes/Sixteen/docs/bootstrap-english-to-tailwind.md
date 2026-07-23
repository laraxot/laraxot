# Migrazione Bootstrap Italia → Tailwind CSS

## Panoramica

Questa documentazione fornisce una **mappatura completa** dei componenti Bootstrap Italia convertiti in **Tailwind CSS** per il tema Sixteen. Ogni componente è stato adattato per mantenere la conformità alle **Linee Guida di Design per i Servizi Digitali della Pubblica Amministrazione** utilizzando Tailwind CSS.

## Struttura dei Blocchi

I componenti sono organizzati in categorie per facilitare la manutenzione e il riutilizzo:

```
resources/views/components/blocks/
├── alerts/              # Alert e notifiche
│   ├── alert.blade.php
│   ├── alert-link.blade.php
│   └── toast.blade.php
├── buttons/             # Pulsanti e azioni
│   ├── button.blade.php
│   ├── button-group.blade.php
│   └── button-group-item.blade.php
├── cards/               # Card e contenitori
│   ├── card.blade.php
│   └── card-overlay.blade.php
├── forms/               # Form e input
│   ├── input.blade.php
│   ├── select.blade.php
│   ├── checkbox.blade.php
│   ├── radio.blade.php
│   ├── switch.blade.php
│   ├── textarea.blade.php
│   └── file-upload.blade.php
├── navigation/          # Navigazione e menu
│   ├── navbar.blade.php
│   ├── breadcrumb.blade.php
│   └── pagination.blade.php
├── layout/              # Layout e struttura
│   ├── container.blade.php
│   └── grid.blade.php
├── feedback/            # Feedback e stati
│   ├── progress.blade.php
│   └── spinner.blade.php
└── utilities/           # Utilità e helper
    ├── badge.blade.php
    └── tooltip.blade.php
```

## Componenti Alert

### Alert Base
```blade
<x-pub_theme::blocks.alerts.alert 
    variant="info" 
    dismissible="true"
    title="Informazione"
>
    Questo è un messaggio di informazione.
</x-pub_theme::blocks.alerts.alert>
```

**Varianti**: `info`, `success`, `warning`, `danger`

### Alert con Link
```blade
<x-pub_theme::blocks.alerts.alert-link 
    variant="success"
    href="/dettagli"
    link-text="Visualizza dettagli"
>
    Operazione completata con successo.
</x-pub_theme::blocks.alerts.alert-link>
```

### Toast
```blade
<x-pub_theme::blocks.alerts.toast 
    variant="success"
    position="top-right"
    duration="5000"
>
    Messaggio toast di successo
</x-pub_theme::blocks.alerts.toast>
```

## Componenti Button

### Button Base
```blade
<x-pub_theme::blocks.buttons.button 
    variant="primary"
    size="md"
    icon="heroicon-o-plus"
>
    Aggiungi elemento
</x-pub_theme::blocks.buttons.button>
```

**Varianti**: `primary`, `secondary`, `outline`, `ghost`, `danger`
**Dimensioni**: `xs`, `sm`, `md`, `lg`, `xl`

### Button Group
```blade
<x-pub_theme::blocks.buttons.button-group>
    <x-pub_theme::blocks.buttons.button-group-item variant="primary">
        Sinistra
    </x-pub_theme::blocks.buttons.button-group-item>
    <x-pub_theme::blocks.buttons.button-group-item variant="secondary">
        Centro
    </x-pub_theme::blocks.buttons.button-group-item>
    <x-pub_theme::blocks.buttons.button-group-item variant="outline">
        Destra
    </x-pub_theme::blocks.buttons.button-group-item>
</x-pub_theme::blocks.buttons.button-group>
```

## Componenti Card

### Card Base
```blade
<x-pub_theme::blocks.cards.card 
    variant="default"
    with-header="true"
    with-footer="true"
>
    <x-slot name="header">
        <h3 class="text-lg font-semibold">Titolo Card</h3>
    </x-slot>
    
    <p>Contenuto della card</p>
    
    <x-slot name="footer">
        <div class="flex justify-end space-x-2">
            <x-pub_theme::blocks.buttons.button size="sm">Annulla</x-pub_theme::blocks.buttons.button>
            <x-pub_theme::blocks.buttons.button variant="primary" size="sm">Salva</x-pub_theme::blocks.buttons.button>
        </div>
    </x-slot>
</x-pub_theme::blocks.cards.card>
```

### Card con Overlay
```blade
<x-pub_theme::blocks.cards.card-overlay 
    image-src="/path/to/image.jpg"
    overlay-title="Titolo Overlay"
    overlay-subtitle="Sottotitolo"
    overlay-position="bottom"
>
    <p>Contenuto dell'overlay</p>
</x-pub_theme::blocks.cards.card-overlay>
```

## Componenti Form

### Input
```blade
<x-pub_theme::blocks.forms.input 
    name="email"
    label="Indirizzo email"
    placeholder="Inserisci la tua email"
    help-text="Non condivideremo mai la tua email"
    required="true"
    icon-left="heroicon-o-envelope"
/>
```

### Select
```blade
<x-pub_theme::blocks.forms.select 
    name="country"
    label="Paese"
    placeholder="Seleziona un paese"
    :options="[
        'it' => 'Italia',
        'fr' => 'Francia',
        'de' => 'Germania'
    ]"
    searchable="true"
/>
```

### Checkbox
```blade
<x-pub_theme::blocks.forms.checkbox 
    name="terms"
    label="Accetto i termini e condizioni"
    help-text="Devi accettare i termini per continuare"
    required="true"
/>
```

### Radio
```blade
<x-pub_theme::blocks.forms.radio 
    name="gender"
    value="male"
    label="Maschio"
/>
```

### Switch
```blade
<x-pub_theme::blocks.forms.switch 
    name="notifications"
    label="Ricevi notifiche"
    help-text="Riceverai notifiche via email"
/>
```

### Textarea
```blade
<x-pub_theme::blocks.forms.textarea 
    name="description"
    label="Descrizione"
    placeholder="Inserisci una descrizione"
    rows="4"
    maxlength="500"
/>
```

### File Upload
```blade
<x-pub_theme::blocks.forms.file-upload 
    name="document"
    label="Carica documento"
    accept=".pdf,.doc,.docx"
    multiple="true"
    preview="true"
    max-size="5MB"
/>
```

## Componenti Navigazione

### Navbar
```blade
<x-pub_theme::blocks.navigation.navbar 
    brand="Logo Azienda"
    brand-href="/"
    variant="light"
    sticky="true"
>
    <a href="/home" class="text-gray-700 hover:text-blue-600">Home</a>
    <a href="/about" class="text-gray-700 hover:text-blue-600">Chi siamo</a>
    <a href="/contact" class="text-gray-700 hover:text-blue-600">Contatti</a>
</x-pub_theme::blocks.navigation.navbar>
```

### Breadcrumb
```blade
<x-pub_theme::blocks.navigation.breadcrumb 
    :items="[
        ['label' => 'Home', 'href' => '/'],
        ['label' => 'Prodotti', 'href' => '/products'],
        ['label' => 'Dettagli prodotto']
    ]"
/>
```

### Pagination
```blade
<x-pub_theme::blocks.navigation.pagination 
    :current-page="3"
    :total-pages="10"
    base-url="/products"
    size="md"
    show-first-last="true"
    show-prev-next="true"
/>
```

## Componenti Layout

### Container
```blade
<x-pub_theme::blocks.layout.container 
    fluid="false"
    max-width="xl"
    padding="true"
>
    Contenuto del container
</x-pub_theme::blocks.layout.container>
```

### Grid
```blade
<x-pub_theme::blocks.layout.grid 
    cols="3"
    gap="md"
    responsive="true"
>
    <div>Colonna 1</div>
    <div>Colonna 2</div>
    <div>Colonna 3</div>
</x-pub_theme::blocks.layout.grid>
```

## Componenti Feedback

### Progress
```blade
<x-pub_theme::blocks.feedback.progress 
    :value="75"
    :max="100"
    variant="primary"
    size="md"
    animated="true"
    label="Completamento"
    show-percentage="true"
/>
```

### Spinner
```blade
<x-pub_theme::blocks.feedback.spinner 
    variant="primary"
    size="md"
    label="Caricamento in corso..."
/>
```

## Componenti Utilità

### Badge
```blade
<x-pub_theme::blocks.utilities.badge 
    variant="success"
    size="md"
    pill="true"
    dismissible="true"
>
    Nuovo
</x-pub_theme::blocks.utilities.badge>
```

### Tooltip
```blade
<x-pub_theme::blocks.utilities.tooltip 
    content="Informazioni aggiuntive"
    position="top"
    variant="dark"
    trigger="hover"
>
    <button class="px-4 py-2 bg-blue-600 text-white rounded">
        Hover me
    </button>
</x-pub_theme::blocks.utilities.tooltip>
```

## Best Practices

### 1. Accessibilità
- Tutti i componenti includono attributi ARIA appropriati
- Supporto per screen reader e navigazione da tastiera
- Contrasto dei colori conforme agli standard WCAG

### 2. Responsive Design
- Tutti i componenti sono responsive per default
- Utilizzo di classi Tailwind responsive (sm:, md:, lg:, xl:)
- Breakpoint ottimizzati per dispositivi mobili

### 3. Personalizzazione
- Utilizzo di varianti per diversi stati e stili
- Props flessibili per adattare i componenti
- Sistema di colori semantico e coerente

### 4. Performance
- Componenti leggeri senza dipendenze esterne
- Utilizzo di Alpine.js per interattività
- CSS ottimizzato con Tailwind

## Migrazione da Bootstrap Italia

### Differenze Principali

1. **Sistema di Grid**: Tailwind utilizza un sistema di grid CSS Grid/Flexbox invece del sistema Bootstrap
2. **Utility Classes**: Tailwind utilizza classi utility invece di classi semantiche
3. **Responsive**: Approccio mobile-first con breakpoint personalizzati
4. **Customizzazione**: Configurazione tramite `tailwind.config.js` invece di variabili CSS

### Esempi di Conversione

**Bootstrap Italia:**
```html
<div class="alert alert-info">
    <h5 class="alert-heading">Informazione</h5>
    <p>Messaggio di informazione</p>
</div>
```

**Tailwind CSS (Tema Sixteen):**
```blade
<x-pub_theme::blocks.alerts.alert variant="info" title="Informazione">
    Messaggio di informazione
</x-pub_theme::blocks.alerts.alert>
```

## Configurazione

### Tailwind Config
```javascript
// tailwind.config.js
module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './Themes/Sixteen/resources/views/**/*.blade.php',
  ],
  theme: {
    extend: {
      colors: {
        // Colori semantici per la PA
        primary: {
          50: '#eff6ff',
          500: '#3b82f6',
          600: '#2563eb',
          700: '#1d4ed8',
        },
        // Altri colori...
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
```

### Alpine.js
I componenti interattivi utilizzano Alpine.js per la gestione dello stato:

```html
<script src="//unpkg.com/alpinejs" defer></script>
```

## Documentazione Componenti

Ogni componente include:
- **Props**: Tutti i parametri configurabili
- **Varianti**: Stati e stili disponibili
- **Esempi**: Codice di utilizzo pratico
- **Accessibilità**: Attributi ARIA e supporto screen reader
- **Responsive**: Comportamento su diversi dispositivi

## Collegamenti

- [Bootstrap Italia Documentation](https://italia.github.io/bootstrap-italia/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Alpine.js Documentation](https://alpinejs.dev/)
- [WCAG Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)

---

**Ultimo aggiornamento**: Dicembre 2024
**Versione**: 1.0
**Compatibilità**: Tailwind CSS 3.x, Alpine.js 3.x, Laravel 10.x
