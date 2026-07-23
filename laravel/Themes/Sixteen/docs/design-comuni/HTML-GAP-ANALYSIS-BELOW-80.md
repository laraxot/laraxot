# HTML Gap Analysis — Pages Below 80% Parity

**Project**: FixCity Design Comuni  
**Date**: 2026-04-09  
**Threshold**: 80%  
**Pages analyzed**: 2 (segnalazione-04-conferma, homepage)

---

## 1. segnalazione-04-conferma

| Metric | Value |
|--------|-------|
| **Current parity** | 77.53% |
| **Target** | 80.00% |
| **Gap needed** | +2.47 percentage points |
| **Reference elements** | 551 |
| **Local elements** | 603 |
| **Identical** | 412 |
| **Different** | 111 |
| **Missing** | 28 |
| **Extra** | 80 |

### Score Breakdown

| Dimension | Score |
|-----------|-------|
| Tag parity | 99.09% |
| ID parity | 100.0% |
| Class parity | 95.83% |

### Top 5 Missing Elements (by impact)

| # | XPath | Tag | Impact |
|---|-------|-----|--------|
| 1 | `body/main[1]/div[2]/…/fieldset[2]` (entire subtree) | fieldset + 16 children | **HIGH** — Second rating fieldset (multi-step follow-up questions: positive/negative radio options, text feedback input, back/next buttons). ~17 elements missing from the reference's second rating card. |
| 2 | `body/main[1]/div[2]/…/fieldset[1]/div[1]/div[1]/div[4]` (input + label) | input, label | **MED** — Radio option #4 in first follow-up fieldset (`fieldset2`). |
| 3 | `body/main[1]/div[2]/…/fieldset[1]/div[1]/div[1]/div[5]` (input + label) | input, label | **MED** — Radio option #5 in first follow-up fieldset. |
| 4 | `body/main[1]/div[2]/…/fieldset[1]/legend[1]/h3[1]/span[1..2]` | span ×2 | **MED** — Step indicator spans inside legend h3 (one for question text, one for step number). |
| 5 | `body/main[1]/div[1]/div[1]/nav[1]/ol[1]/li[2]/a[1]` | a | **LOW** — Breadcrumb link for "Servizi" (second breadcrumb item). |

### Top 5 Extra Elements (by impact)

| # | XPath | Tag | Impact |
|---|-------|-----|--------|
| 1 | `body/main[1]/div[1]/section[1]` + 45 descendants | section subtree | **HIGH** — Entire extra `<section>` subtree containing a duplicate stepper/flow-stepper component with buttons for each step. ~46 extra elements. This is a LOCAL artifact — the reference does NOT have a standalone section with stepper buttons. |
| 2 | `body/main[1]/div[1]/div[1]` + 2 descendants | div subtree | **MED** — Extra wrapper divs before the main content area. |
| 3 | `body/main[1]/div[1]/div[2]/…/fieldset[1]` + 27 descendants | fieldset subtree | **HIGH** — Duplicate fieldset subtree at a different DOM path (likely the rating component rendered at wrong nesting level). ~28 extra elements. |
| 4 | `body/main[1]/div[1]/section[1]/div[1]/div[2]/nav[1]/button[3]` subtree | button + children | **LOW** — Extra navigation button in stepper nav. |
| 5 | Various `<use>` elements under extra subtrees | use | **LOW** — SVG `<use>` elements that are children of the extra section/fieldset subtrees above. |

### Top 5 Attribute Differences

| # | XPath | Difference | Pattern |
|---|-------|------------|---------|
| 1 | `body/header[1]/div[2]/…/nav[1]/ul[1]/li[3]/a[1]` | Classes: `active nav-link` → `nav-link` | Missing `active` class on current-page nav link |
| 2 | ~90 `<use>` elements across header | `href` attribute differs | Local uses `/themes/Sixteen/design-comuni/assets/.../sprites.svg` vs reference relative `../assets/.../sprites.svg` — this is EXPECTED and correct for the project |
| 3 | Various `<a>` and `<button>` elements in header | `href`, `class` minor diffs | Path differences between reference (static HTML) and local (Laravel routes) |
| 4 | `body/header[1]` | Attribute differences | Header-level attribute mismatch (likely `data-bs-*` or `aria-*`) |
| 5 | `body/header[1]/…/image[1]` (logo) | `href`/`xlink:href` | SVG image reference path difference |

### Recommended Fixes (Prioritized)

