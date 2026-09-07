<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
# 🏛️ Architecture Zen Philosophy

**Part of**: [00-INDEX.md](00-INDEX.md) — AI Agents Coordination  
**Related**: [04-FILAMENT-PHILOSOPHY.md](04-FILAMENT-PHILOSOPHY.md) — Filament Widgets
=======
=======
>>>>>>> a988596b (first)
---
title: "🏛️ Architecture Zen Philosophy"
type: concept
tags: [architecture, zen]
created: 2026-07-14
updated: 2026-07-14
qmd: "03-architecture-zen 🏛️ architecture zen philosophy"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./01-gsd-workflow.md"
  - "./02-bmad-workflow.md"
  - "./04-filament-philosophy.md"
  - "./05-front-office-audit.md"
  - "./06-cinematic-effects.md"
  - "./07-mcp-tailwind-ui.md"
  - "./08-verified-commit-governance.md"
---

# 🏛️ Architecture Zen Philosophy

**Part of**: [00-index-1.md](00-index-1.md) — AI Agents Coordination  
**Related**: [04-filament-philosophy.md](04-filament-philosophy.md) — Filament Widgets
<<<<<<< HEAD
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
=======
# 🏛️ Architecture Zen Philosophy

**Part of**: [00-INDEX.md](00-INDEX.md) — AI Agents Coordination  
**Related**: [04-FILAMENT-PHILOSOPHY.md](04-FILAMENT-PHILOSOPHY.md) — Filament Widgets
>>>>>>> a377e9e6 (.)

---

## 🔴 Fundamental Rules

### 1. **NO Themes/*/Http/Livewire/**

**Philosophy**: Theme is the DRESS, not the LOGIC.

```
❌ WRONG
Themes/TwentyOne/Http/Livewire/
Themes/Sixteen/Http/Livewire/

✅ CORRECT
<<<<<<< HEAD
<<<<<<< HEAD
Modules/Predict/app/Filament/Widgets/
=======
Modules/Forecast/app/Filament/Widgets/
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
Modules/Predict/app/Filament/Widgets/
<<<<<<< HEAD
Modules/Forecast/app/Filament/Widgets/
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
Modules/UI/app/Filament/Widgets/
```

**Why**:
- **Modules** = Business logic (agnostic, reusable)
- **Themes** = Dress (aesthetics, layout, CSS)
- **Filament Widgets** = UI components (back office + front office)
- **NO Livewire in theme** = Separation of concerns

---

### 2. **NO laravel/docs/**

**Philosophy**: Documentation distributed, close to code.

```
❌ WRONG
laravel/docs/
  ├── COMPOSER_RULE.md
  └── FILAMENT_RULE.md

✅ CORRECT
docs/                          # Only CROSS-MODULE documents
  ├── ARCHITECTURE_ZEN.md
  └── MULTI_AGENT_COORDINATION.md

<<<<<<< HEAD
<<<<<<< HEAD
Modules/Predict/docs/          # Predict-specific docs
=======
Modules/Forecast/docs/          # Forecast-specific docs
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
Modules/Predict/docs/          # Predict-specific docs
<<<<<<< HEAD
Modules/Forecast/docs/          # Forecast-specific docs
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
  ├── PHILOSOPHY.md
  ├── WIDGETS.md
  └── SEEDERS.md

Themes/TwentyOne/docs/         # Theme-specific docs
  ├── DESIGN_SYSTEM.md
  └── KINETIC_WEB_DESIGN.md
```

**Why**:
- **Documentation close to code** = Easier to maintain
- **docs/ root** = Only cross-module documents
- **Module docs** = Module-specific documentation
- **Theme docs** = Theme-specific design system

---

### 3. **NO foreach in blade for lists**

**Philosophy**: Filament Tables does everything.

```blade
❌ WRONG
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
@foreach($predicts as $predict)
    <div class="card">{{ $predict->title }}</div>
@endforeach

✅ CORRECT
<x-page side="content" slug="predicts.index" />

// JSON: predicts.index.json
{
  "type": "widget",
  "data": {
    "view": "pub_theme::filament.widgets.predict-table",
    "widget": "Modules\\Predict\\Filament\\Widgets\\PredictTableWidget"
=======
=======
>>>>>>> a988596b (first)
@foreach($forecasts as $forecast)
    <div class="card">{{ $forecast->title }}</div>
=======
@foreach($predicts as $predict)
    <div class="card">{{ $predict->title }}</div>
>>>>>>> a377e9e6 (.)
@endforeach

✅ CORRECT
<x-page side="content" slug="predicts.index" />

// JSON: predicts.index.json
{
  "type": "widget",
  "data": {
<<<<<<< HEAD
    "view": "pub_theme::filament.widgets.forecast-table",
    "widget": "Modules\\Forecast\\Filament\\Widgets\\ForecastTableWidget"
<<<<<<< HEAD
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
=======
    "view": "pub_theme::filament.widgets.predict-table",
    "widget": "Modules\\Predict\\Filament\\Widgets\\PredictTableWidget"
>>>>>>> a377e9e6 (.)
  }
}
```

