# Layout Architecture - TwentyOne Theme

**Last Updated**: 2026-03-20  
**Status**: ✅ OPERATIONAL - Component-based layout system

---

## 🎯 Philosophy

> "Composizione > Duplicazione"  
> "Separazione delle concern"  
> "DRY (Don't Repeat Yourself)"

### Core Principles

1. **Composizione**: I layout si estendono, non si duplicano
2. **Separazione**: HTML base ≠ SEO ≠ Navigation
3. **DRY**: Una sola volta, ben fatto
4. **Zen**: Semplicità, manutenibilità, chiarezza

---

## 🏗 Architecture

### Component Hierarchy

```
layouts/
├── main.blade.php      ← BASE LAYOUT (HTML structure)
│   ├── <head> (meta base, CSS, JS)
│   ├── <body> (skip link, main)
│   └── @stack('head'), @stack('scripts')
│
└── app.blade.php       ← APP LAYOUT (extends main)
    ├── @push('head') (SEO, analytics)
    ├── <x-section slug="header" />
    ├── {{ $slot }} (content)
    ├── <x-section slug="footer" />
    └── @push('scripts') (cookie consent)
```

### File Responsibilities

| File | Responsibility | DOI | NON FAI |
|------|---------------|-----|---------|
| `main.blade.php` | HTML structure, CSS, JS base | Meta base, Vite, Livewire | SEO, analytics, navigation |
| `app.blade.php` | SEO, analytics, navigation | Meta SEO, GA4, Matomo, header, footer | HTML structure, CSS, JS base |

---

## 📝 Usage

### Basic Page

```blade
{{-- Homepage --}}
<x-layouts.app title="Home" meta-description="Predict homepage">
    <h1>Benvenuto</h1>
</x-layouts.app>
```

### Folio Page (Volt Class-Based)

```blade
<?php
use function Laravel\Folio\name;
use Livewire\Volt\Component;

name('predicts.index');

new class extends Component {
    public function with(): array {
        return [
            'predicts' => Predict::paginate(12),
        ];
    }
};
?>
<x-layouts.app
    title="Mercati delle Previsioni"
    meta-description="Esplora tutti i mercati"
>
    @volt('predicts.index')
    <div>
        <!-- Content -->
    </div>
    @endvolt
</x-layouts.app>
```

---

## 🔧 Build Process

### Development

```bash
cd laravel/Themes/TwentyOne
npm run dev        # Vite dev server with HMR
```

### Production

```bash
cd laravel/Themes/TwentyOne
npm run build      # Build for production
npm run copy       # Copy to public_html/themes/TwentyOne
```

### Why Separate Build?

1. **Theme Independence**: Ogni tema ha il suo build process
2. **Asset Isolation**: CSS/JS del tema non confliggono con l'app
3. **Performance**: Vite ottimizza per tema
4. **Caching**: Browser cache per tema specifico

---

## 📦 Assets Structure

```
Themes/TwentyOne/
├── resources/
│   ├── css/
│   │   └── app.css          # Tailwind v4 + custom CSS
│   ├── js/
│   │   ├── app.js           # Main JS (Kinetic, GSAP, etc.)
│   │   ├── particles.js     # Cinematic particles
│   │   ├── custom.js        # Custom scripts
│   │   └── cookie-consent.js # GDPR cookie
│   └── views/
│       └── components/
│           └── layouts/
│               ├── main.blade.php
│               └── app.blade.php
│
├── public/                  # Built assets (Vite output)
│   ├── assets/
│   │   ├── app-*.css
│   │   └── app-*.js
│   └── manifest.json
│
└── package.json             # Dependencies + scripts
```

---

## 🎨 CSS Architecture

### Tailwind v4

```css
/* resources/css/app.css */
@import "tailwindcss";

@source "../../Modules/**/resources/views/**/*.blade.php";
@source "./resources/views/**/*.blade.php";

/* Custom CSS */
@layer components {
    .card-kinetic { /* ... */ }
    .btn-kinetic { /* ... */ }
    .probability-bar-animated { /* ... */ }
}
```

### Why CSS in JS File?

**NO!** CSS è in `resources/css/app.css`, JS in `resources/js/app.js`.

