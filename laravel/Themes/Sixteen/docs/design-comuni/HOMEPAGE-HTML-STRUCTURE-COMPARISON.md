# Homepage HTML Structure Comparison

**Remote Reference:** https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
**Local Page:** http://127.0.0.1:8000/it/tests/homepage
**Blade Template:** `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`
**Content Source:** `laravel/config/local/fixcity/database/content/pages/tests.homepage.json`
**Date:** 2026-04-07

---

## 1. Overall Architecture

### Remote (Static HTML)

Monolithic single HTML file:
```
<body>
  <div.skiplink>
  <header.it-header-wrapper>
    <div.it-header-slim-wrapper>          (top bar: region link, language, login)
    <div.it-nav-wrapper>
      <div.it-header-center-wrapper>      (brand, socials, search)
      <div.it-header-navbar-wrapper>       (navigation with mega-menu)
  <main>
    <h1.visually-hidden id="main-container">  ("Nome del comune")
    <section#head-section>                     (Hero: news card + image)
    <section#calendario>                       (Governance cards + Events carousel)
    <section.evidence-section>                 (Topics + Thematic sites)
    <section.useful-links-section>             (Search + useful links)
    <div.bg-primary>                           (Feedback rating: 5 stars + multi-step form)
    <div.bg-grey-card>                         (Contacts + report issues)
  </main>
  <div.modal#search-modal>                     (Search modal, outside main)
```

### Local (Blade-rendered)

Component-based architecture driven by JSON content blocks:
```
<body>
  <div.skiplink>
  <x-section slug="header" />
  <main>
    <div class="container" id="main-container">   <-- NOTE: id on container, not H1
      <x-page side="content">
        <x-blocks.hero.homepage>              (Hero section)
        <x-blocks.governance.cards>           (Governance cards + calendar)
        <x-blocks.topics.highlight>           (Topics + thematic sites)
        <x-blocks.search.support-links>       (Search + useful links)
        <x-blocks.feedback.rating>            (Feedback rating, Alpine.js)
        <x-blocks.contact.homepage>           (Contacts)
        <x-blocks.services.homepage>          (EXTRA - not in remote)
        <x-blocks.administration.homepage>    (EXTRA - not in remote)
        <x-blocks.search.final>               (EXTRA - not in remote)
  <x-section slug="search-modal" />
  <x-section slug="footer" tpl="full" />
```

### Key Architectural Differences

| Aspect | Remote | Local |
|--------|--------|-------|
| Layout approach | Monolithic single file | Component-based (10+ blade components) |
| Content source | Hardcoded HTML | JSON-driven content blocks |
| `#main-container` | On `<h1 class="visually-hidden">` | On `<div class="container">` |
| Hero row | `<div class="row">` | `<div class="row align-items-center min-vh-lg-50">` |
| H1 placement | Inside `<main>`, before sections | Inside hero block |
| Services section | NOT present | Extra block |
| Administration section | NOT present | Extra block |
| Final search section | NOT present | Extra block |

---

## 2. Side-by-Side Structure Comparison

### 2.1 Skip Links -- MATCH

Both use identical `visually-hidden-focusable` links to `#main-container` and `#footer`.

### 2.2 Main Container -- DIFFERENT

Remote: `id="main-container"` is on the H1 element inside `<main>`.
Local: `id="main-container"` is on the `<div class="container">` that wraps all block content.

This means skip-link targets differ. The remote skips to the H1; the local skips to the container div.

### 2.3 Hero Section (`#head-section`)

| Element | Remote | Local | Difference |
|---------|--------|-------|------------|
| Row | `<div class="row">` | `<div class="row align-items-center min-vh-lg-50">` | Local adds alignment + min-height |
| Category span | `title-xsmall-semi-bold fw-semibold` | `title-xsmall-semi-bold fw-semibold text-uppercase` | Local adds uppercase |
| News H3 | `card-title` | `card-title text-success` | Local adds green color |
| Chip | `chip chip-simple` | `chip chip-simple chip-success mb-3 d-inline-block` | Local adds color, spacing |
| Read more | `read-more pb-3` | `read-more text-success fw-bold text-uppercase` | Different classes |
| Inline search | NOT present | Has `<div class="cmp-search">` | Local has extra search form |

