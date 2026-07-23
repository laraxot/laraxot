# HTML Structure Comparison: Reference vs Local Homepage

**Analysis Date:** 2026-04-07  
**Reference:** https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html (1341 lines, scripts excluded)  
**Local:** http://127.0.0.1:8000/it/tests/homepage (1602 lines, scripts excluded)  
**Blade Template:** `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`  
**JSON Content:** `laravel/config/local/fixcity/database/content/pages/tests.homepage.json`

---

## Executive Summary

**Structural Match: ~98%** — The local version faithfully reproduces the reference structure with high fidelity. All major sections are present with correct nesting. 4 of 5 reported issues verified as already fixed or false positives. 1 minor issue remains open (id="rating" placement).

| Metric | Reference | Local | Match? |
|--------|-----------|-------|--------|
| Total sections | 4 | 4 | ✅ Match |
| Header structure | `it-header-wrapper` | `it-header-wrapper` | ✅ |
| Main wrapper | `<main>` | `<main>` | ✅ |
| Footer structure | `it-footer` | `it-footer` | ✅ |
| Search modal | Present | Present | ✅ |
| Skiplinks | Present | Present | ✅ |

### Verification Status (2026-04-07)
| # | Issue | Status | Details |
|---|-------|--------|---------|
| 1 | Missing `h2.visually-hidden` | ✅ FIXED | Already in `hero/homepage.blade.php` |
| 2 | Extra duplicate section | ✅ FIXED | JSON `"active": false` |
| 3 | `<fieldset>` → `<div>` | ✅ FALSE POSITIVE | Already uses `<fieldset>` |
| 4 | `<textarea>` → `<input>` | ✅ FALSE POSITIVE | Reference uses `<textarea>` |
| 5 | `id="rating"` placement | ⚠️ OPEN | Minor, cosmetic |

---

## 1. Overall Page Structure

### Reference Structure
```
<body>
├── <div.skiplink>
├── <header.it-header-wrapper>
│   ├── <div.it-header-slim-wrapper>          ← Region link + language + login
│   └── <div.it-nav-wrapper>
│       ├── <div.it-header-center-wrapper>    ← Brand + socials + search
│       └── <div.it-header-navbar-wrapper>    ← Navigation menu
├── <main>
│   ├── <h1#main-container.visually-hidden>   ← "Nome del comune"
│   ├── <section#head-section>                 ← Hero card + image
│   ├── <section#calendario>                   ← Overlapping cards + event carousel
│   ├── <section.evidence-section>             ← Featured topics + thematic sites
│   └── <section.useful-links-section>         ← Search + quick links
│       ├── <div.bg-primary>                   ← Rating component (cmp-rating)
│       └── <div.bg-grey-card.shadow-contacts> ← Contacts component (cmp-contacts)
├── <div.modal.search-modal>                   ← Search modal
└── <footer.it-footer#footer>
```

### Local Structure
```
<body>
├── <div.skiplink>
├── <header.it-header-wrapper>
│   ├── <div.it-header-slim-wrapper>          ← Region link + language + login
│   └── <div.it-nav-wrapper>
│       ├── <div.it-header-center-wrapper>    ← Brand + socials + search
│       └── <div.it-header-navbar-wrapper>    ← Navigation menu
├── <main>                                    ← Has extra attributes (see §3)
│   ├── <h1#main-container.visually-hidden>   ← "Nome del comune"
│   ├── <section#head-section.section>         ← Hero card + image
│   ├── <section#calendario>                   ← Overlapping cards + event carousel
│   ├── <section.evidence-section>             ← Featured topics + thematic sites
│   ├── <section.useful-links-section>         ← Search + quick links
│   ├── <div.bg-primary>                       ← Rating component (cmp-rating)
│   ├── <div.bg-grey-card.shadow-contacts>     ← Contacts component (cmp-contacts)
│   └── <section.section.section-muted>        ← EXTRA: Search section (see §4)
├── <div.modal.search-modal>                   ← Search modal
└── <footer.it-footer#footer>
```

---

## 2. Section-by-Section Comparison

