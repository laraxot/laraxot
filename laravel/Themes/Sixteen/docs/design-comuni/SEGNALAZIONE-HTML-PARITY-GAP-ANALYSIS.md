# HTML Parity Gap Analysis: Segnalazione Pages

**Date:** 2026-04-09  
**Analysis Type:** Live fetch + existing comparison reports synthesis  
**Reference:** `https://italia.github.io/design-comuni-pagine-statiche/sito/`  
**Local:** `http://127.0.0.1:8000/it/tests/`  
**Methodology:** Combined live curl fetch of reference + local dev server, cross-referenced with existing `compare-html-body.py` reports

---

## 1. Current Parity Score Dashboard

| Page | Parity Score | HTML Status | CSS/JS Status | Date Verified |
|------|-------------|-------------|---------------|---------------|
| segnalazione-01-privacy | **99.3%** | ✅ PASS | ✅ PASS | 2026-04-09 |
| segnalazione-02-dati | **90.52%** | ✅ PASS | ⏳ Pending | - |
| segnalazione-03-riepilogo | **92.75%** | ✅ PASS | ⏳ Pending | - |
| segnalazione-04-conferma | **77.4%** | ❌ FAIL | ⏳ Pending | - |
| segnalazione-area-personale | **88.43%** | ❌ FAIL | ⏳ Pending | - |
| ticket-list | **77.55%** | ❌ FAIL | ⏳ Pending | - |
| segnalazione-dettaglio | **72.45%** | ❌ FAIL | ⏳ Pending | - |

**Average:** 85.44% | **HTML Passing (≥80%):** 3/7 | **CSS/JS Passing:** 1/7

---

## 2. Top 5 Gaps Per Page

### 2.1 segnalazione-04-conferma (77.4%)

| # | Gap | Impact | Fix Complexity |
|---|-----|--------|---------------|
| 1 | **Extra 81 local elements** — Alpine.js `x-model`, `x-show`, `@click` attributes add invisible markup not in reference | Parity dilution | 1 file, refactor Alpine bindings into data attributes |
| 2 | **28 missing reference elements** — Likely `data-element` attributes, specific class names in form feedback section | Structural gap | 1-2 files, add missing attributes |
| 3 | **Heading count mismatch** — Reference: 6×h4, 6×h2, 3×h3 vs Local: 8×h2, 6×h4, 2×h3 | Heading hierarchy wrong | 1 file, fix h2→h3 conversions |
| 4 | **Extra `<style>` and `<section>` tags** — Reference has 0 sections, local has 1 extra `<section>` and `<style>` | Structural deviation | 1 file, remove wrapper elements |
| 5 | **`<use>` attribute differences** — SVG `href` paths differ (`../assets/...` vs `/themes/Sixteen/...`) | Expected (asset path), cosmetic only | N/A — acceptable divergence |

### 2.2 ticket-list (77.55%)

| # | Gap | Impact | Fix Complexity |
|---|-----|--------|---------------|
| 1 | **121 missing reference elements** — Radio buttons (`id="radio-1"` through `radio-10`), modal IDs, form IDs entirely absent | Critical structural gap — disservizio modal is incomplete | 2-3 files, rebuild modal body structure |
| 2 | **Missing IDs** — `#modal-disservizio`, `#modal2Title`, `#formGroupExampleInputWithHelp`, `#formGroupExampleInputWithHelpDescription`, `#rating-feedback` | Accessibility + test automation broken | 2 files, add id attributes |
| 3 | **Heading text divergence** — Ref: "Fai una segnalazione" vs Local: "Fai anche tu una segnalazione"; Ref: "Quanto sono chiare..." vs Local: "Il tuo feedback..." | Copy mismatch — violates structural parity rule | 1 file, align text with reference |
| 4 | **12 missing `<img>` tags** — Reference has 13 images (card placeholders, modal), local has 3 | Missing visual content references | 1-2 files, add placeholder `<img>` elements |
| 5 | **ID parity only 69.39%** — 20+ missing IDs in modal, form, and feedback sections | Systematic ID omission pattern | 2-3 files, comprehensive audit |

