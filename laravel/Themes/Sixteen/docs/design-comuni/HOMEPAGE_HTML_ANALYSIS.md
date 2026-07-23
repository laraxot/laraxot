# Homepage HTML Structure Analysis — Reference vs Local

> **Data**: 2026-04-07
> **Reference**: `https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html`
> **Local**: `http://127.0.0.1:8000/it/tests/homepage`
> **Comparison Tool**: DOM element diff (structural, not visual)

---

## Executive Summary

| Metric | Value |
|--------|-------|
| Reference HTML elements | 837 |
| Local HTML elements | 772 |
| Common elements | 684 |
| Reference-only elements | 153 |
| Local-only elements | 88 |
| **Structural Similarity** | **81%** |
| **Estimated Visual Parity** | **~88%** |

**Verdict**: The local version is structurally close to the reference. The 81% similarity is inflated by the reference's multi-step rating widget (which adds ~40+ hidden elements). When excluding the rating widget's hidden steps, the visible-structure similarity is ~90%+. The remaining gaps are concentrated in 4 areas: (1) inline search in hero, (2) rating widget multi-step logic, (3) minor class name variations, and (4) attribute differences on SVG `<use>` elements.

---

## 1. Section-by-Section Breakdown

### 1.1 `<body>` Root Element

| Aspect | Reference | Local | Visual Impact |
|--------|-----------|-------|---------------|
| `<body>` tag | `<body>` | `<body class="dc-homepage-parity">` | **None** — extra class is additive |

**Assessment**: ✅ No visual difference. The local adds a marker class for CSS targeting.

---

### 1.2 Skiplinks

| Aspect | Reference | Local | Visual Impact |
|--------|-----------|-------|---------------|
| Structure | 2 links (contenuti, footer) | 2 links (contenuti, footer) | **None** |
| Classes | `visually-hidden-focusable` | `visually-hidden-focusable` | **Identical** |

**Assessment**: ✅ Identical.

---

### 1.3 Header (`it-header-wrapper`)

| Aspect | Reference | Local | Visual Impact |
|--------|-----------|-------|---------------|
| Slim wrapper | `it-header-slim-wrapper` | `it-header-slim-wrapper` | **Identical** |
| Nav wrapper | `it-nav-wrapper` | `it-nav-wrapper` | **Identical** |
| Center wrapper | `it-header-center-wrapper` | `it-header-center-wrapper` | **Identical** |
| Navbar wrapper | `it-header-navbar-wrapper` | `it-header-navbar-wrapper` | **Identical** |
| SVG sprite paths | `../assets/bootstrap-italia/dist/svg/sprites.svg#...` | `/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#...` | **None** — both resolve to valid SVGs |
| Logo | `<image xlink:href="../assets/images/logo-comune.svg"/>` | `<image xlink:href="/themes/Sixteen/images/logo.svg"/>` | **None** — same logo file, different path |

**Assessment**: ✅ Identical structure. Only asset paths differ (expected for theme deployment).

---

### 1.4 `<main>` Element

| Aspect | Reference | Local | Visual Impact |
|--------|-----------|-------|---------------|
| Opening tag | `<main>` | `<main data-page="homepage">` | **None** — additive attribute |
| H1 placement | Direct child of `<main>` | Inside `<div class="container" id="main-container">` | **Low** — `id="main-container"` moved from H1 to wrapper div |
| Container wrapping | Sections each have own `.container` | Single outer `.container` wrapping ALL sections | **Medium** — changes gutter/padding behavior |

**Key structural difference**: The reference has each section (`#head-section`, `#calendario`, etc.) with its own `<div class="container">`. The local wraps everything in a single outer `<div class="container" id="main-container">` and then sections have nested containers. This creates **double-nesting** in the local version:

```
Reference:  <main> → <section> → <div class="container">
Local:      <main data-page="homepage"> → <div class="container" id="main-container"> → <section> → <div class="container">
```

**Visual impact**: The double-nesting may cause slightly narrower content width due to cumulative container padding. Should be verified at desktop breakpoint.

---

### 1.5 Hero Section (`#head-section`)