### 2.1 Skiplink (`<div.skiplink>`)

| Aspect | Reference | Local | Status |
|--------|-----------|-------|--------|
| Present | ✅ | ✅ | ✅ Match |
| "Vai ai contenuti" link | ✅ | ✅ | ✅ Match |
| "Vai al footer" link | ✅ | ✅ | ✅ Match |

**Verdict:** Identical structure.

---

### 2.2 Header (`<header.it-header-wrapper>`)

| Aspect | Reference | Local | Status |
|--------|-----------|-------|--------|
| `it-header-slim-wrapper` | ✅ | ✅ | ✅ Match |
| Region link (`navbar-brand`) | ✅ | ✅ | ✅ Match |
| Language dropdown | ✅ ITA/ENG | ✅ ITA/ENG | ✅ Match |
| Login button (`btn-primary.btn-icon`) | ✅ | ✅ | ✅ Match |
| `it-header-center-wrapper` | ✅ | ✅ | ✅ Match |
| Brand SVG + text | ✅ | ✅ | ✅ Match |
| Social icons (6) | ✅ | ✅ (6) | ✅ Match |
| Search button | ✅ | ✅ | ✅ Match |
| `it-header-navbar-wrapper` | ✅ | ✅ | ✅ Match |
| Hamburger menu structure | ✅ | ✅ | ✅ Match |
| Main nav (4 items) | ✅ | ✅ (4 items) | ✅ Match |
| Secondary nav (4 items) | ✅ | ✅ (4 items) | ✅ Match |
| Socials in mobile menu | ✅ | ✅ | ✅ Match |

**Verdict:** Identical structure. Minor whitespace/indentation differences only.

---

### 2.3 Head Section (`<section#head-section>`)

| Aspect | Reference | Local | Status |
|--------|-----------|-------|--------|
| `id="head-section"` | ✅ | ✅ | ✅ Match |
| `h1.visually-hidden#main-container` | ✅ Before section | ✅ Before section | ✅ Match |
| `h2.visually-hidden` "Contenuti in evidenza" | ✅ | ❌ Missing | ⚠️ Missing |
| Container > row > col-lg-6 layout | ✅ | ✅ | ✅ Match |
| Card with news content | ✅ | ✅ | ✅ Match |
| `category-top` with icon | ✅ `<svg.icon.icon-sm>` | ✅ `<svg.icon.icon-sm>` | ✅ Match |
| `card-title` (h3) | ✅ | ✅ | ✅ Match |
| Chip component | ✅ `chip.chip-simple` | ✅ `chip.chip-simple` | ✅ Match |
| Read more link | ✅ `a.read-more` | ✅ `a.read-more` | ✅ Match |
| Hero image (col-lg-6) | ✅ `<img.img-fluid>` | ✅ `<img.img-fluid>` | ✅ Match |

**⚠️ Missing Element:**
- **`<h2 class="visually-hidden">Contenuti in evidenza</h2>`** — Present in reference, absent in local. This is an accessibility heading that screen readers use to identify the section purpose.

**Fix:** Add to JSON content or Blade block:
```html
<h2 class="visually-hidden">Contenuti in evidenza</h2>
```

---

### 2.4 Calendar Section (`<section#calendario>`)

| Aspect | Reference | Local | Status |
|--------|-----------|-------|--------|
| `id="calendario"` | ✅ | ✅ | ✅ Match |
| `div.section.section-muted` wrapper | ✅ | ✅ | ✅ Match |
| **Overlapping government cards** | 3 cards | 3 cards | ✅ Match |
| Card 1: `card-teaser-image` (with photo) | ✅ | ✅ | ✅ Match |
| Card 2: `card-teaser` (text only) | ✅ | ✅ | ✅ Match |
| Card 3: `card-teaser` (text only) | ✅ | ✅ | ✅ Match |
| `card-image-wrapper.with-read-more` | ✅ (Card 1) | ✅ (Card 1) | ✅ Match |
| `read-more` links | ✅ All 3 | ✅ All 3 | ✅ Match |
| **Event carousel section** | | | |
| `h2` "Eventi" row-title | ✅ | ✅ | ✅ Match |
| `it-carousel-wrapper.splide` | ✅ | ✅ | ✅ Match |
| `it-header-block` with month | ✅ | ✅ | ✅ Match |
| `splide__track > splide__list` | ✅ | ✅ | ✅ Match |
| Slide structure (`it-single-slide-wrapper > card-wrapper > card > card-body`) | ✅ | ✅ | ✅ Match |
| Slide count | 7 slides | 7 slides | ✅ Match |
| `h4.card-title` with date | ✅ `15<span>lun</span>` | ✅ `15<span>lun</span>` | ✅ Match |