Philosophy:
- ✅ CSS separato da HTML (come la logica dalla presentazione)
- ✅ JS separato da HTML (come il comportamento dalla struttura)
- ✅ Vite compila tutto insieme (performance)

---

## 🚀 JavaScript Features

### Kinetic Web Design

```js
// resources/js/app.js

// 1. Reveal blocks on scroll
revealKineticBlocks();

// 2. Counter animation (for stats)
initCounterAnimation();

// 3. Antigravity fields (mouse tracking)
initAntigravityFields();

// 4. Cinematic particles
initCinematicParticles();

// 5. GSAP count-up (for hero stats)
initGSAPCountUp();
```

### Accessibility

- ✅ Skip to content link (WCAG 2.2 AA)
- ✅ Reduced motion support (`prefers-reduced-motion`)
- ✅ Keyboard navigation
- ✅ Focus indicators

---

## 📊 Performance

### Metrics (Target)

| Metric | Target | Actual |
|--------|--------|--------|
| LCP (Largest Contentful Paint) | < 2.5s | ✅ |
| INP (Interaction to Next Paint) | < 200ms | ✅ |
| CLS (Cumulative Layout Shift) | < 0.1 | ✅ |
| FCP (First Contentful Paint) | < 1.5s | ✅ |

### Optimization

1. **Vite Build**: Minification, tree-shaking, code-splitting
2. **Lazy Loading**: GSAP, Swiper caricati solo se necessari
3. **Reduced Motion**: Rispetta preferenze utente
4. **Intersection Observer**: Animazioni solo in viewport

---

## 🧪 Testing

### Manual Testing

```bash
# 1. Build
npm run build

# 2. Copy
npm run copy

# 3. Test in browser
curl http://predict.local/it
```

### Checklist

- [ ] CSS caricato correttamente
- [ ] JS eseguito senza errori
- [ ] Animazioni funzionano
- [ ] Skip link visibile on focus
- [ ] Cookie banner GDPR
- [ ] Mobile responsive

---

## 📚 Documentation

### Related Docs

- `docs/HOMEPAGE_LAYOUT_ARCHITECTURE.md` - This file
- `docs/KINETIC_WEB_DESIGN_SPEC.md` - Kinetic design specs
- `docs/CSS_ARCHITECTURE_PHILOSOPHY.md` - CSS separation
- `docs/JS_ARCHITECTURE_PHILOSOPHY.md` - JS separation

### Module Docs

- `Modules/Predict/docs/BLADE_MINIMAL_LOGIC_BEST_PRACTICES.md`
- `Modules/Predict/docs/VOLT_CLASS_BASED_COMPONENTS.md`

---

## 🎯 Pre-Commit Checklist

Before committing layout changes:

- [ ] **Build**: `npm run build`
- [ ] **Copy**: `npm run copy`
- [ ] **Test**: Homepage, Predicts, Detail pages
- [ ] **Validate**: HTML valid, no console errors
- [ ] **Docs**: Update this file if architecture changes
- [ ] **Git**: Commit built assets (`public/assets/*`)

---

## 🧠 Learnings

### 2026-03-20: Layout Refactoring

**Problem**: JS inline in `app.blade.php`, no component base

**Solution**:
1. Created `main.blade.php` (base HTML structure)
2. `app.blade.php` extends `main.blade.php`
3. Moved inline JS to `resources/js/app.js`
4. Build with Vite (`npm run build`)

**Benefits**:
- ✅ DRY: No duplicate HTML structure
- ✅ Separation: HTML ≠ SEO ≠ JS
- ✅ Build process: Vite optimization
- ✅ Maintainability: Clear file responsibilities

**Philosophy**:
> "La logica JS va in file .js, non in blade.  
> Il CSS va in file .css, non in blade.  
> Blade è per la presentazione, non per la logica."

---

## 📞 Support

For questions or issues:
1. Check this documentation
2. Check related docs in `docs/`
3. Check module docs in `Modules/*/docs/`
4. Open GitHub Issue

---

**Maintained By**: AI Agents Team  
**Last Review**: 2026-03-20  
**Next Review**: After major layout changes
