# Bootstrap Italia Design System Implementation

Implementazione del design system Bootstrap Italia con Tailwind CSS per il tema Sixteen.

## 🎨 Sistema di Colori

### Colori Primari
- **Primary Blue**: `#0066CC` - Blu istituzionale principale
- **Success Green**: `#00B373` - Verde per messaggi di successo
- **Danger Red**: `#D9364F` - Rosso per errori e avvisi critici
- **Warning Yellow**: `#FFB400` - Giallo per avvisi e attenzioni

### Palette Completa
Ogni colore ha 10 variazioni (50-950) conformi al design system:
```css
italia-blue-500    // Colore principale
italia-green-500   // Successo
italia-red-500     // Errore
italia-yellow-500  // Warning
italia-gray-500    // Neutro
```

## 🔤 Tipografia

### Font Stack
1. **Titillium Web** - Font principale (sistema PA)
2. **Lora** - Font serif per contenuti editoriali
3. **Roboto Mono** - Font monospace per codice

### Gerarchia
```css
h1: text-4xl font-bold    // 36px, 700
h2: text-3xl font-semibold // 30px, 600
h3: text-2xl font-semibold // 24px, 600
h4: text-xl font-semibold  // 20px, 600
h5: text-lg font-semibold  // 18px, 600
h6: text-base font-semibold // 16px, 600
```

## 📱 Breakpoints

Conformi alle specifiche Bootstrap Italia:
```css
xs: 475px   // Extra small
sm: 576px   // Small
md: 768px   // Medium
lg: 992px   // Large
xl: 1200px  // Extra large
2xl: 1400px // XXL
```

## 🧩 Componenti Principali

### Header System
- **Header Slim**: Barra istituzionale superiore
- **Header Main**: Header principale con logo e navigazione

### Navigazione
- **Breadcrumb**: Percorso di navigazione
- **Menu**: Navigazione principale responsive

### UI Elements
- **Alert**: Messaggi di stato (info, success, warning, danger)
- **Button**: Bottoni con varianti conformi
- **Card**: Card per contenuti strutturati
- **Footer**: Footer istituzionale completo

## 📂 Struttura Files

```
Themes/Sixteen/
├── tailwind.config.js          // Config Tailwind + Bootstrap Italia
├── resources/css/app.css        // Stili base + componenti
└── resources/views/components/
    ├── layouts/
    │   └── main.blade.php       // Layout principale
    ├── blocks/navigation/
    │   ├── header-slim.blade.php
    │   ├── header-main.blade.php
    │   └── breadcrumb.blade.php
    ├── blocks/cards/
    │   └── italia.blade.php
    └── bootstrap-italia/
        ├── alert.blade.php
        ├── button.blade.php
        └── footer.blade.php
```

## 🎯 Utilizzo

### Layout Base
```blade
<x-layouts.main title="Titolo Pagina">
    <div class="container-italia py-8">
        <h1>Contenuto della pagina</h1>
    </div>
</x-layouts.main>
```

### Componenti
```blade
<!-- Alert -->
<x-bootstrap-italia.alert type="success" title="Successo">
    Operazione completata con successo
</x-bootstrap-italia.alert>

<!-- Button -->
<x-bootstrap-italia.button variant="primary" href="/servizi">
    Vai ai servizi
</x-bootstrap-italia.button>

<!-- Card -->
<x-blocks.cards.italia title="Titolo" subtitle="Sottotitolo">
    Contenuto della card
</x-blocks.cards.italia>
```

### Container
```blade
<!-- Container standard -->
<div class="container-italia">Content</div>

<!-- Container fluido -->
<div class="container-italia-fluid">Content</div>
```

## 🎨 Classi CSS Principali

### Button System
```css
.btn-italia              // Base button
.btn-primary-italia      // Bottone primario
.btn-secondary-italia    // Bottone secondario
.btn-outline-primary-italia // Bottone outline
```

### Alert System
```css
.alert-italia            // Base alert
.alert-success-italia    // Alert successo
.alert-warning-italia    // Alert warning
.alert-danger-italia     // Alert errore
.alert-info-italia       // Alert informativo
```

### Form Controls
```css
.form-control-italia     // Input base
.form-label-italia       // Label form
.form-error-italia       // Messaggio errore
```

## ♿ Accessibilità

Tutti i componenti seguono le linee guida WCAG 2.1 AA:
- Contrasto colori conforme
- Supporto screen reader
- Navigazione da tastiera
- Struttura semantica HTML

## 📐 Principi di Design

### DRY (Don't Repeat Yourself)
- Componenti riutilizzabili
- Classi CSS modulari
- Props configurabili

### KISS (Keep It Simple, Stupid)
- API componenti semplici
- Documentazione chiara
- Configurazione minimale

## 🔧 Build e Compilazione

```bash
cd /var/www/html/_bases/base_fixcity_fila5_mono/laravel/Themes/Sixteen

# Installa dipendenze
npm install

# Build development
npm run dev

# Build production
npm run build
```

## 🌐 SEO e Performance

- Meta tags strutturati
- Schema.org per enti pubblici
- Font ottimizzati con font-display: swap
- Lazy loading per immagini
- Minificazione CSS/JS in produzione

## 📚 Risorse

- [Bootstrap Italia](https://italia.github.io/bootstrap-italia/)
- [Design System PA](https://designers.italia.it/)
- [AGID Guidelines](https://www.agid.gov.it/)
- [Tailwind CSS](https://tailwindcss.com/)

---

*Implementazione conforme alle linee guida AGID per i siti web della Pubblica Amministrazione*