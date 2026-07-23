# Design Comuni Visual Parity - Action Plan

**Created**: 2026-04-04  
**Status**: ✅ Phase 1 complete, Phase 2 in progress  
**Priority**: CRITICAL  

---

## Current State (Updated 2026-04-04 End of Session)

### Visual Parity Scores

| Page | Before | After | Improvement | Status |
|------|--------|-------|-------------|--------|
| homepage | 97% | **98%** | +1% | ✅ Near perfect |
| servizi | 53% (broken) | **75%** | +22% | 🟡 Good progress |
| amministrazione | 67% (broken) | **86%** | +19% | 🟢 Very good |

### HTML Structure Similarity

| Page | Similarity | Missing data-elements |
|------|------------|----------------------|
| servizi | 82% | service-link, load-other-cards, service-category-link |
| amministrazione | N/A | None major |

### What Works

- ✅ All pages render without errors (HTTP 200)
- ✅ View paths fixed in 4 JSON files
- ✅ Missing content blocks added (feedback, contacts, FAQ, booking)
- ✅ CSS infrastructure in place (servizi-parity-fix.css created)
- ✅ Build system working (npm run build && npm run copy)
- ✅ Automated testing tools created

### What Still Needs Work

- ⚠️ Servizi: 75% → 90%+ target (card icons, fine-tuning)
- ⚠️ Amministrazione: 86% → 90%+ target (card grid, links styling)
- ⚠️ Other 51 pages: Need parity check and fixes
- ⚠️ JavaScript/Alpine.js: Star rating, FAQ accordion, search

---

## Action Plan

### Phase 1: Fix Broken Pages (CRITICAL)

**Goal**: Make servizi and amministrazione pages render without errors

**Tasks**:

1. **Investigate view alias configuration**
   - Check if `pub_theme::` alias is correctly configured
   - Verify view namespace registration in ServiceProvider
   - File: `laravel/Themes/Sixteen/src/ServiceProvider.php` (or similar)

2. **Fix view paths in JSON** (if alias is wrong)
   - Update `tests.servizi.json` view references
   - Update `tests.amministrazione.json` view references
   - Test: `http://127.0.0.1:8000/it/tests/servizi` loads without error

3. **Verify all 54 pages render**
   - Run: `node bashscripts/screenshots/capture-visual-parity.mjs` (all pages)
   - Check for other broken pages
   - Fix any additional view errors

**Expected outcome**: All pages render, parity scores reflect actual CSS/JS differences

---

### Phase 2: CSS/JS Visual Fixes (HIGH PRIORITY)

**Goal**: Achieve 90%+ visual parity across all pages

**Tasks** (per page, starting with highest priority):

1. **Homepage (97% → 100%)**
   - Minor CSS refinements
   - Verify card layouts, spacing, hover effects
   - Test: Visual comparison matches reference

2. **Servizi (67% → 90%+)** (after fixing breakage)
   - Analyze screenshot differences
   - Fix grid layout CSS
   - Fix card styling
   - Fix header/footer styling
   - Test: Visual comparison matches reference

3. **Amministrazione (67% → 90%+)** (after fixing breakage)
   - Analyze screenshot differences
   - Fix administrative section layout
   - Fix card and list styling
   - Test: Visual comparison matches reference

4. **Other pages** (after Phase 1)
   - Prioritize by usage frequency
   - Fix CSS/JS differences
   - Test each page

---

### Phase 3: Documentation & Automation

**Goal**: Sustainable workflow for ongoing improvements

**Tasks**:

1. **Document all fixes** in `laravel/Themes/Sixteen/docs/`
   - Create per-page fix reports
   - Update master index with bidirectional links
   - Document CSS patterns and conventions

2. **Automate testing**
   - Screenshot comparison script (✅ already created)
   - HTML structure comparison (✅ already created)
   - Automated regression testing

3. **Create style guide**
   - Document Tailwind @apply patterns
   - Document Bootstrap Italia → Tailwind mapping
   - Create reusable component patterns

---

## File Structure

### Screenshot & Analysis Tools

```
bashscripts/
├── screenshots/
│   ├── capture-visual-parity.mjs      (Playwright screenshot capture)
│   └── compare-html-structure.mjs     (HTML structure comparison)
└── docs/
    └── SCREENSHOT-TOOLS.md            (Tool documentation)
```

### Theme Documentation

```
laravel/Themes/Sixteen/
├── resources/
│   ├── css/
│   │   ├── design-comuni.css          (main CSS)
│   │   ├── style-apply.css            (Bootstrap Italia override)
│   │   ├── design-comuni-visual-fix.css
│   │   └── homepage-visual-fix.css
│   ├── views/
│   │   └── components/blocks/         (All block views)
│   └── js/
│       └── app.js                     (Alpine.js + JS)
└── docs/
    ├── visual-comparison/
    │   ├── screenshots/
    │   │   ├── VISUAL-PARITY-REPORT.md
    │   │   └── visual-parity-data.json
    │   └── structure-analysis/
    │       └── {page}-html-comparison.md
    └── design-comuni/
        └── design-comuni-html-match-css-js-plan.md
```

---

## Build Commands

```bash
# Development
cd laravel/Themes/Sixteen
npm run dev          # Watch mode

# Build and deploy
npm run build        # Build CSS/JS with Vite
npm run copy         # Copy to public_html/themes/Sixteen/

# Analysis
node bashscripts/screenshots/capture-visual-parity.mjs [pages...]
node bashscripts/screenshots/compare-html-structure.mjs [page]
```

---

## Next Steps (Immediate)

1. **Fix view alias configuration** for `pub_theme::blocks.breadcrumb.default`
2. **Test servizi page** loads without error
3. **Re-run screenshot comparison** to get accurate parity score
4. **Begin CSS/JS fixes** for visual differences

---

## Related Documentation

| Document | Location |
|----------|----------|
| [Visual Parity Report](visual-comparison/screenshots/VISUAL-PARITY-REPORT.md) | Latest parity scores |
| [CSS/JS Plan](design-comuni-html-match-css-js-plan.md) | CSS fix strategy |
| [Screenshot Tools](../../../../bashscripts/docs/SCREENSHOT-TOOLS.md) | Tool documentation |
| [Complete Visual Parity Report](COMPLETE-VISUAL-PARITY-REPORT.md) | Historical data |
| [Design Comuni Index](design-comuni-implementation.md) | Implementation status |

---

**Last Updated**: 2026-04-04  
**Author**: AI Agent (BMAD + GSD workflow)