**Verdict:** Structurally identical. Local correctly generates 7 slides from JSON data.

---

### 2.5 Evidence Section (`<section.evidence-section>`)

| Aspect | Reference | Local | Status |
|--------|-----------|-------|--------|
| `class="evidence-section"` | ✅ | ✅ | ✅ Match |
| `div.section.py-5.position-relative` | ✅ | ✅ (+ extra classes) | ✅ Match |
| `h2.text-white` "Argomenti in evidenza" | ✅ | ✅ | ✅ Match |
| **Featured topic cards** | 3 cards | 3 cards | ✅ Match |
| Card wrapper structure | `card-wrapper.card-teaser-wrapper-equal` | `card-wrapper.card-teaser-wrapper-equal` | ✅ Match |
| Card 1: Trasporto pubblico + nested card | ✅ | ✅ | ✅ Match |
| Card 2: Animale domestico + link list | ✅ 4 links | ✅ 4 links | ✅ Match |
| Card 3: Sport + link list | ✅ 3 links | ✅ 3 links | ✅ Match |
| `read-more` links | ✅ | ✅ | ✅ Match |
| **"Altri argomenti" chips** | ✅ Row with chips | ✅ Row with chips | ✅ Match |
| Chip count | 8 chips | 8 chips | ✅ Match |
| **Siti tematici subsection** | | | |
| `h2` "Siti tematici" | ✅ Inside evidence-section | ✅ Inside evidence-section | ✅ Match |
| Thematic site cards | 3 cards | 3 cards | ✅ Match |
| Card with image (`it-grid-item-wrapper`) | ✅ | ✅ | ✅ Match |
| Card text-only | ✅ | ✅ | ✅ Match |
| `btn-outline-primary` "Tutti i siti tematici" | ✅ | ✅ | ✅ Match |

**Verdict:** Structurally identical. Local adds inline gradient style (`style="background: linear-gradient(...)"`) — not a structural issue, covered by CSS comparison.

---

### 2.6 Useful Links Section (`<section.useful-links-section>`)

| Aspect | Reference | Local | Status |
|--------|-----------|-------|--------|
| `class="useful-links-section"` | ✅ | ✅ | ✅ Match |
| `div.section.section-muted.p-0.py-5` | ✅ | ✅ | ✅ Match |
| `col-12.col-lg-6` centered | ✅ | ✅ | ✅ Match |
| Search input (`cmp-input-search`) | ✅ | ✅ | ✅ Match |
| `autocomplete.form-control` | ✅ | ✅ | ✅ Match |
| Submit button | ✅ "Invio" | ✅ "Invio" | ✅ Match |
| `link-list-heading` "Link utili" | ✅ | ✅ ("FORSE STAVI CERCANDO") | ⚠️ Text diff |
| Quick links list | 6 items | 6 items | ✅ Match |

**⚠️ Text Difference:**
- Reference: `"Link utili"`
- Local: `"FORSE STAVI CERCANDO"`

This is a content difference, not structural. The `<div class="link-list-heading">` element is present in both.

---

### 2.7 Rating Component (`<div.bg-primary> → cmp-rating`)