### 2.4 Governance Cards (`#calendario`)

| Element | Remote | Local | Difference |
|---------|--------|-------|------------|
| Row | `row mb-2` | `row g-4 mb-5` | Different spacing |
| Card wrapper | `card-wrapper px-0 card-overlapping card-teaser-wrapper ...` | MISSING (uses grid columns) | Missing wrapper classes |
| First card body | `card-body p-3 pb-5` | `card-body d-flex flex-row align-items-start p-3` | Local uses flexbox |
| Card title | `card-title text-paragraph-medium u-grey-light` | `card-title title-medium mb-1` | Different typography |
| Card text | `text-paragraph-card u-grey-light m-0` | `card-text text-paragraph-small text-secondary mb-3` | Different color/size |
| Read more | `read-more` | `read-more text-success ps-3` | Local adds color/padding |

### 2.5 Evidence Section (Topics)

| Element | Remote | Local | Difference |
|---------|--------|-------|------------|
| Background div | `section py-5 pb-lg-80 px-lg-5 position-relative` | Same + `evidence-section-header` | Local adds extra class |
| Card wrapper | `card-wrapper card-teaser-wrapper ...` | MISSING (uses grid) | Missing wrapper |
| Thematic sites wrapper | `card-wrapper card-teaser-wrapper ... pb-0` | MISSING (uses grid) | Missing wrapper |

### 2.6 Useful Links -- MATCH

Structure, classes, and IDs match exactly.

### 2.7 Feedback Rating

| Element | Remote | Local | Difference |
|---------|--------|-------|------------|
| Outer wrapper | `bg-primary` | `rating-green-bg` | Different background class |
| Row | `row d-flex justify-content-center bg-primary` | `row justify-content-center` | Missing bg-primary repeat |
| Col | `col-12 col-lg-6` | `col-12 col-lg-10` | Different width |
| Rating wrapper | `cmp-rating pt-lg-80 pb-lg-80` | `cmp-rating` | Missing padding |
| Card | `card shadow card-wrapper` | `card card-wrapper` | Missing shadow |
| Star labels | `full rating-star active` | `full rating-star` + Alpine `:class` | Dynamic vs static |
| Multi-step form | FULL implementation with steps, radio lists, buttons | SIMPLIFIED - stars + subtitle only | Missing entire form |
| Follow-up card | `cmp-rating__card-second d-none` | `cmp-rating__card-second` | Missing d-none |

### 2.8 Contacts -- MATCH

Structure, classes, and data attributes match exactly.

### 2.9 Search Modal -- MATCH

Structure matches. Content is equivalent.

---

## 3. Missing Elements in Local Version

### 3.1 Feedback Rating Multi-Step Form (HIGH PRIORITY)

The remote has a complete multi-step feedback form that the local version lacks entirely:

- `cmp-rating__card-second d-none` with `data-step="3"` (thank-you message)
- `form-rating d-none` container
- Step 1: `fieldset-rating-one` (positive feedback, 5 radio options) and `fieldset-rating-two` (negative feedback, 5 radio options)
- Step 2: text input with `data-element="feedback-input-text"` (max 200 chars)
- Navigation buttons: `btn-back` and `btn-next`
- `data-element` attributes: `feedback-rating-positive`, `feedback-rating-negative`, `feedback-rating-question`, `feedback-rating-answer`, `feedback-input-text`

### 3.2 Card Wrapper Classes

Remote uses these wrapper classes that local does NOT have:
- Governance cards: `card-wrapper px-0 card-overlapping card-teaser-wrapper card-teaser-wrapper-equal card-teaser-block-3`
- Thematic sites: `card-wrapper card-teaser-wrapper card-teaser-wrapper-equal card-teaser-block-3 pb-0`

Local replaces these with Bootstrap grid (`row g-4` + `col-md-6 col-lg-4`).

### 3.3 Missing `shadow` on rating card

Remote: `<div class="card shadow card-wrapper">`
Local: `<div class="card card-wrapper">`

### 3.4 Missing padding on rating wrapper

Remote: `<div class="cmp-rating pt-lg-80 pb-lg-80">`
Local: `<div class="cmp-rating">`

