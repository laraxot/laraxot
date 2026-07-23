# Segnalazione Pages - HTML Structure Parity Analysis

**Date:** 2026-04-07
**Reference:** `/tmp/design-comuni-ref/src/pages/sito/*.hbs` (7 templates)
**Local:** `laravel/Themes/Sixteen/resources/views/components/blocks/flow.ticket/*.blade.php`
**Goal:** 90%+ HTML structure parity, then CSS/JS visual parity

---

## Pages Analyzed

| # | Page | Reference HBS | Local Blade | Structure Match |
|---|------|--------------|-------------|-----------------|
| 1 | segnalazione-dettaglio | `segnalazione-dettaglio.hbs` | `segnalazione-dettaglio.blade.php` | ⚠️ 70% |
| 2 | segnalazione-01-privacy | `segnalazione-01-privacy.hbs` | `flow.ticket/01-privacy.blade.php` | ❌ 30% |
| 3 | segnalazione-02-dati | `segnalazione-02-dati.hbs` | `flow.ticket/02-dati.blade.php` | ❌ 25% |
| 4 | segnalazione-03-riepilogo | `segnalazione-03-riepilogo.hbs` | `flow.ticket/03-riepilogo.blade.php` | ❌ 30% |
| 5 | segnalazione-04-conferma | `segnalazione-04-conferma.hbs` | `flow.ticket/04-conferma.blade.php` | ✅ 85% |
| 6 | segnalazione-area-personale | `segnalazione-area-personale.hbs` | `flow/area-personale/dashboard.blade.php` | ⚠️ 50% |
| 7 | ticket-list | `ticket-list.hbs` | *(missing)* | ❌ 0% |

---

## Critical Findings

### Problem: Generic Tailwind vs Design Comuni Classes

The local flow templates (01-privacy, 02-dati, 03-riepilogo) use **generic Tailwind utility classes** instead of the **Design Comuni design token classes**:

| Generic Tailwind (WRONG) | Design Comuni (CORRECT) | Purpose |
|--------------------------|------------------------|---------|
| `text-lg font-semibold text-gray-900` | `title-xxlarge mb-4` | Heading |
| `bg-gray-50 rounded-lg p-6` | `has-bg-grey p-3` / `card card-teaser` | Card bg |
| `text-gray-600` | `text-paragraph lora` | Body text |
| `text-blue-600` | `t-primary` | Link color |
| `border-gray-300` | Bootstrap Italia form styling | Input border |
| `focus:ring-blue-500` | Design Comuni focus ring | Focus state |
| `bg-blue-600 hover:bg-blue-700` | `btn btn-primary` | Button |
| `border border-gray-300` | `btn btn-outline-primary` | Outline button |
| `flex items-start` | `checkbox-body d-flex align-items-center` | Checkbox layout |
| `grid grid-cols-1 md:grid-cols-2` | Bootstrap grid `row col-*` | Layout grid |

### Root Cause

The flow templates were written as standalone Tailwind components, **not** using the Design Comuni component system that maps Bootstrap Italia classes to Tailwind via `@apply` in `style-apply.css`.

---

## Detailed Structure Comparison

### 1. segnalazione-dettaglio

**Reference Structure:**
```
main
├── container#main-container
│   └── row > col-12 col-lg-10
│       └── cmp-breadcrumbs
├── container
│   └── row > col-12 col-lg-10
│       └── cmp-heading-detail (h1.title-xxxlarge, chip, buttons)
│   └── hr.d-none.d-lg-block
├── container
│   └── row.row-column-menu-left
│       ├── col-12 col-lg-3 (cmp-navscroll sidebar)
│       └── col-12 col-lg-8.offset-lg-1
│           └── div.it-page-sections-container
│               ├── section.it-page-section (who-needs, description, how-to, needed, obtain, costs, service-access, conditions)
│               └── section#contacts (card card-teaser, topics, updated date)
├── div.bg-primary > container > row > col-12 col-lg-6 (cmp-rating)
└── div.bg-grey-card.shadow-contacts > container > row
    ├── col-12 (cmp-carousel)
    └── col-12 col-lg-6.offset-lg-3.p-contacts (cmp-contacts)
```

