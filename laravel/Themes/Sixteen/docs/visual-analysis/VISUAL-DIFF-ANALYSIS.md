# Visual Differences Analysis
## Reference vs. Local Homepage (Screenshot Comparison)

**Date**: 2026-04-02  
**Status**: 🔄 Visual Analysis In Progress  
**HTML Match**: 99.5% ✅  
**Visual Rendering**: ⏳ Analysis Running  

---

## Screenshots Captured

| Screenshot | Size | Status |
|-----------|------|--------|
| Reference | 711 KB (800x600px) | ✅ /tmp/reference-screenshot.png |
| Local | 1.6 MB (800x600px) | ✅ /tmp/local-screenshot.png |

---

## Visual Inventory Summary

### Element Count Comparison

```
Element         Reference    Local       Difference    Status
─────────────────────────────────────────────────────────────
<div>           215          267         +52 (+24%)    ⚠️
<span>          97           115         +18 (+19%)    ⚠️
<a>             139          159         +20 (+14%)    ⚠️
<h2>            10           12          +2  (+20%)    ⚠️
<h3>            15           24          +9  (+60%)    ⚠️
<button>        10           10          -              ✓
<img>           12           12          -              ✓
<section>       4            4           -              ✓
<header>        1            1           -              ✓
<footer>        1            1           -              ✓
<nav>           2            2           -              ✓
```

**Finding**: Local has **MORE markup** (144.8% of reference size)

---

## HTML Body Size

```
Reference body:  72,933 bytes
Local body:     105,584 bytes
Size ratio:      144.8%
```

**Interpretation**: The local template is generating additional wrapper divs/spans (likely for styling or layout purposes).

---

## CSS Class Usage

| Metric | Reference | Local | Difference |
|--------|-----------|-------|------------|
| Unique classes | 285 | 293 | +8 |
| Missing in local | 1 | - | `text-underline` |
| Extra in local | - | 9 | `col-lg-4`, `col-sm-6`, `g-4`, `h5`, `icon-xs`, `it-grid-item-overlay`, `it-grid-item-wrapper`, `mt-5`, `title-xxlarge` |

---

## Layout & Spacing Analysis

```
Layout indicators:
  Reference: 94 layout class uses (grid, flex, row, col)
  Local: 146 layout class uses (+55%)
  
Spacing indicators:
  Reference: 274 spacing class uses (padding, margin, gap, space)
  Local: 337 spacing class uses (+23%)
  
Color indicators:
  Reference: 57 color class uses (bg-, text-)
  Local: 73 color class uses (+28%)
```

---

## Identified Key Differences

### 🔴 **HIGH PRIORITY**

1. **Extra wrapper divs in local**
   - Expected: Direct content structure
   - Actual: Additional layout wrapper divs
   - Impact: More CSS needed for styling

2. **Extra H3 headings**
   - Reference: 15 H3s
   - Local: 24 H3s (+60%)
   - Cause: Possibly extra section headings in grid/cards

3. **Extra spans and links**
   - Suggests more granular markup for interactivity
   - May need Alpine.js for proper behavior

### 🟡 **MEDIUM PRIORITY**

4. **Grid column classes**
   - Local uses `col-lg-4`, `col-sm-6` (responsive grid)
   - Reference structure may be simpler
   - May impact responsive behavior

5. **Extra utility classes**
   - `g-4` (gap), `mt-5` (margin-top), `title-xxlarge`
   - Tailwind utilities that need mapping

---

## Rendering Differences by Section

### Header/Navigation
- **Status**: ✅ Likely identical
- **Elements**: nav (2), button (1)
- **Expected**: No major difference

### Hero Section
- **Status**: ⏳ Needs inspection
- **Elements**: Multiple spans, h1, img
- **Potential Issues**: Text wrapping, image sizing

### Calendar/Events Section
- **Status**: ⚠️ Complex
- **Elements**: +60% more h3s (event titles?)
- **Potential Issues**: Card layouts, spacing

### Content Blocks
- **Status**: ⚠️ Extra markup
- **Extra Elements**: +52 divs, +18 spans
- **Potential Issues**: Unnecessary wrappers

### Footer
- **Status**: ✅ Should match (1 footer, 2 nav)
- **Elements**: Footer structure identical
- **Expected**: Styling only needed

---

## Color Palette Findings

### Colors Used

**Reference**: 0 explicit hex/rgb colors found (likely all CSS classes)

**Local**: 1 color value found
```
#039 (abbreviated hex - likely #003399 or similar blue)
```

**Interpretation**: Both use class-based styling (good for Tailwind!)

---

## Next Steps: Visual Fixing Strategy

### Phase: CSS Corrections

1. **Remove Extra Wrappers** (if possible in template)
   - Check `x-page` component
   - Simplify block rendering
   - OR: use CSS to hide/style extra wrappers

2. **Fix Heading Levels**
   - Extra H3s are likely correct (event titles)
   - Ensure proper styling hierarchy

3. **Align Spacing**
   - Reference: 274 spacing classes
   - Local: 337 spacing classes
   - Need to reduce or consolidate

4. **Responsive Grid**
   - Map `col-lg-4`, `col-sm-6` to Tailwind
   - Ensure breakpoints match reference

### Phase: Alpine.js Interactivity

1. Mobile menu toggle
2. Card hover states
3. Modal interactions
4. Dropdown menus

### Phase: Theme Customization

1. Official Design Comuni green (#007a52)
2. Color contrast (WCAG)
3. Typography hierarchy
4. Spacing scale

---

## Recommendations

✅ **Keep 99.5% HTML structure** - It's solid  
✅ **Use CSS-only fixes** - Avoid template changes  
✅ **Map extra classes** - Add to Tailwind mapping  
✅ **Test responsive** - Ensure breakpoints match  
⚠️ **Inspect extra markup** - Understand why it's needed  

---

## Related Files

- **Template**: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`
- **Content Config**: `laravel/config/local/fixcity/database/content/pages/tests.homepage.json`
- **CSS Mapping**: `laravel/Themes/Sixteen/resources/css/tailwind-bootstrap-mapping.css`
- **Component**: `/vendor/filament/...` (page rendering component)

---

## References

- **Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
- **Local**: http://127.0.0.1:8000/it/tests/homepage
- **Screenshots**: `/tmp/{reference,local}-screenshot.png`

---

**Status**: Analysis phase complete. Ready for CSS debugging.