| Priority | Fix | Files to Change | Est. Impact |
|----------|-----|-----------------|-------------|
| **P0** | Add second rating fieldset (`fieldset[2]`) — the multi-step follow-up card with positive/negative radio options, text input, and back/next buttons. This is the SINGLE biggest gap (~17 missing elements). The local rating.blade.php already HAS this structure but it's conditionally rendered via Alpine.js (`:class="{ 'd-none': step !== 2 }"`). The comparison tool may be counting d-none elements as missing if they're not in the initial DOM. **Verify**: check if `cmp-rating__card-second` elements exist in local HTML but with `d-none` class. | `rating.blade.php` (already has structure), `04-conferma.blade.php` | +1.5–2.0% |
| **P1** | Remove or relocate the extra `<section>` subtree (~46 elements) at `body/main[1]/div[1]/section[1]`. This appears to be a duplicate flow-stepper component rendered outside the expected DOM position. Consolidate into the single stepper block. | JSON content block (`tests.segnalazione-04-conferma.json`), `[slug].blade.php` or component view | -0.5% (removes noise) |
| **P2** | Fix breadcrumb missing "Servizi" link — add second breadcrumb item `<li><a href="#">Servizi</a><span class="separator">/</span></li>`. | JSON content block or `flow-stepper` component view | +0.1% |
| **P3** | Add `active` class to current-page nav link (`li[3]/a[1]` should have `class="active nav-link"` not just `nav-link`). | Header/nav component | +0.1% |
| **P4** | Fix legend span elements inside fieldset2 — ensure `<span>` for question text and step number are present in the follow-up rating cards. | `rating.blade.php` — verify `iscrizioni-header` legend structure | +0.2% |

### Estimated Effort

- **Files to change**: 3–4 files
  - `tests.segnalazione-04-conferma.json` (content config)
  - `04-conferma.blade.php` (main confirmation view)
  - `rating.blade.php` (rating component — verify d-none rendering)
  - Header/nav component (active class fix)
- **Risk**: LOW — all changes are additive or repositioning existing structures
- **Key insight**: The ~2.5% gap is primarily caused by the second rating fieldset's follow-up cards (step 2/3 of rating) being rendered with `d-none` class. If the comparison tool counts `d-none` elements as missing, this alone accounts for most of the gap.

---

## 2. homepage

| Metric | Value |
|--------|-------|
| **Current parity** | 79.64% |
| **Target** | 80.00% |
| **Gap needed** | +0.36 percentage points |
| **Reference elements** | 836 (inferred) |
| **Local elements** | 945 (inferred) |
| **Identical** | 652 |
| **Different** | 155 |
| **Missing** | 29 |
| **Extra** | 109 |

### Score Breakdown

| Dimension | Score |
|-----------|-------|
| Tag parity | 99.64% |
| ID parity | 100.0% |
| Class parity | 98.16% |

### Top 5 Missing Elements (by impact)

| # | XPath | Tag | Impact |
|---|-------|-----|--------|
| 1 | `body/main[1]/div[1]/…/fieldset[2]` (entire subtree) | fieldset + 16 children | **HIGH** — Same pattern as segnalazione-04-conferma: second rating fieldset with follow-up questions (positive/negative radio options, text input, buttons). ~17 elements. |
| 2 | `body/main[1]/div[1]/…/fieldset[1]/div[1]/div[1]/div[4]` (input + label) | input, label | **MED** — Radio options 4–5 in first follow-up fieldset. |
| 3 | `body/main[1]/div[1]/…/fieldset[1]/div[1]/div[1]/div[5]` (input + label) | input, label | **MED** — Same as above. |
| 4 | `body/main[1]/div[1]/…/fieldset[1]/legend[1]/h3[1]/span[1..2]` | span ×2 | **MED** — Legend step indicator spans. |
| 5 | `body/main[1]/section[3]/div[1]/div[1]/div[2]/div[1]/div[2]/a[1]/span[1]` | span | **LOW** — Span inside a link in section 3 (likely a "See all" or CTA link). |

### Top 5 Extra Elements (by impact)

| # | XPath | Tag | Impact |
|---|-------|-----|--------|
| 1 | `body/main[1]/div[1]` wrapper + nested subtrees | div subtree | **HIGH** — Extra wrapper div creating additional nesting level not present in reference. |
| 2 | `body/main[1]/div[1]/section[1]/…/div[2]` (extra div inside section) | div | **MED** — Extra div inside first section's content area. |
| 3 | `body/main[1]/div[1]/section[2]/…/div[1]` (extra div inside section 2) | div | **MED** — Extra div inside second section. |
| 4–109 | Various `<use>`, `<span>`, `<div>` elements | mixed | **LOW** — Children of the extra wrapper divs above. Most are SVG icon `<use>` elements. |