**Local Structure:**
```
div.container.dc-service-detail
├── div.row > div.col-12.col-lg-10
│   └── div.cmp-heading (h1, ul.chip, buttons)
├── div.row > div.col-lg-3.offset-lg-1 (dropdowns for share/actions)
├── hr.d-none.d-lg-block
├── div.container.dc-service-detail__content
│   └── div.row.row-column-menu-left
│       ├── col-12.col-lg-3 (cmp-navscroll)
│       └── col-12.col-lg-8.offset-lg-1
│           └── div.it-page-sections-container
│               ├── sections (same IDs)
│               └── section#contacts
├── (NO rating section)
└── (NO contacts footer)
```

**Differences:**
- ❌ Missing: `div.bg-primary` rating section
- ❌ Missing: `div.bg-grey-card.shadow-contacts` footer with contacts
- ❌ Missing: `cmp-carousel` related content
- ⚠️ Share/action dropdowns in different position (local has them in header row)
- ✅ Core content sections match (who-needs, description, how-to, etc.)

---

### 2. segnalazione-01-privacy

**Reference Structure:**
```
main
├── container#main-container > row > col-12 col-lg-10 (breadcrumbs)
├── container > row > col-12 col-lg-10 (h1.title-xxxlarge.mb-4)
├── container > row > col-12 (cmp-info-progress step 1/3)
├── container > row > col-12 col-lg-8.pb-40.pb-lg-80
│   ├── p.text-paragraph (GDPR description)
│   ├── p.text-paragraph (privacy link)
│   ├── div.form-check > div.checkbox-body (checkbox + label.title-small-semi-bold)
│   └── cmp-button "Avanti"
└── div.bg-grey-card.shadow-contacts > container > row > col-12 col-lg-6.offset-lg-3.p-contacts (cmp-contacts)
```

**Local Structure:**
```
div.step-content.mt-8
├── h3.text-lg.font-semibold.text-gray-900.mb-4 (WRONG classes)
├── div.bg-gray-50.rounded-lg.p-6.mb-6 (WRONG classes)
│   └── div.prose.prose-sm > p (WRONG classes)
├── div.space-y-4
│   ├── div.flex.items-start > input[type=checkbox] + label (WRONG classes)
│   └── div.flex.items-start > input[type=checkbox] + label (WRONG classes)
└── div.mt-6.flex.justify-end > button (WRONG classes)
```

**Differences:**
- ❌ NO container/row/col Bootstrap grid structure
- ❌ NO cmp-breadcrumbs
- ❌ NO h1.title-xxxlarge heading
- ❌ NO cmp-info-progress stepper
- ❌ NO bg-grey-card footer with contacts
- ❌ ALL classes are generic Tailwind, not Design Comuni tokens
- ❌ Missing: GDPR article reference text
- ❌ Missing: "Ho letto e compreso l'informativa sulla privacy" label text

---

### 3. segnalazione-02-dati

**Reference Structure:**
```
main
├── container#main-container > row > col-12 col-lg-10 (breadcrumbs + heading)
├── col-12 (cmp-info-progress step 2/3)
├── row
│   ├── col-12 col-lg-3.d-none.d-lg-block (cmp-navscroll sidebar)
│   └── col-12 col-lg-8.offset-lg-1
│       └── div.steppers-content > div.it-page-sections-container
│           ├── section#report-place (card with autocomplete)
│           ├── section#report-info (card with select, inputs, textarea, upload)
│           └── section#report-author (card with user info)
│       └── cmp-nav-steps (save button)
└── div.bg-grey-card.shadow-contacts (cmp-contacts)
```

