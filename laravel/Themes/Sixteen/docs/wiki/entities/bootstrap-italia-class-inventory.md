---
title: "Bootstrap Italia — inventario classi per le 7 pagine segnalazione"
type: entity
sources:
  - "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html"
  - "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html"
  - "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-03-riepilogo.html"
  - "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-04-conferma.html"
  - "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-area-personale.html"
  - "https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html"
  - "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-dettaglio.html"
confidence: high
created: 2026-05-04
updated: 2026-05-04
tags: [bootstrap-italia, tailwind, class-map, segnalazione, html-parity]
related:
  - "../comparisons/segnalazione-01-privacy-design-comuni-vs-local-wizard.md"
  - "../concepts/segnalazione-html-samples-class-token-extraction.md"
  - "../concepts/segnalazione-local-html-class-token-table.md"
  - "../../../../../../_bmad-output/implementation-artifacts/7-105-bootstrap-italia-tailwind-class-map-sette-pagine-segnalazione.md"
---

# Bootstrap Italia — Inventario classi (7 pagine segnalazione)

## Statistiche

| Metrica | Valore |
|---------|--------|
| Pagine analizzate | 7 |
| Classi uniche estratte | 486 |
| Già implementate in tema | 387 |
| **Mancanti da implementare** | **99** |
| File di implementazione | `laravel/Themes/Sixteen/resources/css/components/bootstrap-italia-classes.css` |

### Parallelo: estrazione token `class=` su HTML locali

| Pipeline | Valore |
|----------|--------|
| Inventario entity (URL / build, categorie) | **486** classi uniche |
| Script `bashscripts/extract-segnalazione-class-tokens.php` su `.planning/research/local-*.html` (dopo filtro plausibile) | **529** token — tabella rigenerabile in [segnalazione-local-html-class-token-table](../concepts/segnalazione-local-html-class-token-table.md) |

I due numeri non sono duplicati: l’entity raggruppa per significato implementativo; lo script elenca ogni token whitespace-separated in `class="..."`. Dettaglio metodo: [segnalazione-html-samples-class-token-extraction](../concepts/segnalazione-html-samples-class-token-extraction.md).

## Principio chiave

> **HTML parity** = stessi nomi di classe nell'HTML, implementazione via Tailwind `@apply`.  
> Non cambiare i nomi delle classi nel markup — cambia solo il CSS che le definisce.

---

## Stato per categoria

| Categoria | Totale | Implementate | Mancanti |
|-----------|--------|-------------|---------|
| Grid | 17 | 17 | 0 |
| Display/Flex | 20 | 19 | 1 |
| Buttons | 14 | 11 | 3 |
| Typography | 47 | 31 | 16 |
| Spacing (m/p) | 101 | 78 | 23 |
| Colors/BG | 29 | 27 | 2 |
| Header/Nav | 33 | 27 | 6 |
| Cards | 15 | 13 | 2 |
| CMP components | 39 | 28 | 11 |
| Modal | 18 | 9 | 9 |
| Steppers | 14 | 12 | 2 |
| Breadcrumb | 4 | 4 | 0 |
| Icons | 15 | 9 | 6 |
| Footer | 7 | 7 | 0 |
| Accordion | 9 | 8 | 1 |
| Carousel | 11 | 7 | 4 |
| Misc | 123 | 98 | 25 |

---

## Classi mancanti (99) con mapping Tailwind

### Spacing (23)