### 3.5 Static `active` class on star labels

Remote: all stars have `class="full rating-star active"`
Local: uses Alpine `:class="{'active': ...}"` which starts with no active class

---

## 4. Extra Elements in Local Version

### 4.1 Services Block

`services/homepage.blade.php` -- NOT in remote. Contains 6 service cards with icons.

### 4.2 Administration Block

`administration/homepage.blade.php` -- NOT in remote. Contains 3 administration category cards.

### 4.3 Final Search Block

`search/final.blade.php` -- NOT in remote. Contains search form with suggestions.

### 4.4 Hero Inline Search

The hero block includes `<div class="cmp-search">` which does not exist in the remote hero.

### 4.5 Alpine.js Directives

`x-data`, `x-model`, `@click`, `@mouseenter`, `@mouseleave`, `:class` on star rating. Remote uses Bootstrap Italia JS instead.

### 4.6 Body Class

Local: `<body class="dc-homepage-parity">` -- Remote: `<body>` (no class)

### 4.7 `min-vh-lg-50` on Hero Row

Local adds this class. Remote does not have it.

---

## 5. CSS Class Differences Summary

### 5.1 Hero
- Local adds: `text-uppercase`, `text-success`, `chip-success`, `mb-3`, `d-inline-block`, `fw-bold`
- Local removes: `pb-3` from read-more

### 5.2 Governance Cards
- Local adds: `g-4`, `d-flex`, `flex-row`, `align-items-start`, `text-success`, `ps-3`, `title-xxsmall-semi-bold`
- Local removes: `px-0`, `card-overlapping`, `card-teaser-wrapper`, `card-teaser-wrapper-equal`, `card-teaser-block-3`, `text-paragraph-medium`, `u-grey-light`

### 5.3 Topics
- Local adds: `evidence-section-header`
- Local removes: `card-wrapper`, `card-teaser-wrapper`, `card-teaser-wrapper-equal`, `card-teaser-block-3`

### 5.4 Feedback Rating
- Local adds: Alpine `x-data`, `x-model`, `@click`, `@mouseenter`, `@mouseleave`, `:class`
- Local removes: `bg-primary`, `d-flex`, `shadow`, `pt-lg-80`, `pb-lg-80`, `active` (on stars), `d-none` (on follow-up)
- Local changes: `col-lg-6` to `col-lg-10`

### 5.5 SVG Icon Paths
- Services/Administration blocks have BROKEN path: `themes/sixteen/themes/Sixteen/...` (double theme prefix)
- Other blocks use correct: `/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-*`

---

## 6. Data Attributes Comparison

| data-element | Remote | Local | Status |
|-------------|--------|-------|--------|
| `main-container` | On H1 | On container div | DIFFERENT |
| `feedback` | On rating card | On rating card | Match |
| `feedback-title` | On H2 | On H2 | Match |
| `feedback-rate-1` to `-5` | On star labels | On star labels | Match |
| `feedback-rating-positive` | On fieldset | MISSING | Local has no step form |
| `feedback-rating-negative` | On fieldset | MISSING | Local has no step form |
| `feedback-rating-question` | On legend span | MISSING | Local has no step form |
| `feedback-rating-answer` | On radio labels | MISSING | Local has no radio options |
| `feedback-input-text` | On text input | MISSING | Local has no text input |
| `contacts` | On contact link | On contact link (dynamic) | Match |
| `appointment-booking` | On contact link | On contact link (dynamic) | Match |

---

## 7. Alpine.js Directives

Local uses Alpine.js for interactive star rating:
```html
<div x-data="{ rating: 0, hover: 0 }">
  <input type="radio" x-model="rating">
  <label @click="rating = 5" @mouseenter="hover = 5" @mouseleave="hover = 0"
         :class="{'active': hover >= 5 || rating >= 5}">
```

Remote relies on Bootstrap Italia JavaScript. The Alpine approach is a valid replacement for star interaction, but the multi-step form logic is NOT implemented.

---

## 8. Recommendations for CSS/JS Fixes

### HIGH PRIORITY

**P1: Fix `id="main-container"` placement**

File: `laravel/Themes/Sixteen/resources/views/components/layouts/app.blade.php`

