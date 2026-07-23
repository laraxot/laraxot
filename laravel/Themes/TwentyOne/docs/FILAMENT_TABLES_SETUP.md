# Filament Tables Setup - TwentyOne Theme

## Panoramica

Questo documento descrive come configurare il tema TwentyOne per supportare Filament Tables v5.x nel front-office.

## Principio chiave (Filament 5)

Secondo l'overview widget di Filament 5, il `TableWidget` e il punto nativo per dashboard/listing con:
- ricerca;
- filtri;
- ordinamento;
- paginazione.

Nel tema TwentyOne questo implica:
- wrapper Blade per composizione visuale;
- logica tabellare in widget PHP;
- nessuna duplicazione di query/filtro nel Blade.

## Architettura

```
Themes/TwentyOne/
├── resources/
│   ├── css/
│   │   └── app.css              ← Import CSS Filament
│   ├── js/
│   │   └── app.js               ← Eventuali script custom
│   └── views/
│       ├── components/
│       │   └── layouts/
│       │       ├── main.blade.php    ← @filamentStyles, @filamentScripts
│       │       └── app.blade.php
│       └── filament/
│           └── widgets/
│               └── predict-table.blade.php  ← Wrapper widget
└── vite.config.js              ← Configurazione Vite + Tailwind
```

## Configurazione Richiesta

### 1. CSS Imports

**File**: `Themes/TwentyOne/resources/css/app.css`

```css
@import 'tailwindcss';

/* ============================================
   Filament 5.x CSS Imports - REQUIRED
   ============================================ */

/* Required by ALL components */
@import '../../../../vendor/filament/support/resources/css/index.css';

/* Required by actions and tables */
@import '../../../../vendor/filament/actions/resources/css/index.css';

/* Required by actions, forms and tables */
@import '../../../../vendor/filament/forms/resources/css/index.css';

/* Required by notifications */
@import '../../../../vendor/filament/notifications/resources/css/index.css';

/* Required by actions, infolists, forms, schemas and tables */
@import '../../../../vendor/filament/schemas/resources/css/index.css';

/* Required by tables */
@import '../../../../vendor/filament/tables/resources/css/index.css';

/* Required by widgets */
@import '../../../../vendor/filament/widgets/resources/css/index.css';

@variant dark (&:where(.dark, .dark *));

/* ============================================
   Custom Filament Tables Styling
   ============================================ */

/* Search icon size fix - Lente d'ingrandimento */
.fi-ta-search-input .fi-icon {
    width: 18px !important;
    height: 18px !important;
}

/* Search input styling */
.fi-ta-search-input input {
    font-size: 0.875rem !important;
    padding-left: 2.5rem !important;
}

/* Filters dropdown styling */
.fi-ta-filter-trigger {
    padding: 0.5rem 0.75rem !important;
    font-size: 0.875rem !important;
}

/* Pagination styling */
.fi-ta-pagination {
    margin-top: 1rem !important;
}

.fi-ta-pagination select {
    font-size: 0.875rem !important;
    padding: 0.375rem 2rem 0.375rem 0.75rem !important;
}

/* Table container responsive */
.fi-ta-content {
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch;
}

/* Mobile optimization */
@media (max-width: 640px) {
    .fi-ta-search-input .fi-icon {
        width: 16px !important;
        height: 16px !important;
    }
    
    .fi-ta-filter-trigger span {
        display: none;
    }
    
    .fi-ta-pagination {
        flex-direction: column;
        gap: 0.5rem;
    }
}
```

### 2. Layout Blade

**File**: `Themes/TwentyOne/resources/views/components/layouts/main.blade.php`

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        @if($metaDescription)
            <meta name="description" content="{{ $metaDescription }}">
        @endif

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Theme Color -->
        <meta name="theme-color" content="#10b981">

        <!-- x-cloak for Alpine.js/Livewire -->
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        {{-- ============================================
            FILAMENT STYLES - REQUIRED
            ============================================ --}}
        @filamentStyles
        @vite(['resources/css/app.css'], 'themes/TwentyOne')
    </head>

    <body class="{{ $bodyClass }} antialiased">
        <!-- Skip to Content (WCAG 2.2 AA) -->
        <a href="#main-content" class="sr-only focus:not-sr-only">
            {{ __('Skip to content') }}
        </a>

        <!-- Main Content -->
        <main id="main-content" role="main">
            {{ $slot }}
        </main>

        {{-- ============================================
            FILAMENT SCRIPTS - REQUIRED
            ============================================ --}}
        @vite(['resources/js/app.js'], 'themes/TwentyOne')
        @filamentScripts
        
        {{-- Livewire Notifications (REQUIRED for Filament) --}}
        @livewire('notifications')
        @livewireScripts
    </body>
</html>
```

### 3. Vite Configuration

**File**: `Themes/TwentyOne/vite.config.js`

```javascript
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        outDir: '../../public/build',
        emptyOutDir: true,
        manifest: true,
    },
})
```

### 4. Package.json

**File**: `Themes/TwentyOne/package.json`

```json
{
    "private": true,
    "type": "module",
    "scripts": {
        "dev": "vite",
        "build": "vite build"
    },
    "devDependencies": {
        "@tailwindcss/vite": "^4.0.0",
        "laravel-vite-plugin": "^1.0.0",
        "tailwindcss": "^4.0.0",
        "vite": "^6.0.0"
    }
}
```

## Widget Wrapper

### Creare Wrapper per Widget Filament

**File**: `Themes/TwentyOne/resources/views/filament/widgets/predict-table.blade.php`

```blade
{{--
|--------------------------------------------------------------------------
| Predict Table Widget Wrapper
|--------------------------------------------------------------------------
|
| Wrapper per il widget Filament PredictTableWidget.
| Questo file fornisce:
| - Container responsive
| - Styling coerente con il tema
| - Eventuali customizzazioni UI
|
--}}

