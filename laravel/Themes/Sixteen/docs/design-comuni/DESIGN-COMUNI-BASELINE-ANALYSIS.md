# Design Comuni Baseline Analysis Report

**Date:** 2026-04-04  
**Status:** Baseline complete  
**Tool:** `bashscripts/design-comuni-compare/html-diff.js`

---

## Executive Summary

We compared **16 Design Comuni pages** between the reference (italia.github.io) and local implementation (127.0.0.1:8000) using DOM-based structural analysis.

| Metric | Value |
|--------|-------|
| Pages Compared | 16 |
| Average Similarity | **29%** |
| Good Pages (>=90%) | 1 (homepage) |
| Fair Pages (70-89%) | 3 (servizi, lista-categorie, amministrazione, lista-risorse) |
| Critical Pages (<70%) | 11 |

---

## Page Categories by Similarity

### ✅ GOOD (>=90%) — 1 page

| Page | Similarity | Ref Tags | Local Tags | Missing Classes | Extra Classes |
|------|-----------|----------|------------|-----------------|---------------|
| homepage | **92%** | 844 | 780 | 44 | 23 |

**Analysis:** Homepage is very close. Missing classes are mainly:
- Splide slider classes (carousel pagination/arrows)
- Rating component classes (Bootstrap Italia stars)
- Form/radio list classes (step-based rating form)
- A few `<fieldset>`, `<legend>`, `<small>` tags missing

### 🟡 FAIR (70-89%) — 4 pages

| Page | Similarity | Ref Tags | Local Tags | Missing Classes | Extra Classes |
|------|-----------|----------|------------|-----------------|---------------|
| lista-risorse | 86% | 690 | 601 | 83 | 36 |
| amministrazione | 81% | 603 | 425 | 64 | 9 |
| lista-categorie | 78% | 640 | 409 | 69 | 6 |
| servizi | 74% | 730 | 512 | 96 | 72 |

**Analysis:** These pages have reasonable structure but are missing significant chunks of Bootstrap Italia classes. Common patterns:
- Missing card/teaser layout classes
- Missing list item styling classes
- Missing pagination components
- Missing filter/sidebar components

### 🔴 CRITICAL (<70%) — 11 pages

#### Category A: Minimal reference pages (6% similarity, ~20 ref tags)
These reference pages are extremely minimal (only 20 tags in the reference):

| Page | Similarity | Ref Tags | Local Tags | Issue |
|------|-----------|----------|------------|-------|
| aree-amministrative | 6% | 20 | 404 | Reference is nearly empty |
| organo | 6% | 20 | 404 | Reference is nearly empty |
| ufficio | 6% | 20 | 404 | Reference is nearly empty |
| enti-e-fondazioni | 6% | 20 | 404 | Reference is nearly empty |
| luoghi | 6% | 20 | 404 | Reference is nearly empty |
| contatti | 6% | 20 | 400 | Reference is nearly empty |

**Root Cause:** The reference pages at `italia.github.io/design-comuni-pagine-statiche/sito/<page>.html` for these URLs contain almost no content — likely placeholder pages or pages that redirect/load content dynamically. The local versions have full content (400+ tags). **This is a false negative** — the local pages may actually be more complete than the reference.

#### Category B: Massive local pages (0-7% similarity, 8504 local tags)
These local pages have ~8500 tags vs ~700-790 in the reference:

| Page | Similarity | Ref Tags | Local Tags | Issue |
|------|-----------|----------|------------|-------|
| prenotazione-appuntamento | 0% | 20 | 8504 | Block view not found, page renders empty + debug toolbar |
| segnalazione-disservizio | 0% | 20 | 8504 | Block view not found, page renders empty + debug toolbar |
| documenti-dati | 7% | 702 | 8504 | Block view not found, page renders empty + debug toolbar |
| novita | 7% | 713 | 8504 | Block view not found, page renders empty + debug toolbar |
| eventi | 7% | 791 | 8504 | Block view not found, page renders empty + debug toolbar |

