# Homepage HTML Structure Comparison Analysis

**Date:** 2026-04-07
**Reference:** https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
**Local:** http://127.0.0.1:8000/it/tests/homepage
**Screenshots:** [ref-homepage.png](./screenshots/ref-homepage.png) | [local-homepage.png](./screenshots/local-homepage.png)

## Summary

| Metric | Reference | Local | Delta |
|--------|-----------|-------|-------|
| Body HTML Length | 74,575 chars | 111,736 chars | +50% |
| Total Tags | 1,610 | 2,026 | +26% |
| Sections | 8 | 8 | ✓ Same |

## Architecture

The local page is rendered through this pipeline:
1. **Route:** `/it/tests/homepage` → `pages/tests/[slug].blade.php`
2. **Layout:** `<x-layouts.app>` → `pub_theme::layouts.app`
3. **Page Component:** `<x-page side="content" :slug="$pageSlug" :data="$data" />`
4. **JSON Content:** `config/local/fixcity/database/content/pages/tests.homepage.json`
5. **Blocks:** JSON `content_blocks` → individual blade views via `@include($block->view)`

## Section-by-Section Analysis

### 1. Skip Links ✓ MATCH
Both have identical structure:
```html
<div class="skiplink">
    <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
    <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
</div>
```

### 2. Header ~ DIFFERENCES
Structure is identical but with these differences:

| Element | Reference | Local | Impact |
|---------|-----------|-------|--------|
| Login button classes | `btn btn-primary btn-icon btn-full` | `btn btn-outline-light btn-icon` | Visual: different button style |
| Login button text | `<span class="d-none d-lg-block">Accedi...</span>` | No text, icon only | Visual: missing label on desktop |
| Login button wrapper | No `aria-label` | Has `aria-label="Accedi..."` | Accessibility: good addition |
| Navbar toggler | No extra button | `custom-navbar-toggler` added | Visual: extra burger menu on mobile |
| SVG sprite paths | `../assets/bootstrap-italia/...` | `/themes/Sixteen/design-comuni/assets/...` | Functional: correct for local |
| Logo source | `../assets/images/logo-comune.svg` | `/themes/Sixteen/images/logo.svg` | Visual: different logo file |
| Icon use href | `<use xlink:href="...">` (some) | Mixed `<use href>` and `<use xlink:href>` | Minor: inconsistent |

**Header Structure (both):**
```
header.it-header-wrapper
├── .it-header-slim-wrapper (dark green #00402B)
│   └── .it-header-slim-wrapper-content
│       ├── .navbar-brand (Regione link)
│       └── .it-header-slim-right-zone
│           ├── Language dropdown
│           └── Login button
├── .it-nav-wrapper
│   └── .it-header-center-wrapper (green #007A52)
│       └── .it-header-center-content-wrapper
│           ├── .it-brand-wrapper (logo + title)
│           └── .it-right-zone
│               ├── .it-socials
│               └── .it-search-wrapper
└── .it-header-navbar-wrapper (green #007A52)
    └── .navbar-collapsable (megamenu)
```

### 3. Main Content Sections ✓ SAME STRUCTURE

Both have the same 8 sections in order:

| # | Section ID | Reference Tags | Local Tags | Status |
|---|------------|---------------|------------|--------|
| 1 | `#head-section` (Hero) | 85 | 88 | ~ Minor |
| 2 | `#calendario` (Calendar) | 280 | 290 | ~ Minor |
| 3 | `.evidence-section` (Topics) | 380 | 410 | ~ Extra wrappers |
| 4 | `.useful-links-section` (Search) | 95 | 92 | ✓ Close |
| 5 | `#rating` (Feedback) | 220 | 240 | ~ Alpine.js extras |
| 6 | `.bg-grey-card` (Contacts) | 110 | 115 | ~ Minor |
| 7 | Servizi (Services) | 140 | 155 | ~ Extra classes |
| 8 | Amministrazione (Admin) | 120 | 130 | ~ Extra classes |

