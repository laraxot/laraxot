# Widget Filament vs Componenti Livewire - Regole Architetturali

## Principio Fondamentale

**REGOLA CRITICA**: Nei temi Laraxot NON utilizzare mai componenti Livewire (`<livewire:nome-componente />`), utilizzare SEMPRE widget Filament o componenti Blade.

## Motivazioni Architetturali

### 1. Separazione delle Responsabilità

- **Temi**: Si occupano solo di presentazione e layout
- **Moduli**: Contengono la logica di business e i componenti funzionali
- **Widget**: Componenti riutilizzabili con logica specifica del dominio

### 2. Modularità e Riusabilità

- **Widget UI comuni** → Modulo UI (`Modules/UI/app/Filament/Widgets/`)
- **Widget specifici del dominio** → Modulo appropriato (es. `Modules/Lang/app/Filament/Widgets/`)
- **Componenti Livewire** → Solo per interazioni complesse in admin panel

### 3. Manutenibilità

- Widget centralizzati nei moduli appropriati
- Logica separata dalla presentazione
- Configurazione tramite parametri invece di hardcoding

## Mappatura Componenti

### Dark Mode Switcher

```php
// ❌ ERRATO - Componente Livewire nel tema
<livewire:dark-mode-switcher />

// ✅ CORRETTO - Widget UI nel modulo appropriato
<x-ui::dark-mode-switcher />
```

**Posizione**: `Modules/UI/app/Filament/Widgets/DarkModeSwitcherWidget.php`
**Motivazione**: Elemento UI comune, utilizzabile in più contesti

### Language Switcher

```php
// ❌ ERRATO - Componente Livewire nel tema
<livewire:lang.switcher />

// ✅ CORRETTO - Widget Lang nel modulo appropriato
<x-lang::language-switcher />
```

**Posizione**: `Modules/Lang/app/Filament/Widgets/LanguageSwitcherWidget.php`
**Motivazione**: Funzionalità specifica della gestione lingue

## Struttura dei Widget

### Widget Base Structure

```php
<?php

declare(strict_types=1);

namespace Modules\UI\Filament\Widgets;

use Modules\Xot\Filament\Widgets\XotBaseWidget;

class DarkModeSwitcherWidget extends XotBaseWidget
{
    protected static string $view = 'ui::filament.widgets.dark-mode-switcher';

    /**
     * Determina se il widget può essere visualizzato.
     */
    public function canView(): bool
    {
        return true;
    }

    /**
     * Schema del form per la configurazione del widget.
     */
    public function getFormSchema(): array
    {
        return [];
    }

    /**
     * Dati da passare alla vista.
     */
    protected function getViewData(): array
    {
        return [
            'current_theme' => session('theme', 'light'),
        ];
    }
}
```

### View Template

```blade
{{-- resources/views/filament/widgets/dark-mode-switcher.blade.php --}}
<div class="dark-mode-switcher">
    <button 
        type="button" 
        onclick="toggleDarkMode()"
        class="p-2 rounded-lg transition-colors duration-200 hover:bg-gray-100 dark:hover:bg-gray-700"
        aria-label="@lang('ui::widgets.dark_mode_switcher.toggle')"
    >
        <x-heroicon-o-sun class="w-5 h-5 dark:hidden" />
        <x-heroicon-o-moon class="w-5 h-5 hidden dark:block" />
    </button>
</div>

<script>
function toggleDarkMode() {
    const html = document.documentElement;
    const isDark = html.classList.contains('dark');
    
    if (isDark) {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    } else {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    }
}
</script>
```

## Registrazione nei Service Provider

### Modulo UI

```php
// Modules/UI/app/Providers/UIServiceProvider.php
public function boot(): void
{
    parent::boot();
    
    // Registra i widget UI
    $this->loadViewsFrom(__DIR__.'/../../resources/views', 'ui');
    
    // Registra i componenti Blade
    Blade::componentNamespace('Modules\\UI\\View\\Components', 'ui');
}
```

### Modulo Lang