| Classe BI | Tailwind @apply |
|-----------|----------------|
| `.mb-20` | `mb-5` |
| `.mb-50` | `mb-12` |
| `.mb-60` | `mb-16` |
| `.mb-lg-20` | `lg:mb-5` |
| `.mb-lg-40` | `lg:mb-10` |
| `.mb-lg-5` | `lg:mb-1.5` |
| `.mb-lg-60` | `lg:mb-16` |
| `.mb-lg-90` | `lg:mb-24` |
| `.mb-md-5` | `md:mb-1.5` |
| `.ml-30` | `ml-8` |
| `.mr-lg-10` | `lg:mr-2.5` |
| `.mt-lg-4` | `lg:mt-4` |
| `.mt-lg-50` | `lg:mt-12` |
| `.mt-md-40` | `md:mt-10` |
| `.mx-0` | `mx-0` |
| `.my-5` | `my-5` |
| `.p-md-3` | `md:p-3` |
| `.pb-40` | `pb-10` |
| `.pb-70` | `pb-[4.375rem]` |
| `.pr-30` | `pr-8` |
| `.ps-lg-3` | `lg:ps-3` |
| `.pt-20` | `pt-5` |
| `.px-sm-3` | `sm:px-3` |

### Tipografia (16)

| Classe BI | Tailwind @apply |
|-----------|----------------|
| `.big-title` | `text-4xl font-bold leading-tight` |
| `.title-large-semi-bold` | `text-3xl font-semibold` |
| `.title-mini` | `text-xs font-semibold uppercase tracking-wide` |
| `.title-snall-semi-bold` | `text-sm font-semibold` *(typo nel sorgente BI)* |
| `.title-xsmall-regular` | `text-xs font-normal` |
| `.subtitle-large` | `text-xl font-normal leading-relaxed` |
| `.header-font` | `font-sans` *(Titillium Web)* |
| `.titillium` | `font-sans` |
| `.date-regular` | `text-sm text-gray-600` |
| `.date-xsmall` | `text-xs text-gray-500` |
| `.text-tab` | `text-sm font-medium` |
| `.underline` | `underline` |
| `.small` | `text-sm` |
| `.divider` | `border-t border-gray-200 my-4` |
| `.warning` | `text-yellow-700 bg-yellow-50 border border-yellow-200 rounded p-3` |
| `.hr-shadow-sm` | `border-t border-gray-200 shadow-sm` |

### Display (1)

| Classe BI | Tailwind @apply |
|-----------|----------------|
| `.d-sm-none` | `sm:hidden` |

### Buttons (3)

| Classe BI | Tailwind @apply |
|-----------|----------------|
| `.btn-back` | `flex items-center gap-1 text-[#007a52] hover:underline` |
| `.btn-me` | `btn-comuni-primary text-sm px-3 py-1` |
| `.btn-xs` | `text-xs px-2 py-0.5` |

### Header/Nav (6)

| Classe BI | Tailwind @apply |
|-----------|----------------|
| `.it-navscroll-progressbar` | `h-1 bg-[#007a52] transition-all duration-300` |
| `.it-user-wrapper` | `flex items-center gap-2` |
| `.it-carousel-all` | `relative w-full` |
| `.logo-hamburger` | `flex items-center gap-3` |
| `.logo-wrapper` | `flex items-center` |
| `.nav-tabs-icon-text` | `flex items-center gap-2` |

### Cards (2)

| Classe BI | Tailwind @apply |
|-----------|----------------|
| `.single-card` | `flex flex-col gap-2` |
| `.cmp-card-title` | `text-base font-semibold leading-snug` |

### CMP components (11)

| Classe BI | Tailwind @apply |
|-----------|----------------|
| `.cmp-carousel` | `w-full overflow-hidden` |
| `.cmp-carousel__title` | `text-xl font-bold mb-4` |
| `.cmp-filter__title` | `text-sm font-semibold uppercase tracking-wider text-gray-700` |
| `.cmp-icon-card__description` | `text-sm text-gray-600 mt-1` |
| `.cmp-input__label` | `text-sm font-medium text-gray-700 mb-1` |
| `.cmp-steps-rating` | `flex flex-col gap-4` |
| `.cmp-steps-rating__body` | `space-y-2` |
| `.cmp-modal__header` | `flex items-start justify-between p-4 border-b` |
| `.cmp-modal__header-info` | `flex-1` |
| `.cmp-modal__header-link` | `text-sm text-[#007a52] underline` |
| `.cmp-modal__header-title` | `text-lg font-semibold` |

### Modal (9)

