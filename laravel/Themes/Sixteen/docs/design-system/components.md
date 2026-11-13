---
id: ds-sixteen-components
type: design-system
title: "Component Catalog — Tema Sixteen"
status: active
updated: 2026-10-11
tags: [docs, sixteen, design-system]
qmd: "catalogo componenti   tema sixteen sixteen"
---

# Catalogo Componenti — Tema Sixteen

## Lit Web Components (da creare in `resources/js/components/`)

### 1. `<muni-navbar>`
**Scopo**: Header responsivo con brand istituzionale, primary nav, second nav (ricerca, SPID/CIE, it/eng).
**Props**: `brand-logo`, `brand-name`, `logo-alt`, `show-search`, `show-login`, `menu-items[]`, `secondary-menu[]`.
**Stati**: collapsed / expanded / sticky / login-open.
**Focus-visible**: ogni link con outline 3px `--tw-ring-primary`.
**Motion**: mobile menu slide-in con `prefers-reduced-motion: opacity` fallback.

### 2. `<muni-footer>`
**Scopo**: Footer istituzionale Design Comuni.
**Props**: `sections[]`, `social-links[]`, `legal-links[]`, `contact-info`, `print-version-url`.
**Link richiesti**: Home, Privacy, Cookie, Contatti, PEC, Accesso ai documenti, Versione stampabile, Disclaimer, Note legali.

### 3. `<muni-hero>`
**Scopo**: Hero section con titolo grande, sottotitolo, immagine/CTA.
**Props**: `title`, `subtitle`, `image-src`, `image-alt`, `cta-text`, `cta-href`, `bg-tint` (optional).
**Stati**: image-loaded / loading / error.

### 4. `<muni-card-news>`
**Scopo**: Card notizia/novità.
**Props**: `category`, `category-icon`, `title`, `date`, `excerpt`, `link-text`, `link-href`, `accent` (green per notizie).
**Composizione**: header (category), titolo, estratto, `read-more` link con freccia.

### 5. `<muni-card-service>`
**Scopo**: Card servizio.
**Props**: `icon`, `title`, `description`, `link-text`, `link-href`.

### 6. `<muni-breadcrumb>`
**Scopo**: Breadcrumb semantico (list-item + aria-current).
**Props**: `items[]` (label, href?, isCurrent?).

### 7. `<muni-chip>` / `<muni-tag>`
**Scopo**: Badge categorico.
**Props**: `variant` (success / info / warning / danger / neutral), `icon`, `text`.

### 8. `<muni-read-more>`
**Scopo**: Link "Tutte le novità" con freccia →.
**Props**: `text`, `href`, `direction` (right default, left).

### 9. `<muni-skip-links>`
**Scopo**: Skip links WCAG.
**Props**: `main-id`, `footer-id`, `texts: { main, footer }`.

### 10. `<muni-modal>`
**Scopo**: Modal accessibile.
**Props**: `title`, `open` (binding).
**Azione**: ESC close, outside click close, focus trap.

### 11. `<muni-report-wizard>` (per segnalazione)
**Scopo**: Multi-step wizard.
**Props**: `steps[]`, `current-step`, `on-complete`.
**Internals**: `<muni-step>`, `<muni-stepper>` indicator.

## DaisyUI Components Riusati
- `btn` (primary, secondary, outline, ghost, link)
- `card` + `card-body` + `card-title`
- `badge` (multiple variants)
- `breadcrumb` (via DaisyUI `join`)
- `input` (multiple variants)
- `select` (multiple variants)
- `textarea`
- `file-input` (per allegati segnalazione)
- `table` (responsive)
- `accordion` / `collapse` (FAQ, FAQ-sezione)
- `pagination` / `join`

## Flowbite Components Riusati
- `modal` (fallback per modali semplici)
- `dropdown` (fallback, preferire Lit)
- `pagination` (fallback)
- `tooltip` (fallback)
- `accordion` (fallback)
- `datepicker` (fallback)
- `toast` (notifiche)

## Pattern Tailwind Ricorrenti (`@layer components`)
```css
@layer components {
  .prose-heading-3xl { @apply text-4xl font-bold tracking-tight text-italia-blue-dark; }
  .hero-pattern { @apply relative overflow-hidden bg-base-100; } /* bg paper tint */
  .card-pattern { @apply bg-white rounded-lg border border-italia-gray-200 shadow-sm; }
  .link-pattern { @apply underline decoration-primary underline-offset-4 hover:text-primary; }
  .skiplink-pattern { @apply absolute -top-16 left-4 bg-primary text-white px-4 py-2 rounded opacity-0 focus:top-4 focus:opacity-100 focus:z-50 transition; }
  .focus-visible-pattern { @apply outline-none ring-2 ring-primary ring-offset-2; }
  .btn-pattern { @apply inline-flex items-center gap-2 rounded-md bg-primary px-6 py-2.5 text-base font-medium text-white transition hover:bg-italia-blue-dark focus-visible-pattern; }
}
```

## Icone
Sostituire Bootstrap Italia SVG sprites (`sprites.svg#it-calendar`) con:
- **Heroicons** (via Tailwind, inline SVG) per icone standard
- **Lucide** per icone moderne
- **Custom SVG** solo per icone PA specifiche (nessun file sprite)

## Immagini / Icone Decorative
Usare CSS `background-image` per decorazioni (bg pattern) al posto di `alt=""` immagini, per ridurre rumore accessibilità.

## Placeholder Monogrammi
Sostituiti con: role label + one real credential line.

## Evidenza
Ogni componente testato con Playwright: capture 390px → no overflow, focus-visible visibile, contrast APCA ≥75 (Lc).
