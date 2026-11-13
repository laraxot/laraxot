---
title: "DESIGN-COMUNI-CANONICAL"
type: reference
tags: [design-comuni, tailwind, bootstrap-italia-migration, canonical]
created: 2026-10-11
updated: 2026-10-11
qmd: "design comuni canonical"
status: draft
---

# Design Comuni v2.4.0 — Canonical Reference (TailwindCSS)

> SSoT per la replica Design Comuni nel tema Sixteen usando TailwindCSS + Alpine.js.
> Niente Bootstrap Italia runtime — solo markup HTML semantico + utility Tailwind.

**Reference visivo**: https://italia.github.io/design-comuni-pagine-statiche/index.html
**Tema**: `laravel/Themes/Sixteen/`
**Stato**: Front-office pages conversion in progress. Vedi [Front Office Redesign Status](../bmad/front-office-redesign-tech-spec.md).

---

## 1. Architettura CSS

| Layer | Path | Scopo |
|-------|------|-------|
| Base | `resources/css/app.css` | Entry-point Vite, importa tutti i layer |
| Tokens | `resources/css/components/civic-design-tokens.css` | Colori PA, tipografia, spaziature |
| Componenti | `resources/css/components/civic-design-components.css` | `.fc-button`, `.fc-how-card`, `.fc-track-section`, etc. |
| Utilities | `resources/css/app/*.css` | Override specifici per pagina/componente |
| Motion | `resources/css/components/motion-reveal.css` | Scroll-reveal animations |

### Font
- **Titillium Web** (solo font, caricato via Google Fonts in `app.css`)

### Colori PA
| Nome | Hex | Tailwind |
|------|-----|----------|
| Verde PA | `#007A52` | `text-emerald-700` / `bg-emerald-700` |
| Verde chiaro | `#1AB374` | `text-emerald-500` |
| Testo primario | `#1A1A1A` | `text-slate-900` |
| Testo secondario | `#666666` | `text-slate-600` |

---

## 2. Mappatura Bootstrap Italia → TailwindCSS

| Bootstrap Italia | TailwindCSS equivalente |
|-----------------|------------------------|
| `.container` | `.mx-auto.max-w-7xl.px-4.sm:px-6.lg:px-8` |
| `.row` | `.grid.grid-cols-1.gap-4.sm:grid-cols-2` |
| `.col-12` | `.sm:col-span-1` |
| `.col-lg-6` | `.lg:col-span-6` |
| `.col-lg-10` | `.lg:max-w-5xl.mx-auto` |
| `.col-lg-3` | `.lg:col-span-3` |
| `.col-md-3` | `.md:col-span-3` |
| `.mb-0` | `.mb-0` (mantiene) |
| `.mb-3`, `.mb-4`, `.mb-5` | `.mb-3`, `.mb-4`, `.mb-5` (mantiene) |
| `.d-flex` | `.flex` |
| `.d-inline-flex` | `.inline-flex` |
| `.flex-column` | `.flex.flex-col` |
| `.flex-sm-row` | `.sm:flex-row` |
| `.align-items-center` | `.items-center` |
| `.justify-content-center` | `.justify-center` |
| `.justify-content-sm-end` | `.sm:justify-end` |
| `.text-decoration-none` | `.text-decoration-none` (mantiene) |
| `.visually-hidden` | `.visually-hidden` (mantiene) |
| `.text-success` | `.text-emerald-700` |
| `.text-secondary` | `.text-slate-600` |
| `.text-primary` | `.text-emerald-700` |
| `.title-xxlarge` | `.text-3xl.font-bold.md:text-4xl` |
| `.title-xlarge` | `.text-2xl` |
| `.title-medium-2-semi-bold` | `.text-xl.font-semibold` |
| `.title-xsmall-semi-bold` | `.text-xs.font-semibold` |
| `.fw-semibold` | `.font-semibold` |
| `.fw-normal` | `.font-normal` |
| `.lora` | `.font-serif` |
| `.chip.chip-simple` | `.chip.chip-simple` (mantiene CSS) |
| `.chip-green` | `.chip-green` (mantiene CSS) |
| `.btn.btn-outline-primary` | `.btn.btn-outline-primary` (mantiene CSS) |
| `.card.shadow` | `.overflow-hidden.rounded-lg.bg-white.shadow` |
| `.card-body` | `.p-6` |
| `.card-title` | `.text-lg.font-semibold` |
| `.card-text` | `.text-sm.text-slate-600` |
| `.read-more` | `.read-more` (mantiene CSS) |
| `.read-more-green` | `.read-more-green` (mantiene CSS) |
| `.category-top` | `.mb-3.flex.items-center.gap-2` |
| `.it-header-wrapper` | `.it-header-wrapper` (mantiene per header) |
| `.it-footer` | `.it-footer` (mantiene per footer) |
| `.it-brand-wrapper` | `.it-brand-wrapper` (mantiene) |
| `.it-brand-text` | `.it-brand-text` (mantiene) |