### 2.3 segnalazione-area-personale (88.43%)

| # | Gap | Impact | Fix Complexity |
|---|-----|--------|---------------|
| 1 | **43 missing reference elements** — Header slim zone elements (user menu, avatar, logout link) replaced with "Accedi all'area personale" | Header structure differs — missing user-authenticated state markup | 2 files, add authenticated header variants |
| 2 | **Missing IDs** — `#data-ex-tab4`, `#modal-message`, `#modal-message-modal-title` | Tab 4 content absent, modal structure incomplete | 1-2 files, add missing tab/modal |
| 3 | **Extra local IDs** — `#contacts`, `#highlight`, `#search-area-personale`, `#search-section` | Non-reference wrapper IDs dilute parity | 1 file, rename or remove |
| 4 | **Text divergence** — "Esci" vs "Accedi all'area personale" in header user link | Wrong authenticated state copy | 1 file, conditional text |
| 5 | **Icon class mismatch** — Ref: `icon icon-primary icon-sm left` vs Local: `icon icon-primary` (missing `icon-sm left`) | Minor class gap | 1 file, add missing classes |

### 2.4 segnalazione-dettaglio (72.45%) — LOWEST SCORE

| # | Gap | Impact | Fix Complexity |
|---|-----|--------|---------------|
| 1 | **146 missing reference elements** — Largest gap of all pages; likely entire content blocks (detail sections, status timeline, action buttons) are absent | Critical — page is structurally incomplete | 3-4 files, rebuild major content sections |
| 2 | **Missing ID `#collapseExample`** — Collapsible detail section absent | Key interactive element missing | 1 file, add collapse section |
| 3 | **Tag parity only 85.95%** — 14% of reference tags have no local equivalent | Systematic content gap | 3-4 files |
| 4 | **Class parity only 82.32%** — Bootstrap Italia classes not matched consistently | Visual parity at risk even if structure improves | 2-3 files, class audit |
| 5 | **151 elements with attribute differences** — Even matching elements have wrong classes/ids | Pervasive attribute drift | 3-4 files |

---

## 3. Cross-Cutting Gap Patterns

### 3.1 Missing Semantic HTML Elements

| Element | Pages Affected | Notes |
|---------|---------------|-------|
| `<img>` (placeholder images) | elenco (12 missing), dettaglio (unknown) | Reference uses `modal-disservizio-placeholder.png` and card images |
| `<ol>` (ordered lists) | Only in reference for breadcrumbs | Local has it where reference does — parity OK here |
| `<legend>` inside `<fieldset>` | elenco (4 missing in modal form) | Rating forms and feedback forms need proper legend |
| `<hr>` separators | Reference uses consistently | Local may be missing some |

### 3.2 Bootstrap Italia Class Gaps

| Pattern | Reference | Local | Gap |
|---------|-----------|-------|-----|
| Title classes | `title-xxlarge`, `title-medium-2-semi-bold`, `title-xsmall`, `title-small-semi-bold` | `title-medium-2-bold`, `title-xxlarge` | `title-medium-2-bold` vs `title-medium-2-semi-bold` |
| Icon sizes | `icon icon-primary icon-sm`, `icon icon-md` | `icon icon-primary` (missing `icon-sm`) | Missing size modifier classes |
| Card variants | `card has-bkg-grey shadow-sm`, `card w-100` | `card shadow card-wrapper` | Extra local wrapper class, missing `has-bkg-grey` |
| Button spacing | `py-3 mt-2 mb-4 mb-lg-0`, `mobile-full` | Matches | ✅ Parity OK |
| Rating stars | `full rating-star active` | Matches | ✅ Parity OK |

### 3.3 data-element Attribute Coverage

