# Filament 5 Installation & Setup - Complete Guide

**Data**: 2026-03-22  
**Version**: Filament 5.x  
**Status**: ✅ DOCUMENTED

---

## 🎯 OBIETTIVO

Documentare l'installazione corretta di Filament 5 per tabelle, form e widgets nel front-office, con configurazione CSS/JS e Tailwind.

---

## 📚 FILAMENT 5 ARCHITECTURE

### Core Packages

```bash
# Root composer.json (solo core infrastructure)
composer require filament/actions:^5.0
composer require filament/filament:^5.0
composer require filament/forms:^5.0
composer require filament/tables:^5.0
composer require filament/widgets:^5.0
```

### Module-Specific Dependencies

```json
// Modules/Predict/composer.json
{
  "require": {
    "filament/actions": "^5.0",
    "filament/tables": "^5.0",
    "filament/widgets": "^5.0"
  }
}
```

---

## 🔧 INSTALLATION STEPS

### Step 1: Composer Install

```bash
cd laravel
composer require filament/actions:^5.0
composer require filament/tables:^5.0
composer require filament/widgets:^5.0
```

### Step 2: Tailwind CSS Configuration

```css
/* Themes/TwentyOne/resources/css/app.css */
@import "tailwindcss";

@source "../../Modules/**/resources/views/**/*.blade.php";
@source "../../Modules/**/Filament/**/*.php";
@source "./resources/views/**/*.blade.php";
@source "../../resources/views/**/*.blade.php";

/* Filament CSS (auto-included via @filamentStyles) */
```

### Step 3: Vite Configuration

```js
// Themes/TwentyOne/vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```

### Step 4: Blade Layout Setup

```blade
{{-- Themes/TwentyOne/resources/views/components/layouts/main.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Meta tags -->
    
    {{-- Filament Styles (auto-includes CSS) --}}
    @filamentStyles
    
    {{-- Livewire Styles --}}
    @livewireStyles
    
    {{-- Vite CSS --}}
    @vite(['resources/css/app.css'], 'themes/TwentyOne')
</head>
<body>
    {{ $slot }}
    
    {{-- Filament Scripts (auto-includes JS) --}}
    @filamentScripts
    
    {{-- Livewire Scripts --}}
    @livewireScripts
    
    {{-- Vite JS --}}
    @vite(['resources/js/app.js'], 'themes/TwentyOne')
</body>
</html>
```

---

## 📦 FILAMENT ASSETS

### Automatic Asset Injection

Filament 5 gestisce automaticamente CSS/JS tramite:

```blade
@filamentStyles  <!-- Include all Filament CSS -->
@filamentScripts <!-- Include all Filament JS -->
```

**NON serve**:
- ❌ `<link>` manuali per CSS
- ❌ `<script>` manuali per JS
- ❌ npm install di pacchetti Filament
- ❌ Import in app.js

### Asset Loading Order

```blade
<head>
    <!-- 1. Meta tags -->
    @stack('head')
    
    <!-- 2. Filament CSS (core) -->
    @filamentStyles
    
    <!-- 3. Livewire CSS -->
    @livewireStyles
    
    <!-- 4. Theme CSS (Tailwind) -->
    @vite(['resources/css/app.css'])
</head>

<body>
    {{ $slot }}
    
    <!-- 1. Filament JS (core + components) -->
    @filamentScripts
    
    <!-- 2. Livewire JS -->
    @livewireScripts
    
    <!-- 3. Theme JS (Alpine, custom) -->
    @vite(['resources/js/app.js'])
    
    @stack('scripts')
</body>
```

---

## 🎨 TABLE WIDGET SETUP

### Widget Class

```php
<?php

namespace Modules\Predict\Filament\Widgets;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Modules\Xot\Filament\Widgets\XotBaseTableWidget;

class PredictTableWidget extends XotBaseTableWidget
{
    protected int|string|array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'title'),
            ])
            ->paginated([12, 24, 48]);
    }
}
```

### Widget View Bridge

```blade
{{-- Themes/TwentyOne/resources/views/filament/widgets/predict-table.blade.php --}}
@livewire(
    \Modules\Predict\Filament\Widgets\PredictTableWidget::class,
    [
        'homepageMode' => true,
        'minimumOutcomes' => 4,
    ],
    key('predict-table-widget-homepage')
)
```

### CMS JSON Integration

```json
{
  "type": "widget",
  "enabled": true,
  "data": {
    "view": "pub_theme::filament.widgets.predict-table",
    "widget": "Modules\\Predict\\Filament\\Widgets\\PredictTableWidget"
  }
}
```