**Local Structure:**
```
div.step-content.mt-8
├── h3.text-lg.font-semibold.text-gray-900.mb-4
├── p.text-gray-600.mb-6
├── div.space-y-6
│   ├── div.grid (nome, cognome inputs)
│   ├── div (email input)
│   ├── div (telefono input)
│   └── div (indirizzo input)
└── div.mt-6.flex.justify-between (prev/next buttons)
```

**Differences:**
- ❌ Completely different structure - local is a simple form, reference has cards/navscroll/steppers
- ❌ NO cmp-navscroll sidebar
- ❌ NO card wrappers (cmp-card-content-box)
- ❌ NO place autocomplete
- ❌ NO inefficiency type select
- ❌ NO title/details textarea
- ❌ NO image upload section
- ❌ NO author info card
- ❌ NO cmp-nav-steps
- ❌ NO bg-grey-card footer

---

### 4. segnalazione-03-riepilogo

**Reference Structure:**
```
main
├── container > row (breadcrumbs + heading + cmp-info-progress step 3/3)
├── row > col-12 col-lg-8
│   ├── cmp-callout.warning (attention warning)
│   ├── h2.title-xxlarge (Segnalazione)
│   ├── cmp-card > cmp-info-summary (disservizio details)
│   ├── h2.title-xxlarge (Dati Generali)
│   ├── cmp-card > cmp-info-summary-no-modify (author)
│   ├── cmp-card > cmp-info-summary-no-modify (contacts)
│   └── cmp-nav-steps (next + modal + save)
└── div.bg-grey-card.shadow-contacts (cmp-contacts)
```

**Local Structure:**
```
div.step-content.mt-8
├── h3.text-lg.font-semibold.text-gray-900.mb-4
├── div.bg-gray-50.rounded-lg.p-6 (reporter data - dl/dt/dd)
├── div.bg-gray-50.rounded-lg.p-6 (signal details - dl/dt/dd)
├── div.flex.items-start.mb-6 (checkbox for terms)
└── div.mt-6.flex.justify-between (prev/next buttons)
```

**Differences:**
- ❌ NO cmp-callout warning box
- ❌ NO cmp-info-summary components
- ❌ NO cmp-card wrappers
- ❌ NO cmp-nav-steps
- ❌ NO bg-grey-card footer
- ❌ Uses generic Tailwind classes throughout

---

### 5. segnalazione-04-conferma

**Reference Structure:**
```
main
├── container > row (breadcrumbs + cmp-heading with icon + subtitle + button)
├── hr.d-none.d-lg-block
├── row > col-12 col-lg-10 (cmp-icon-list servizi correlati)
├── div.bg-primary > container > row > col-12 col-lg-6 (cmp-rating)
└── div.bg-grey-card.shadow-contacts > container > row > col-12 col-lg-6.offset-lg-3.p-contacts (cmp-contacts)
```

**Local Structure:**
```
div.container#main-container
├── div.row > div.col-12.col-lg-10
│   └── div.cmp-heading (icon + h1 + subtitles + button)
├── p.mt-3 (consulta richiesta link)
├── hr.d-none.d-lg-block
├── div.row > div.col-12.col-lg-10 (cmp-icon-list servizi correlati)
├── div.bg-primary (cmp-rating) ✅
└── div.bg-grey-card.shadow-contacts (cmp-contacts) ✅
```

**Differences:**
- ✅ Rating section present
- ✅ Contacts footer present
- ✅ Icon list present
- ⚠️ Minor: "Consulta la richiesta" link in different position
- ✅ **BEST MATCH** - 85% parity

---

### 6. segnalazione-area-personale

**Reference Structure:**
```
main (logged=true)
├── container > row (breadcrumbs + cmp-heading with user name + CF)
├── col-12.p-0 (cmp-nav-tab)
├── div.it-page-sections-container > div.tab-content
│   ├── div.tab-pane#data-ex-tab1 (active)
│   │   └── row
│   │       ├── col-12 col-lg-3.d-none.d-lg-block (cmp-navscroll)
│   │       └── col-12 col-lg-8.offset-lg-1
│   │           ├── div#latest-posts (cmp-card > cmp-card-latest-messages x3 + button)
│   │           └── div#latest-activities (cmp-card > cmp-icon-card x3 + button)
│   ├── div.tab-pane#data-ex-tab3
│   │   └── row > col-12 col-lg-8.offset-lg-1
│   │       ├── section#practices (cmp-filter + cmp-accordion x3 + button)
│   │       └── section#payments (cmp-filter + cmp-accordion x3 + button)
│   └── other tab panes
└── div.bg-grey-card.shadow-contacts (cmp-contacts)
```