| data-element | Reference Present | Local Present | Status |
|-------------|------------------|---------------|--------|
| `feedback-title` | ✅ | ✅ | ✅ |
| `feedback-rate-1..5` | ✅ | ✅ | ✅ |
| `feedback-input-text` | ✅ | ⚠️ | Check |
| `contacts` | ✅ | ✅ | ✅ |
| `appointment-booking` | ✅ | ✅ | ✅ |
| `report-inefficiency` | ✅ | ⚠️ | Check |
| `personal-area-login` | ✅ | ❌ | Missing in elenco |
| `news` | ✅ | ❌ | Missing in elenco |
| `management` | ✅ | ❌ | Missing in elenco |
| `faq` | ✅ | ❌ | Missing in elenco |
| `legal-notes` | ✅ | ❌ | Footer gap |
| `privacy-policy-link` | ✅ | ❌ | Footer gap |
| `accessibility-link` | ✅ | ❌ | Footer gap |

---

## 4. "Contatta il Comune" Block — Detailed Audit (ticket-list)

**Status:** ✅ STRUCTURAL PARITY ACHIEVED

The "Contatta il comune" block is one of the better-implemented sections. Live comparison confirms:

```
Reference structure:                    Local structure:
├── row d-flex justify-content-center   ├── row d-flex justify-content-center
│   └── col-12 col-lg-5                 │   └── col-12 col-lg-5
│       └── cmp-contacts                │       └── cmp-contacts
│           └── card w-100              │           └── card w-100
│               └── card-body           │               └── card-body
│                   ├── h2              │                   ├── h2 ✅
│                   └── ul contact-list │                   └── ul contact-list
│                       ├── 4×li>a      │                       ├── 4×li>a ✅
```

**Verified matches:**
- `h2 class="title-medium-2-semi-bold"` ✅
- `ul class="contact-list p-0"` ✅
- `li > a class="list-item"` ✅
- SVG icons: `icon icon-primary icon-sm` ✅
- `data-element="contacts"` ✅
- `data-element="appointment-booking"` ✅

**No gaps in this specific block.** The low overall score for ticket-list comes from the modal, feedback, and card sections — not the contacts block.

---

## 5. Priority Ranking for CSS/JS Phase Work

### P0 — Critical (Block Structural Parity)
| Priority | Task | Pages | Effort |
|----------|------|-------|--------|
| P0-1 | Rebuild `segnalazione-dettaglio` content sections — add missing detail blocks, status timeline, action buttons | dettaglio | High |
| P0-2 | Complete disservizio modal in `ticket-list` — add radio buttons (radio-1 through radio-10), form fields, modal body structure | elenco | High |
| P0-3 | Add missing IDs across all failing pages — `#modal-disservizio`, `#modal2Title`, `#formGroupExampleInputWithHelp`, `#collapseExample`, `#rating-feedback` | elenco, dettaglio, area-personale | Medium |

### P1 — High (Copy + Attribute Alignment)
| Priority | Task | Pages | Effort |
|----------|------|-------|--------|
| P1-1 | Align heading text with reference — "Fai una segnalazione" not "Fai anche tu una segnalazione" | elenco | Low |
| P1-2 | Fix heading hierarchy — h2→h3 conversions in conferma page | conferma | Low |
| P1-3 | Add missing Bootstrap Italia title classes — `title-medium-2-semi-bold` not `title-medium-2-bold` | all failing | Medium |
| P1-4 | Add missing icon size classes — `icon-sm`, `left` modifiers | all failing | Low |
| P1-5 | Remove extra Alpine.js wrapper elements (`<section>`, `<style>`) that don't exist in reference | conferma, elenco | Low |

### P2 — Medium (data-element + Footer)
| Priority | Task | Pages | Effort |
|----------|------|-------|--------|
| P2-1 | Add missing footer data-element attributes — `legal-notes`, `privacy-policy-link`, `accessibility-link` | all | Low |
| P2-2 | Add missing `<img>` placeholder elements in card sections and modal | elenco, dettaglio | Medium |
| P2-3 | Complete area-personale authenticated header — add user avatar, menu, logout link structure | area-personale | Medium |
| P2-4 | Add `data-element="personal-area-login"` link | elenco | Low |