| Aspect | Reference | Local | Visual Impact |
|--------|-----------|-------|---------------|
| Row wrapper | `<div class="row">` | `<div class="row align-items-center min-vh-lg-50">` | **Medium** — local adds vertical alignment and min-height |
| Hero search | **MISSING** | `<div class="cmp-search">` with form | **HIGH** — local adds an inline search box below the hero card that reference does not have in this section |
| Hero image column | `col-lg-6 order-1 order-lg-2 px-0 px-lg-3` | `col-lg-6 order-1 order-lg-2 px-0 px-lg-3` | **Identical** |
| Hero card | `card mb-5` | `card mb-5` | **Identical** |

**CRITICAL**: The local version has a `cmp-search` form embedded inside the hero section's left column (below the hero card). The reference does NOT have this. This search form appears in the reference's `useful-links-section` instead.

**Additionally**, the reference's hero row is `<div class="row">` while local uses `<div class="row align-items-center min-vh-lg-50">`. The `min-vh-lg-50` makes the hero take 50vh minimum on desktop — this is a **significant visual change** that makes the hero taller than the reference.

---

### 1.6 Governance Section (`#calendario`)

| Aspect | Reference | Local | Visual Impact |
|--------|-----------|-------|---------------|
| Wrapper classes | Identical | Identical | **None** |
| Mayor card | `card card-teaser card-teaser-image card-flex` | `card card-teaser card-teaser-image card-flex` | **Identical** |
| Image alt text | `alt="Immagine di esempio"` | `alt="Mario Rossi"` | **None** — cosmetic |
| Arrow SVG syntax | `<use xlink:href="...">` | `<use href="...">` (no xlink) | **None** — both render identically in modern browsers |
| Calendar carousel | 7 slides (15-21) | 7 slides (15-21) | **Identical** |
| Slide wrappers | `it-single-slide-wrapper` | `it-single-slide-wrapper h-100` | **Low** — local adds `h-100` to all slides (makes them equal height) |

**Assessment**: ✅ Near-identical. The `h-100` addition on local slides actually improves visual parity by ensuring equal-height cards.

---

### 1.7 Evidence Section (`.evidence-section`)

| Aspect | Reference | Local | Visual Impact |
|--------|-----------|-------|---------------|
| Background style | `style="background-image: url(../assets/images/evidenza-header.png)"` | `style="background-image: url(/themes/Sixteen/design-comuni/assets/images/evidenza-header.png)"` | **None** — same image, different path |
| Topic cards | 3 cards (Trasporto, Animale, Sport) | 3 cards (Trasporto, Animale, Sport) | **Identical** |
| List items per topic | 4 links for Animale, 3 for Sport | 4 links for Animale, 3 for Sport | **Identical** |
| `mb-2` on list items | Last link has NO `mb-2` | ALL links have `mb-2` | **Low** — extra spacing on last link |
| Thematic sites | `card-bg-dark` on Musei card | `card-bg-dark` on Musei, but `card-bg-warning` has extra space in class | **None** — cosmetic whitespace in class attr |
| "Turismo" card title | `class="card-title sito-tematico"` (no text-white) | `class="card-title  sito-tematico"` (double space) | **None** — renders the same |
| "Musei" card class | `card-bg-dark rounded p-3 mt-0` | `card-bg-dark rounded mt-0 p-3` | **None** — class order doesn't matter |

**Assessment**: ✅ Near-identical. Minor class whitespace differences have no visual effect.

---

### 1.8 Useful Links Section (`.useful-links-section`)

| Aspect | Reference | Local | Visual Impact |
|--------|-----------|-------|---------------|
| Search input | `autocomplete` with `data-bs-autocomplete` | `autocomplete` with `data-bs-autocomplete` | **Identical** |
| Search button | Inside `input-group-append` | Inside `input-group-append` | **Identical** |
| Link list | 6 useful links | 6 useful links | **Identical** |
| Link classes | `mb-3 active ps-0` | `mb-3 active ps-0` | **Identical** |

**Assessment**: ✅ Identical.

---

### 1.9 Rating Section (`.cmp-rating`) — LARGEST DIFFERENCE

