# Homepage HTML Similarity: 81% → 90% Action Plan

> **Data**: 2026-04-07
> **Author**: GSD Deep-Dive Analysis
> **Current Similarity**: 81% (684/837 common elements)
> **Target Similarity**: 90%+ (≥754/837 common elements)
> **Gap to Close**: 153 reference-only elements

---

## Executive Summary

### Element Breakdown by Category

| Category | Reference Elements | Local Elements | Matching | Missing (Ref-only) | Extra (Local-only) |
|----------|-------------------|----------------|----------|--------------------|--------------------|
| Header (slim + nav + navbar) | ~200 | ~200 | ~200 | 0 | 0 |
| `<main>` wrapper | 1 | 2 | 0 | 0 | 1 (`div.container#main-container`) |
| Hero (`#head-section`) | ~30 | ~35 | ~28 | 0 | ~5 (search form extras) |
| Governance (`#calendario`) | ~140 | ~138 | ~135 | 2 | 0 |
| Evidence (`.evidence-section`) | ~150 | ~148 | ~145 | 3 | 1 |
| Useful Links (`.useful-links-section`) | ~30 | ~30 | ~30 | 0 | 0 |
| **Rating (`.cmp-rating`)** | **~120** | **~55** | **~50** | **~70** | **~5** |
| Contacts (`.bg-grey-card`) | ~40 | ~38 | ~36 | 2 | 0 |
| Search Modal | ~40 | ~40 | ~40 | 0 | 0 |
| Footer | ~100 | ~100 | ~100 | 0 | 0 |
| **TOTAL** | **837** | **772** | **684** | **153** | **88** |

### The 19% Gap Decomposed

| Gap Source | Elements | % of Total Gap | Fixable? |
|------------|----------|----------------|----------|
| Rating multi-step form (reference has, local simplified) | ~70 | 46% | **YES** — add missing wrapper/structure |
| Rating wrapper `<div.bg-primary>` structure (reference has, local has but different) | ~4 | 3% | **YES** — fix wrapper nesting |
| Rating star labels missing `active` class + `data-element` on inputs | ~10 | 7% | **YES** — add static classes |
| Rating thank-you card `d-none` attribute mismatch | ~1 | 1% | **YES** — add `d-none` |
| `<main>` nesting difference (outer container wrapper) | ~3 | 2% | **YES** — restructure layout |
| Evidence section: 4th list item missing `mb-2` class in local (Sport card) | ~1 | 1% | **YES** — fix blade |
| Evidence section: `text-paragraph-small-semi` class on "Visita il sito" | ~1 | 1% | **YES** — fix blade |
| Contacts section: extra whitespace/newlines in local (cosmetic) | ~2 | 1% | **YES** — compact HTML |
| Governance carousel: slide wrapper `h-100` extra class on local | ~7 | 5% | **YES** — remove class |
| Minor attribute differences (`xlink:href` vs `href`, `x-model` additions) | ~10 | 7% | **ACCEPT** — intentional modernizations |
| Alpine.js directives on rating stars (`@click`, `:class`) | ~25 | 16% | **ACCEPT** — JS behavior, not structural |
| Hero row `min-vh-lg-50` / `align-items-center` extra classes | ~3 | 2% | **ACCEPT** — intentional enhancement |
| `<body class="dc-homepage-parity">` extra class | 1 | 1% | **ACCEPT** — marker class |
| `<main data-page="homepage">` extra attribute | 1 | 1% | **ACCEPT** — CSS scoping |

**Total fixable**: ~92 elements → ~11% similarity gain
**Total acceptable deviations**: ~40 elements → ~5% remains as intentional differences

**Projected similarity after fixes**: 81% + ~11% = **~92%**

---

## Per-Block Analysis

### 1. Rating Section (`.cmp-rating`) — HIGHEST IMPACT

**Current state**: Local has simplified single-step Alpine.js rating (~55 elements). Reference has full Bootstrap Italia multi-step wizard (~120 elements).

**Missing reference elements (70)**:

