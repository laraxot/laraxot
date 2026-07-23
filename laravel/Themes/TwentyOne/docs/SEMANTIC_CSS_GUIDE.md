# 🎨 TwentyOne Theme - Semantic CSS Guide

**Path**: `laravel/Themes/TwentyOne/docs/SEMANTIC_CSS_GUIDE.md`  
**Last Updated**: 2026-03-26  
**Status**: ✅ MANDATORY  
**Version**: 1.0

---

## 🎯 Core Principle

> **"Il tema è bridge-only: usa classi semantiche, non inventare stili."**

Il tema TwentyOne segue i principi di **MaintainableCSS** per il naming delle classi CSS.

---

## 📋 Regole per il Tema

### 1. **Blade Agnostici con Classi Semantiche** ✅

Il tema usa blade agnostici (`[container0]/[slug0]/index.blade.php`) con classi semantiche:

```blade
{{-- ✅ CORRETTO: Classe semantica --}}
<div class="page-hero">
  @livewire(ViewPredictWidget)
</div>

<div class="content-grid">
  @livewire(OutcomesTableWidget)
</div>

{{-- ❌ SBAGLIATO: Utility classes --}}
<div class="bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
  @livewire(ViewPredictWidget)
</div>
```

---

### 2. **Componenti Riutilizzabili dal Modulo** ✅

Il tema NON crea componenti UI. Usa quelli del modulo Predict:

```blade
{{-- ✅ CORRETTO: Componenti dal modulo --}}
<x-predict.hero :title="$title" :subtitle="$subtitle" />
<x-predict.outcomes-grid :outcomes="$outcomes" />
<x-predict.activity-feed :activities="$activities" />

{{-- ❌ SBAGLIATO: Componenti inline nel tema --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
  @foreach($outcomes as $outcome)
    <div class="bg-white rounded-lg shadow p-4">
      {{ $outcome->title }}
    </div>
  @endforeach
</div>
```

---

### 3. **Layout con Classi Semantiche** ✅

```blade
{{-- ✅ CORRETTO: Semantic layout classes --}}
<main class="site-main">
  <section class="hero-section">
    @include('twentyone::partials.hero')
  </section>
  
  <section class="content-section">
    @livewire(OutcomesTableWidget)
  </section>
  
  <aside class="sidebar">
    @include('twentyone::partials.sidebar')
  </aside>
</main>

{{-- ❌ SBAGLIATO: Utility layout --}}
<main class="flex flex-col lg:flex-row gap-4 p-4">
  <section class="flex-1 bg-white rounded-lg shadow">
    ...
  </section>
  <aside class="w-64 bg-gray-100 p-4">
    ...
  </aside>
</main>
```

---

### 4. **Responsive nel CSS, non nell'HTML** ✅

**CSS** (`twentyone/resources/css/theme.css`):
```css
.hero-section {
  padding: 1rem;
}

@media (min-width: 768px) {
  .hero-section {
    padding: 2rem;
  }
}

.content-grid {
  display: grid;
  grid-template-columns: 1fr;
}

@media (min-width: 1024px) {
  .content-grid {
    grid-template-columns: 2fr 1fr;
  }
}
```

**HTML** (blade):
```blade
{{-- ✅ CORRETTO: Solo classi semantiche --}}
<section class="hero-section">
  <h1 class="hero-title">{{ $title }}</h1>
</section>

{{-- ❌ SBAGLIATO: Responsive nell'HTML --}}
<section class="py-4 py-8-md py-12-lg">
  <h1 class="text-xl md:text-2xl lg:text-3xl">
    {{ $title }}
  </h1>
</section>
```

---

## 🏗️ Architecture Pattern

### Theme-First, Module-Agnostic

```
Themes/TwentyOne/
└── resources/views/pages/[container0]/[slug0]/index.blade.php (agnostic)
    └── @livewire(ViewPredictWidget)
        └── Modules/Predict/resources/views/components/
            ├── hero/ (semantic classes)
            ├── outcomes-grid/ (Filament Table Widget)
            ├── activity-feed/ (semantic classes)
            └── price-chart/ (semantic classes)
```

**Regole**:
- ❌ Container blade NON contiene domain logic
- ✅ Container blade usa SOLO `@livewire()`
- ✅ Modulo fornisce componenti riutilizzabili
- ✅ Tema è agnostico (funziona per ANY content type)

---

## 📊 Comparison Table