**Why**:
- **Filament Table** = Search, filters, sorting, pagination (automatic)
- **NO foreach** = NO logic in blade
- **Widget** = Reusable, testable, maintainable

---

### 4. **NO Blade Logic**

**Philosophy**: Blade is presentation only.

```blade
❌ WRONG
@php
<<<<<<< HEAD
<<<<<<< HEAD
    $probability = $predict->transactions()->sum('amount');
    $participants = $predict->transactions()->distinct('user_id')->count();
=======
    $probability = $forecast->transactions()->sum('amount');
    $participants = $forecast->transactions()->distinct('user_id')->count();
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    $probability = $predict->transactions()->sum('amount');
    $participants = $predict->transactions()->distinct('user_id')->count();
<<<<<<< HEAD
    $probability = $forecast->transactions()->sum('amount');
    $participants = $forecast->transactions()->distinct('user_id')->count();
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
@endphp

✅ CORRECT
// Action class
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
class CalculatePredictStatsAction {
    public function execute(Predict $predict): array {
        return [
            'probability' => $predict->transactions()->sum('amount'),
            'participants' => $predict->transactions()->distinct('user_id')->count(),
=======
=======
>>>>>>> a988596b (first)
class CalculateForecastStatsAction {
    public function execute(Forecast $forecast): array {
        return [
            'probability' => $forecast->transactions()->sum('amount'),
            'participants' => $forecast->transactions()->distinct('user_id')->count(),
<<<<<<< HEAD
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
=======
class CalculatePredictStatsAction {
    public function execute(Predict $predict): array {
        return [
            'probability' => $predict->transactions()->sum('amount'),
            'participants' => $predict->transactions()->distinct('user_id')->count(),
>>>>>>> a377e9e6 (.)
        ];
    }
}

// Blade
{{ $stats['probability'] }}
```

**Why**:
- **Blade** = Presentation only (HTML, CSS classes)
- **Actions** = Business logic
- **Components** = Complex presentation logic

---

## 🧠 Zen Philosophy

### Theme is the Dress

> **"Module is the body, theme is the dress"**

**Module (Body)**:
- ✅ Business logic
- ✅ Data models
- ✅ Filament Widgets
- ✅ Actions, Services
- ✅ Seeders, Migrations

**Theme (Clothing)**:
- ✅ Layout (app.blade.php)
- ✅ CSS (Tailwind)
- ✅ Aesthetic components
- ✅ Folio pages (routing)
- ❌ NO business logic
- ❌ NO Livewire
- ❌ NO Models

---

### Filament Widgets are Universal

> **"One widget for all themes"**

```
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
Modules/Predict/app/Filament/Widgets/PredictTableWidget.php
    ↓
pub_theme::filament.widgets.predict-table (view namespace)
    ↓
Themes/TwentyOne/resources/views/filament/widgets/predict-table.blade.php
=======
=======
>>>>>>> a988596b (first)
Modules/Forecast/app/Filament/Widgets/ForecastTableWidget.php
=======
Modules/Predict/app/Filament/Widgets/PredictTableWidget.php
>>>>>>> a377e9e6 (.)
    ↓
pub_theme::filament.widgets.predict-table (view namespace)
    ↓
<<<<<<< HEAD
Themes/TwentyOne/resources/views/filament/widgets/forecast-table.blade.php
<<<<<<< HEAD
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
=======
Themes/TwentyOne/resources/views/filament/widgets/predict-table.blade.php
>>>>>>> a377e9e6 (.)
```

**Benefits**:
- ✅ Widget written ONCE
- ✅ Used in ALL themes
- ✅ Theme customizes only view
- ✅ Logic ALWAYS in module

---

### Documentation is Treasure Map

> **"Documentation close to code = Treasure found"**