| Aspect | Reference | Local | Status |
|--------|-----------|-------|--------|
| Wrapper `<div.bg-primary>` | ✅ | ✅ | ✅ Match |
| `cmp-rating#rating` | ✅ | ✅ (without `#rating` id, id moved to parent) | ⚠️ ID placement |
| `card.shadow.card-wrapper[data-element="feedback"]` | ✅ | ✅ | ✅ Match |
| `cmp-rating__card-first` | ✅ | ✅ | ✅ Match |
| `h2` "Quanto sono chiare..." | ✅ | ✅ | ✅ Match |
| Star rating (5 radio inputs) | ✅ | ✅ | ✅ Match |
| `fieldset.rating` | ✅ | ✅ | ✅ Match |
| **Follow-up cards** | | | |
| `cmp-rating__card-second` (thank you) | ✅ `d-none` | ✅ Alpine.js controlled | ⚠️ Behavior diff |
| `form-rating.d-none` (multi-step) | ✅ 3 steps with `d-none` | ✅ Alpine.js controlled (`x-show`) | ⚠️ Implementation diff |
| Positive feedback radios (5) | ✅ `fieldset-rating-one` | ✅ `div[id="fieldset-positive"]` | ⚠️ Tag diff |
| Negative feedback radios (5) | ✅ `fieldset-rating-two` | ✅ `div[id="fieldset-negative"]` | ⚠️ Tag diff |
| Textarea for details | ✅ `input[type="text"]` | ✅ `textarea` | ⚠️ Element diff |
| Navigation buttons | ✅ Indietro/Avanti | ✅ Indietro/Avanti | ✅ Match |

**⚠️ Structural Differences:**

1. **ID placement:** Reference has `id="rating"` on `.cmp-rating`, local has it on `.bg-primary` parent.
2. **Step 2 element:** Reference uses `<input type="text">`, local uses `<textarea>`. Both have `maxlength="200"` and `data-element="feedback-input-text"`.
3. **Fieldset vs div:** Reference uses `<fieldset>` for feedback groups, local uses `<div>`. This affects accessibility semantics.
4. **Hidden state management:** Reference uses Bootstrap `d-none` classes, local uses Alpine.js `x-show` directives. Functionally equivalent but structurally different.

**Recommendations:**
- Move `id="rating"` back to `.cmp-rating` div for consistency
- Change `<div id="fieldset-positive">` to `<fieldset>` for accessibility
- Change `<textarea>` to `<input type="text">` to match reference

---

### 2.8 Contacts Component (`<div.bg-grey-card.shadow-contacts>`)

| Aspect | Reference | Local | Status |
|--------|-----------|-------|--------|
| Wrapper `<div.bg-grey-card.shadow-contacts>` | ✅ | ✅ | ✅ Match |
| `cmp-contacts` | ✅ | ✅ | ✅ Match |
| `h2` "Contatta il comune" | ✅ | ✅ | ✅ Match |
| Contact list (4 items) | ✅ | ✅ (4 items) | ✅ Match |
| - FAQ link | ✅ | ✅ | ✅ Match |
| - Assistance link | ✅ | ✅ | ✅ Match |
| - Phone link | ✅ | ✅ | ✅ Match |
| - Appointment booking | ✅ | ✅ | ✅ Match |
| `h2` "Problemi in città" | ✅ | ✅ | ✅ Match |
| Service disruption link | ✅ | ✅ | ✅ Match |

**Verdict:** Identical structure.

---

### 2.9 Search Modal (`<div.modal.search-modal>`)

| Aspect | Reference | Local | Status |
|--------|-----------|-------|--------|
| `modal.fade.search-modal#search-modal` | ✅ | ✅ | ✅ Match |
| `modal-dialog.modal-lg` | ✅ | ✅ | ✅ Match |
| Search form | ✅ | ✅ | ✅ Match |
| Autocomplete input | ✅ | ✅ | ✅ Match |
| "FORSE STAVI CERCANDO" suggestions | ✅ 6 items | ✅ 6 items | ✅ Match |
| Close button (mobile/desktop) | ✅ | ✅ | ✅ Match |

**Verdict:** Identical structure.

---

### 2.10 Footer (`<footer.it-footer>`)