Remove `id="main-container"` from the container div. The H1 in `hero/homepage.blade.php` already has this ID, which matches the remote.

**P2: Implement multi-step feedback form**

File: `laravel/Themes/Sixteen/resources/views/components/blocks/feedback/rating.blade.php`

Add the complete multi-step form with:
- `cmp-rating__card-second d-none` with `data-step="3"`
- `form-rating d-none` container with step 1 (positive/negative fieldsets) and step 2 (text input)
- Navigation buttons (`btn-back`, `btn-next`)
- All `data-element` attributes
- Alpine.js logic to show positive vs negative fieldset based on star rating (4-5 = positive, 1-3 = negative)

**P3: Fix broken SVG paths in services/administration blocks**

Files:
- `laravel/Themes/Sixteen/resources/views/components/blocks/services/homepage.blade.php` (3 occurrences)
- `laravel/Themes/Sixteen/resources/views/components/blocks/administration/homepage.blade.php` (1 occurrence)

Change `themes/sixteen/themes/Sixteen/...` to `/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-*`

### MEDIUM PRIORITY

**P4:** Add `shadow` class to rating card in `feedback/rating.blade.php`

**P5:** Add `pt-lg-80 pb-lg-80` to rating wrapper in `feedback/rating.blade.php`

**P6:** Change `rating-green-bg` to `bg-primary` on rating outer wrapper, or ensure `rating-green-bg` produces equivalent visual via `@apply`

**P7:** Add card wrapper classes to governance cards: `card-wrapper px-0 card-overlapping card-teaser-wrapper card-teaser-wrapper-equal card-teaser-block-3`

**P8:** Add card wrapper classes to thematic sites: `card-wrapper card-teaser-wrapper card-teaser-wrapper-equal card-teaser-block-3 pb-0`

**P9:** Review whether hero inline search should be kept (enhancement) or removed (parity)

**P10:** Standardize on `href` (not `xlink:href`) for all SVG `<use>` elements

### LOW PRIORITY

**P11:** Document decision on extra blocks (Services, Administration, Final Search)

**P12:** Verify `min-vh-lg-50` on hero row is intentional

**P13:** Verify governance card typography classes produce equivalent visual output

**P14:** Remove `dc-homepage-parity` body class when parity is achieved

---

## Appendix: File Reference

| File | Role |
|------|------|
| `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php` | Entry point for tests pages |
| `laravel/Themes/Sixteen/resources/views/components/layouts/main.blade.php` | Root layout (DOCTYPE, head, body) |
| `laravel/Themes/Sixteen/resources/views/components/layouts/app.blade.php` | App layout (skiplink, header, main, footer) |
| `laravel/Themes/Sixteen/resources/views/components/page.blade.php` | Block renderer |
| `laravel/Themes/Sixteen/resources/views/components/blocks/hero/homepage.blade.php` | Hero block |
| `laravel/Themes/Sixteen/resources/views/components/blocks/governance/cards.blade.php` | Governance + Calendar |
| `laravel/Themes/Sixteen/resources/views/components/blocks/topics/highlight.blade.php` | Topics + Thematic sites |
| `laravel/Themes/Sixteen/resources/views/components/blocks/search/support-links.blade.php` | Search + Useful links |
| `laravel/Themes/Sixteen/resources/views/components/blocks/feedback/rating.blade.php` | Feedback rating |
| `laravel/Themes/Sixteen/resources/views/components/blocks/contact/homepage.blade.php` | Contacts block |
| `laravel/Themes/Sixteen/resources/views/components/blocks/services/homepage.blade.php` | Services grid (extra) |
| `laravel/Themes/Sixteen/resources/views/components/blocks/administration/homepage.blade.php` | Administration grid (extra) |
| `laravel/Themes/Sixteen/resources/views/components/blocks/search/final.blade.php` | Final search (extra) |
| `laravel/Themes/Sixteen/resources/views/components/sections/search-modal.blade.php` | Search modal |
| `laravel/config/local/fixcity/database/content/pages/tests.homepage.json` | Content data |

---

*All paths are relative to the project root at `/var/www/_bases/base_fixcity_fila5`.*