@props([
    'title' => 'Mercati',
    'subtitle' => 'Esplora tutti i mercati di previsione',
])

<div class="filament-table-wrapper w-full">
    {{-- Header opzionale --}}
    @if($title)
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-white">{{ $title }}</h2>
        @if($subtitle)
        <p class="mt-2 text-sm text-slate-400">{{ $subtitle }}</p>
        @endif
    </div>
    @endif

    {{-- Widget Filament --}}
    <div class="filament-table-container 
                rounded-2xl 
                border border-white/10 
                bg-white/5 
                backdrop-blur-sm
                p-4
                md:p-6">
        @livewire(\Modules\Predict\Filament\Widgets\PredictTableWidget::class)
    </div>
</div>
```

## Checklist Implementazione

### Pre-Commit

- [ ] CSS Filament importati in `app.css`
- [ ] `@filamentStyles` nel `<head>` del layout
- [ ] `@filamentScripts` prima di `</body>`
- [ ] `@livewire('notifications')` presente
- [ ] Vite configurato con Tailwind v4
- [ ] Asset compilati (`npm run build` o `npm run dev`)
- [ ] Search icon size fissata (18px)
- [ ] Filtri stilizzati correttamente
- [ ] Paginazione responsive
- [ ] Test su mobile (max-width: 640px)

### Quality Gate

```bash
# 1. Compila asset
cd Themes/TwentyOne
npm run build

# 2. Verifica nel browser
# - Apri http://predict.local/it/predicts
# - Controlla che la tabella sia stilizzata
# - Verifica search, filtri, paginazione
# - Test responsive (mobile view)

# 3. Controlla console browser
# - Nessun errore JavaScript
# - Nessun warning CSS
```

## Troubleshooting

### Lente d'ingrandimento troppo grande

**Sintomi**: Icona search occupa troppo spazio (>24px)

**Soluzione**:
```css
.fi-ta-search-input .fi-icon {
    width: 18px !important;
    height: 18px !important;
}
```

### Filtri senza stile

**Sintomi**: Filtri appaiono come elementi HTML non stilizzati

**Causa**: CSS Filament non caricato

**Verifica**:
1. Controlla che `@filamentStyles` sia nel layout
2. Verifica che i percorsi degli import in `app.css` siano corretti
3. Esegui `npm run build`
4. Svuota cache browser

### Paginazione non visibile

**Sintomi**: La paginazione non appare sotto la tabella

**Causa**: JavaScript Filament non caricato

**Verifica**:
1. Controlla che `@filamentScripts` sia nel layout
2. Verifica che `@livewireScripts` sia presente
3. Controlla console browser per errori JavaScript
4. Verifica che Livewire sia installato: `composer show livewire/livewire`

### Tabella non responsive

**Sintomi**: La tabella esce dal container su mobile

**Soluzione**:
```css
.fi-ta-content {
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch;
}

@media (max-width: 640px) {
    .fi-ta-content {
        font-size: 0.75rem !important;
    }
}
```

### Featured Predicts appare come tabella classica

**Sintomi**: il blocco homepage mostra righe/colonne invece delle card cinematic.

**Fix applicato**:
- usare il wrapper `resources/views/filament/widgets/featured-predicts.blade.php` per montare `\Modules\Predict\Filament\Widgets\PredictTableWidget`;
- passare i parametri:
  - `homepageMode = true`
  - `minimumOutcomes = 4`
  - `showTableControls = true`

In questo modo il rendering resta card-based (`contentGrid` + `homepage-item`) ma con la potenza Filament (search, filters, sorting).

### Table widget: confini tema vs widget

**Tema (`resources/views/filament/widgets/*.blade.php`)**
- gestisce contenitore, sfondo, effetti visivi, spacing.

**Widget Filament (`Modules/Predict/app/Filament/Widgets/*`)**
- gestisce dataset, `filters`, `searchable`, `defaultSort`, `paginated`.

Questo confine evita drift funzionale e mantiene il tema focalizzato su UI/UX.

### Contrasto sfondo widget/card

**Sintomi**: le card Featured Predicts contrastano troppo con il background pagina.

**Fix applicato**:
- nel template `resources/views/filament/widgets/predict-table/homepage-item.blade.php` il container card usa `bg-transparent`, lasciando il tono di sfondo al contesto sezione.

## Riferimenti

- [Filament 5.x Tables](https://filamentphp.com/docs/5.x/tables/overview)
- [Filament 5.x Installation](https://filamentphp.com/docs/5.x/introduction/installation)
- [Tailwind CSS v4](https://tailwindcss.com/docs)
- [Laraxot Theme Architecture](../../docs/THEME_ARCHITECTURE.md)

---

**Ultimo Aggiornamento**: 2026-03-22  
**Versione Filament**: 5.4.1  
**Versione Tema**: TwentyOne  
**Stato**: ✅ Operativo
