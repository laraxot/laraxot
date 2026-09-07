<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
# 🔴 CRITICAL RULES - AI Agents

**Path**: `./.agents/docs/rules/00-INDEX.md`  
=======
=======
>>>>>>> a988596b (first)
---
title: "🔴 CRITICAL RULES - AI Agents"
type: concept
tags: [index]
created: 2026-07-14
updated: 2026-07-14
qmd: "00-index 🔴 critical rules - ai agents"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./bash-commands-auto-allow.md"
  - "./llm-wiki-rule.md"
  - "./multi-outcome-no-binary-fields.md"
  - "./one-migration-per-model.md"
  - "./phpmd-phar-installation.md"
  - "./translation-structure-5-levels-mandatory.md"
  - "./translation-structure-5-levels.md"
  - "./use-models-not-db-table.md"
---

# 🔴 CRITICAL RULES - AI Agents

**Path**: `./.agents/docs/rules/00-index-1.md`  
<<<<<<< HEAD
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
=======
# 🔴 CRITICAL RULES - AI Agents

**Path**: `./.agents/docs/rules/00-INDEX.md`  
>>>>>>> a377e9e6 (.)
**Last Updated**: 2026-03-26  
**Status**: ✅ ALWAYS ACTIVE  
**Priority**: BLOCKER (violation = STOP immediately)

---

## 🎯 Rule #1: Filament Tables for Lists

<<<<<<< HEAD
<<<<<<< HEAD
> **MAI** creare blade personalizzati per liste di outcomes, predict, o dati tabellari.
=======
> **MAI** creare blade personalizzati per liste di outcomes, forecast, o dati tabellari.
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
> **MAI** creare blade personalizzati per liste di outcomes, predict, o dati tabellari.
<<<<<<< HEAD
> **MAI** creare blade personalizzati per liste di outcomes, forecast, o dati tabellari.
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
> **SEMPRE** usare Filament Table Widget che ha già:
> - ✅ Search (debounce 400ms)
> - ✅ Sorting (multi-column)
> - ✅ Filters (status, category, date, hot)
> - ✅ Pagination (12/24/48)
> - ✅ CSS hooks (fi-ta-*)
> - ✅ Livewire reactivity
> - ✅ URL synchronization
> - ✅ Export ready
> - ✅ Accessibility built-in

### ✅ CORRECT - Filament Table Widget

```php
// ✅ CORRETTO - Filament Table Widget
<<<<<<< HEAD
<<<<<<< HEAD
class PredictTableWidget extends TableWidget
=======
class ForecastTableWidget extends TableWidget
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
class PredictTableWidget extends TableWidget
<<<<<<< HEAD
class ForecastTableWidget extends TableWidget
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
{
    public function table(Table $table): Table
    {
        return $table
            ->searchable()      // ← Automatic search
            ->filters([...])    // ← Automatic filters
            ->columns([         // ← Automatic sorting
                TextColumn::make('title')->sortable(),
                TextColumn::make('probability')->sortable(),
            ]);
    }
}
```

### ❌ WRONG - Custom Blade

```blade
{{-- ❌ SBAGLIATO - Custom blade con loop manuale --}}
@foreach($outcomes as $outcome)
    <div class="outcome-card">
        {{ $outcome['title'] }}
        {{ $outcome['probability'] }}%
    </div>
@endforeach

{{-- ❌ Implementazione manuale di search, sorting, filters --}}
<input type="text" wire:model.debounce.400ms="search" />
{{-- MAI FARE QUESTO! Filament lo fa già --}}
```

---

## 🎯 Rule #2: Multi-Outcome Universal

> **TUTTO è multi-risposta** (anche SÌ/NO = 2 outcomes)
> - SÌ/NO = 2 outcomes (caso particolare)
> - F1 = 6 outcomes (caso generale)
> - Politica = 10+ outcomes (caso esteso)
> - **NON ESISTE** dicotomia binary vs multi-outcome