---

## 3. Icon System

**Principio**: Nessun riferimento a sprite SVG esterni. Tutte le icone sono inline SVG tramite il componente Blade:

```blade
@include('pub_theme::components.ui.icon', ['name' => 'it-search', 'class' => 'h-5 w-5'])
```

**Componente**: `resources/views/components/ui/icon.blade.php`

**Icone supportate**:

| Icon name | Descrizione |
|-----------|-------------|
| `it-search` | Search / magnifying glass |
| `it-plus`, `it-add` | Plus / add |
| `it-arrow-right` | Arrow pointing right |
| `it-exclamation-triangle` | Warning / alert |
| `it-check-circle`, `it-check` | Success / checkmark |
| `it-map-pin`, `it-pin` | Location / pin |
| `it-calendar` | Calendar |
| `it-pa` | Pubblica amministrazione |
| `it-phone` | Phone |
| `it-mail` | Email |
| `it-twitter` | Twitter |
| `it-facebook` | Facebook |
| `it-youtube` | YouTube |
| `it-telegram` | Telegram |
| `it-whatsapp` | WhatsApp |
| `it-rss` | RSS |
| `it-expand` | Expand / chevron down |
| `it-chevron-right` | Chevron right |
| `it-close`, `it-close-big` | Close / X |
| `it-burger` | Hamburger menu |
| `it-user` | User profile |
| `it-files` | Files / documents |
| `it-settings` | Settings / gear |
| `it-funnel` | Filter funnel |
| `it-services` | Services |
| `it-folder` | Folder |

### Migration dalle sprite SVG

Prima:
```html
<svg class="icon icon-sm" aria-hidden="true">
    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-search"></use>
</svg>
```

Dopo:
```blade
@include('pub_theme::components.ui.icon', ['name' => 'it-search', 'class' => 'h-5 w-5 text-emerald-600'])
```

---

## 4. Motion System (Alpine.js)

**Principio**: Animazioni di scroll-reveal con `IntersectionObserver`, rispetto di `prefers-reduced-motion`.

### Utility JS
File: `resources/js/components/motion-reveal.js`

```js
import motionReveal from './components/motion-reveal.js';

// In app.js, registrato su Alpine:
AlpineInstance.data('howItWorks', () => ({
    ...motionReveal(),
    init() {
        this.setupReveal('[data-section="how-it-works"] [data-reveal]');
        this.setupCardHover('.fc-how-card');
    }
}));
```

### Metodi
- `setupReveal(selector)` — applica fade-up a tutti gli elementi con `data-reveal="fade-up"`
- `setupCardHover(selector)` — applica effetto hover elevation su cards

### CSS
File: `resources/css/components/motion-reveal.css`

```css
[data-reveal="fade-up"] {
    opacity: 0;
    transform: translateY(1.25rem);
    transition-property: opacity, transform;
    transition-duration: 0.5s;
    transition-timing-function: cubic-bezier(0.25, 0.1, 0.25, 1);
}

@media (prefers-reduced-motion: reduce) {
    [data-reveal="fade-up"] { opacity: 1; transform: none; }
}

[data-reveal="fade-up"].motion-reveal-visible {
    opacity: 1;
    transform: translateY(0);
}
```

---

## 5. Component Blocks

### How It Works Block
- **File**: `resources/views/components/blocks/how-it-works.blade.php`
- **Data bag**: `$steps` array con `number`, `title`, `body`, `icon`
- **Class prefix**: `fc-how-section`, `fc-how-grid`, `fc-how-card`
- **Alpine controller**: `howItWorks()`

