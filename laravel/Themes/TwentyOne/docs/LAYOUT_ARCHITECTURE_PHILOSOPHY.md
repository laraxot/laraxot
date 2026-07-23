# Layout Architecture Philosophy

**Last Updated**: 2026-03-20  
**Status**: ✅ IMPLEMENTED  
**Branch**: dev

---

## 🎯 Core Principle

> **"Separazione delle concern, composizione > duplicazione"**

L'architettura del layout segue il principio della **separazione delle concern**:
- **HTML** → Struttura semantica
- **CSS** → Stile e presentazione (in `app.css`)
- **JavaScript** → Comportamento e interazioni (in `app.js`)
- **Blade** → Composizione e riutilizzo

---

## 🏗️ Architecture Layers

```
Themes/TwentyOne/resources/views/components/layouts/
├── main.blade.php      ← Layout base (HTML structure)
│   ├── DOCTYPE, html, head, body
│   ├── Vite assets (CSS + JS)
│   ├── Filament/Livewire styles/scripts
│   └── Cookie consent banner (HTML only)
│
└── app.blade.php       ← Layout applicativo (estende main)
    ├── <x-layouts.main>
    ├── SEO meta tags (Open Graph, Twitter Card)
    ├── Structured data (JSON-LD)
    ├── Analytics (GA4, Matomo)
    ├── Navigation (header, footer)
    └── Mobile tabs
```

---

## 📁 File Responsibilities

### `main.blade.php` - Base Layout

**Scopo**: Fornire la struttura HTML fondamentale.

**Cosa contiene**:
- ✅ DOCTYPE, html, head, body
- ✅ Meta tags base (charset, viewport, CSRF)
- ✅ Vite assets (`@vite(['resources/css/app.css'])`, `@vite(['resources/js/app.js'])`)
- ✅ Filament/Livewire styles/scripts
- ✅ Skip navigation link (WCAG 2.2 AA)
- ✅ Cookie consent banner (HTML structure)
- ✅ `<main id="main-content">` slot

**Cosa NON contiene**:
- ❌ SEO meta tags specifici (Open Graph, Twitter Card)
- ❌ Analytics scripts (GA4, Matomo)
- ❌ Navigation (header, footer)
- ❌ JavaScript inline

**Usage**:
```blade
<x-layouts.main title="Page Title" meta-description="Description">
    <h1>Content</h1>
</x-layouts.main>
```

---

### `app.blade.php` - Application Layout

**Scopo**: Estendere `main.blade.php` aggiungendo funzionalità applicative.

**Cosa contiene**:
- ✅ `<x-layouts.main>` component
- ✅ SEO meta tags (Open Graph, Twitter Card, canonical URL)
- ✅ Structured data (JSON-LD for WebSite)
- ✅ Analytics scripts (Google Analytics 4, Matomo)
- ✅ Header navigation (`<x-section slug="header" />`)
- ✅ Footer navigation (`<x-section slug="footer" />`)
- ✅ Mobile tabs
- ✅ Livewire notifications

**Cosa NON contiene**:
- ❌ HTML structure (DOCTYPE, html, head, body)
- ❌ JavaScript inline (spostato in `app.js`)
- ❌ CSS inline (spostato in `app.css`)

**Usage**:
```blade
<x-layouts.app title="Predict List" meta-description="Browse predictions">
    @livewire(\Modules\Predict\Filament\Widgets\PredictTableWidget::class)
</x-layouts.app>
```

---

## 🔄 Composition Flow

```
User Request
    ↓
Folio Route (pages/[container0]/index.blade.php)
    ↓
<x-layouts.app>
    ↓
<x-layouts.main>
    ↓
HTML Output
```

**Example**:
```blade
{{-- resources/views/pages/predicts/index.blade.php --}}
<x-layouts.app title="Predicts" meta-description="Browse all predictions">
    @livewire(\Modules\Predict\Filament\Widgets\PredictTableWidget::class)
</x-layouts.app>
```

---

## 🚫 What We Avoid (Anti-Patterns)

### ❌ Inline JavaScript

**PRIMA** (SBAGLIATO):
```blade
<!-- app.blade.php -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const banner = document.getElementById('cookie-consent');
        // ... logic
    });
</script>
```

**DOPO** (CORRETTO):
```blade
<!-- main.blade.php -->
@vite(['resources/js/app.js'])

<!-- resources/js/app.js -->
const initCookieConsent = () => {
    // ... logic
};
```

**Perché**:
- ✅ Separazione delle concern
- ✅ Build process (minification, tree-shaking)
- ✅ Caching (browser cache JS file)
- ✅ Testing (puoi testare JS separatamente)
- ✅ Maintainability (codice organizzato)

---

### ❌ CSS Inline o @push('styles')

**PRIMA** (SBAGLIATO):
```blade
@push('styles')
<style>
    .hero { background: linear-gradient(...) }
</style>
@endpush
```

**DOPO** (CORRETTO):
```css
/* resources/css/app.css */
.hero {
    @apply bg-gradient-to-r from-emerald-500 to-cyan-500;
}
```

**Perché**:
- ✅ Separazione delle concern
- ✅ Build process (Tailwind purge, minification)
- ✅ Caching
- ✅ Maintainability

---

### ❌ Duplicazione HTML Structure

**PRIMA** (SBAGLIATO):
```blade
<!-- auth.blade.php -->
<!DOCTYPE html>
<html>
<head>...</head>
<body>...</body>
</html>

<!-- app.blade.php -->
<!DOCTYPE html>
<html>
<head>...</head>
<body>...</body>
</html>
```