### ✅ CORRECT - Universal Approach

```php
// ✅ CORRETTO - Tutti gli outcomes trattati allo stesso modo
foreach ($outcomes as $outcome) {
    // Funziona per F1 (6), Politica (10), Binary (2)
}
```

### ❌ WRONG - Binary Dichotomy

```php
// ❌ SBAGLIATO - Distinzione binary vs multi
if ($isBinary) {
    // logica speciale per YES/NO
} else {
    // logica per multi-outcome
}
```

---

## 🎯 Rule #3: Container Agnostic

> **MAI** logica specifica nel container blade
<<<<<<< HEAD
<<<<<<< HEAD
> Container deve essere **agnostico** (predicts, articles, events, etc.)
=======
> Container deve essere **agnostico** (forecasts, articles, events, etc.)
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
> Container deve essere **agnostico** (predicts, articles, events, etc.)
<<<<<<< HEAD
> Container deve essere **agnostico** (forecasts, articles, events, etc.)
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)

### ✅ CORRECT - Agnostic Container

```blade
{{-- ✅ CORRETTO - Container agnostico --}}
<div>
<<<<<<< HEAD
<<<<<<< HEAD
    @livewire('view-predict-widget', ['predict' => $predict])
=======
    @livewire('view-forecast-widget', ['forecast' => $forecast])
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    @livewire('view-predict-widget', ['predict' => $predict])
<<<<<<< HEAD
    @livewire('view-forecast-widget', ['forecast' => $forecast])
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
</div>
```

### ❌ WRONG - Container Pollution

```blade
{{-- ❌ SBAGLIATO - Logica specifica nel container --}}
<<<<<<< HEAD
<<<<<<< HEAD
@if($container0 === 'predicts')
=======
@if($container0 === 'forecasts')
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
@if($container0 === 'predicts')
<<<<<<< HEAD
@if($container0 === 'forecasts')
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
    {{-- domain logic --}}
@endif
```

---

## 🎯 Rule #4: Actions Over Services

> **USARE** Actions per business logic
> **MAI** creare Service classes

### ✅ CORRECT - Action Class

```php
// ✅ CORRETTO - Action class
class BuildOutcomesAction extends Action
{
<<<<<<< HEAD
<<<<<<< HEAD
    public function execute(Predict $predict): array
=======
    public function execute(Forecast $forecast): array
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    public function execute(Predict $predict): array
<<<<<<< HEAD
    public function execute(Forecast $forecast): array
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
    {
        // Business logic here
    }
}
```

### ❌ WRONG - Service Class

```php
// ❌ SBAGLIATO - Service class
class OutcomeService
{
    // MAI FARE QUESTO!
}
```

---

## 🎯 Rule #5: PHPStan Level MAX

> **SEMPRE** PHPStan Level MAX dopo modifiche PHP
> **MAI** ignorare errori PHPStan

```bash
# ✅ SEMPRE eseguire prima di commit
composer phpstan
# MUST PASS: 0 errors
```

---

## 📋 Pre-Commit Checklist

**BEFORE** any `git commit`:

- [ ] ✅ PHPStan: 0 errors
- [ ] ✅ PHPInsights: Quality > 90%
- [ ] ✅ Laravel Pint: Code formatted
- [ ] ✅ Pest Tests: All passing
- [ ] ✅ Screenshots: Page verified
- [ ] ✅ Documentation: Indices updated
- [ ] ✅ Cache: Cleared
- [ ] ✅ **Filament Tables used (NOT custom blade)**

**IF ANY CHECK FAILS** → **DO NOT COMMIT**

---

## 🔗 Related Documentation