```
<<<<<<< HEAD
<<<<<<< HEAD
Modules/Predict/
=======
Modules/Forecast/
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
Modules/Predict/
<<<<<<< HEAD
Modules/Forecast/
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
├── app/
│   ├── Models/
│   ├── Filament/
│   └── Actions/
<<<<<<< HEAD
<<<<<<< HEAD
├── docs/              ← Treasure map of Predict
=======
├── docs/              ← Treasure map of Forecast
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
├── docs/              ← Treasure map of Predict
<<<<<<< HEAD
├── docs/              ← Treasure map of Forecast
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
│   ├── PHILOSOPHY.md
│   ├── WIDGETS.md
│   └── SEEDERS.md
└── database/
    └── seeders/
```

**Rule**:
- **docs/ root** = Only cross-module documents
- **Module docs** = Module-specific
- **Theme docs** = Design system, CSS

---

## 🚨 Common Mistakes

### 1. ❌ Livewire in Theme

```
Themes/TwentyOne/Http/Livewire/
```

**✅ CORRECT**:
```
<<<<<<< HEAD
<<<<<<< HEAD
Modules/Predict/app/Filament/Widgets/
=======
Modules/Forecast/app/Filament/Widgets/
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
Modules/Predict/app/Filament/Widgets/
<<<<<<< HEAD
Modules/Forecast/app/Filament/Widgets/
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
```

---

### 2. ❌ Documentation in root

```
laravel/docs/
  └── FILAMENT_RULE.md
```

**✅ CORRECT**:
```
docs/
  └── ARCHITECTURE_ZEN.md

<<<<<<< HEAD
<<<<<<< HEAD
Modules/Predict/docs/
=======
Modules/Forecast/docs/
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
Modules/Predict/docs/
<<<<<<< HEAD
Modules/Forecast/docs/
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
  └── WIDGETS.md
```

---

### 3. ❌ Foreach in blade

```blade
<<<<<<< HEAD
<<<<<<< HEAD
@foreach($predicts as $predict)
    <x-predict.card :predict="$predict"/>
=======
@foreach($forecasts as $forecast)
    <x-forecast.card :forecast="$forecast"/>
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
@foreach($predicts as $predict)
    <x-predict.card :predict="$predict"/>
<<<<<<< HEAD
@foreach($forecasts as $forecast)
    <x-forecast.card :forecast="$forecast"/>
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
@endforeach
```

**✅ CORRECT**:
```blade
<<<<<<< HEAD
<<<<<<< HEAD
<x-page side="content" slug="predicts.index" />
=======
<x-page side="content" slug="forecasts.index" />
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
<x-page side="content" slug="predicts.index" />
<<<<<<< HEAD
<x-page side="content" slug="forecasts.index" />
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)

// JSON
{
  "type": "widget",
<<<<<<< HEAD
<<<<<<< HEAD
  "widget": "Modules\\Predict\\Filament\\Widgets\\PredictTableWidget"
=======
  "widget": "Modules\\Forecast\\Filament\\Widgets\\ForecastTableWidget"
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
  "widget": "Modules\\Predict\\Filament\\Widgets\\PredictTableWidget"
<<<<<<< HEAD
  "widget": "Modules\\Forecast\\Filament\\Widgets\\ForecastTableWidget"
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
}
```

---

### 4. ❌ Logic in blade

```blade
@php
<<<<<<< HEAD
<<<<<<< HEAD
    $volume = $predict->transactions()->sum('amount');
=======
    $volume = $forecast->transactions()->sum('amount');
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    $volume = $predict->transactions()->sum('amount');
<<<<<<< HEAD
    $volume = $forecast->transactions()->sum('amount');
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
@endphp
```

**✅ CORRECT**:
```php
// Action
class CalculateVolumeAction {
<<<<<<< HEAD
<<<<<<< HEAD
    public function execute(Predict $predict) {
        return $predict->transactions()->sum('amount');
=======
    public function execute(Forecast $forecast) {
        return $forecast->transactions()->sum('amount');
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    public function execute(Predict $predict) {
        return $predict->transactions()->sum('amount');
<<<<<<< HEAD
    public function execute(Forecast $forecast) {
        return $forecast->transactions()->sum('amount');
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
    }
}

// Blade
{{ $volume }}
```

---

## ✅ Code Review Checklist

Before committing:

### Architecture
- [ ] ✅ NO `Themes/*/Http/Livewire/`
- [ ] ✅ Widgets in `Modules/*/Filament/Widgets/`
- [ ] ✅ NO `laravel/docs/` (use `docs/` or `Modules/*/docs/`)
- [ ] ✅ NO foreach in blade for lists (use Filament Table)
- [ ] ✅ NO logic in blade (use Actions)

### Documentation
- [ ] ✅ Documentation close to code
- [ ] ✅ docs/ root = only cross-module
- [ ] ✅ Module docs = module-specific
- [ ] ✅ Theme docs = design system, CSS