#### 3.1 Hero Section (#head-section)
**Reference:**
```html
<section id="head-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body pb-5 px-0">
                        <div class="category-top">...</div>
                        <a><h3 class="card-title text-success">...</h3></a>
                        <p class="mb-4 pt-3 lora">...</p>
                        <a class="chip chip-simple chip-green">...</a>
                        <a class="read-more read-more-green pb-3">...</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 px-0">
                <img src="..." class="img-fluid">
            </div>
        </div>
    </div>
</section>
```

**Local:** Same structure but:
- `h3.card-title` has NO `text-success` class
- `chip` has NO `chip-green` class  
- `read-more` has NO `read-more-green` class
- Extra `d-block` wrapper around read-more

#### 3.2 Calendar Section (#calendario)
**Structure matches** but local has:
- Extra `section-muted`, `pb-90`, `pb-lg-50`, `px-lg-5`, `pt-0` spacing classes
- Calendar wrapper has extra Splide state classes (`is-overflow`, `splide--slide`, etc.)
- Local slide wrappers have `h-100` on more elements

#### 3.3 Evidence Section (Topics)
**Reference:** `<section class="evidence-section">`
**Local:** Same but with inline style `style="background: linear-gradient(...)"`

Local has extra card wrappers:
- `card-wrapper`, `card-teaser-wrapper`, `card-teaser-wrapper-equal`, `card-teaser-block-3`
- These are NOT in the reference HTML

#### 3.4 Useful Links Section
**Structure matches well.** Minor class differences:
- Reference: `input-group-prepend` / Local: same
- Reference: `form-control autocomplete` / Local: same

#### 3.5 Feedback/Rating Section
**Reference:** Static HTML with radio buttons and labels
**Local:** Same structure PLUS Alpine.js `x-data`, `x-model`, `@click`, `:class` bindings for interactivity

#### 3.6 Contacts Section
**Reference:** `<div class="bg-grey-card shadow-contacts">`
**Local:** Same structure ✓

#### 3.7 Services Section
**Reference:** Direct HTML with `it-grid-item-wrapper`
**Local:** Same structure with `@foreach` loop

#### 3.8 Administration Section
**Reference:** Direct HTML
**Local:** Same structure with `@foreach` loop

### 4. Footer ✓ SAME STRUCTURE
Both have identical footer structure with:
- `.it-footer-main` with institutional links
- `.it-footer-secondary` with EU logo and credits
- Same number of tag groups (154 each)

## Key Findings

### Structural Differences (HTML)
1. **Extra wrapper divs** in local for topics section (+4 wrapper classes per card)
2. **Missing color classes** on hero elements (`text-success`, `chip-green`, `read-more-green`)
3. **Login button** different style and missing desktop text
4. **Custom navbar toggler** added in local

### CSS Differences (Visual)
1. Hero card title color not green
2. Chip not green-bordered
3. Read-more link not green
4. Evidence section cards have extra wrappers that affect spacing
5. Calendar section has extra spacing classes

### What's Working ✓
- Overall section order and hierarchy matches
- Skip links identical
- Footer structure identical
- Calendar carousel structure matches
- Contact section structure matches
- Search section structure matches

## Action Plan

### Priority 1: Hero Section Colors
- Add `text-success` (or CSS equivalent) to hero `h3.card-title`
- Add `chip-green` class or CSS override for the tag chip
- Add `read-more-green` class or CSS override for "Tutte le novità" link

### Priority 2: Login Button
- Change from `btn-outline-light` to `btn-primary btn-full`
- Add `<span class="d-none d-lg-block">Accedi all'area personale</span>`

### Priority 3: Evidence Section Cards
- Remove extra wrapper classes or CSS-normalize them
- Ensure card spacing matches reference

### Priority 4: Calendar Section
- Verify spacing matches reference
- Ensure carousel slide layout matches

## Files Involved