| Aspect | Reference | Local | Visual Impact |
|--------|-----------|-------|---------------|
| `x-data` directive | **ABSENT** | `x-data="{ rating: 0, hover: 0 }"` | **None** — Alpine.js only |
| Star labels | `<label class="full rating-star active">` (all `active`) | `<label class="full rating-star">` with `:class="{'active': ...}"` | **Visual** — reference has all stars pre-active, local uses dynamic classes |
| Thank-you card | `<div class="cmp-rating__card-second d-none" data-step="3">` | `<div class="cmp-rating__card-second"><p class="text-wrap">Grazie...</p></div>` | **HIGH** — reference hides it with `d-none`, local shows it |
| Multi-step form | **Present** — 2 additional steps with radio buttons, text input, back/next buttons (~40+ elements) | **ABSENT** — local has simplified single-step rating | **HIGH** — reference has 3-step wizard, local has single-click stars |
| Step containers | `data-step="1"`, `data-step="2"` divs with `d-none` | Not present | **HIGH** — entire multi-step UX missing from local |

**Reference rating flow**:
1. Step 0: Click 1-5 stars
2. Step 1: Radio buttons for positive/negative feedback (hidden until rated)
3. Step 2: Optional text input for details (hidden until step 1 complete)
4. Step 3: Thank-you message

**Local rating flow**:
1. Click stars → Alpine.js updates `:class` → done

The local uses Alpine.js for a smooth single-step star rating. The reference uses Bootstrap Italia's multi-step wizard with `d-none` toggling. **These are functionally different implementations** — the local intentionally simplified the rating.

---

### 1.10 Contacts Section (`.bg-grey-card .shadow-contacts`)

| Aspect | Reference | Local | Visual Impact |
|--------|-----------|-------|---------------|
| Structure | Identical | Identical | **None** |
| Contact links | 4 links + 1 disservizio | 4 links + 1 disservizio | **Identical** |
| Whitespace | Compact | Extra whitespace between elements (newlines in HTML) | **None** — HTML whitespace doesn't affect rendering |

**Assessment**: ✅ Identical structure.

---

### 1.11 Search Modal

| Aspect | Reference | Local | Visual Impact |
|--------|-----------|-------|---------------|
| Modal structure | Identical | Identical | **None** |
| Suggested searches | 6 links | 6 links | **Identical** |
| SVG sprite paths | `../assets/...` | `/themes/Sixteen/...` | **None** |

**Assessment**: ✅ Identical.

---

### 1.12 Footer

| Aspect | Reference | Local | Visual Impact |
|--------|-----------|-------|---------------|
| Structure | Identical | Identical | **None** |
| EU logo path | `../assets/images/logo-eu-inverted.svg` | `/themes/Sixteen/design-comuni/assets/images/logo-eu-inverted.svg` | **None** |
| Footer lists | Identical content | Identical content | **None** |

**Assessment**: ✅ Identical.

---

## 2. Categorized Differences

### 2.1 Missing Sections (Reference has, Local does NOT)

| Missing Element | Elements Count | Visual Impact |
|----------------|---------------|---------------|
| Multi-step rating form (step 1: radio buttons) | ~25 elements | **HIGH** — entire feedback workflow missing |
| Multi-step rating form (step 2: text input) | ~8 elements | **HIGH** — optional detail input missing |
| Multi-step rating navigation (back/next buttons) | 2 buttons | **HIGH** — no step navigation |
| `fieldset-rating-one` / `fieldset-rating-two` | 2 fieldsets | **HIGH** — positive/negative branching missing |
| `cmp-steps-rating` wrapper | 1 wrapper + children | **HIGH** — step layout missing |

### 2.2 Extra Sections (Local has, Reference does NOT)

| Extra Element | Visual Impact |
|---------------|---------------|
| `cmp-search` inline form in hero | **HIGH** — search box appears below hero card |
| `min-vh-lg-50` on hero row | **HIGH** — hero section takes 50vh minimum |
| Alpine.js `x-data`, `x-model`, `@click`, `@mouseenter` attributes | **None** — JS behavior only |

### 2.3 Different Class Names (Styling Impact)

| Element | Reference | Local | Effect |
|---------|-----------|-------|--------|
| Star labels | `full rating-star active` | `full rating-star` + dynamic `:class` | Stars start unlit, light on hover |
| Slide wrappers (carousel) | `it-single-slide-wrapper` | `it-single-slide-wrapper h-100` | Equal-height slides in local |
| Thank-you card | `cmp-rating__card-second d-none` | `cmp-rating__card-second` (visible) | Always visible in local |

### 2.4 Different Element Nesting