**DOPO** (CORRETTO):
```blade
<!-- auth.blade.php -->
<x-layouts.main title="Login">
    <!-- auth content -->
</x-layouts.main>

<!-- app.blade.php -->
<x-layouts.main title="App">
    <!-- app content -->
</x-layouts.main>
```

**Perché**:
- ✅ DRY (Don't Repeat Yourself)
- ✅ Single source of truth
- ✅ Maintainability (cambi una volta sola)

---

## 📊 JavaScript Architecture

### File Structure

```
Themes/TwentyOne/resources/js/
├── app.js              ← Entry point (inizializza tutto)
├── custom.js           ← Custom interactions
├── particles.js        ← Cinematic particles
├── cookie-consent.js   ← Cookie consent logic (optional, inline in app.js)
└── components/         ← Reusable JS components
```

### app.js Pattern

```javascript
// ============================================
// Feature Name
// ============================================

const initFeatureName = () => {
    // 1. Check if feature should run
    if (!condition) {
        return;
    }

    // 2. Feature logic
    // ...
};

// ============================================
// Initialize on DOM Ready
// ============================================

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        initFeatureName();
        // ... other initializers
    }, { once: true });
} else {
    initFeatureName();
    // ... other initializers
}
```

### Features Included

1. **Flowbite** - UI components
2. **Swiper** - Carousel/slider
3. **Kinetic Blocks** - Scroll reveal animations
4. **Counter Animation** - Animated counters
5. **Antigravity Fields** - Mouse tracking effects
6. **GSAP Count-up** - Number animations
7. **Cinematic Particles** - Background particles
8. **Cookie Consent** - GDPR compliance

---

## 🎨 CSS Architecture

### File Structure

```
Themes/TwentyOne/resources/css/
├── app.css             ← Entry point
├── tailwind.css        ← Tailwind directives
├── components/         ← Component styles
│   ├── kinetic.css     ← Kinetic animations
│   ├── particles.css   ← Particle effects
│   └── ...
└── utilities/          ← Utility classes
```

### app.css Pattern

```css
/* Tailwind base */
@import 'tailwindcss';

/* Custom components */
@layer components {
    .btn-kinetic {
        @apply transition-transform duration-300 hover:scale-105;
    }
}

/* Custom utilities */
@layer utilities {
    .text-gradient {
        background-clip: text;
        -webkit-background-clip: text;
    }
}
```

---

## ♿ Accessibility (WCAG 2.2 AA)

### Skip Navigation

Entrambi i layout includono skip navigation:

```blade
<a href="#main-content" class="sr-only focus:not-sr-only ...">
    {{ __('predict::predict.labels.navigation.skip_to_content.label') }}
</a>
```

**Perché**:
- ✅ Screen reader support
- ✅ Keyboard navigation
- ✅ WCAG 2.2 AA compliance

### Main Content

```blade
<main id="main-content" role="main" tabindex="-1">
    {{ $slot }}
</main>
```

**Perché**:
- ✅ `id="main-content"` - Target per skip link
- ✅ `role="main"` - Semantic HTML
- ✅ `tabindex="-1"` - Focus management

---

## 🔒 GDPR Compliance

### Cookie Consent

Il banner cookie è implementato in `main.blade.php`:

```blade
<div id="cookie-consent" class="fixed bottom-0 ... hidden">
    <p>Utilizziamo cookie per migliorare la tua esperienza...</p>
    <button id="accept-cookies">Accetta Tutti</button>
    <button id="decline-cookies">Rifiuta</button>
</div>
```

**JavaScript** (in `app.js`):
- Controlla `localStorage` per scelta precedente
- Mostra banner se nessuna scelta
- Salva scelta utente in `localStorage`
- Aggiorna consenso GA4 (`gtag('consent', 'update', ...)`)

**Perché**:
- ✅ GDPR compliance
- ✅ User choice respected
- ✅ Analytics opt-in/opt-out

---

## 📚 Related Documentation

- `docs/project/CSS_ARCHITECTURE_PHILOSOPHY.md` - CSS separation
- `docs/project/VOLT_CLASS_BASED_COMPONENTS.md` - Volt components
- `docs/project/FILAMENT_WIDGETS_FOR_LISTS_RULE.md` - Filament widgets
- `docs/project/KINETIC_WEB_DESIGN_SPEC.md` - Kinetic animations

---

## 🧠 Philosophy & Zen

> **"Il layout è come una cipolla: strati su strati, ognuno con il proprio scopo."**

### Separation of Concerns

- **HTML** → Struttura
- **CSS** → Presentazione
- **JavaScript** → Comportamento
- **Blade** → Composizione

### Composition > Duplication

- Usa `<x-layouts.main>` come base
- Estendi con `<x-layouts.app>`
- Aggiungi features specifiche nelle pagine

### Build Process

- **CSS** → `npm run build` (Tailwind, minification)
- **JS** → `npm run build` (Vite, tree-shaking)
- **NO inline** → Tutto in file separati

---

## ✅ Pre-Commit Checklist

Prima di commitare modifiche al layout:

- [ ] **NO inline JavaScript** (spostato in `app.js`)
- [ ] **NO inline CSS** (spostato in `app.css` o `@layer`)
- [ ] **Build completato** (`npm run build` senza errori)
- [ ] **Manifest aggiornato** (`public/manifest.json`)
- [ ] **Accessibility test** (skip link, focus, ARIA)
- [ ] **GDPR compliance** (cookie consent funziona)
- [ ] **Docs aggiornate** (questo file)

---

**Maintained By**: AI Agents Team  
**Last Review**: 2026-03-20  
**Next Review**: After major layout changes
