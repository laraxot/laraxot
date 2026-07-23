# Configurazione Stili Filament 4.x - Tema Sixteen

## Panoramica

Questo documento descrive la configurazione degli stili per Filament 4.x nel tema Sixteen, seguendo le best practice ufficiali di Filament per la personalizzazione dei temi.

## Configurazione Base

### 1. Installazione Dipendenze

Il tema Sixteen utilizza le seguenti dipendenze per la configurazione degli stili:

```json
{
  "devDependencies": {
    "tailwindcss": "^3.4.17",
    "@tailwindcss/forms": "^0.5.10",
    "@tailwindcss/typography": "^0.5.16",
    "daisyui": "^4.12.22",
    "flowbite": "^2.5.1",
    "vite": "^6.3.5",
    "laravel-vite-plugin": "^1.3.0"
  }
}
```

### 2. Configurazione Tailwind CSS

Il file `tailwind.config.js` del tema Sixteen è configurato per supportare Filament 4.x:

```javascript
import defaultTheme from 'tailwindcss/defaultTheme';
import preset from "./vendor/filament/support/tailwind.config.preset";
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'
import daisyui from 'daisyui'
import colors from 'tailwindcss/colors';

module.exports = {
    presets: [preset], // Preset Filament 4.x
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Colori Bootstrap Italia per conformità PA
                'italia-blue': {
                    500: '#0066CC', // Primary blue Bootstrap Italia
                    600: '#0059B3',
                    700: '#004C99',
                },
                'italia-green': {
                    500: '#00B373', // Success green Bootstrap Italia
                    600: '#009959',
                },
                'italia-red': {
                    500: '#D9364F', // Error red Bootstrap Italia
                    600: '#CC1F38',
                },
                'italia-yellow': {
                    500: '#FFB400', // Warning yellow Bootstrap Italia
                    600: '#E6A200',
                },
                // Colori Filament 4.x compatibili
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#0066CC',
                    600: '#0059B3',
                    700: '#004C99',
                    800: '#003F80',
                    900: '#003366',
                },
                success: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#00B373',
                    600: '#009959',
                    700: '#00804D',
                    800: '#006640',
                    900: '#004D33',
                },
                warning: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#FFB400',
                    600: '#E6A200',
                    700: '#CC9100',
                    800: '#B37F00',
                    900: '#996D00',
                },
                danger: {
                    50: '#fef2f2',
                    100: '#fee2e2',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#D9364F',
                    600: '#CC1F38',
                    700: '#B31829',
                    800: '#99141F',
                    900: '#800F17',
                },
            },
        },
    },
    content: [
        // Paths Filament 4.x
        '../../app/Filament/**/*.php',
        '../../resources/views/**/*.blade.php',
        '../../vendor/filament/**/*.blade.php',
        // Paths tema Sixteen
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        // Paths moduli
        "../../Modules/**/Filament/**/*.php",
        "../../Modules/**/resources/views/**/*.blade.php",
        // Paths temi
        "../../Themes/**/resources/views/**/*.blade.php",
        // Node modules
        "./node_modules/flowbite/**/*.js",
    ],
    plugins: [
        forms,
        typography,
        daisyui,
        require("flowbite/plugin"),
    ],
    daisyui: {
        themes: ['light', 'dark'],
    },
}
```

### 3. Configurazione Vite

Il file `vite.config.js` è configurato per supportare Filament 4.x:

```javascript
import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin'

export default defineConfig({
    build: {
        outDir: './public',
        emptyOutDir: false,
        manifest: 'manifest.json',
    },
    ssr: {
        noExternal: ['chart.js/**']
    },
    plugins: [
        laravel({
            publicDirectory: '../../../public_html/',
            input: [
                __dirname + '/resources/css/app.css',
                __dirname + '/resources/js/app.js',
            ],
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
            ],
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources',
        },
    },
});
```

## Comandi di Build

### 1. Installazione Dipendenze

```bash
# Nella cartella del tema Sixteen
cd laravel/Themes/Sixteen

# Installare dipendenze NPM
npm install

# Installare dipendenze Composer (se necessario)
composer install
```

### 2. Build degli Asset

```bash
# Build per sviluppo
npm run dev

# Build per produzione
npm run build

# Build con analisi
npm run build:analyze

# Build per produzione ottimizzato
npm run build:production
```

### 3. Pubblicazione Asset

```bash
# Copiare asset compilati nella directory pubblica
npm run copy

# Watch mode per sviluppo
npm run copy:watch
```

## Configurazione Filament 4.x

### 1. AdminPanelProvider

Il tema Sixteen è configurato per funzionare con Filament 4.x attraverso l'AdminPanelProvider:

```php
<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Modules\Xot\Providers\Filament\XotBaseMainPanelProvider;
use Filament\Panel;
use Filament\PanelProvider;

class AdminPanelProvider extends XotBaseMainPanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => '#0066CC', // Italia Blue
                'success' => '#00B373', // Italia Green
                'warning' => '#FFB400', // Italia Yellow
                'danger' => '#D9364F',  // Italia Red
            ])
            ->font('Inter')
            ->favicon(asset('themes/Sixteen/favicon.ico'))
            ->brandName('<nome progetto>')
            ->brandLogo(asset('themes/Sixteen/logo.svg'))
            ->brandLogoHeight('2rem')
            ->viteTheme('themes/Sixteen');
    }
}
```

### 2. Configurazione Tema

Il tema Sixteen utilizza il sistema di temi di Filament 4.x:

```php
// config/filament.php
'themes' => [
    'sixteen' => [
        'path' => 'Themes/Sixteen',
        'namespace' => 'pub_theme',
    ],
],
```

