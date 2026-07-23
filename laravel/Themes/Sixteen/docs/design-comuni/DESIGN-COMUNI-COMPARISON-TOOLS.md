# Design Comuni Visual Comparison Tools

**Purpose:** Systematically compare and align local Design Comuni pages with the reference implementation (italia.github.io) to achieve visual parity using Tailwind CSS + Alpine.js (no Bootstrap Italia).

**Date:** 2026-04-04  
**Status:** Active  
**Location:** `bashscripts/design-comuni-compare/`

---

## Overview

This suite of tools automates the comparison of 47+ Design Comuni static pages between:
- **Reference:** `https://italia.github.io/design-comuni-pagine-statiche/sito/<pagina>.html`
- **Local:** `http://127.0.0.1:8000/it/tests/<pagina>`

### Architecture

```
Reference HTML (GitHub Pages)          Local HTML (Laravel + Blade)
        │                                       │
        ▼                                       ▼
┌───────────────────┐              ┌─────────────────────────┐
│ Playwright captures│              │ Playwright captures      │
│ reference screenshot│             │ local screenshot         │
│ and HTML structure │              │ and HTML structure       │
└───────────────────┘              └─────────────────────────┘
        │                                       │
        ▼                                       ▼
┌───────────────────┐              ┌─────────────────────────┐
│ Reference          │              │ Local                    │
│ screenshots/       │              │ screenshots/             │
│ reference/         │              │ local/                   │
│ HTML structure     │              │ HTML structure           │
└───────────────────┘              └─────────────────────────┘
        │                                       │
        └───────────────┬───────────────────────┘
                        ▼
              ┌─────────────────────┐
              │ Comparison Engine   │
              │ • LCS similarity    │
              │ • Class diff        │
              │ • Tag hierarchy     │
              │ • Visual diff       │
              └─────────────────────┘
                        │
                        ▼
              ┌─────────────────────┐
              │ Reports             │
              │ • similarity %      │
              │ • missing classes   │
              │ • extra classes     │
              │ • screenshots       │
              │ • HTML diff         │
              └─────────────────────┘
```

---

## Tools

### 1. `screenshots.js` — Screenshot Capture

Captures full-page screenshots for reference and local pages at desktop and mobile viewports.

**Usage:**
```bash
# All pages
node bashscripts/design-comuni-compare/screenshots.js

# Single page
node bashscripts/design-comuni-compare/screenshots.js homepage
```

**Output:**
- `laravel/Themes/Sixteen/docs/design-comuni/screenshots/reference/<page>-desktop.png`
- `laravel/Themes/Sixteen/docs/design-comuni/screenshots/reference/<page>-mobile.png`
- `laravel/Themes/Sixteen/docs/design-comuni/screenshots/local/<page>-desktop.png`
- `laravel/Themes/Sixteen/docs/design-comuni/screenshots/local/<page>-mobile.png`

### 2. `html-diff.js` — HTML Structure Comparison

Extracts and compares the body tag structure between reference and local pages.

**Metrics:**
- **Similarity Score:** LCS-based structural similarity (0-100%)
- **Missing Classes:** Bootstrap Italia classes not yet replicated in Tailwind
- **Extra Classes:** Local-only classes (potential cleanup targets)
- **Tag Differences:** Missing/extra HTML elements

**Usage:**
```bash
# All pages
node bashscripts/design-comuni-compare/html-diff.js

# Single page
node bashscripts/design-comuni-compare/html-diff.js homepage
```

**Output:**
- `laravel/Themes/Sixteen/docs/design-comuni/html-comparisons/<page>-comparison.json`
- `laravel/Themes/Sixteen/docs/design-comuni/html-comparisons/summary.json`
- `laravel/Themes/Sixteen/docs/design-comuni/html-comparisons/HTML-STRUCTURE-COMPARISON.md`

### 3. `run.sh` — Unified Runner

```bash
bash bashscripts/design-comuni-compare/run.sh [command] [page]

Commands:
  screenshots [page]  - Capture screenshots
  html-diff [page]    - Compare HTML structure
  full [page]         - Run both screenshots + html-diff
  build               - npm run build && npm run copy
  status              - Show comparison summary
```

---

## Workflow

### Phase 1: Baseline Measurement

1. **Capture all screenshots:**
   ```bash
   bash bashscripts/design-comuni-compare/run.sh screenshots
   ```

2. **Run HTML structure comparison:**
   ```bash
   bash bashscripts/design-comuni-compare/run.sh html-diff
   ```

3. **Review results:**
   ```bash
   bash bashscripts/design-comuni-compare/run.sh status
   ```

### Phase 2: CSS/JS Fixes

For each page below 90% similarity:

1. **Open side-by-side screenshots:**
   - Reference: `docs/design-comuni/screenshots/reference/<page>-desktop.png`
   - Local: `docs/design-comuni/screenshots/local/<page>-desktop.png`

2. **Identify missing classes** from the comparison JSON