---

## 🔍 SEARCH ICON FIX

### Problema: Lente d'ingrandimento troppo grande

**Causa**: Icone Heroicons non caricate correttamente o CSS mancante.

**Soluzione**:

1. **Verificare Filament Scripts**:
```blade
@filamentScripts  <!-- DEVE essere prima di @livewireScripts -->
```

2. **Verificare Tailwind Source**:
```css
/* app.css */
@source "../../Modules/**/Filament/**/*.php";
```

3. **Clear Cache**:
```bash
php artisan view:clear
php artisan cache:clear
npm run build
```

---

## 📊 PAGINATION & FILTERS STYLING

### Se non funzionano:

1. **Controllare Alpine.js**:
```blade
<!-- In app.js -->
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
```

2. **Controllare Livewire**:
```blade
@livewireScripts  <!-- DEVE essere presente -->
```

3. **Controllare Filament Assets**:
```bash
php artisan filament:assets
```

---

## ✅ PRE-INSTALLATION CHECKLIST

### Theme Requirements

- [ ] **Tailwind CSS v4** installato
- [ ] **Vite** configurato
- [ ] **@filamentStyles** nel `<head>`
- [ ] **@filamentScripts** prima di `</body>`
- [ ] **@livewireStyles** e **@livewireScripts**
- [ ] **Alpine.js** installato e avviato
- [ ] **CSS source paths** includono `Modules/**/Filament/`

### Module Requirements

- [ ] **filament/tables** in composer.json
- [ ] **filament/widgets** in composer.json
- [ ] **filament/actions** in composer.json
- [ ] Widget estende `XotBaseTableWidget`
- [ ] View bridge creata in `Themes/*/resources/views/filament/widgets/`

---

## 🧪 TESTING

### Test 1: Search Icon

```bash
# Verifica che icona sia visibile
curl http://predict.local/it | grep -o "heroicon-o-magnifying-glass"
# Deve restituire match
```

### Test 2: Filters Dropdown

```bash
# Verifica che filters siano renderizzati
curl http://predict.local/it/predicts | grep -o "fi-ta-filters"
# Deve restituire match
```

### Test 3: Pagination

```bash
# Verifica che pagination sia presente
curl http://predict.local/it/predicts | grep -o "fi-pagination"
# Deve restituire match
```

---

## 📝 FILE DA CREARE/MODIFICARE

### Theme Files

```
Themes/TwentyOne/
├── resources/
│   ├── css/
│   │   └── app.css (aggiungere @source Filament)
│   ├── js/
│   │   └── app.js (Alpine.js)
│   └── views/
│       └── components/
│           └── layouts/
│               ├── main.blade.php (@filamentStyles/Scripts)
│               └── app.blade.php (extends main)
└── vite.config.js
```

### Module Files

```
Modules/Predict/
├── app/
│   └── Filament/
│       └── Widgets/
│           └── PredictTableWidget.php
└── composer.json (aggiungere filament/*)
```

---

## 🚨 COMMON ERRORS

### 1. Search Icon Missing

**Errore**: Icona lente non visibile o troppo grande

**Causa**: Filament CSS non caricato

**Fix**:
```blade
<head>
    @filamentStyles  <!-- Aggiungere PRIMA di @vite -->
</head>
```

### 2. Filters Not Working

**Errore**: Dropdown filters non si aprono

**Causa**: Alpine.js non avviato

**Fix**:
```js
// app.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
```

### 3. Pagination Broken

**Errore**: Click su pagination non funziona

**Causa**: Livewire Scripts mancanti

**Fix**:
```blade
<body>
    @livewireScripts  <!-- Aggiungere PRIMA di @vite -->
</body>
```

---

## 📚 RESOURCES

- **Filament 5 Docs**: https://filamentphp.com/docs/5.x
- **Tables**: https://filamentphp.com/docs/5.x/tables
- **Widgets**: https://filamentphp.com/docs/5.x/widgets
- **Forms**: https://filamentphp.com/docs/5.x/forms

---

## 🎯 NEXT STEPS

1. ✅ Verificare che tutti i temi abbiano `@filamentStyles` e `@filamentScripts`
2. ✅ Aggiornare `app.css` con source paths corretti
3. ✅ Testare search icon, filters, pagination
4. ✅ Documentare eventuali fix specifici per tema

---

**Maintained By**: AI Agents Team  
**Last Review**: 2026-03-22  
**Next Review**: After Filament 5 upgrade