```php
// Modules/Lang/app/Providers/LangServiceProvider.php
public function boot(): void
{
    parent::boot();
    
    // Registra i widget Lang
    $this->loadViewsFrom(__DIR__.'/../../resources/views', 'lang');
    
    // Registra i componenti Blade
    Blade::componentNamespace('Modules\\Lang\\View\\Components', 'lang');
}
```

## Utilizzo nei Temi

### Header Template Corretto

```blade
{{-- Themes/Sixteen/resources/views/components/sections/header.blade.php --}}
<header class="text-white bg-emerald-700">
    <div class="h-12 bg-emerald-900 min-h-12 navbar">
        <div class="flex justify-between w-full max-w-screen-xl mx-auto">
            <div class="flex-1 py-1">
                <a class="text-sm" href="#">Nome della Regione</a>
            </div>
            <div class="flex-none">
                <ul class="px-1 menu menu-horizontal">
                    {{-- Widget UI per dark mode --}}
                    <x-ui::dark-mode-switcher />
                    
                    {{-- Widget Lang per cambio lingua --}}
                    <x-lang::language-switcher />
                    
                    {{-- Resto del codice... --}}
                </ul>
            </div>
        </div>
    </div>
</header>
```

## Best Practices

### 1. Nomenclatura

- **Widget files**: `NomeFunzionaleWidget.php` (es. `DarkModeSwitcherWidget.php`)
- **View files**: `nome-funzionale-widget.blade.php` (kebab-case)
- **Component calls**: `<x-modulo::nome-componente />` (kebab-case)

### 2. Configurazione

- Utilizzare parametri per personalizzazione
- Fornire valori di default sensati
- Documentare tutte le opzioni disponibili

### 3. Accessibilità

- Includere attributi ARIA appropriati
- Supportare navigazione da tastiera
- Fornire alternative testuali

### 4. Performance

- Minimizzare JavaScript inline
- Utilizzare lazy loading quando appropriato
- Cacheare dati statici

## Migrazione da Livewire a Widget

### Checklist di Migrazione

1. **Identifica componenti Livewire nei temi**
   - Cerca pattern `<livewire:nome-componente />`
   - Classifica per dominio funzionale

2. **Determina il modulo appropriato**
   - UI comune → Modulo UI
   - Funzionalità specifica → Modulo del dominio

3. **Crea il widget**
   - Estendi `XotBaseWidget`
   - Implementa metodi richiesti
   - Crea vista appropriata

4. **Registra il componente**
   - Aggiorna ServiceProvider del modulo
   - Registra namespace Blade

5. **Aggiorna i temi**
   - Sostituisci chiamate Livewire
   - Testa funzionalità

6. **Documenta**
   - Aggiorna documentazione modulo
   - Crea collegamenti bidirezionali

## Errori Comuni da Evitare

### ❌ Componenti Livewire nei Temi
```blade
<livewire:dark-mode-switcher />
<livewire:lang.switcher />
```

### ❌ Logica di Business nei Temi
```blade
@php
$languages = App\Models\Language::active()->get();
@endphp
```

### ❌ Hardcoding nei Widget
```php
protected function getViewData(): array
{
    return [
        'languages' => ['it', 'en', 'de'], // ❌ Hardcoded
    ];
}
```

### ✅ Pattern Corretto
```blade
<x-ui::dark-mode-switcher />
<x-lang::language-switcher />
```

## Riferimenti

- [Modulo UI](../laravel/Modules/UI/)
- [Modulo Lang](../laravel/Modules/Lang/)
- [Widget Documentation](../laravel/Modules/UI/docs/widgets.md)
- [Theme Components](./theme_components.md)
- [Architecture](./architecture.md)

## Changelog

### 2024-01-XX - Migrazione da Livewire a Widget
- **Problema**: Componenti Livewire utilizzati nei temi
- **Motivazione**: Separazione responsabilità, modularità, riusabilità
- **Soluzione**: 
  - Creati widget appropriati nei moduli UI e Lang
  - Aggiornata documentazione architetturale
  - Definite regole per prevenire errori futuri

*Ultimo aggiornamento: gennaio 2025*