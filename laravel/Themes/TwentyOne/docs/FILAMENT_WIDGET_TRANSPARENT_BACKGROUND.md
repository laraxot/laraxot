# Filament Widgets - Front-Office Styling

**Tema**: TwentyOne  
**Data**: 2026-03-23  
**Stato**: ✅ IMPLEMENTATO  

---

## Architettura CSS

Il tema TwentyOne controlla lo styling di tutti i widget Filament usati nel front-office.

## Transparent Background Policy

### Principio Fondamentale

> "Il widget è un contenitore trasparente, non un elemento visivo."

### Implementazione CSS

File: `resources/css/app.css`

```css
/* ============================================
   Filament Tables - Transparent Background
   ============================================ */

/* Widget container - SEMPRE trasparente */
.fi-ta-content {
    background-color: transparent !important;
    border: none !important;
}

/* Table wrapper - trasparente */
.fi-ta-table {
    background-color: transparent !important;
}

/* Toolbar (search, filters) - trasparente */
.fi-ta-toolbar {
    background-color: transparent !important;
}

/* Pagination - trasparente */
.fi-ta-pagination {
    background-color: transparent !important;
    border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
}

/* Filters dropdown - dark theme */
.fi-ta-filter-dropdown {
    background-color: rgba(15, 23, 42, 0.95) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    color: #f8fafc !important;
}

/* Search input - dark theme */
.fi-ta-search-input input {
    background-color: rgba(255, 255, 255, 0.05) !important;
    border-color: rgba(255, 255, 255, 0.1) !important;
    color: #f8fafc !important;
}

.fi-ta-search-input input::placeholder {
    color: #64748b !important;
}

.fi-ta-search-input input:focus {
    border-color: rgba(16, 185, 129, 0.5) !important;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
}
```

## Dark Theme Integration

### Background

```css
body {
    background-color: #020617; /* slate-950 */
}
```

### Cards

Le predict cards mantengono il loro background proprio:

```css
.predict-card-kinetic {
    background-color: rgba(2, 6, 23, 0.6);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}
```

### Widget Container

```css
.fi-ta-content {
    background-color: transparent; /* ← Chiave! */
}
```

## Kinetic Design

### Animazioni

```css
/* Card entrance */
.predict-card-entrance {
    animation: predict-card-entrance 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* Probability bar fill */
.predict-bar-fill {
    animation: predict-bar-fill 1.2s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}

/* Button hover */
.predict-btn-kinetic:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(56, 189, 248, 0.25);
}
```

### Reduced Motion

```css
@media (prefers-reduced-motion: reduce) {
    .predict-card-kinetic,
    .predict-bar-kinetic,
    .predict-btn-kinetic {
        animation: none !important;
        transition: none !important;
    }
}
```

## File Structure

```
Themes/TwentyOne/
├── resources/
│   ├── css/
│   │   └── app.css              ← Filament widget styling
│   └── views/
│       └── filament/
│           └── widgets/
│               ├── predict-table.blade.php      ← Wrapper
│               └── predict-table/
│                   ├── item.blade.php           ← Card standard
│                   └── homepage-item.blade.php  ← Card homepage
```

## Quality Gate

### Pre-Commit Checklist

- [ ] ✅ Sfondo trasparente verificato (desktop + mobile)
- [ ] ✅ Contrasto WCAG AA (Lighthouse > 90)
- [ ] ✅ prefers-reduced-motion rispettato
- [ ] ✅ Animazioni funzionanti (GSAP + CSS)
- [ ] ✅ NO errori console JavaScript
- [ ] ✅ Livewire reactivity funzionante

### Testing

```bash
# 1. Lighthouse
chrome://inspect → Lighthouse → Accessibility

# 2. Visual regression test
# Confronta prima/dopo su:
# - Homepage (/it)
# - Predict list (/it/predicts)
# - Category pages

# 3. Mobile test
# iOS Safari, Chrome Android
```

## Riferimenti

- `docs/project/FILAMENT_WIDGET_TRANSPARENT_BACKGROUND.md`
- `docs/project/KINETIC_DESIGN_IMPLEMENTATION.md`
- `docs/project/CSS_ARCHITHECTURE_RULE.md`
- `resources/css/app.css` (implementazione)
- `Modules/Predict/docs/FILAMENT_TABLE_WIDGET_V5_GUIDE.md`
- `Modules/Xot/docs/FILAMENT_WIDGETS_V5_GUIDE.md`

---

## 📚 Table Widget v5.x Riferimenti Ufficiali

### Filament 5.x Widgets Overview
https://filamentphp.com/docs/5.x/widgets/overview

### Creazione Table Widget
```bash
php artisan make:filament-widget LatestOrders --table
```

### contentGrid() - Table as Grid
```php
->contentGrid([
    'md' => 2,
    'lg' => 3,
    'xl' => 4,
])
```

### InteractsWithPageFilters
```php
use Filament\Widgets\Concerns\InteractsWithPageFilters;

$startDate = $this->pageFilters['startDate'] ?? null;
```

---

**Maintained By**: Theme Team  
**Last Review**: 2026-03-23  
**Status**: ✅ OPERATIONAL