### Project Rules
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
- **[00-INDEX.md](00-INDEX.md)** - Master rules index
=======
- **[00-index-1.md](00-index-1.md)** - Master rules index
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
- **[00-index-1.md](00-index-1.md)** - Master rules index
>>>>>>> a988596b (first)
=======
- **[00-INDEX.md](00-INDEX.md)** - Master rules index
>>>>>>> a377e9e6 (.)
- **[multi-outcome-universal.md](multi-outcome-universal.md)** - Multi-outcome principle
- **[container-agnostic.md](container-agnostic.md)** - Container agnostic rule
- **[actions-over-services.md](actions-over-services.md)** - Actions over services

### Guidelines
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
- **[../guidelines/00-INDEX.md](../guidelines/00-INDEX.md)** - Guidelines index
=======
=======
>>>>>>> a988596b (first)
- **[../guidelines/00-index-1.md](../guidelines/00-index-1.md)** - Guidelines index
- **[../guidelines/filament-tables.md](../guidelines/filament-tables.md)** - Filament tables guide

### Skills
- **[../skills/filament-tables.md](../skills/filament-tables.md)** - Filament tables skill
- **[../skills/laravel-best-practices.md](../skills/laravel-best-practices.md)** - Laravel best practices

---

## 📝 Changelog

### 2026-03-26 - CRITICAL UPDATE
- ✅ Added Rule #1: Filament Tables for Lists (BLOCKER)
- ✅ Updated pre-commit checklist
- ✅ Added examples (CORRECT vs WRONG)

---

**Maintained By**: AI Agents Team  
**Review Cycle**: Per-release  
**Next Review**: 2026-04-02  
**Enforcement**: 🔴 CRITICAL rules are BLOCKERS
<<<<<<< HEAD

=======
>>>>>>> a988596b (first)
---

<!-- Merged from 00-INDEX.md, which collided with this file on case-insensitive filesystems. -->

# 🔴 CRITICAL RULES - AI Agents

**Path**: `./.agents/docs/rules/00-index.md`  
**Last Updated**: 2026-03-26  
**Status**: ✅ ALWAYS ACTIVE  
**Priority**: BLOCKER (violation = STOP immediately)

---

## 🎯 Rule #1: Filament Tables for Lists

> **MAI** creare blade personalizzati per liste di outcomes, forecast, o dati tabellari.
> **SEMPRE** usare Filament Table Widget che ha già:
> - ✅ Search (debounce 400ms)
> - ✅ Sorting (multi-column)
> - ✅ Filters (status, category, date, hot)
> - ✅ Pagination (12/24/48)
> - ✅ CSS hooks (fi-ta-*)
> - ✅ Livewire reactivity
> - ✅ URL synchronization
> - ✅ Export ready
> - ✅ Accessibility built-in

### ✅ CORRECT - Filament Table Widget

```php
// ✅ CORRETTO - Filament Table Widget
class ForecastTableWidget extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->searchable()      // ← Automatic search
            ->filters([...])    // ← Automatic filters
            ->columns([         // ← Automatic sorting
                TextColumn::make('title')->sortable(),
                TextColumn::make('probability')->sortable(),
            ]);
    }
}
```

### ❌ WRONG - Custom Blade

```blade
{{-- ❌ SBAGLIATO - Custom blade con loop manuale --}}
@foreach($outcomes as $outcome)
    <div class="outcome-card">
        {{ $outcome['title'] }}
        {{ $outcome['probability'] }}%
    </div>
@endforeach

{{-- ❌ Implementazione manuale di search, sorting, filters --}}
<input type="text" wire:model.debounce.400ms="search" />
{{-- MAI FARE QUESTO! Filament lo fa già --}}
```

---

## 🎯 Rule #2: Multi-Outcome Universal

> **TUTTO è multi-risposta** (anche SÌ/NO = 2 outcomes)
> - SÌ/NO = 2 outcomes (caso particolare)
> - F1 = 6 outcomes (caso generale)
> - Politica = 10+ outcomes (caso esteso)
> - **NON ESISTE** dicotomia binary vs multi-outcome

### ✅ CORRECT - Universal Approach