### Blade Templates
- `resources/views/pages/tests/[slug].blade.php` - Route handler
- `resources/views/components/page.blade.php` - Block assembler
- `resources/views/components/blocks/hero/homepage.blade.php` - Hero section
- `resources/views/components/blocks/governance/cards.blade.php` - Calendar
- `resources/views/components/blocks/topics/highlight.blade.php` - Topics
- `resources/views/components/blocks/search/support-links.blade.php` - Search
- `resources/views/components/blocks/feedback/rating.blade.php` - Rating
- `resources/views/components/blocks/contact/homepage.blade.php` - Contacts
- `resources/views/components/blocks/services/homepage.blade.php` - Services
- `resources/views/components/blocks/administration/homepage.blade.php` - Admin

### CSS Files
- `resources/css/app.css` - Main stylesheet (2514 lines)
- `resources/css/style-apply.css` - Bootstrap Italia → Tailwind mapping
- `resources/css/design-comuni-global.css` - Global Design Comuni styles
- `resources/css/design-comuni-visual-fix.css` - Visual fixes
- `resources/css/homepage-parity-v2.css` - Homepage parity fixes

### Content
- `config/local/fixcity/database/content/pages/tests.homepage.json` - Page content

## Related Documentation
- [Design Comuni Block Analysis](../../../../_bmad-output/design-comuni-block-analysis.md)
- [Page Component Architecture](../../../../Modules/Cms/docs/PAGE_COMPONENT_ARCHITECTURE.md)
- [Page Routing Architecture](../architecture/PAGE_ROUTING_ARCHITECTURE.md)

## CSS Fix Progress Report (2026-04-07)

### Structural HTML Fixes ✓
1. **Extra container wrapper removed** - `<div class="container">` was wrapping `{{ $slot }}` in `app.blade.php` ✓
2. **Hero section class fixed** - `class="section"` → `class=""` matching reference ✓
3. **Sections are now direct children of `<main>`** - No extra wrappers ✓
4. **Body class** - `dc-homepage-parity` correctly applied ✓

### Verified CSS Fixes ✓
1. **Hero section colors** - Title, chip, and read-more link are now green (#007A52) ✓
2. **Rating block** - White card on green background, proper star sizing (24px) ✓
3. **Login button** - White background with green text/icon ✓
4. **Evidence section** - Green links, proper card styling ✓
5. **Governance cards** - 3-column layout, green "VAI ALLA PAGINA" ✓
6. **Events carousel** - Visibility fixed, proper date card styling ✓
7. **Siti tematici** - Colored cards (blue/warning/dark) with white text ✓
8. **Contacts section** - Green links with icons ✓
9. **Footer** - Dark navy (#202A2E) background, white text ✓

### Content Verification ✓
The "Animale domestico" topic card IS present with all 4 subordinate links:
- "Come adottare un cane al Canile Municipale"
- "Elenco delle aree per cani"
- "Come segnalare una colonia felina e ricevere il tesserino"
- "Come segnalare lo smarrimento del proprio animale"

### Computed Style Verification
| Element | Expected | Actual | Status |
|---------|----------|--------|--------|
| Hero title color | rgb(0, 122, 82) | rgb(0, 122, 82) | ✓ |
| Chip color | rgb(0, 122, 82) | rgb(0, 122, 82) | ✓ |
| Read-more color | rgb(0, 122, 82) | rgb(0, 122, 82) | ✓ |
| Body class | dc-homepage-parity | dc-homepage-parity page-tests page-tests-homepage | ✓ |
| Hero section class | "" (empty) | "" (empty) | ✓ |
| Main > section structure | Direct child | Direct child | ✓ |

### Files Modified
- `resources/views/components/layouts/app.blade.php` - Removed extra container wrapper
- `resources/views/components/blocks/hero/homepage.blade.php` - Fixed section class

### Remaining Work
- Fine-tune responsive breakpoints for mobile/tablet
- Verify JS interactivity (Alpine.js rating, carousel)
- Cross-browser testing

### Screenshots
- **Before:** [ref-homepage.png](screenshots/ref-homepage.png) | [local-homepage.png](screenshots/local-homepage.png)
- **After:** [local-homepage-after-fix.png](screenshots/local-homepage-after-fix.png)
- **Section screenshots:** hero, calendar, evidence, rating, contacts, footer (all in screenshots/)
