# Integrazione Vite con Temi - Tema Sixteen

## Problema Identificato

L'utilizzo di `@vite(['resources/css/app.css', 'resources/js/app.js'])` senza specificare il tema è **ERRATO** nel sistema Laraxot.

## Analisi del Problema

### ❌ Utilizzo Errato
```blade
<!-- ERRORE: Non specifica il tema -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

### Problemi Identificati
1. **Path errato**: Vite cerca i file nella directory principale invece che nel tema
2. **Build directory errata**: I file compilati non vengono trovati
3. **Tema non specificato**: Il sistema non sa quale tema utilizzare per gli asset

## Soluzione Corretta

### ✅ Pattern Corretto (IMPLEMENTATO)
```blade
<!-- CSS -->
@vite(['resources/css/app.css'], 'themes/Sixteen/dist')

<!-- JavaScript -->
@vite(['resources/js/app.js'], 'themes/Sixteen/dist')
```

## Struttura Vite del Sistema

### Configurazione Tema Sixteen
```javascript
// vite.config.js del tema Sixteen
export default defineConfig({
    build: {
        outDir: './resources/dist',  // Output nel tema
        emptyOutDir: false,
        manifest: 'manifest.json',
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
});
```

### Pattern Vite per Temi
```blade
// ✅ CORRETTO - Specifica tema e directory build
@vite(['resources/css/app.css'], 'themes/Sixteen/dist')
@vite(['resources/js/app.js'], 'themes/Sixteen/dist')

// ❌ ERRATO - Non specifica tema
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

## Implementazione nei Layout

### Layout Base (Pattern Corretto)
```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ url(asset('favicon.ico')) }}">
        
        <!-- Fonts -->
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
        @filamentStyles
        
        <!-- CSS del Tema -->
        @vite(['resources/css/app.css'], 'themes/Sixteen/dist')
        
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    
    <body>
        @yield('body')
        
        @livewire('notifications')
        
        @filamentScripts
        
        <!-- JavaScript del Tema -->
        @vite(['resources/js/app.js'], 'themes/Sixteen/dist')
    </body>
</html>
```

### Layout Guest (Correzione Necessaria)
```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css'], 'themes/Sixteen/dist')
        @vite(['resources/js/app.js'], 'themes/Sixteen/dist')
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
```

## Struttura File del Tema

### Directory Assets
```
laravel/Themes/Sixteen/
├── resources/
│   ├── css/
│   │   └── app.css          # CSS principale del tema
│   ├── js/
│   │   └── app.js           # JavaScript principale del tema
│   └── dist/                # File compilati da Vite
├── vite.config.js           # Configurazione Vite del tema
├── package.json             # Dipendenze del tema
└── tailwind.config.js       # Configurazione Tailwind del tema
```

### File CSS del Tema
```css
/* resources/css/app.css */
@tailwind base;
@tailwind components;
@tailwind utilities;

/* Custom Filament button styles */
@layer components {
    .filament-button {
        @apply inline-flex items-center justify-center py-2 px-4 font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors;
    }
    
    .filament-button-primary {
        @apply bg-blue-600 text-white hover:bg-blue-500 focus:ring-blue-500;
    }
}
```

### File JavaScript del Tema
```javascript
/* resources/js/app.js */
import "./flowbite.js";
import "./swiper.js";
import "./custom.js";
```

## Build Process

### Comando Build per Tema
```bash
# Dalla directory del tema
cd laravel/Themes/Sixteen
npm run build

# Oppure per sviluppo
npm run dev
```

### Output Build
```
laravel/Themes/Sixteen/resources/dist/
├── manifest.json            # Manifesto Vite
├── assets/
│   ├── app-[hash].css      # CSS compilato
│   └── app-[hash].js       # JavaScript compilato
```

## Best Practices

### ✅ DO
- Utilizzare sempre `@vite(['file'], 'themes/Tema/dist')`
- Separare CSS e JavaScript in chiamate separate
- Verificare che i file esistano nel tema
- Utilizzare il pattern corretto per ogni tema

### ❌ DON'T
- Utilizzare `@vite(['file'])` senza specificare il tema
- Combinare CSS e JS in una sola chiamata per temi
- Assumere che i file esistano senza verificare
- Utilizzare path relativi invece di path del tema

## Pattern per Altri Temi

### Tema One
```blade
@vite(['resources/css/app.css'], 'themes/One')
@vite(['resources/js/app.js'], 'themes/One')
```

### Tema Two
```blade
@vite(['Resources/css/app.css'], 'themes/Two/dist')
@vite(['Resources/js/app.js'], 'themes/Two/dist')
```

### Tema Zero
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Zero')
```

## Verifica Build

### Controllo File Compilati
```bash
# Verificare che i file esistano
ls -la laravel/Themes/Sixteen/resources/dist/

# Verificare manifest
cat laravel/Themes/Sixteen/resources/dist/manifest.json
```

### Test Build
```bash
# Build di produzione
cd laravel/Themes/Sixteen
npm run build

# Verificare output
ls -la resources/dist/assets/
```

## Collegamenti

- [Layout Usage Patterns](layout-usage-patterns.md)
- [AGID Login Implementation](agid-login-implementation.md)
- [Route Patterns](route-patterns.md)
- [Layout Base](resources/views/layouts/base.blade.php)
- [Vite Config](vite.config.js)
- [Package.json](package.json)

