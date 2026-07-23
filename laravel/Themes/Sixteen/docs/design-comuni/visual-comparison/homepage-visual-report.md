# Homepage Visual Comparison Report

**Date:** 2026-04-03  
**Reference:** https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html  
**Local:** http://127.0.0.1:8000/it/tests/homepage  
**Screenshots:** [homepage-reference.png](./screenshots/homepage-reference.png) | [homepage-local.png](./screenshots/homepage-local.png)

**Related:**
- [00-index.md](../00-index.md) - Design Comuni Documentation Index
- [CSS-JS-ALIGNMENT-PLAN.md](../css-js-alignment-plan.md) - CSS/JS Fix Plan
- [bmad-gsd-status-2026-04-03.md](../bmad-gsd-status-2026-04-03.md) - GSD Status

---

## Executive Summary

Visual comparison reveals **significant layout differences** between reference and local implementation. The HTML structure is mostly correct, but CSS styling needs substantial work to match the Bootstrap Italia design using Tailwind CSS.

**Overall Match: ~60%** - Core structure present, but visual styling gaps remain.

---

## Detailed Differences by Section

### 1. Header / Navigation

| Element | Reference | Local | Status |
|---------|-----------|-------|--------|
| Top bar background | Dark green (#006655) | Dark green ✓ | ✓ Match |
| Logo + "Il mio Comune" | White text, proper spacing | White text, spacing slightly off | ⚠ Minor |
| Social icons row | Inline with "Seguici su" | Inline ✓ | ✓ Match |
| Main nav (Amministrazione, Novità, Servizi, Vivere il Comune) | White, bold, proper spacing | White, but spacing tighter | ⚠ Minor |
| Secondary nav (Iscrizioni, Estate in città, etc.) | Right-aligned, white | Right-aligned ✓ | ✓ Match |
| Search button | White circle with magnifying glass | White circle ✓ | ✓ Match |

**CSS Fixes Needed:**
- Adjust nav item spacing (padding/margin)
- Verify font-weight on main nav items

---

### 2. Hero / Notizie Section

| Element | Reference | Local | Status |
|---------|-----------|-------|--------|
| Layout | 2-column: text left, image right | 2-column ✓ | ✓ Match |
| Date badge | "NOTIZIE — 18 MAG 2022" with calendar icon | Present ✓ | ✓ Match |
| Title | Green, bold, multi-line | Green, bold ✓ | ✓ Match |
| Subtitle | Black, regular weight | Italic (WRONG) | ✗ **DIFF** |
| "Estate in città" tag | Pill badge, green border | Present ✓ | ✓ Match |
| "TUTTE LE NOVITÀ" link | Green text + arrow | Present, but "TUTTE" capitalized | ⚠ Minor |
| Hero image | Under-bridge perspective | Castle image (different content) | ⚠ Content |

**CSS Fixes Needed:**
- **CRITICAL:** Remove italic from subtitle (`font-style: normal`)
- Match exact green color for title (#006655 or similar)
- Verify link styling matches reference

---

### 3. Organi di Governo Cards

| Element | Reference | Local | Status |
|---------|-----------|-------|--------|
| Layout | 3 cards in horizontal row | 3 cards in **vertical column** | ✗ **DIFF** |
| Card design | White bg, shadow, image right | White bg, shadow, image right ✓ | ✓ Match |
| Card content | "ORGANI DI GOVERNO" label, name/title | Same structure ✓ | ✓ Match |
| "VAI ALLA PAGINA" link | Green text + arrow, bottom | Present ✓ | ✓ Match |

**CSS Fixes Needed:**
- **CRITICAL:** Change card container to horizontal flex/grid layout
- Reference: `display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;`
- Current: Cards stacking vertically (flex-col or block)

---

### 4. Eventi Section

| Element | Reference | Local | Status |
|---------|-----------|-------|--------|
| Title | "Eventi", bold, black | **MISSING** | ✗ **DIFF** |
| Calendar strip | Green header "Settembre 2022", 4 date columns | **MISSING** | ✗ **DIFF** |
| Date columns | "15 lun", "16 mar", etc. with events | **MISSING** | ✗ **DIFF** |
| Navigation dots | Green dots below calendar | **MISSING** | ✗ **DIFF** |

**CSS/HTML Fixes Needed:**
- **CRITICAL:** Entire Eventi calendar section is missing or not rendering
- Check JSON content for `eventi` block
- Verify component renders calendar strip correctly
- May need Alpine.js for date navigation

---

### 5. Argomenti in Evidenza Section

| Element | Reference | Local | Status |
|---------|-----------|-------|--------|
| Background | Green gradient with geometric shapes | Green gradient ✓ | ✓ Match |
| Title | "Argomenti in evidenza", white | White ✓ | ✓ Match |
| Cards layout | 3 cards in row | **1 card visible** | ✗ **DIFF** |
| Card design | White bg, title, links, "ESPLORA ARGOMENTO" | White bg, links ✓ | ✓ Match |
| Card content | "Trasporto pubblico", "Animale domestico", "Sport" | Only "Trasporto pubblico" visible | ✗ **DIFF** |
| "ALTRI ARGOMENTI" pills | Tags row below cards | **MISSING** | ✗ **DIFF** |
| "Mostra tutti" button | Green button centered | **MISSING** | ✗ **DIFF** |

**CSS Fixes Needed:**
- **CRITICAL:** Card container must be 3-column grid/flex
- Check JSON for all 3 argomenti blocks
- Render "ALTRI ARGOMENTI" pill tags
- Add "Mostra tutti" button

---

### 6. Siti Tematici Section

| Element | Reference | Local | Status |
|---------|-----------|-------|--------|
| Title | "Siti tematici", bold, black | **MISSING** | ✗ **DIFF** |
| Cards | 3 colored cards (blue, brown, dark) | **MISSING** | ✗ **DIFF** |
| Card content | "Mobilità in Comune", "Turismo", "Musei Civici" | **MISSING** | ✗ **DIFF** |

**CSS/HTML Fixes Needed:**
- **CRITICAL:** Entire Siti Tematici section missing
- Check JSON for `siti-tematici` block
- Add colored card variants (blue-600, amber-700, gray-800)

---

### 7. Search / Link Utili Section

| Element | Reference | Local | Status |
|---------|-----------|-------|--------|
| Search bar | Centered, input + green "Invio" button | Present ✓ | ✓ Match |
| "LINK UTILI" label | Uppercase, gray | Present ✓ | ✓ Match |
| Links list | 6 green underlined links | Present ✓ | ✓ Match |

**Status:** ✓ Good match

---

### 8. Rating Section ("Quanto sono chiare...")

| Element | Reference | Local | Status |
|---------|-----------|-------|--------|
| Background | Green solid | **Dark teal** (#004444) | ✗ **DIFF** |
| Box | White card, centered | White card ✓ | ✓ Match |
| Text | Black, bold | **Dark, hard to read** | ✗ **DIFF** |
| Stars | 5 gray stars | 5 black stars | ⚠ Minor |

**CSS Fixes Needed:**
- Change background to green (#006655 or similar)
- Ensure text is readable (black on white card)
- Star color should be gray (#6c757d or similar)

---

### 9. Contatta il Comune / Problemi in Città

| Element | Reference | Local | Status |
|---------|-----------|-------|--------|
| Card | White, centered, shadow | White, centered, shadow ✓ | ✓ Match |
| "Contatta il comune" title | Bold, black | Bold, black ✓ | ✓ Match |
| Links with icons | Green icons + text | Green icons + text ✓ | ✓ Match |
| "Problemi in città" section | Bold title + link | Present ✓ | ✓ Match |

**Status:** ✓ Good match

---

### 10. Footer

| Element | Reference | Local | Status |
|---------|-----------|-------|--------|
| Background | Dark gray/charcoal | Dark gray ✓ | ✓ Match |
| EU badge + "Nome del Comune" | EU flag + building icon + text | Present ✓ | ✓ Match |
| Column layout | 4 columns: Amministrazione, Categorie, Novità, etc. | Present ✓ | ✓ Match |
| Links | White, underlined on hover | White ✓ | ✓ Match |
| Social icons | White icons | White ✓ | ✓ Match |
| Bottom bar | "Media policy" "Mappa del sito" | Present ✓ | ✓ Match |

**Status:** ✓ Good match (~90%)

---

## Priority Fixes

### 🔴 CRITICAL (Must Fix)

1. **Organi di Governo cards** - Change from vertical to horizontal 3-column layout
2. **Eventi section** - Missing entirely, needs calendar strip component
3. **Argomenti cards** - Change from 1 column to 3-column layout
4. **Siti Tematici section** - Missing entirely
5. **Subtitle font-style** - Remove italic from hero subtitle

### 🟡 HIGH (Should Fix)

6. **Rating section background** - Change from dark teal to green
7. **"ALTRI ARGOMENTI" pills** - Missing tag pills row
8. **"Mostra tutti" button** - Missing green button
9. **Nav spacing** - Adjust padding/margin on main nav items

### 🟢 LOW (Nice to Have)

10. **Hero image** - Different image (content issue, not CSS)
11. **Star color** - Should be gray, currently black
12. **"TUTTE LE NOVITÀ" capitalization** - Minor text difference

---

## Next Steps

1. **Fix CSS layout issues** (cards, sections)
2. **Add missing sections** (Eventi, Siti Tematici)
3. **Verify JSON content** has all blocks
4. **Run `npm run build && npm run copy`** after each fix
5. **Re-capture screenshots** and compare

---

## Screenshot Archive

- [homepage-reference.png](./screenshots/homepage-reference.png) - Reference Design Comuni
- [homepage-local.png](./screenshots/homepage-local.png) - Our implementation

---

*Generated: 2026-04-03 | Tool: Playwright screenshot capture | See [capture-single.cjs](../../../../../bashscripts/design-comuni/capture-single.cjs)*