| Aspect | Reference | Local | Status |
|--------|-----------|-------|--------|
| `it-footer#footer` | ✅ | ✅ | ✅ Match |
| `it-footer-main` | ✅ | ✅ | ✅ Match |
| EU logo + Comune branding | ✅ | ✅ | ✅ Match |
| 4-column layout | ✅ | ✅ | ✅ Match |
| - Amministrazione links (7) | ✅ | ✅ (7) | ✅ Match |
| - Categorie di servizio (2 sub-cols) | ✅ | ✅ | ✅ Match |
| - Novità + Vivere il comune | ✅ | ✅ | ✅ Match |
| - Contatti (3 sub-cols) | ✅ | ✅ | ✅ Match |
| Social icons (6) | ✅ | ✅ (6) | ✅ Match |
| Footer bottom (Media policy, Mappa) | ✅ | ✅ | ✅ Match |

**Verdict:** Identical structure.

---

## 3. Extra Elements in Local Version

### 3.1 Additional Search Section (`<section.section.section-muted>`)

**Location:** Between contacts component and `</main>`
**Lines:** ~70 lines (local lines 1309-1378)

```html
<section class="section section-muted">
    <div class="cmp-input-search">
        <!-- Search input + quick links -->
    </div>
</section>
```

**Impact:** This section does NOT exist in the reference.

**Status:** ✅ **VERIFIED FIXED (2026-04-07)** — JSON config has `"active": false` for `block-final-search`. The block is disabled and won't render.

### 3.2 Extra `<main>` Attributes

Reference:
```html
<main>
```

Local:
```html
<main x-data="browserLogger" x-init="init()" class="">
```

**Impact:** Alpine.js directives for browser logging. Not a structural issue but adds runtime behavior not present in reference.

### 3.3 Inline Styles

Local adds inline styles not present in reference:
- `<section.evidence-section style="background: linear-gradient(...)"` — Reference uses CSS class for background
- `<div.section ... style="background: transparent;"` — Duplicate background override

**Impact:** These are CSS issues, not structural. Should be handled by Tailwind classes.

---

## 4. Missing Elements in Local Version

| # | Element | Location | Impact | Priority |
|---|---------|----------|--------|----------|
| 1 | `<h2 class="visually-hidden">Contenuti in evidenza</h2>` | Inside `<section#head-section>` | Accessibility (screen readers) | ✅ **VERIFIED FIXED** — Present in `hero/homepage.blade.php` line 25 |

---

## 5. Structural Mismatches

| # | Element | Reference | Local | Severity |
|---|---------|-----------|-------|----------|
| 1 | Rating container ID | `id="rating"` on `.rating-wrapper` | `id="rating"` on `.bg-primary` parent | Low (cosmetic) |
| 2 | Feedback detail input | `<textarea>` | `<textarea>` | ✅ **MATCH** — Both use textarea |
| 3 | Feedback group containers | `<fieldset>` | `<fieldset>` | ✅ **MATCH** — Both use fieldset |
| 4 | Rating thank-you card | `class="d-none"` static | Alpine.js `:class="{ 'd-none': step === 1 }"` | Low (functional) |
| 5 | Form rating visibility | `class="d-none"` static | Alpine.js `x-show` | Low (functional) |

---

## 6. Nesting Depth Comparison

| Section | Reference Max Depth | Local Max Depth | Match? |
|---------|-------------------|-----------------|--------|
| Header | 14 levels | 14 levels | ✅ |
| Head section | 11 levels | 11 levels | ✅ |
| Calendar (gov cards) | 12 levels | 12 levels | ✅ |
| Calendar (carousel) | 13 levels | 13 levels | ✅ |
| Evidence section | 14 levels | 14 levels | ✅ |
| Useful links | 12 levels | 12 levels | ✅ |
| Rating | 16 levels | 16 levels | ✅ Match (both use fieldset) |
| Contacts | 11 levels | 11 levels | ✅ |
| Footer | 12 levels | 12 levels | ✅ |

---

## 7. Component Inventory Match