### Enforcement
- [ ] ✅ PHPStan: NO errors
- [ ] ✅ PHPMD: NO warnings
- [ ] ✅ Code Review: Architecture check
- [ ] ✅ Pre-commit hook: Architecture validation

---

## 🔗 Related Documentation

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
- **Filament Philosophy**: [04-FILAMENT-PHILOSOPHY.md](04-FILAMENT-PHILOSOPHY.md)
- **Front Office Audit**: [05-FRONT-OFFICE-AUDIT.md](05-FRONT-OFFICE-AUDIT.md)
=======
=======
>>>>>>> a988596b (first)
- **Filament Philosophy**: [04-filament-philosophy.md](04-filament-philosophy.md)
- **Front Office Audit**: [05-front-office-audit.md](05-front-office-audit.md)
- **External**: https://github.com/nWidart/laravel-modules

---

**Last Updated**: 2026-03-20  
**Status**: ✅ Mandatory  
**Enforcement**: PHPStan + Code Review + Pre-commit Hook
<<<<<<< HEAD

=======
>>>>>>> a988596b (first)
---

<!-- Merged from 03-ARCHITECTURE-ZEN.md, which collided with this file on case-insensitive filesystems. -->

# 🏛️ Architecture Zen Philosophy

**Part of**: [00-index.md](00-index.md) — AI Agents Coordination  
**Related**: [04-FILAMENT-PHILOSOPHY.md](04-filament-philosophy.md) — Filament Widgets

---

## 🔴 Fundamental Rules

### 1. **NO Themes/*/Http/Livewire/**

**Philosophy**: Theme is the DRESS, not the LOGIC.

```
❌ WRONG
Themes/TwentyOne/Http/Livewire/
Themes/Sixteen/Http/Livewire/

✅ CORRECT
Modules/Forecast/app/Filament/Widgets/
Modules/UI/app/Filament/Widgets/
```

**Why**:
- **Modules** = Business logic (agnostic, reusable)
- **Themes** = Dress (aesthetics, layout, CSS)
- **Filament Widgets** = UI components (back office + front office)
- **NO Livewire in theme** = Separation of concerns

---

### 2. **NO laravel/docs/**

**Philosophy**: Documentation distributed, close to code.

```
❌ WRONG
laravel/docs/
  ├── COMPOSER_RULE.md
  └── FILAMENT_RULE.md

✅ CORRECT
docs/                          # Only CROSS-MODULE documents
  ├── ARCHITECTURE_ZEN.md
  └── MULTI_AGENT_COORDINATION.md

Modules/Forecast/docs/          # Forecast-specific docs
  ├── PHILOSOPHY.md
  ├── WIDGETS.md
  └── SEEDERS.md

Themes/TwentyOne/docs/         # Theme-specific docs
  ├── DESIGN_SYSTEM.md
  └── KINETIC_WEB_DESIGN.md
```

**Why**:
- **Documentation close to code** = Easier to maintain
- **docs/ root** = Only cross-module documents
- **Module docs** = Module-specific documentation
- **Theme docs** = Theme-specific design system

---

### 3. **NO foreach in blade for lists**

**Philosophy**: Filament Tables does everything.

```blade
❌ WRONG
@foreach($forecasts as $forecast)
    <div class="card">{{ $forecast->title }}</div>
@endforeach

✅ CORRECT
<x-page side="content" slug="forecasts.index" />

// JSON: forecasts.index.json
{
  "type": "widget",
  "data": {
    "view": "pub_theme::filament.widgets.forecast-table",
    "widget": "Modules\\Forecast\\Filament\\Widgets\\ForecastTableWidget"
  }
}
```

**Why**:
- **Filament Table** = Search, filters, sorting, pagination (automatic)
- **NO foreach** = NO logic in blade
- **Widget** = Reusable, testable, maintainable

---

### 4. **NO Blade Logic**

**Philosophy**: Blade is presentation only.

```blade
❌ WRONG
@php
    $probability = $forecast->transactions()->sum('amount');
    $participants = $forecast->transactions()->distinct('user_id')->count();
@endphp

✅ CORRECT
// Action class
class CalculateForecastStatsAction {
    public function execute(Forecast $forecast): array {
        return [
            'probability' => $forecast->transactions()->sum('amount'),
            'participants' => $forecast->transactions()->distinct('user_id')->count(),
        ];
    }
}

// Blade
{{ $stats['probability'] }}
```

**Why**:
- **Blade** = Presentation only (HTML, CSS classes)
- **Actions** = Business logic
- **Components** = Complex presentation logic

---

## 🧠 Zen Philosophy