3. **Add Tailwind @apply rules** to:
   - `laravel/Themes/Sixteen/resources/css/style-apply.css` (Bootstrap Italia → Tailwind)
   - `laravel/Themes/Sixteen/resources/css/components/design-comuni.css` (component classes)
   - Specific block view files if structure differs

4. **Build and copy:**
   ```bash
   bash bashscripts/design-comuni-compare/run.sh build
   ```

5. **Re-capture and verify:**
   ```bash
   bash bashscripts/design-comuni-compare/run.sh full <page>
   ```

### Phase 3: Verification

1. All pages >= 90% structural similarity
2. Visual screenshots match reference
3. Interactive behavior (Alpine.js) matches reference

---

## Pages List (47 total)

### Core Pages (Priority 1)
1. `homepage` — Main landing page
2. `amministrazione` — Administration overview
3. `servizi` — Services listing
4. `novita` — News listing
5. `contatti` — Contact page

### Administration (Priority 2)
6. `aree-amministrative` — Administrative areas
7. `area-amministrativa-dettaglio` — Area detail
8. `organo` — Governing body
9. `persona` — Person listing
10. `persona-dettaglio` — Person detail
11. `ufficio` — Office listing
12. `ufficio-dettaglio` — Office detail
13. `enti-e-fondazioni` — Entities and foundations
14. `ente-dettaglio` — Entity detail

### Content Pages (Priority 2)
15. `documenti-dati` — Documents and data
16. `documento-dettaglio` — Document detail
17. `dataset-dettaglio` — Dataset detail
18. `novita-dettaglio` — News detail
19. `eventi` — Events listing
20. `evento-dettaglio` — Event detail
21. `luoghi` — Places listing
22. `luogo-dettaglio` — Place detail

### Services (Priority 2)
23. `servizi-categoria` — Service category
24. `servizio-dettaglio` — Service detail
25. `lista-risorse` — Resource list
26. `lista-categorie` — Category list
27. `lista-risorse-categorie` — Resources by category
28. `mappa-sito` — Site map

### Appointment Booking (Priority 3)
29. `prenotazione-appuntamento` — Booking start
30. `appuntamento-01-ufficio` — Step 1: Office
31. `appuntamento-01-ufficio-luogo` — Step 1b: Office + Place
32. `appuntamento-02-data-orario` — Step 2: Date/Time
33. `appuntamento-03-dettagli` — Step 3: Details
34. `appuntamento-04-richiedente` — Step 4: Requester
35. `appuntamento-04-richiedente-autenticato` — Step 4b: Authenticated
36. `appuntamento-05-riepilogo` — Step 5: Summary
37. `appuntamento-06-conferma` — Step 6: Confirmation

### Service Requests (Priority 3)
38. `richiesta-assistenza` — Assistance request
39. `assistenza-01-dati` — Step 1: Data
40. `assistenza-02-conferma` — Step 2: Confirmation

### Reports (Priority 3)
41. `segnalazione-disservizio` — Service issue report
42. `segnalazione-01-privacy` — Step 1: Privacy
43. `segnalazione-02-dati` — Step 2: Data
44. `segnalazione-03-riepilogo` — Step 3: Summary
45. `segnalazione-04-conferma` — Step 4: Confirmation
46. `ticket-list` — Reports list
47. `segnalazione-dettaglio` — Report detail

### Payments (Priority 3)
48. `pagamento` — Payment page
49. `pagamento-dettaglio` — Payment detail

---

## Key Principles

### No Bootstrap Italia
- **NEVER** import Bootstrap Italia CSS
- **ALWAYS** replicate visual design using Tailwind @apply
- Bootstrap Italia class names → Tailwind utility classes via `style-apply.css`

### Alpine.js for Interactivity
- Mobile menu toggle
- Accordion expand/collapse
- Tab switching
- Modal dialogs
- Form validation

### Tailwind @apply Pattern
```css
/* In style-apply.css */
.it-header-wrapper {
  @apply bg-primary text-white shadow-lg;
}

.it-hero-banner {
  @apply relative overflow-hidden bg-cover bg-center min-h-[400px];
}
```

### Build Process
```bash
cd laravel/Themes/Sixteen
npm run build    # Builds to ./public
npm run copy     # Copies to public_html/themes/Sixteen
```

---

## Related Documentation

- [Design Comuni Index](../00-index.md) — Main design-comuni documentation index
- [Block Census](../design-comuni-census-blocks.md) — All content blocks analyzed
- [Implementation Plan](../design-comuni-implementation.md) — Original implementation strategy
- [HTML Parity Plan](../design-comuni-html-parity-plan.md) — Original HTML parity approach
- [Visual Fix Plan](../design-comuni-css-fix-plan.md) — CSS fix strategy *(create after baseline)*

## See Also

- [Screenshot Index](screenshots/README.md) — Screenshot organization
- [HTML Comparisons](html-comparisons/HTML-STRUCTURE-COMPARISON.md) — Structure comparison results
- [Master Project Index](../../../../docs/MODULE_DOCS_INDEX.md) — Project-wide documentation index

---

*Last updated: 2026-04-04*  
*Next review: After Phase 1 baseline measurement complete*