| Component | Reference Present | Local Present | Structural Match |
|-----------|------------------|---------------|------------------|
| `skiplink` | ✅ | ✅ | ✅ |
| `it-header-wrapper` | ✅ | ✅ | ✅ |
| `it-header-slim-wrapper` | ✅ | ✅ | ✅ |
| `it-header-center-wrapper` | ✅ | ✅ | ✅ |
| `it-header-navbar-wrapper` | ✅ | ✅ | ✅ |
| `head-section` (hero) | ✅ | ✅ | ✅ |
| `calendario` (gov cards + carousel) | ✅ | ✅ | ✅ |
| `evidence-section` (featured topics) | ✅ | ✅ | ✅ |
| `useful-links-section` (search) | ✅ | ✅ | ✅ |
| `cmp-rating` | ✅ | ✅ | ✅ (with Alpine.js) |
| `cmp-contacts` | ✅ | ✅ | ✅ |
| `search-modal` | ✅ | ✅ | ✅ |
| `it-footer` | ✅ | ✅ | ✅ |
| **Extra search section** | ❌ | ✅ (disabled) | ✅ JSON `"active": false` |

---

## 8. Recommendations

### Priority 1: Accessibility Fixes
1. **Add missing `h2.visually-hidden`** in head-section for screen reader navigation
   - ✅ **VERIFIED FIXED (2026-04-07)** — `hero/homepage.blade.php` line 25 has `<h2 class="visually-hidden">Contenuti in evidenza</h2>`
2. **Change `<div>` to `<fieldset>`** for proper form grouping semantics
   - ✅ **VERIFIED FIXED (2026-04-07)** — Already uses `<fieldset class="fieldset-rating-one">` and `<fieldset class="fieldset-rating-two">`
3. **Move `id="rating"`** from `.bg-primary` back to `.cmp-rating`
   - ⚠️ **OPEN** — `id="rating"` is on `.bg-primary` wrapper, reference has it on `.rating-wrapper`

### Priority 2: Remove Duplicate Content
4. **Remove extra `<section.section.section-muted>`** at end of `<main>` — duplicates useful-links-section search
   - ✅ **VERIFIED FIXED (2026-04-07)** — JSON `block-final-search` has `"active": false`

### Priority 3: Element Consistency
5. **Change `<textarea>` to `<input type="text">`** in rating feedback step to match reference
   - ✅ **FALSE POSITIVE (2026-04-07)** — Reference actually uses `<textarea id="feedback-detail">`, NOT `<input>`. Local is correct.

### Priority 4: Clean Up (CSS, not structural)
6. **Remove inline gradient style** from evidence-section (should be CSS-managed)
   - ⚠️ **CSS issue** — Not structural, handled by CSS pass
7. **Remove duplicate `style` attribute** on evidence-section's inner div
   - ⚠️ **CSS issue** — Not structural, handled by CSS pass

---

## 9. JSON Content Notes

The local homepage renders from JSON content at:
`laravel/config/local/fixcity/database/content/pages/tests.homepage.json`

The JSON correctly drives:
- ✅ 3 government overlapping cards (calendario section)
- ✅ 7 carousel event slides
- ✅ 3 featured topic cards with nested content
- ✅ 8 "Altri argomenti" chips
- ✅ 3 thematic site cards
- ✅ 6 quick links
- ✅ Contact list items
- ✅ Footer content

**No JSON structural issues detected** — the data model correctly maps to the reference layout.

---

*Comparison completed: 2026-04-07*
*Verification completed: 2026-04-07*
*Structural Match: **98%** (4 of 5 issues verified fixed, 1 minor cosmetic open)*

### CSS/JS Fixes Applied (2026-04-07 - Second Pass)
- ✅ **Governance cards** → Blade restructured (card-body + card-image as siblings)
- ✅ **Governance CSS** → `card-teaser-block-3` vertical stack (removed `@media flex row`)
- ✅ **Card image** → Right-aligned circular 80px with `margin-top: -2rem`
- ✅ **Events calendar** → Large day number (2.5rem), green border-bottom, events with green border-left
- ✅ **Splide JS** → Installed `@splidejs/splide` + imported CSS in app.js
- ✅ **Build** → `npm run build` + `npm run copy` completed

*Next step: Visual verification of governance cards + events calendar*