| Missing Element | Count | Element Type |
|----------------|-------|-------------|
| `<div class="bg-primary">` outer wrapper | 1 | Container |
| `<div class="container">` inside bg-primary | 1 | Container |
| `<div class="row d-flex justify-content-center bg-primary">` | 1 | Row |
| `<div class="col-12 col-lg-6">` | 1 | Column |
| `label.full rating-star active` (static `active` class, no Alpine) | 5 | Labels |
| `input` with NO `x-model` attribute | 5 | Inputs |
| `data-element="feedback-rate-5"` through `feedback-rate-1` (static, no Alpine) | 5 | Attributes |
| `<div class="cmp-rating__card-second d-none" data-step="3">` (thank-you, hidden) | 1 | Container |
| `<div class="form-rating d-none">` wrapper | 1 | Container |
| `<div class="d-none" data-step="1">` wrapper | 1 | Container |
| `<div class="cmp-steps-rating">` wrapper (×2) | 2 | Container |
| `<fieldset class="fieldset-rating-one d-none" data-element="feedback-rating-positive">` | 1 | Fieldset |
| `<fieldset class="fieldset-rating-two d-none" data-element="feedback-rating-negative">` | 1 | Fieldset |
| `<legend class="iscrizioni-header w-100">` (×2) | 2 | Legend |
| `<h3 class="step-title ...">` (×2) | 2 | Heading |
| `<span class="step">1/2</span>` (×2) | 2 | Step indicator |
| `<div class="cmp-radio-list">` wrapper (×2) | 2 | Container |
| `<div class="card card-teaser shadow-rating">` (×2) | 2 | Card |
| `<div class="radio-body border-bottom border-light cmp-radio-list__item">` (×10) | 10 | Radio wrapper |
| `<input name="rating1" type="radio" id="radio-1">` (×5 positive) | 5 | Radio input |
| `<input name="rating2" type="radio" id="radio-6">` (×5 negative) | 5 | Radio input |
| `<label for="radio-X" class="active" data-element="feedback-rating-answer">` (×10) | 10 | Radio label |
| `<div class="d-none" data-step="2">` wrapper | 1 | Container |
| `<fieldset>` (step 2 text input) | 1 | Fieldset |
| `<label for="formGroupExampleInputWithHelp">` | 1 | Label |
| `<input type="text" class="form-control" id="formGroupExampleInputWithHelp" ...>` | 1 | Text input |
| `<small id="formGroupExampleInputWithHelpDescription">` | 1 | Help text |
| `<div class="d-flex flex-nowrap pt-4 w-100 justify-content-center button-shadow">` | 1 | Button wrapper |
| `<button class="btn btn-outline-primary fw-bold me-4 btn-back">Indietro</button>` | 1 | Button |
| `<button class="btn btn-primary fw-bold btn-next" type="submit" form="rating">Avanti</button>` | 1 | Button |

**Extra local elements reference doesn't have (5)**:

| Extra Element | Reason |
|-------------|--------|
| `x-data="{ rating: 0, hover: 0 }"` | Alpine.js state — acceptable |
| `@click`, `@mouseenter`, `@mouseleave`, `:class` directives (×5 labels) | Alpine.js interactivity — acceptable |
| `x-model="rating"` on inputs | Alpine.js binding — acceptable |

#### Fix Priority: CRITICAL (~70 elements = ~9% similarity)

**File**: `Themes/Sixteen/resources/views/components/blocks/feedback/rating.blade.php`

**Action**: The `origin/dev` branch already has the multi-step structure. The `HEAD` (current) has the simplified version. **Merge the dev branch rating component** but ensure:

1. All `label` elements have static `active` class (matching reference's pre-lit stars)
2. All `input` elements do NOT have `x-model` (reference has plain inputs)
3. The `d-none` class is present on `cmp-rating__card-second` initially
4. All `data-element` attributes match reference exactly
5. The `data-step="1"`, `data-step="2"`, `data-step="3"` wrapper structure matches reference
6. Radio buttons use reference's exact `name="rating1"` / `name="rating2"` naming
7. Step title text uses translatable strings from JSON content (NOT hardcoded)

**Before** (current simplified):
```html
<div class="cmp-rating pt-lg-80 pb-lg-80" id="rating" x-data="{ rating: 0, hover: 0 }">
    <div class="card shadow card-wrapper" data-element="feedback">
        ...5 stars with Alpine...
        <div class="cmp-rating__card-second d-none">
            <p class="text-wrap">Grazie...</p>
        </div>
    </div>
</div>
```

**After** (reference-matching structure):
```html
<div class="bg-primary">
    <div class="container">
        <div class="row d-flex justify-content-center bg-primary">
            <div class="col-12 col-lg-6">
                <div class="cmp-rating pt-lg-80 pb-lg-80" id="rating">
                    <div class="card shadow card-wrapper" data-element="feedback">
                        <div class="cmp-rating__card-first">
                            ...5 stars with static active class...
                        </div>
                        <div class="cmp-rating__card-second d-none" data-step="3">
                            <h2 class="title-medium-2-bold mb-0" id="rating-feedback">Grazie...</h2>
                        </div>
                        <div class="form-rating d-none">
                            <div class="d-none" data-step="1">
                                ...fieldset-rating-one...
                                ...fieldset-rating-two...
                            </div>
                            <div class="d-none" data-step="2">
                                ...text input...
                            </div>
                            <div class="d-flex flex-nowrap pt-4 w-100 justify-content-center button-shadow">
                                <button class="btn btn-outline-primary fw-bold me-4 btn-back">Indietro</button>
                                <button class="btn btn-primary fw-bold btn-next" type="submit" form="rating">Avanti</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

**Multilingual**: All button text ("Indietro", "Avanti"), labels, and questions must come from JSON content data, NOT hardcoded in blade. Add to `tests.homepage.json` block-feedback data:
```json
{
    "positive_question": "Quali sono stati gli aspetti che hai preferito?",
    "negative_question": "Dove hai incontrato le maggiori difficoltà?",
    "detail_question": "Vuoi aggiungere altri dettagli?",
    "detail_label": "Dettaglio",
    "detail_help": "Inserire massimo 200 caratteri",
    "back_button": "Indietro",
    "next_button": "Avanti",
    "thank_you": "Grazie, il tuo parere ci aiuterà a migliorare il servizio!",
    "positive_answers": ["Le indicazioni erano chiare", "Le indicazioni erano complete", "Capivo sempre che stavo procedendo correttamente", "Non ho avuto problemi tecnici", "Altro"],
    "negative_answers": ["A volte le indicazioni non erano chiare", "A volte le indicazioni non erano complete", "A volte non capivo se stavo procedendo correttamente", "Ho avuto problemi tecnici", "Altro"]
}
```

---

### 2. `<main>` Container Nesting — HIGH IMPACT

**Current state**: Local wraps ALL sections in `<div class="container" id="main-container">`. Reference puts `id="main-container"` on `<h1>` and each section has its own `.container`.

**Missing reference elements (3)**:

| Missing Element | Count | Element Type |
|----------------|-------|-------------|
| `<main>` (no wrapper div) | 1 | Direct child |
| `<h1 class="visually-hidden" id="main-container">` as direct `<main>` child | 1 | Heading |
| Each section's own `.container` (no outer wrapper) | 1 | Structural |

**Extra local elements (1)**:

| Extra Element | Visual Impact |
|-------------|--------------|
| `<div class="container" id="main-container">` wrapping all sections | **Medium** — causes double-nested containers |

#### Fix Priority: HIGH (~3 elements = ~1% similarity, but improves layout correctness)

**File**: This is controlled by the page component architecture (`x-page` component in Cms module) and the `[slug].blade.php` layout.

**Action**: The `x-page` component or the layout must NOT add an outer `.container` wrapper. Each block's blade view already renders its own `.container`. The fix is:

1. Move `id="main-container"` from outer wrapper to `<h1>` element
2. Remove outer `<div class="container">` that wraps all sections
3. Ensure `<main>` has NO wrapping container — sections render directly inside

**Before**:
```html
<main data-page="homepage">
    <div class="container" id="main-container">
        <h1 class="visually-hidden">Nome del comune</h1>
        <section id="head-section">
            <div class="container">...</div>
        </section>
        ...
    </div>
</main>
```

**After**:
```html
<main>
    <h1 class="visually-hidden" id="main-container">Nome del comune</h1>
    <section id="head-section">
        <div class="container">...</div>
    </section>
    ...
</main>
```

**Where to fix**: The outer container is likely added by the `x-page` component or the CMS page renderer. Check:
- `Modules/Cms/resources/views/components/page.blade.php`
- `Modules/Cms/app/View/Components/Page.php`
- The hero block template that may inject the `<h1>`

---

### 3. Governance/Calendar Section (`#calendario`) — MEDIUM IMPACT

**Missing reference elements (2)**:

| Missing Element | Count | Element Type |
|----------------|-------|-------------|
| `h-100` class NOT on `it-single-slide-wrapper` (reference has plain wrapper) | 7 | Extra class on local |

**Local has correct, reference has different**:

| Element | Reference | Local | Fix |
|---------|-----------|-------|-----|
| Slide wrapper | `it-single-slide-wrapper` | `it-single-slide-wrapper h-100` | Remove `h-100` |

**Additionally**: The `p class="card-text"` elements in the reference have slightly different whitespace formatting (compact vs expanded). This is cosmetic and doesn't affect element count.

#### Fix Priority: MEDIUM (~7 elements = ~1% similarity)

**File**: `Themes/Sixteen/resources/views/components/blocks/governance/calendar.blade.php` (or the carousel partial)

**Action**: Remove `h-100` from `it-single-slide-wrapper` divs in the calendar carousel slides. The last slide (day 21) in reference also lacks `h-100` on `it-single-slide-wrapper` and `card-wrapper`.

**Before**:
```html
<div class="it-single-slide-wrapper h-100">
    <div class="card-wrapper h-100">
```

**After** (reference-matching for most slides):
```html
<div class="it-single-slide-wrapper">
    <div class="card-wrapper">
```

Note: The first 6 slides in reference DO have `h-100` on both wrappers. Only the 7th slide (day 21) lacks them. If your carousel template applies `h-100` uniformly, either:
- Conditionally omit for last slide, OR
- Accept this as minor deviation (only 1 slide differs)

---

### 4. Evidence Section (`.evidence-section`) — LOW IMPACT

**Missing reference elements (3)**:

| Missing Element | Count | Element Type |
|----------------|-------|-------------|
| `mb-2` class on last 4th link of Animale card (local has it, reference doesn't) | 1 | Extra class |
| `text-paragraph-small-semi` class on `<p class="mb-10 text-paragraph-small-semi">` | 1 | Class name match |
| `list-item-title-icon-wrapper` wrapper span on Sport card links (local has it, reference doesn't) | 1 | Extra wrapper |

**Detailed comparison**:

| Element | Reference | Local | Impact |
|---------|-----------|-------|--------|
| Animale 4th link | NO `mb-2` | HAS `mb-2` | Low — extra spacing |
| Sport links | `<span class="text-underline">` | `<span class="list-item-title-icon-wrapper"><span class="text-success">` | Medium — different class names |
| "Visita il sito:" paragraph | `class="mb-10 text-paragraph-small-semi"` | `class="mb-10 text-paragraph-small-semi"` | ✅ Match |

#### Fix Priority: LOW (~3 elements = <1% similarity)

**File**: `Themes/Sixteen/resources/views/components/blocks/topics/highlight.blade.php` (or the topic card partial)

**Action**:
1. Remove `mb-2` from the 4th (last) link in topic cards that have link lists
2. For Sport card links: use `<span class="text-underline">` instead of `<span class="list-item-title-icon-wrapper"><span class="text-success">`

**Before** (Sport links):
```html
<li>
    <a class="list-item active icon-left mb-2" href="#">
        <span class="list-item-title-icon-wrapper">
            <span class="text-success">Tutte le strutture sportive della città</span>
        </span>
    </a>
</li>
```

**After**:
```html
<li>
    <a class="list-item active icon-left mb-2" href="#">
        <span class="text-underline">Tutte le strutture sportive della città</span>
    </a>
</li>
```

**Multilingual**: Link text comes from JSON content. The class name change is in the blade template and affects all languages equally.

---

### 5. Contacts Section (`.bg-grey-card .shadow-contacts`) — MINIMAL IMPACT

**Missing reference elements (2)**:

| Missing Element | Count | Element Type |
|----------------|-------|-------------|
| `data-element="contacts"` on mail link | 1 | Missing attribute |
| `data-element="appointment-booking"` on calendar link | 1 | Missing attribute |

**Local has these attributes** but the comparison shows extra whitespace/newlines around the elements. The structure is identical.

#### Fix Priority: MINIMAL (~2 elements = <1% similarity)

**File**: `Themes/Sixteen/resources/views/components/blocks/contact/homepage.blade.php`

**Action**: Verify `data-element` attributes are present on the correct links. They appear to already be in the JSON content. Ensure the blade template renders them without extra whitespace between attribute and value.

**Before** (with extra whitespace):
```html
<a class="list-item" href="#"  data-element="contacts" >
```

**After** (compact):
```html
<a class="list-item" href="#" data-element="contacts">
```

---

### 6. Useful Links Section — NO FIX NEEDED

**Status**: ✅ Identical to reference. All 6 links, search input, and structure match.

---

### 7. Header — NO FIX NEEDED

**Status**: ✅ Identical structure. Only asset paths differ (`../assets/` vs `/themes/Sixteen/`). SVG sprite `href` vs `xlink:href` difference is cosmetic.

---

### 8. Footer — NO FIX NEEDED

**Status**: ✅ Identical structure, content, and nesting.

---

### 9. Search Modal — NO FIX NEEDED

**Status**: ✅ Identical structure and content.

---

## Priority Order for Fixes

| Priority | Fix | File(s) | Elements Gained | Est. Similarity After |
|----------|-----|---------|-----------------|----------------------|
| **P0** | Add rating multi-step structure | `rating.blade.php` | ~70 | 81% → **90%** |
| **P1** | Fix `<main>` container nesting | `x-page` component or CMS page renderer | ~3 | 90% → **90.5%** |
| **P2** | Remove `h-100` from carousel slides | `governance/calendar.blade.php` | ~7 | 90.5% → **91%** |
| **P3** | Fix evidence section link classes | `topics/highlight.blade.php` | ~3 | 91% → **91.5%** |
| **P4** | Compact contacts whitespace | `contact/homepage.blade.php` | ~2 | 91.5% → **92%** |

---

## Acceptable Deviations (Do NOT Fix)

| Deviation | Reason |
|-----------|--------|
| Alpine.js directives on rating stars | Intentional modernization — `x-model` + `@click` replace Bootstrap JS wizard |
| `href` vs `xlink:href` on SVG `<use>` | `xlink:href` is deprecated in SVG 2. Modern browsers support `href` |
| `<body class="dc-homepage-parity">` | Marker class for CSS scoping — additive, no conflict |
| `<main data-page="homepage">` | Useful for CSS/JS detection — additive |
| Hero row `min-vh-lg-50` / `align-items-center` | Intentional visual enhancement |
| Asset paths (`/themes/Sixteen/...` vs relative) | Correct for theme deployment architecture |
| `<image xlink:href="/themes/Sixteen/images/logo.svg"/>` vs reference `../assets/images/logo-comune.svg` | Same logo, different deployment path |

---

## Implementation Checklist

### P0: Rating Multi-Step Structure

- [ ] Merge `origin/dev` rating blade template into current
- [ ] Verify all 5 star labels have static `active` class
- [ ] Verify all 5 star inputs have NO `x-model`
- [ ] Verify `data-element="feedback-rate-5"` through `feedback-rate-1` on labels
- [ ] Verify `d-none` on `cmp-rating__card-second` with `data-step="3"`
- [ ] Add `form-rating d-none` wrapper
- [ ] Add `data-step="1"` wrapper with `fieldset-rating-one` and `fieldset-rating-two`
- [ ] Add `data-step="2"` wrapper with text input fieldset
- [ ] Add back/next button row
- [ ] Add positive answer texts (5 items from reference)
- [ ] Add negative answer texts (5 items from reference)
- [ ] Add `data-element` attributes on all radio inputs and labels
- [ ] Move all text strings to JSON content (multilingual)
- [ ] Run comparison tool to verify similarity ≥ 90%

### P1: Main Container Nesting

- [ ] Identify where outer `.container` is added (x-page component or CMS renderer)
- [ ] Move `id="main-container"` to `<h1>` element
- [ ] Remove outer `<div class="container">` wrapper
- [ ] Verify visual parity at desktop/tablet/mobile breakpoints
- [ ] Run comparison tool to verify

### P2: Carousel Slide Classes

- [ ] Remove `h-100` from `it-single-slide-wrapper` in calendar template
- [ ] Remove `h-100` from `card-wrapper` in calendar template
- [ ] Run comparison tool to verify

### P3: Evidence Section Link Classes

- [ ] Remove `mb-2` from last link in topic cards with link lists
- [ ] Replace `list-item-title-icon-wrapper` + `text-success` with `text-underline` for Sport links
- [ ] Run comparison tool to verify

### P4: Contacts Whitespace

- [ ] Compact HTML attribute whitespace in contact blade template
- [ ] Run comparison tool to verify

---

## Multilingual Considerations

- **NO hardcoded strings** in any blade template
- All text content MUST come from `tests.homepage.json` content blocks
- The `origin/dev` rating template has hardcoded Italian strings — these must be extracted to JSON
- Button text ("Indietro", "Avanti"), questions, answers, and labels all need JSON keys
- The JSON content structure should support both `it` and `en` variants
- Class names are language-agnostic and do not need translation

---

## Related Documentation

### Internal Links
- [Homepage HTML Analysis](../design-comuni/HOMEPAGE_HTML_ANALYSIS.md) — Previous analysis with section-by-section breakdown
- [HTML Body Comparison Report](../design-comuni/HTML_BODY_COMPARISON_REPORT.md) — Raw comparison data
- [CSS/JS Alignment Plan](../design-comuni/css-js-alignment-plan.md) — CSS and JS parity strategy
- [ARCHITECTURAL_DECISIONS.md](../design-comuni/ARCHITECTURAL_DECISIONS.md) — Design Comuni architecture decisions
- [PAGE_COMPONENT_ARCHITECTURE.md](../../../../Modules/Cms/docs/PAGE_COMPONENT_ARCHITECTURE.md) — CMS page component structure

### Master Index
- ← Torna all'[indice principale Design Comuni](../design-comuni/00-index.md)
- → Vedi [ALL_PAGES_ANALYSIS.md](../design-comuni/ALL_PAGES_ANALYSIS.md) per analisi completa 54 pagine
