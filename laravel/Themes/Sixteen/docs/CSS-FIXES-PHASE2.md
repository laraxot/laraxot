# CSS Fixes Phase 2 - Detailed Analysis

## Target
Make local homepage pixel-perfect match to reference.

##  Key Findings

### 1. Footer Padding Conflict
**Local**: `padding: 48px 0px` (py-8 from mapping + extra from somewhere)
**Reference**: `padding: 0px` (no padding)
**Root Cause**: 
- CSS in `app.css` sets `padding: 0 !important` on `.it-footer`
- CSS in `tailwind-bootstrap-mapping.css` applies `py-8` (2rem = 32px)
- Somewhere else adds extra 16px
**Fix**: Remove `py-8` from mapping or adjust specificity

### 2. Useful-Links Section (118px difference)
**Local**: 591px inner height, 800px width
**Reference**: 473px inner height, 720px width
**Hypothesis**: Grid layout or content spacing differs
**Possible Causes**:
- Row/column spacing
- Gap between items
- Line height differences
- Font size differences
**Status**: NEEDS INVESTIGATION

### 3. Other Sections (49-74px differences)
- Calendar, Evidence, Hero sections all +15-74px
- Likely due to cumulative spacing issues

## Fix Strategy

1. **Priority 1: Remove Footer padding**
   - Edit `tailwind-bootstrap-mapping.css` line 256
   - Remove or reduce `py-8` 
   - Test: should match 0px reference

2. **Priority 2: Debug Useful-Links**
   - Check grid layout (grid-template-columns)
   - Check gap between items
   - Check line-height and font-size

3. **Priority 3: General spacing audit**
   - Review all `py-*`, `px-*` values
   - Check `gap-*` values on grid sections
   - Verify Bootstrap class mappings

## Implementation

```bash
# After each change:
cd laravel/Themes/Sixteen
npm run build && npm run copy

# Then check at:
# http://127.0.0.1:8000/it/tests/homepage
```

## Testing
Use Puppeteer script to measure sections and compare:
```js
// Measure section heights
document.querySelector('footer.it-footer').clientHeight
document.querySelector('.useful-links-section').clientHeight
```