**Root Cause:** The JSON files reference block views that don't exist:
- `pub_theme::components.blocks.events.list` — only `calendar.blade.php` exists
- `pub_theme::components.blocks.news.list` — view doesn't exist
- `pub_theme::components.blocks.documents.list` — view doesn't exist
- `pub_theme::components.blocks.appointment.booking` — view doesn't exist
- `pub_theme::components.blocks.report.layout` — view doesn't exist

When the BlockData constructor can't find the view, it throws an exception. The exception bubbles up through the `@include` which silently fails (or renders empty). The page renders with just the layout + Laravel Boost debug toolbar (8504 tags of debug output).

**Fix needed:** Create the missing block view files for each page type.

---

## Key Findings

### 1. Laravel Boost Debug Toolbar Skewing Results
The debug toolbar was inflating tag counts from ~700 to ~8500 on some pages. Fixed by using DOM-based cleanup in the browser before extraction.

### 2. Block Rendering Bug on Multi-step Pages
Pages like `prenotazione-appuntamento`, `segnalazione-disservizio`, `documenti-dati`, `novita`, `eventi` are rendering ALL blocks instead of their own. This needs investigation in:
- `laravel/Modules/Cms/app/Models/Traits/HasBlocks.php` → `getBlocksBySlug()`
- `laravel/Modules/Cms/app/Datas/BlockData.php` → view resolution
- The specific JSON files for these pages

### 3. Reference Pages Are Minimal Placeholders
Many reference pages (aree-amministrative, organo, ufficio, etc.) have only ~20 HTML tags. This means:
- Either the reference site doesn't have full implementations of these pages
- Or they load content dynamically (JS-rendered)
- Or the URLs are different from what we expect

### 4. Homepage is the Best Reference Point
At 92% similarity, the homepage is our best-aligned page. The remaining 8% gap is:
- **Splide.js carousel** (pagination dots, arrows) — need Tailwind equivalents
- **Rating component** (star ratings, step-based forms) — need Alpine.js + Tailwind implementation
- **Minor structural tags** (`<fieldset>`, `<legend>`, `<small>`)

---

## Recommended Fix Priority

### Phase 1: Fix Block Rendering Bug (CRITICAL)
Before any CSS work, fix the root cause of pages rendering 8500+ tags:
1. Check `tests.prenotazione-appuntamento.json` — does it reference all blocks?
2. Check `Page::getBlocksBySlug()` — is it filtering correctly by slug?
3. Check block view resolution — is there a fallback that renders everything?

### Phase 2: Homepage Polish (92% → 100%)
- Add Splide.js carousel styling with Tailwind @apply
- Implement rating component with Alpine.js
- Add missing structural tags where appropriate

### Phase 3: Fair Pages (74-86% → 90%+)
- Add missing card/teaser classes
- Implement pagination components
- Add filter/sidebar styling
- Fix list item layouts

### Phase 4: Investigate Minimal Reference Pages
- Verify if reference pages are truly minimal or if URLs are wrong
- If reference is genuinely minimal, use design-comuni block analysis as the source of truth instead

---

## Tools Created

| Tool | Location | Purpose |
|------|----------|---------|
| `screenshots.js` | `bashscripts/design-comuni-compare/` | Capture reference + local screenshots |
| `html-diff.js` | `bashscripts/design-comuni-compare/` | DOM-based HTML structure comparison |
| `run.sh` | `bashscripts/design-comuni-compare/` | Unified runner script |
| This doc | `laravel/Themes/Sixteen/docs/design-comuni/` | Baseline analysis |

## Related Documentation

- [Comparison Tools](DESIGN-COMUNI-COMPARISON-TOOLS.md) — Tool documentation
- [HTML Comparisons](html-comparisons/HTML-STRUCTURE-COMPARISON.md) — Raw comparison data
- [Block Census](design-comuni-census-blocks.md) — Content block analysis
- [Master Index](00-index.md) — Design Comuni documentation index

---

*Next review: After Phase 1 bug fix complete*