### Theme is the Dress

> **"Module is the body, theme is the dress"**

**Module (Body)**:
- ✅ Business logic
- ✅ Data models
- ✅ Filament Widgets
- ✅ Actions, Services
- ✅ Seeders, Migrations

**Theme (Clothing)**:
- ✅ Layout (app.blade.php)
- ✅ CSS (Tailwind)
- ✅ Aesthetic components
- ✅ Folio pages (routing)
- ❌ NO business logic
- ❌ NO Livewire
- ❌ NO Models

---

### Filament Widgets are Universal

> **"One widget for all themes"**

```
Modules/Forecast/app/Filament/Widgets/ForecastTableWidget.php
    ↓
pub_theme::filament.widgets.forecast-table (view namespace)
    ↓
Themes/TwentyOne/resources/views/filament/widgets/forecast-table.blade.php
```

**Benefits**:
- ✅ Widget written ONCE
- ✅ Used in ALL themes
- ✅ Theme customizes only view
- ✅ Logic ALWAYS in module

---

### Documentation is Treasure Map

> **"Documentation close to code = Treasure found"**

```
Modules/Forecast/
├── app/
│   ├── Models/
│   ├── Filament/
│   └── Actions/
├── docs/              ← Treasure map of Forecast
│   ├── PHILOSOPHY.md
│   ├── WIDGETS.md
│   └── SEEDERS.md
└── database/
    └── seeders/
```

**Rule**:
- **docs/ root** = Only cross-module documents
- **Module docs** = Module-specific
- **Theme docs** = Design system, CSS

---

## 🚨 Common Mistakes

### 1. ❌ Livewire in Theme

```
Themes/TwentyOne/Http/Livewire/
```

**✅ CORRECT**:
```
Modules/Forecast/app/Filament/Widgets/
```

---

### 2. ❌ Documentation in root

```
laravel/docs/
  └── FILAMENT_RULE.md
```

**✅ CORRECT**:
```
docs/
  └── ARCHITECTURE_ZEN.md

Modules/Forecast/docs/
  └── WIDGETS.md
```

---

### 3. ❌ Foreach in blade

```blade
@foreach($forecasts as $forecast)
    <x-forecast.card :forecast="$forecast"/>
@endforeach
```

**✅ CORRECT**:
```blade
<x-page side="content" slug="forecasts.index" />

// JSON
{
  "type": "widget",
  "widget": "Modules\\Forecast\\Filament\\Widgets\\ForecastTableWidget"
}
```

---

### 4. ❌ Logic in blade

```blade
@php
    $volume = $forecast->transactions()->sum('amount');
@endphp
```

**✅ CORRECT**:
```php
// Action
class CalculateVolumeAction {
    public function execute(Forecast $forecast) {
        return $forecast->transactions()->sum('amount');
    }
}

// Blade
{{ $volume }}
```

---

## ✅ Code Review Checklist

Before committing:

### Architecture
- [ ] ✅ NO `Themes/*/Http/Livewire/`
- [ ] ✅ Widgets in `Modules/*/Filament/Widgets/`
- [ ] ✅ NO `laravel/docs/` (use `docs/` or `Modules/*/docs/`)
- [ ] ✅ NO foreach in blade for lists (use Filament Table)
- [ ] ✅ NO logic in blade (use Actions)

### Documentation
- [ ] ✅ Documentation close to code
- [ ] ✅ docs/ root = only cross-module
- [ ] ✅ Module docs = module-specific
- [ ] ✅ Theme docs = design system, CSS

### Enforcement
- [ ] ✅ PHPStan: NO errors
- [ ] ✅ PHPMD: NO warnings
- [ ] ✅ Code Review: Architecture check
- [ ] ✅ Pre-commit hook: Architecture validation

---

## 🔗 Related Documentation

- **Filament Philosophy**: [04-FILAMENT-PHILOSOPHY.md](04-filament-philosophy.md)
- **Front Office Audit**: [05-FRONT-OFFICE-AUDIT.md](05-front-office-audit.md)
<<<<<<< HEAD
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
=======
- **Filament Philosophy**: [04-FILAMENT-PHILOSOPHY.md](04-FILAMENT-PHILOSOPHY.md)
- **Front Office Audit**: [05-FRONT-OFFICE-AUDIT.md](05-FRONT-OFFICE-AUDIT.md)
>>>>>>> a377e9e6 (.)
- **External**: https://github.com/nWidart/laravel-modules

---

**Last Updated**: 2026-03-20  
**Status**: ✅ Mandatory  
**Enforcement**: PHPStan + Code Review + Pre-commit Hook