### Top 5 Attribute Differences

| # | XPath | Difference | Pattern |
|---|-------|------------|---------|
| 1 | Various `<use>` elements (~80+) | `href` path difference | Same pattern as segnalazione: local uses absolute `/themes/Sixteen/...` vs reference relative paths. EXPECTED. |
| 2 | Nav links in header | `class` differences | Minor class ordering or missing `active` class |
| 3 | Various header `<a>` elements | `href` differences | Static HTML paths vs Laravel routes |
| 4 | Logo `<image>` element | `xlink:href` | SVG sprite path difference |
| 5 | Section `<a>` elements | `class` differences | Minor Bootstrap class variations |

### Recommended Fixes (Prioritized)

| Priority | Fix | Files to Change | Est. Impact |
|----------|-----|-----------------|-------------|
| **P0** | Same as segnalazione-04-conferma: Verify second rating fieldset (`fieldset[2]`) rendering. The ~17 missing elements are almost certainly the `d-none` follow-up cards in the rating component. If the comparison tool doesn't count `d-none` elements, these appear as "missing." | `rating.blade.php` | +0.2–0.3% |
| **P1** | Remove extra wrapper `div[1]` at `body/main[1]/div[1]` if it's not required by the layout. Consolidate nesting to match reference structure. | Homepage JSON or `[slug].blade.php` layout | +0.05% |
| **P2** | Add missing `<span>` inside section 3 CTA link (`section[3]/…/a[1]/span[1]`). | Homepage JSON content block | +0.02% |
| **P3** | Fix section 3 missing `<a>` element (`section[3]/div[1]/div[1]/div[3]/div[2]/a[1]`). | Homepage JSON content block | +0.02% |
| **P4** | Trim extra `<div>` elements inside sections 1 and 2 that create unnecessary nesting. | Homepage JSON or component views | +0.02% |

### Estimated Effort

- **Files to change**: 2–3 files
  - `tests.homepage.json` (content config — add missing spans/links)
  - `rating.blade.php` (verify d-none rendering, same fix as segnalazione)
  - Homepage layout/component view (remove extra wrapper div)
- **Risk**: VERY LOW — only ~0.36% gap needed; minor structural tweaks
- **Key insight**: The homepage is only 0.36% away from 80%. The missing fieldset2 elements (~17 elements out of ~836 total = ~2%) are being partially offset by the 109 extra elements. Removing the extra wrapper div and ensuring the rating component's `d-none` elements are counted should push it over 80%.

---

## Cross-Cutting Observations

### Shared Root Cause: Rating Component `d-none` Rendering

Both pages share the **same primary gap**: the second rating fieldset (`fieldset[2]`) with its follow-up cards is rendered with Alpine.js `d-none` classes (`:class="{ 'd-none': step !== 2 }"`). The HTML comparison tool fetches the initial DOM snapshot, which includes these elements with `d-none` — but the comparison may treat them differently than the reference's always-visible structure.

**Investigation needed**: Check if the reference HTML also uses `d-none` for its follow-up rating cards, or if it renders them without the class. If the reference renders them without `d-none`, the fix is to conditionally apply the class differently or ensure the initial DOM matches.

### SVG Sprite Path Differences (Not a Real Gap)

~90 `<use>` elements show `href` attribute differences because the local project uses absolute paths (`/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg`) while the reference uses relative paths (`../assets/bootstrap-italia/dist/svg/sprites.svg`). This is **expected and correct** for the Laravel theme system and should NOT be counted as a parity issue.

### Extra Wrapper Divs

Both pages have extra `<div>` wrapper elements that add nesting levels not present in the reference. These are likely introduced by the CMS-driven page architecture (JSON → block rendering → wrapper divs).

---

## Summary Table

| Page | Current | Target | Gap | Primary Fix | Files | Risk |
|------|---------|--------|-----|-------------|-------|------|
| segnalazione-04-conferma | 77.53% | 80.00% | +2.47pp | Add fieldset2 follow-up cards, remove extra section | 3–4 | LOW |
| homepage | 79.64% | 80.00% | +0.36pp | Verify d-none elements, remove extra wrapper div | 2–3 | VERY LOW |