```php
// ✅ CORRETTO - Tutti gli outcomes trattati allo stesso modo
foreach ($outcomes as $outcome) {
    // Funziona per F1 (6), Politica (10), Binary (2)
}
```

### ❌ WRONG - Binary Dichotomy

```php
// ❌ SBAGLIATO - Distinzione binary vs multi
if ($isBinary) {
    // logica speciale per YES/NO
} else {
    // logica per multi-outcome
}
```

---

## 🎯 Rule #3: Container Agnostic

> **MAI** logica specifica nel container blade
> Container deve essere **agnostico** (forecasts, articles, events, etc.)

### ✅ CORRECT - Agnostic Container

```blade
{{-- ✅ CORRETTO - Container agnostico --}}
<div>
    @livewire('view-forecast-widget', ['forecast' => $forecast])
</div>
```

### ❌ WRONG - Container Pollution

```blade
{{-- ❌ SBAGLIATO - Logica specifica nel container --}}
@if($container0 === 'forecasts')
    {{-- domain logic --}}
@endif
```

---

## 🎯 Rule #4: Actions Over Services

> **USARE** Actions per business logic
> **MAI** creare Service classes

### ✅ CORRECT - Action Class

```php
// ✅ CORRETTO - Action class
class BuildOutcomesAction extends Action
{
    public function execute(Forecast $forecast): array
    {
        // Business logic here
    }
}
```

### ❌ WRONG - Service Class

```php
// ❌ SBAGLIATO - Service class
class OutcomeService
{
    // MAI FARE QUESTO!
}
```

---

## 🎯 Rule #5: PHPStan Level MAX

> **SEMPRE** PHPStan Level MAX dopo modifiche PHP
> **MAI** ignorare errori PHPStan

```bash
# ✅ SEMPRE eseguire prima di commit
composer phpstan
# MUST PASS: 0 errors
```

---

## 📋 Pre-Commit Checklist

**BEFORE** any `git commit`:

- [ ] ✅ PHPStan: 0 errors
- [ ] ✅ PHPInsights: Quality > 90%
- [ ] ✅ Laravel Pint: Code formatted
- [ ] ✅ Pest Tests: All passing
- [ ] ✅ Screenshots: Page verified
- [ ] ✅ Documentation: Indices updated
- [ ] ✅ Cache: Cleared
- [ ] ✅ **Filament Tables used (NOT custom blade)**

**IF ANY CHECK FAILS** → **DO NOT COMMIT**

---

## 🔗 Related Documentation

### Project Rules
- **[00-index.md](00-index.md)** - Master rules index
- **[multi-outcome-universal.md](multi-outcome-universal.md)** - Multi-outcome principle
- **[container-agnostic.md](container-agnostic.md)** - Container agnostic rule
- **[actions-over-services.md](actions-over-services.md)** - Actions over services

### Guidelines
- **[../guidelines/00-index.md](../guidelines/00-index.md)** - Guidelines index
<<<<<<< HEAD
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
=======
- **[../guidelines/00-INDEX.md](../guidelines/00-INDEX.md)** - Guidelines index
>>>>>>> a377e9e6 (.)
- **[../guidelines/filament-tables.md](../guidelines/filament-tables.md)** - Filament tables guide

### Skills
- **[../skills/filament-tables.md](../skills/filament-tables.md)** - Filament tables skill
- **[../skills/laravel-best-practices.md](../skills/laravel-best-practices.md)** - Laravel best practices

---

## 📝 Changelog

### 2026-03-26 - CRITICAL UPDATE
- ✅ Added Rule #1: Filament Tables for Lists (BLOCKER)
- ✅ Updated pre-commit checklist
- ✅ Added examples (CORRECT vs WRONG)

---

**Maintained By**: AI Agents Team  
**Review Cycle**: Per-release  
**Next Review**: 2026-04-02  
**Enforcement**: 🔴 CRITICAL rules are BLOCKERS