### Quick Actions Block
- **File**: `resources/views/components/blocks/quick-actions.blade.php`
- **Data bag**: `$button`, `$secondary_button` con `label`, `url`, `icon`
- **Class prefix**: `fc-track-section`, `fc-button`
- **Alpine controller**: `quickActions()`

### Hero Block (Home)
- **File**: `resources/views/components/blocks/home/hero.blade.php`
- **Class prefix**: `fc-home-hero`, `fc-eyebrow`, `fc-button`

### Footer
- **File**: `resources/views/components/sections/footer/v1.blade.php`
- **Class**: `it-footer`, `it-footer-main`
- **Conversione**: `.container` → `.mx-auto.max-w-7xl`, sprite SVG → component icon

---

## 6. Stato Pagine Front-Office

| Pagina | Stato | Note |
|--------|-------|------|
| Homepage (design-comuni) | ✅ Convertita | Bootstrap Italia → TailwindCSS + inline icons |
| Header | ✅ Convertita | `sections/header.blade.php` |
| Footer v1 | ✅ Convertita | Sprite SVG → component icon, `.container` → Tailwind |
| Hero block | ✅ Convertita | `.container` → `.mx-auto.max-w-7xl` |
| How-it-works block | ✅ Convertita | Sprite SVG → icon, Alpine.js reveal |
| Quick-actions block | ✅ Convertita | Sprite SVG → icon, Alpine.js reveal |
| Topic highlight | ⏳ Parziale | Sprite SVG refs rimossi, struttura OK |
| Administration | ⏳ Parziale | Sprite SVG refs presenti |
| Services | ⏳ Parziale | Sprite SVG refs presenti |
| News | ⏳ Parziale | Sprite SVG refs presenti |
| Search results | ⏳ Parziale | Sprite SVG refs presenti |
| Accordion | ⏳ Parziale | Sprite SVG refs presenti |

---

## 7. Verifica e Testing

### WCAG 2.1 AA
Script: `bashscripts/tools/visual/wcag-aa-check.mjs`
```bash
node bashscripts/tools/visual/wcag-aa-check.mjs --url=http://127.0.0.1:8000/it/tests/homepage
```

### Smoke test (Puppeteer)
Script: `bashscripts/tools/visual/guest-fo-smoke.mjs`

---

## 8. Guida alla Migrazione

### Passo 1: Identifica le classi Bootstrap Italia
```bash
grep -rn "\.container\b\|\.row\b\|\.col-\|bootstrap-italia/dist/svg" resources/views/
```

### Passo 2: Converte il markup
- `.container` → `.mx-auto.max-w-7xl.px-4.sm:px-6.lg:px-8`
- `.row` → `.grid.grid-cols-1.gap-4.sm:grid-cols-2`
- `<use href="...#it-icon">` → `@include('pub_theme::components.ui.icon', ['name' => 'it-icon'])`

### Passo 3: Registra i controller Alpine
Aggiungi in `resources/js/app.js` dentro `registerAlpineComponents()`:
```js
AlpineInstance.data('myComponent', () => ({
    ...motionReveal(),
    init() {
        this.setupReveal('[data-section="my-section"] [data-reveal]');
    }
}));
```

### Passo 4: Documenta
Aggiorna questa pagina canonica e marca le pagine come convertite nella tabella §6.

---

## 9. Anti-Pattern da Evitare

- ❌ `<svg><use href="...bootstrap-italia/dist/svg/sprites.svg#it-*"></use></svg>` — usa sempre `@include('pub_theme::components.ui.icon')`
- ❌ `<div class="container">` — usa sempre `.mx-auto.max-w-7xl.px-4`
- ❌ `<div class="row"><div class="col-lg-6">` — usa `.grid.grid-cols-1.lg:grid-cols-12`
- ❌ Inline `<script>` con Alpine controller — registra in `app.js` con `AlpineInstance.data()`
- ❌ Testo hardcoded in blade — usa sempre `__('pub_theme::...')` per i18n

---

*Consolidated from 200+ dated docs in `docs/design-comuni/`. Last update: 2026-10-11.*
