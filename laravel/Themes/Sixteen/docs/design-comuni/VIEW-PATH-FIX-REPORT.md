# View Path Fix - Progress Report

**Date**: 2026-04-04  
**Status**: ✅ View paths fixed, pages rendering  
**Next**: CSS/JS visual fixes  

---

## Problem Identified

Pages were broken with error:
```
InvalidArgumentException
View [blocks.breadcrumb.default] not found.
```

### Root Cause

JSON content files referenced views without `components/` prefix:
- ❌ Wrong: `pub_theme::blocks.breadcrumb.default`
- ✅ Correct: `pub_theme::components.blocks.breadcrumb.default`

The `pub_theme::` namespace maps to `laravel/Themes/Sixteen/resources/views/`, and all block views are in the `components/blocks/` subdirectory.

---

## Fix Applied

### Script Created
- **File**: `bashscripts/fix-view-paths.php`
- **Purpose**: Automated fix of view paths in JSON content files
- **Usage**: `php bashscripts/fix-view-paths.php [page1,page2,...]` or `--all`

### Files Fixed
4 JSON files updated:
1. `tests.servizi.json`
2. `tests.amministrazione.json`  
3. `tests.enti-e-fondazioni.json`
4. `tests.organo.json`

### Pattern Applied
```json
// Before
"view": "pub_theme::blocks.breadcrumb.default"

// After
"view": "pub_theme::components.blocks.breadcrumb.default"
```

---

## Results

### Before Fix
| Page | Status | Parity |
|------|--------|--------|
| homepage | ✅ Working | 97% |
| servizi | ❌ Error (500) | 67%* |
| amministrazione | ❌ Error (500) | 67%* |

*Parity score was misleading - pages were broken

### After Fix
| Page | HTTP Status | HTML Similarity | Visual Parity | Height (Local/Ref) |
|------|-------------|-----------------|---------------|-------------------|
| homepage | 200 ✅ | N/A | 97% | 4498px / 4629px |
| servizi | 200 ✅ | 76% | 53% | 2446px / 4628px |
| amministrazione | 200 ✅ | N/A | 67% | 2093px / 3120px |

---

## Analysis

### Homepage (97% parity)
- ✅ Rendering correctly
- ⚠️ Minor CSS refinements needed
- **Next**: Fine-tune spacing, card layouts, hover effects

### Servizi (53% parity)
- ✅ Now rendering (was broken)
- ⚠️ HTML structure 76% similar
- ⚠️ Missing 5 `data-element` attributes:
  - `service-link`
  - `load-other-cards`
  - `service-category-link`
  - `feedback`
  - `feedback-title`
- ⚠️ Page height difference: 2182px (local is shorter)
- **Next**: 
  1. Add missing interactive elements (service links, feedback section)
  2. Fix CSS for card layouts, grids, spacing

### Amministrazione (67% parity)
- ✅ Now rendering (was broken)
- ⚠️ Page height difference: 1027px
- **Next**: Analyze screenshots, fix CSS layouts

---

## Next Steps

### Immediate (Today)
1. ✅ Document view path fix (this file)
2. ⏳ Create CSS fix plan for servizi and amministrazione
3. ⏳ Implement CSS fixes in `design-comuni-visual-fix.css`
4. ⏳ Rebuild and verify

### Short Term (This Week)
1. Fix all pages with <80% parity
2. Create per-page CSS fix reports
3. Automate regression testing with screenshots

### Long Term
1. Achieve 90%+ parity across all pages
2. Document CSS patterns and conventions
3. Create style guide for future development

---

## Build Commands

```bash
# Fix view paths
php bashscripts/fix-view-paths.php --all

# Build and deploy
cd laravel/Themes/Sixteen
npm run build && npm run copy

# Test pages
node bashscripts/screenshots/capture-visual-parity.mjs [pages...]
node bashscripts/screenshots/compare-html-structure.mjs [page]
```

---

## Related Documentation

| Document | Location |
|----------|----------|
| [Action Plan](../design-comuni/VISUAL-PARITY-ACTION-PLAN.md) | Overall strategy |
| [Visual Parity Report](../visual-comparison/screenshots/VISUAL-PARITY-REPORT.md) | Latest scores |
| [HTML Comparison](../visual-comparison/structure-analysis/servizi-html-comparison.md) | Structure analysis |
| [Screenshot Tools](../../../../bashscripts/docs/SCREENSHOT-TOOLS.md) | Tool documentation |
| [JSON Fix Tools](../../../../bashscripts/docs/JSON-FIX-TOOLS.md) | Fix scripts (to create) |

---

**Last Updated**: 2026-04-04  
**Author**: AI Agent (BMAD + GSD workflow)