| Classe BI | Tailwind @apply |
|-----------|----------------|
| `.modal-dialog-centered` | `flex items-center justify-center min-h-screen` |
| `.modal-dimensions` | `max-w-2xl w-full` |
| `.modal-lg` | `max-w-4xl w-full` |
| `.it-dialog-scrollable` | `overflow-y-auto max-h-[90vh]` |
| `.it-example-modal` | *(empty — contenuto CMS)* |
| `.navbar-custom` | *(alias navbar)* |
| `.overlay` | `fixed inset-0 bg-black/50 z-40` |
| `.collapsed` | *(state class — Alpine gestisce)* |
| `.show` | *(state class — Alpine x-show gestisce)* |

### Icons (6)

| Classe BI | Tailwind @apply |
|-----------|----------------|
| `.icon-color` | `text-[#007a52]` |
| `.icon-expand` | `transition-transform duration-200` |
| `.icon-folder` | `w-6 h-6` |
| `.icon-required` | `text-red-600 w-4 h-4` |
| `.icon-success` | `text-[#007a52] w-5 h-5` |
| `.icon-wrapper` | `flex items-center justify-center` |

### Accordion (1)

| Classe BI | Tailwind @apply |
|-----------|----------------|
| `.accordion-date` | `text-xs text-gray-500 mt-1` |

### Carousel (4)

| Classe BI | Tailwind @apply |
|-----------|----------------|
| `.carousel-4-card` | `grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4` |
| `.it-carousel-all` | `relative w-full` |
| `.categoryicon-top` | `flex flex-col items-center text-center gap-2` |
| `.size-xl` | `w-16 h-16` |

### Misc (25)

| Classe BI | Tailwind @apply |
|-----------|----------------|
| `.confirmed` | `text-[#007a52] font-semibold` |
| `.show-more` | `cursor-pointer text-[#007a52] underline text-sm` |
| `.saveBtn` | `btn-comuni-primary` |
| `.send` | `btn-comuni-primary` |
| `.filter-section` | `py-6 border-b border-gray-200` |
| `.filter-wrapper` | `flex flex-wrap gap-3 items-center` |
| `.select-partials` | `text-sm` |
| `.has-bkg-grey` | `bg-gray-100` |
| `.has-border` | `border border-gray-200 rounded` |
| `.link-wrapper` | `flex flex-wrap gap-2` |
| `.left` | `text-left` |
| `.left-icon` | `flex items-center gap-2` |
| `.right` | `text-right` |
| `.dropdown__title` | `text-sm font-semibold text-gray-700 mb-1` |
| `.callout-highlight` | `border-l-4 border-[#007a52] pl-4 py-2 bg-[#007a52]/5` |
| `.social` | `flex gap-3 items-center` |
| `.pin` | `text-xs bg-[#007a52] text-white rounded-full px-2 py-0.5` |
| `.center` | `text-center` |
| `.header-font` | `font-sans` |
| `.navbar-custom` | *(alias navbar)* |

---

## Classi già implementate (387)

Le classi già presenti in `bootstrap-italia-classes.css` includono tutte le classi di griglia (`container`, `row`, `col-*`), le classi header (`it-header-*`), le classi footer, breadcrumb, stepper, card, e la maggior parte delle utility di spacing.

Vedere `laravel/Themes/Sixteen/resources/css/components/bootstrap-italia-classes.css` per l'implementazione completa.

---

## Come aggiungere una nuova classe

1. Aprire `laravel/Themes/Sixteen/resources/css/components/bootstrap-italia-classes.css`
2. Trovare la sezione categorica corretta
3. Aggiungere `.nome-classe { @apply ...; }`
4. `cd laravel/Themes/Sixteen && npm run build && npm run copy`
5. Aggiornare questo file (stato: mancante → implementata)

---

## Backlink story

- Story implementazione: [`7-105`](../../../../../../_bmad-output/implementation-artifacts/7-105-bootstrap-italia-tailwind-class-map-sette-pagine-segnalazione.md)
- Log: [`../log.md`](../log.md)