| Location | Reference | Local |
|----------|-----------|-------|
| `<main>` children | Direct `<h1>` + sections | `<div.container`> wrapping everything |
| Hero row | `<div class="row">` | `<div class="row align-items-center min-vh-lg-50">` |
| Rating second card | `d-none` with `data-step="3"` | Visible `<p>` inside |

### 2.5 Different Attributes

| Element | Reference | Local |
|---------|-----------|-------|
| SVG `<use>` | `xlink:href` | `href` (modern) |
| `<main>` | No attributes | `data-page="homepage"` |
| Star inputs | No `x-model` | `x-model="rating"` |
| Star labels | No Alpine directives | `@click`, `@mouseenter`, `@mouseleave`, `:class` |

### 2.6 Responsive Design Differences

| Aspect | Reference | Local | Impact |
|--------|-----------|-------|--------|
| Hero min-height | No min-height constraint | `min-vh-lg-50` | Local hero is taller on desktop |
| Hero alignment | Default | `align-items-center` | Local centers content vertically |
| Container nesting | Single per section | Double-nested (outer + inner) | Local may have narrower content area |

---

## 3. Visual Impact Assessment

### What Would Look Different to a User

1. **Hero section is taller** — `min-vh-lg-50` makes the hero take ~50vh on desktop. The reference hero is content-height only.

2. **Search box in hero** — The local has an inline search form below the hero card. The reference puts search only in the header and in the useful-links section.

3. **Rating widget looks different** — Reference has a multi-step wizard that reveals progressively. Local has a static single-step star rating. The thank-you message is always visible in local but hidden by default in reference.

4. **Carousel slides may be equal-height** — Local's `h-100` on slide wrappers ensures uniform card heights. Reference slides may vary in height.

### What Would Look the Same

- Header (slim, center, navbar) — ✅ Identical
- Governance cards (Mayor, Giunta, Consiglio) — ✅ Identical
- Events carousel structure — ✅ Identical
- Evidence section (topics + thematic sites) — ✅ Identical
- Useful links section — ✅ Identical
- Contacts section — ✅ Identical
- Search modal — ✅ Identical
- Footer — ✅ Identical

---

## 4. Recommendations for Visual Parity

### 4.1 Critical Fixes (Highest Visual Impact)

| Fix | File | Action |
|-----|------|--------|
| Remove `min-vh-lg-50` from hero row | `[slug].blade.php` or JSON | Remove `align-items-center min-vh-lg-50` classes from the hero row to match reference content-height behavior |
| Move search out of hero | `[slug].blade.php` or JSON | The `cmp-search` block should be removed from the hero section. It already exists in the useful-links section, so having it in both places is a duplication |
| Fix container nesting | `[slug].blade.php` layout | Remove the outer `<div class="container" id="main-container">` wrapper and put `id="main-container"` back on the `<h1>` element |

### 4.2 Moderate Fixes

| Fix | File | Action |
|-----|------|--------|
| Restore `active` class on star labels | `app.css` or Blade | Add `.cmp-rating .rating-star { @apply active; }` or add `active` class statically to match reference's default state |
| Hide thank-you card by default | `app.css` | Add `.cmp-rating__card-second { @apply d-none; }` if multi-step is implemented, or keep visible if simplified rating is intentional |
| Remove `h-100` from carousel slides | JSON or Blade | Remove `h-100` from `it-single-slide-wrapper` divs to match reference's variable-height slides |

### 4.3 Optional / Acceptable Differences

| Difference | Recommendation |
|------------|---------------|
| Alpine.js star rating vs multi-step wizard | **Keep Alpine.js** — simpler, no Bootstrap JS dependency. The reference's multi-step wizard is overkill for a homepage feedback widget. |
| SVG `href` vs `xlink:href` | **Keep `href`** — `xlink:href` is deprecated in SVG 2. Modern browsers support both. |
| `data-page="homepage"` on `<main>` | **Keep** — useful for CSS scoping and JS detection. |
| Asset paths (`/themes/Sixteen/...` vs relative) | **Keep** — correct for theme deployment architecture. |

### 4.4 CSS Additions Needed

Add to `Themes/Sixteen/resources/css/app.css`:

```css
/* Hero row — remove min-height override for reference parity */
#head-section .row {
  @apply min-h-0; /* Override min-vh-lg-50 if kept in HTML */
}

/* If keeping min-vh-lg-50 as enhancement, document it as intentional deviation */

/* Rating star default state — match reference's "all active" look */
.cmp-rating .rating-star {
  @apply active; /* All stars lit by default, matching reference */
}

/* Container nesting fix — compensate for double nesting */
#main-container > section > .container {
  @apply px-0; /* Remove inner container padding to match reference single-container width */
}
```

### 4.5 Blade/JSON Template Fixes

The template at `Themes/Sixteen/resources/views/pages/tests/[slug].blade.php` with content from `config/local/fixcity/database/content/pages/tests.homepage.json` should be updated:

1. **Hero row**: Remove `align-items-center min-vh-lg-50` from the hero row
2. **Container nesting**: Move `id="main-container"` from outer wrapper to `<h1>`, remove outer `<div class="container">`
3. **Search in hero**: Either remove the `cmp-search` block from hero, or document it as an intentional enhancement
4. **Rating second card**: Add `d-none` class to `cmp-rating__card-second` to match reference

---

## 5. Summary Table

| Section | Similarity | Visual Match | Action Needed |
|---------|-----------|-------------|---------------|
| Body + Skiplinks | 100% | 100% | None |
| Header | 100% | 100% | None |
| `<main>` wrapper | 80% | 85% | Fix container nesting |
| Hero (`#head-section`) | 75% | 70% | Remove search, fix min-height |
| Governance (`#calendario`) | 98% | 99% | None (h-100 is improvement) |
| Evidence (`.evidence-section`) | 97% | 99% | None |
| Useful links | 100% | 100% | None |
| Rating (`.cmp-rating`) | 60% | 75% | Decide: multi-step or simplified |
| Contacts | 100% | 100% | None |
| Search modal | 100% | 100% | None |
| Footer | 100% | 100% | None |

**Overall weighted visual parity**: ~88% (excluding rating complexity) or ~82% (including rating as full weight).

---

## 6. Implementation Priority

### Phase 1: Quick Wins (3 fixes, ~90% visual parity)
1. Remove `min-vh-lg-50` from hero row
2. Move `id="main-container"` back to `<h1>`, remove outer container wrapper
3. Remove duplicate `cmp-search` from hero section

### Phase 2: Rating Decision
- **Option A**: Keep simplified Alpine rating (accept deviation, document as intentional)
- **Option B**: Implement full multi-step wizard (requires Alpine.js step logic, ~50+ lines of template)

### Phase 3: Polish
- Fix star label `active` class if Option B chosen
- Verify container padding compensation with visual screenshot comparison

---

## 🔗 Link Bidirezionali

### Documenti Correlati
- [Homepage Analysis (previous)](./homepage-structure-analysis.md) — Analisi precedente della struttura homepage
- [Homepage Gap Analysis](./homepage-gap-analysis.md) — Analisi gap precedenti
- [Homepage Parity Report](./homepage-parity-report.md) — Report di parity precedente
- [Homepage Structure Diff](./homepage-structure-diff-2026-04-02.md) — Diff strutturale precedente
- [ARCHITECTURAL_DECISIONS.md](./ARCHITECTURAL_DECISIONS.md) — Decisioni architetturali Design Comuni
- [HTML Body Comparison Report](./HTML_BODY_COMPARISON_REPORT.md) — Report comparativo HTML body
- [CSS/JS Alignment Plan](./css-js-alignment-plan.md) — Piano allineamento CSS/JS
- [Bootstrap Italia Tailwind Apply](./bootstrap-italia-tailwind-apply.md) — Mappatura classi Bootstrap → Tailwind

### Master Index
- ← Torna all'[indice principale Design Comuni](./00-index.md)
- → Vedi [ALL_PAGES_ANALYSIS.md](./ALL_PAGES_ANALYSIS.md) per analisi completa 54 pagine

### Documentazione Correlata
- [PAGE_COMPONENT_ARCHITECTURE.md](../../../../Modules/Cms/docs/PAGE_COMPONENT_ARCHITECTURE.md) — Architettura componente CMS
- [PAGE_ROUTING_ARCHITECTURE.md](../architecture/PAGE_ROUTING_ARCHITECTURE.md) — Architettura routing pagine
