# Single Design System - Theme Policy

**Tema**: TwentyOne  
**Data**: 2026-03-23  
**Stato**: ✅ ENFORCED  

---

## Principio Fondamentale

> **"Il tema TwentyOne controlla TUTTO il design system. Tutte le pagine DEVONO usare lo stesso layout, CSS, JS."**

## Architettura

### Layout Unico

File: `resources/views/components/layouts/app.blade.php`

```blade
{{-- TUTTE le pagine usano questo layout --}}
<x-layouts.app title="Page Title">
    <!-- Content -->
</x-layouts.app>
```

**Niente**:
```blade
<x-layouts.home>...</x-layouts.home>
<x-layouts.predicts>...</x-layouts.predicts>
<x-layouts.articles>...</x-layouts.articles>
```

### CSS Unico

File: `resources/css/app.css`

```css
/* TUTTE le pagine usano questo CSS */
.bg-slate-950 { background-color: #020617; }
.card-kinetic { /* ... */ }
.btn-kinetic { /* ... */ }
```

**Niente**:
```css
/* pages/home.css */
/* pages/predicts.css */
/* pages/articles.css */
```

### JS Unico

File: `resources/js/app.js`

```javascript
// TUTTE le pagine usano questo JS
import './gsap-config.js';
import './scroll-trigger-config.js';
import './dark-mode.js';
```

**Niente**:
```javascript
// pages/home.js
// pages/predicts.js
// pages/articles.js
```

## Background Policy

### Dark Theme di Default

```blade
{{-- ✅ SEMPRE: Wrapper Div Nudo --}}
<div>
    <x-page ... />
</div>

{{-- ❌ MAI: Hardcoded Classes --}}
<div class="min-h-screen bg-slate-950 max-w-7xl">
    <x-page ... />
</div>
```

### Perché il Wrapper Nudo?

1. **Ereditarietà**: `x-layouts.app` già fornisce `bg-slate-950`.
2. **Delega CMS**: Lo styling di container e spacing è delegato ai CMS blocks.
3. **Cinematic Ready**: Permette sezioni full-width senza vincoli del wrapper di pagina.

## Pagine Template

### 1. Homepage

File: `resources/views/pages/index.blade.php`

```blade
<x-layouts.app title="Homepage">
    <div>
        <x-page side="content" slug="home" />
    </div>
</x-layouts.app>
```

### 2. Generic List (Predicts, Articles, etc.)

File: `resources/views/pages/[container0]/index.blade.php`

```blade
<x-layouts.app title="List">
    <div>
        <x-page side="content" :slug="$this->pageSlug" />
    </div>
</x-layouts.app>
```

### 3. Auth Pages

File: `resources/views/pages/auth/login.blade.php`

```blade
<x-layouts.app title="Login">
    <div class="min-h-screen bg-slate-950 flex items-center justify-center">
        <!-- Login form -->
    </div>
</x-layouts.app>
```

## Quality Gate

### Pre-Commit Checklist

Prima di commitare una nuova pagina:

- [ ] ✅ Usa `<x-layouts.app>` (NO layout custom)
- [ ] ✅ Background: `bg-slate-950` (NO `bg-white`, NO `bg-slate-50`)
- [ ] ✅ CSS: `resources/css/app.css` (NO file CSS separati)
- [ ] ✅ JS: `resources/js/app.js` (NO file JS separati)
- [ ] ✅ Container: `max-w-7xl px-4` (NO container diversi)

### Visual Test

```bash
# 1. Homepage
curl http://predict.local/it | grep -o 'bg-slate-950'

# 2. Predicts
curl http://predict.local/it/predicts | grep -o 'bg-slate-950'

# 3. Devono essere IDENTICI
```

---

**Status**: ✅ ENFORCED  
**Last Review**: 2026-03-23
