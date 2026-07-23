---
type: overview
theme: TwentyOne
sources:
  - ../../../docs/ZEN_ARCHITECTURE_PHILOSOPHY.md
  - ../../../docs/KINETIC_WEB_DESIGN_SPEC.md
  - ../../../docs/HOMEPAGE_ARCHITECTURE.md
  - ../../../docs/LAYOUT_ARCHITECTURE_PHILOSOPHY.md
confidence: high
updated: 2026-04-15
---

# TwentyOne Theme — Overview

> **Ruolo**: Tema frontend cinematografico per prediction market / piattaforme interattive — Agnostic Container, Kinetic Design, GSAP animations.

## Identità del Tema

Il tema TwentyOne è il layer visivo per la piattaforma Predict (market delle previsioni):

- **Filosofia**: "Zen agnostico" — il contenitore non conosce il contenuto
- **Estetica**: Cinematografica, dark mode, animazioni cinetiche (GSAP)
- **Stack**: Tailwind CSS + GSAP + Volt + Laravel Folio
- **Contrasto con Sixteen**: Non è PA, non ha Bootstrap Italia; è orientato engagement e UX moderna

## Zen Architecture (Regola Fondamentale)

> **"Il contenitore è vuoto. Non ha forma. Prende la forma del contenuto."**

Il tema usa un **Agnostic Container** per le pagine dinamiche:

```
resources/views/pages/[container0]/[slug0]/index.blade.php
```

Questo file è **agnostico**: non sa se contiene un predict, un articolo, un profilo. Il modulo fornisce la sua view tramite CMS blocks.

### Cosa NON fare (Anti-pattern)

```blade
❌  predicts/[slug].blade.php     → crea dipendenza dal modulo Predict
❌  @if($container0 === 'predicts') → logica modulo-specifica nel tema
❌  use Modules\Predict\Models\Predict  → il tema non importa moduli
```

### Pattern Corretto

```blade
✅ [container0]/[slug0]/index.blade.php  → sempre agnostico
✅ <x-page slug="home" />              → il CMS fornisce i blocchi
✅ Il modulo fornisce i blocchi in:
   Modules/<Name>/resources/views/components/blocks/
```

## Homepage Architecture

L'homepage segue il pattern **Theme → CMS → Module**:

```
Theme: pages/index.blade.php
  → <x-page side="content" slug="home" />
    → CMS carica content_blocks dal DB (slug "home")
      → Modules/Predict/resources/views/components/blocks/home/
```

```blade
<?php
use function Laravel\Folio\{name};
name('home');
?>
<x-layouts.app>
    @volt('home')
    <div>
        <x-page side="content" slug="home" />
    </div>
    @endvolt
</x-layouts.app>
```

## Kinetic Web Design

Il tema implementa **motion design** come elemento centrale:

### Livelli di Animazione

| Tecnica | Quando usarla | Esempio |
|---------|--------------|---------|
| CSS Transitions | Hover, focus, micro-interazioni | `transition-all duration-200` |
| CSS Keyframes | Entrata elementi, pulse, fadeIn | `@keyframes fadeIn` |
| GSAP Timeline | Animazioni complesse, sequenze | scroll-triggered reveal |
| GSAP ScrollTrigger | Parallax, reveal su scroll | sezioni homepage |

### Principi Cinematici

- Animazioni con **funzione** (non puramente decorative)
- **Performance first**: no jank, no layout shift
- **Brevità**: transizioni < 300ms per feedback UI
- **Consistenza**: stessa easing function in tutto il tema

## Design System

- **Dark mode nativo** (priorità rispetto a light)
- **Tailwind CSS** — no Bootstrap, no Bootstrap Italia
- Semantic CSS guide in `SEMANTIC_CSS_GUIDE.md`
- Icone: policy separata (`icon-rendering-policy.md`) — no SVG hardcoded

## Struttura File Principali

```
resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php          # Layout base con GSAP setup
│   ├── pages/
│   │   ├── index.blade.php        # Homepage (agnostic + CMS)
│   │   ├── [container0]/
│   │   │   └── [slug0]/
│   │   │       └── index.blade.php  # Agnostic container
│   │   └── auth/                  # Login/register
│   └── components/
│       └── blocks/                # Blocchi tema (non modulo-specifici)
├── css/                           # Tailwind + custom CSS
└── js/                            # GSAP + Volt
```

## Regole Operative

1. **Theme agnosticism**: il tema non sa nulla dei moduli — ogni logica modulo-specifica sta nel modulo
2. **CMS-driven content**: i blocchi vengono da `<x-page>` e dal DB, non hardcodati nel Blade
3. **GSAP via CDN o package**: non inline `<script>` nei Blade — usare `@stack('scripts')`
4. **No SVG hardcoded**: usare il sistema icone definito in `icon-rendering-policy.md`
5. **Folio file naming**: seguire le regole in `file-naming-rules.md` — no `old` files in `pages/`

## Cross-References

- [[../../../../../../laravel/Modules/Cms/docs/wiki/overviews/cms-module|Cms Module]] — `<x-page>` component, content_blocks
- [[../../../../../../laravel/Modules/UI/docs/wiki/overviews/ui-module|UI Module]] — componenti base (sovrascritta da Tailwind TwentyOne)
- [[../../../../../../laravel/Modules/Lang/docs/wiki/overviews/lang-module|Lang Module]] — routing localizzato (prefisso `/it/`, `/en/`)
- [[../../../../../../laravel/Themes/Sixteen/docs/wiki/overviews/sixteen-theme|Sixteen Theme]] — tema alternativo PA/AGID

## Raw Sources Prioritari

- `docs/ZEN_ARCHITECTURE_PHILOSOPHY.md` — regola agnostic container, anti-pattern
- `docs/KINETIC_WEB_DESIGN_SPEC.md` — spec animazioni cinetiche, GSAP
- `docs/HOMEPAGE_ARCHITECTURE.md` — architettura theme→CMS→module
- `docs/LAYOUT_ARCHITECTURE_PHILOSOPHY.md` — filosofia layout
- `docs/THEME_ARCHITECTURE_ZEN_BLADE_NAMING.md` — naming convention Blade
- `docs/GSAP_SCROLLTRIGGER_THEME_GUIDE.md` — guida GSAP ScrollTrigger
- `docs/SEMANTIC_CSS_GUIDE.md` — CSS semantico e Tailwind usage
- `docs/file-naming-rules.md` — regole naming file Folio