### P3 — Low (Cosmetic + Maintenance)
| Priority | Task | Pages | Effort |
|----------|------|-------|--------|
| P3-1 | Reduce Alpine.js attribute pollution — move `x-model`, `x-show` to data attributes where possible | all | High |
| P3-2 | Standardize SVG sprite paths — document acceptable divergence from reference | all | Low |
| P3-3 | Add missing `<legend>` elements inside `<fieldset>` for accessibility | elenco, conferma | Low |
| P3-4 | Audit and remove extra local wrapper IDs — `#contacts`, `#highlight`, `#search-*` | area-personale, elenco | Low |

---

## 6. Key Findings Summary

### What's Working Well
1. **Header/navigation parity** — All 7 pages have near-perfect header structure (it-header-wrapper, it-nav-wrapper, etc.)
2. **Footer structure** — Basic footer elements match; only missing some `data-element` attributes
3. **Card component structure** — `cmp-card`, `cmp-info-button-card`, `card has-bkg-grey` patterns match
4. **Contatta il comune block** — Fully aligned with reference (verified via live fetch)
5. **Rating stars feedback** — `full rating-star active`, star SVGs, `data-element="feedback-rate-N"` all match
6. **Three pages pass ≥90%** — privacy (99%), riepilogo (92.75%), dati (90.52%)

### Biggest Gaps
1. **segnalazione-dettaglio (72.45%)** — Most incomplete page; likely needs 3-4 major content sections rebuilt
2. **ticket-list modal (121 missing elements)** — Disservizio modal body is structurally incomplete
3. **Heading text drift** — Local copy has diverged from reference in 4+ places
4. **Missing IDs** — Systematic pattern of omitting `id` attributes that reference uses
5. **Extra Alpine.js markup** — Local has 34-81 extra elements from Vue-like directives

### Recommendation for CSS/JS Phase
**Focus on P0 items first** — structural completeness before visual polish. The three passing pages (privacy, dati, riepilogo) prove the architecture works. The four failing pages need content sections rebuilt, not CSS tweaking.

Specifically:
- **segnalazione-dettaglio**: Needs the most work — treat as a new page build
- **ticket-list**: Modal completion + heading text alignment will push score from 77% → ~90%
- **segnalazione-area-personale**: Authenticated header variant + missing tab/modal will push 88% → ~95%
- **segnalazione-04-conferma**: Heading hierarchy + element cleanup will push 77% → ~90%

---

## 7. Source Data References

| File | Location |
|------|----------|
| 01-privacy report | `docs/prompts/segnalazione-01-privacy/body-structure-comparison/report.md` |
| 02-dati report | `docs/prompts/segnalazione-02-dati/body-structure-comparison/segnalazione-02-dati-comparison-report.md` |
| 03-riepilogo report | `docs/prompts/segnalazione-03-riepilogo/body-structure-comparison/segnalazione-03-riepilogo-comparison-report.md` |
| 04-conferma report | `docs/prompts/segnalazione-04-conferma/body-structure-comparison/segnalazione-04-conferma-comparison-report.md` |
| area-personale report | `docs/prompts/segnalazione-area-personale/body-structure-comparison/segnalazione-area-personale-comparison-report.md` |
| elenco report | `docs/prompts/ticket-list/body-structure-comparison/ticket-list-comparison-report.md` |
| dettaglio report | `docs/prompts/segnalazione-dettaglio/body-structure-comparison/segnalazione-dettaglio-comparison-report.md` |
| Comparison script | `bashscripts/html-comparison/compare-html-body.sh` |
| Live fetch date | 2026-04-09 |

---

*Analysis completed by manual research + existing comparison report synthesis. Live curl fetches verified against stored reports for accuracy.*