| Aspect | Semantic CSS (✅ DO) | Utility CSS (❌ DON'T) |
|--------|----------------------|------------------------|
| **Hero Section** | `.hero-section` | `.py-12.px-4.bg-gray-100` |
| **Content Grid** | `.content-grid` | `.grid.grid-cols-1.md:grid-cols-2` |
| **Sidebar** | `.sidebar` | `.w-64.bg-gray-100.p-4` |
| **Responsive** | CSS media queries | `.md:py-8.lg:py-12` |
| **Typography** | `.hero-title` | `.text-2xl.md:text-3xl.font-bold` |

---

## 🔧 Implementation Examples

### Homepage

```blade
{{-- ✅ CORRETTO: Homepage semantica --}}
@extends('twentyone::layouts.app')

@section('content')
  <main class="site-main">
    <section class="hero-section">
      <x-predict.hero 
        :title="$title" 
        :subtitle="$subtitle"
        :cta="$cta"
      />
    </section>
    
    <section class="outcomes-section">
      @livewire(OutcomesTableWidget)
    </section>
    
    <section class="activity-section">
      @livewire(ActivityFeedWidget)
    </section>
  </main>
@endsection

{{-- ❌ SBAGLIATO: Homepage con utility --}}
<div class="min-h-screen.bg-gray-50.py-12.px-4">
  <div class="max-w-7xl.mx-auto">
    <div class="grid grid-cols-1.md:grid-cols-2.lg:grid-cols-3.gap-4">
      ...
    </div>
  </div>
</div>
```

---

### Detail Page

```blade
{{-- ✅ CORRETTO: Detail page semantica --}}
@props(['predict'])

<article class="predict-detail">
  <header class="predict-header">
    <h1 class="predict-title">{{ $predict->title }}</h1>
    <p class="predict-subtitle">{{ $predict->subtitle }}</p>
  </header>
  
  <section class="predict-outcomes">
    @livewire(OutcomesTableWidget, ['predict' => $predict])
  </section>
  
  <section class="predict-activity">
    @livewire(ActivityFeedWidget, ['predict' => $predict])
  </section>
</article>

{{-- ❌ SBAGLIATO: Detail con utility --}}
<div class="bg-white.rounded-lg.shadow.p-6">
  <h1 class="text-3xl.font-bold.mb-4">{{ $predict->title }}</h1>
  <div class="grid.grid-cols-3.gap-4.mt-6">
    ...
  </div>
</div>
```

---

## 🚨 Violations & Fixes

### Violation 1: Utility Classes in Layout

```blade
{{-- ❌ VIOLATION --}}
<div class="flex.flex-col.lg:flex-row.gap-4.p-4">

{{-- ✅ FIX --}}
<main class="site-layout">
/* CSS: .site-layout { display: flex; flex-direction: column; } */
/* CSS: @media (min-width: 1024px) { .site-layout { flex-direction: row; } } */
```

### Violation 2: Tailwind Spacing in HTML

```blade
{{-- ❌ VIOLATION --}}
<section class="py-4.md:py-8.lg:py-12">

{{-- ✅ FIX --}}
<section class="hero-section">
/* CSS: .hero-section { padding-top: 1rem; padding-bottom: 1rem; } */
/* CSS: @media (min-width: 768px) { .hero-section { padding: 2rem; } } */
```

### Violation 3: Inline Grid System

```blade
{{-- ❌ VIOLATION --}}
<div class="grid.grid-cols-1.md:grid-cols-2.lg:grid-cols-3.gap-4">

{{-- ✅ FIX --}}
<div class="outcomes-grid">
/* CSS: .outcomes-grid { display: grid; grid-template-columns: 1fr; gap: 1rem; } */
/* CSS: @media (min-width: 768px) { .outcomes-grid { grid-template-columns: repeat(2, 1fr); } } */
/* CSS: @media (min-width: 1024px) { .outcomes-grid { grid-template-columns: repeat(3, 1fr); } } */
```

---

## 📚 Related Documents

### Internal
- [THEME_PHILOSOPHY_ZEN.md](./THEME_PHILOSOPHY_ZEN.md) - Ruolo del tema
- [PREDICT_DETAIL_AGNOSTIC_CONTRACT.md](./PREDICT_DETAIL_AGNOSTIC_CONTRACT.md) - Contratto detail
- [NO_PREDICT_SPECIFIC_PAGES_IN_THEME.md](./NO_PREDICT_SPECIFIC_PAGES_IN_THEME.md) - Divieto pagine modulo-specific

### External
- [Semantic CSS Principles](../../../Modules/Predict/docs/SEMANTIC_CSS_PRINCIPLES.md)
- [Semantic CSS Rule](../../../../bashscripts/ai/.agents/rules/frontend/semantic-css-rule.md)
- [MaintainableCSS](https://maintainablecss.com/chapters/semantics/)

---

## ✅ Enforcement Checklist

- [ ] **Audit blade esistenti**: Cerca utility classes (`.py-`, `.px-`, `.grid-`, `.flex-`)
- [ ] **Refactor**: Sostituisci con classi semantiche
- [ ] **CSS**: Sposta responsività da HTML a CSS
- [ ] **Documentation**: Aggiungi esempi ai docs
- [ ] **Code Review**: Verifica classi semantiche in ogni PR

---

**Maintained By**: AI Agents Team  
**Last Review**: 2026-03-26  
**Next Review**: 2026-04-26  
**Enforcement**: MANDATORY
