---
title: "filament widget styling fix"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "filament widget styling fix"
issues: []
discussions: []
status: draft
---

# Fix: Filament Widget Styling su Pagine Non-Panel

**Data**: 14 Ottobre 2025  
**Problema**: Widget Filament non visibile su pagine pubbliche  
**Causa**: Mancanza stili CSS Filament fuori dai panel

## 🔍 Problema

Quando si usa un widget Filament (`@livewire(\Modules\User\Filament\Widgets\Auth\LoginWidget::class)`) in una pagina **fuori da un panel Filament**, il widget viene renderizzato ma **non è visibile** perché mancano gli stili CSS.

### Sintomi
- Widget presente nell'HTML (verificabile con DevTools)
- Area completamente bianca
- Form fields presenti ma invisibili
- Nessun errore JavaScript

## ✅ Soluzione

Aggiungere le direttive Filament al layout principale.

### 1. Aggiungere Stili Filament

**File**: `Themes/Sixteen/resources/views/layouts/app.blade.php`

```blade
<head>
    <!-- ... altri meta tags ... -->
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')

    <!-- Filament Styles -->
    @filamentStyles
    
    <!-- Styles -->
    @livewireStyles
</head>
```

### 2. Aggiungere Scripts Filament

```blade
<body>
    <!-- ... contenuto ... -->
    
    @stack('modals')

    @livewireScripts
    
    <!-- Filament Scripts -->
    @filamentScripts
</body>
```

## 📋 File Completo

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
        @vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')

        <!-- Filament Styles -->
        @filamentStyles
        
        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-layout.sections.banner />

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
        
        <!-- Filament Scripts -->
        @filamentScripts
    </body>
</html>
```

## 🎯 Cosa Fanno le Direttive

### `@filamentStyles`
Carica:
- CSS di Filament core
- Stili per form components
- Stili per widgets
- Stili per sections
- Font Inter (Filament default)

### `@filamentScripts`
Carica:
- JavaScript di Filament core
- Alpine.js components
- Form interactions
- Widget behaviors
- Livewire integrations

## ⚠️ Note Importanti

### 1. Ordine di Caricamento
```blade
<!-- CORRETTO -->
@vite(...)
@filamentStyles  ← Prima di Livewire
@livewireStyles

<!-- ERRATO -->
@livewireStyles
@filamentStyles  ← Troppo tardi
```

### 2. Performance
Gli stili Filament sono pesanti (~500KB). Considera di:
- Usare lazy loading per pagine pubbliche
- Caricare solo su pagine che usano widget
- Ottimizzare con PurgeCSS

### 3. Alternative

#### Opzione A: Caricamento Condizionale
```blade
@if(request()->is('auth/*'))
    @filamentStyles
@endif
```

#### Opzione B: Layout Separato
Creare `layouts/auth.blade.php` specifico per pagine auth:
```blade
<!-- layouts/auth.blade.php -->
<!DOCTYPE html>
<html>
<head>
    @vite(...)
    @filamentStyles
    @livewireStyles
</head>
<body>
    {{ $slot }}
    @livewireScripts
    @filamentScripts
</body>
</html>
```

Poi usarlo:
```blade
<!-- pages/auth/login.blade.php -->
<x-layouts.auth>
    @livewire(\Modules\User\Filament\Widgets\Auth\LoginWidget::class)
</x-layouts.auth>
```

## 🧪 Verifica

Dopo aver applicato il fix:

1. **Ricarica la pagina** (Ctrl+Shift+R per hard refresh)
2. **Verifica DevTools**:
   - Network tab: cerca `filament/filament/app.css`
   - Elements tab: cerca classi `fi-*`
3. **Verifica visibilità**: Il form dovrebbe essere visibile

## 📚 Riferimenti

- [Filament Docs: Standalone Widgets](https://filamentphp.com/docs/4.x/widgets#standalone-widgets)
- [Filament Docs: Assets](https://filamentphp.com/docs/4.x/support/assets)
- [Laravel Blade Directives](https://laravel.com/docs/blade#stacks)

## 🎓 Lezione Appresa

**Regola**: Quando usi widget Filament **fuori da un panel**, devi sempre includere:
1. `@filamentStyles` nel `<head>`
2. `@filamentScripts` prima di `</body>`

Altrimenti il widget sarà presente nell'HTML ma invisibile.