## Personalizzazione Stili

### 1. CSS Personalizzato

Il file `resources/css/app.css` del tema Sixteen include:

```css
@import 'tailwindcss/base';
@import 'tailwindcss/components';
@import 'tailwindcss/utilities';

/* Stili personalizzati per Filament 4.x */
@layer components {
    /* Personalizzazione componenti Filament */
    .filament-forms-field-wrapper {
        @apply mb-4;
    }
    
    .filament-forms-field-wrapper-label {
        @apply text-sm font-medium text-gray-700 mb-1;
    }
    
    .filament-forms-field-wrapper-error {
        @apply text-sm text-red-600 mt-1;
    }
    
    /* Personalizzazione tabelle Filament */
    .filament-tables-table {
        @apply bg-white rounded-lg shadow-sm border border-gray-200;
    }
    
    .filament-tables-header-cell {
        @apply px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
    }
    
    .filament-tables-cell {
        @apply px-6 py-4 whitespace-nowrap text-sm text-gray-900;
    }
    
    /* Personalizzazione form Filament */
    .filament-forms-section {
        @apply bg-white rounded-lg shadow-sm border border-gray-200 p-6;
    }
    
    .filament-forms-section-header {
        @apply text-lg font-medium text-gray-900 mb-4;
    }
    
    /* Personalizzazione bottoni Filament */
    .filament-button {
        @apply inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2;
    }
    
    .filament-button-primary {
        @apply bg-italia-blue-500 text-white hover:bg-italia-blue-600 focus:ring-italia-blue-500;
    }
    
    .filament-button-success {
        @apply bg-italia-green-500 text-white hover:bg-italia-green-600 focus:ring-italia-green-500;
    }
    
    .filament-button-warning {
        @apply bg-italia-yellow-500 text-white hover:bg-italia-yellow-600 focus:ring-italia-yellow-500;
    }
    
    .filament-button-danger {
        @apply bg-italia-red-500 text-white hover:bg-italia-red-600 focus:ring-italia-red-500;
    }
}

/* Stili per conformità AGID */
@layer utilities {
    .agid-compliant {
        @apply text-base leading-relaxed;
    }
    
    .agid-focus {
        @apply focus:outline-none focus:ring-2 focus:ring-italia-blue-500 focus:ring-offset-2;
    }
    
    .agid-button {
        @apply agid-compliant agid-focus;
    }
}
```

### 2. JavaScript Personalizzato

Il file `resources/js/app.js` include:

```javascript
import './bootstrap';
import Alpine from 'alpinejs';
import 'flowbite';

// Configurazione Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Configurazione Flowbite per componenti interattivi
import { initFlowbite } from 'flowbite';
initFlowbite();

// Personalizzazioni per Filament 4.x
document.addEventListener('DOMContentLoaded', function() {
    // Inizializzazione componenti personalizzati
    initializeFilamentCustomizations();
});

function initializeFilamentCustomizations() {
    // Personalizzazione tabelle Filament
    const tables = document.querySelectorAll('.filament-tables-table');
    tables.forEach(table => {
        table.classList.add('agid-compliant');
    });
    
    // Personalizzazione form Filament
    const forms = document.querySelectorAll('.filament-forms-section');
    forms.forEach(form => {
        form.classList.add('agid-compliant');
    });
    
    // Personalizzazione bottoni Filament
    const buttons = document.querySelectorAll('.filament-button');
    buttons.forEach(button => {
        button.classList.add('agid-button');
    });
}
```

## Best Practices

### 1. Conformità AGID

- Utilizzare sempre i colori semantici definiti nel tema
- Mantenere il contrasto conforme agli standard WCAG 2.1 AA
- Utilizzare font Inter per la leggibilità ottimale
- Implementare focus states accessibili

### 2. Performance

- Utilizzare il preset Filament per ottimizzazioni automatiche
- Implementare lazy loading per componenti pesanti
- Ottimizzare le immagini e i font
- Utilizzare il sistema di caching di Vite

### 3. Manutenibilità

- Seguire le convenzioni di naming del tema
- Documentare le personalizzazioni CSS
- Utilizzare le utility classes di Tailwind quando possibile
- Mantenere la coerenza con il design system

## Troubleshooting

### 1. Asset non caricati

**Problema**: Gli asset del tema non vengono caricati correttamente.

**Soluzione**:
```bash
# Verificare che il build sia stato eseguito
npm run build

# Copiare gli asset nella directory pubblica
npm run copy

# Verificare che la direttiva @vite sia corretta
@vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')
```

### 2. Stili non applicati

**Problema**: Le personalizzazioni CSS non vengono applicate.

**Soluzione**:
```bash
# Pulire la cache
php artisan view:clear
php artisan config:clear

# Ricompilare gli asset
npm run build
npm run copy
```

### 3. Errori di build

**Problema**: Errori durante il build degli asset.

**Soluzione**:
```bash
# Verificare le dipendenze
npm install

# Verificare la configurazione Tailwind
npx tailwindcss --init

# Verificare la configurazione Vite
npm run build --verbose
```

## Collegamenti

- [Documentazione Filament 4.x](https://filamentphp.com/docs/4.x/introduction/installation#configuring-styles)
- [Documentazione Tailwind CSS](https://tailwindcss.com/docs)
- [Documentazione Vite](https://vitejs.dev/guide/)
- [Linee Guida Design PA](https://designers.italia.it/)

---

**Ultimo aggiornamento**: Gennaio 2025
**Versione**: 1.0
**Compatibilità**: Filament 4.x, Tailwind CSS 3.x, Laravel 10.x