**Local Structure:**
```
(need to check flow/area-personale/dashboard.blade.php)
```

---

### 7. ticket-list

**Reference Structure:**
```
main
├── container > row.mb-md-40.mb-lg-80 (breadcrumbs + cmp-heading + hr)
├── row
│   ├── col-lg-3.d-none.d-lg-block (cmp-category-list sidebar)
│   └── col-lg-8.offset-lg-1
│       ├── div.d-flex (search results count + filter buttons)
│       ├── ul.nav.nav-tabs (Mappa/Elenco tabs)
│       └── div.tab-content
│           ├── div.tab-pane#data-ex-disservizio1 (active - Mappa)
│           │   └── row
│           │       ├── col-12 (cmp-map)
│           │       └── col-lg-6 (cmp-text-button + cmp-button "Segnala")
│           └── div.tab-pane#data-ex-disservizio2 (Elenco)
│               └── row > cmp-card x3 + cmp-button "Carica altre"
├── div.bg-primary (cmp-rating)
└── div.bg-grey-card.shadow-contacts (cmp-contacts)
```

**Local Structure:**
```
(NO local blade template exists for this page)
```

**Differences:**
- ❌ NO local blade template exists
- ❌ Needs to be created from scratch

---

## Action Plan

### Phase 1: Fix Blade Templates (Structure)

1. **segnalazione-01-privacy.blade.php** - Rewrite with:
   - container/row/col grid structure
   - cmp-breadcrumbs
   - h1.title-xxxlarge
   - cmp-info-progress (step 1/3)
   - form-check + checkbox-body
   - cmp-button "Avanti"
   - bg-grey-card footer with cmp-contacts

2. **segnalazione-02-dati.blade.php** - Rewrite with:
   - container/row/col grid structure
   - cmp-navscroll sidebar
   - cmp-card-content-box wrappers
   - cmp-input-autocomplete for place
   - cmp-select for inefficiency type
   - cmp-text-area for details
   - upload-wrapper for images
   - cmp-nav-steps
   - bg-grey-card footer

3. **segnalazione-03-riepilogo.blade.php** - Rewrite with:
   - cmp-callout warning
   - cmp-info-summary components
   - cmp-card wrappers
   - cmp-nav-steps
   - bg-grey-card footer

4. **ticket-list.blade.php** - Create new:
   - Full structure from reference HBS
   - cmp-category-list sidebar
   - cmp-map component
   - cmp-card list items
   - cmp-rating
   - cmp-contacts

### Phase 2: CSS Parity

After blade templates match reference structure:
- Verify all Design Comuni classes render correctly via Tailwind @apply
- Check responsive behavior (mobile, tablet, desktop)
- Verify form styling, button styling, card styling

### Phase 3: JS/Alpine.js Parity

- Ensure stepper navigation works
- Ensure form validation works
- Ensure accordion/navscroll behavior matches reference

---

## Cross-References

- → [segnalazione-parity/](./) - This analysis
- → [reference-html/](./reference-html/) - Reference HBS templates
- → [../../../00-index.md](../../../00-index.md) - Theme docs index
- → [../../../../Modules/Cms/docs/design-comuni-homepage.md](../../../../Modules/Cms/docs/design-comuni-homepage.md) - Cms module docs
- → [../../../../../bashscripts/docs/design-comuni-selected-page-comparisons.md](../../../../../bashscripts/docs/design-comuni-selected-page-comparisons.md) - Scripts docs

---

*Analysis completed: 2026-04-07*
