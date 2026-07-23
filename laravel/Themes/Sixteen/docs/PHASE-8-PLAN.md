# Phase 8 Plan: Visual Verification + Alpine.js Investigation

**Date**: 2026-04-02  
**Phase**: 8 - Multi-track parallel work  
**Status**: 🟡 PLANNING

---

## Overview

Parallel execution of two tracks:
1. **Track A**: Visual verification and final refinement
2. **Track B**: Alpine.js blocker investigation

**Duration**: 2-3 hours estimated  
**Complexity**: Medium (Track B is exploratory)

---

## Track A: Visual Verification (1-1.5h)

### Goals
- ✅ Confirm visual parity achieved
- ✅ Document any remaining differences
- ✅ Screenshot comparison
- ✅ Browser compatibility check

### Tasks

#### A1: Manual Browser Testing (30 min)
**Objective**: Verify all 5 CSS fixes are visually applied

**Steps**:
1. Open private/incognito browser window
2. Navigate to: http://127.0.0.1:8000/it/tests/homepage
3. Hard refresh (Ctrl+F5 or Cmd+Shift+R)
4. Check each fix:
   - [ ] Links are green (#007A52) - scan all areas (header, nav, body, footer)
   - [ ] Footer background color correct
   - [ ] Header height proportional
   - [ ] H1 color dark gray (not black)
   - [ ] Footer spacing adequate
5. No visual glitches or broken layouts
6. Document observations

**Verification Points**:
- Visual fix 1: Link colors ✅ or ❌
- Visual fix 2: Footer background ✅ or ❌
- Visual fix 3: Header height ✅ or ❌
- Visual fix 4: H1 color ✅ or ❌
- Visual fix 5: Footer spacing ✅ or ❌

#### A2: Side-by-Side Comparison (30 min)
**Objective**: Compare local vs reference in detail

**Process**:
1. Open two browser windows side-by-side
   - Left: http://127.0.0.1:8000/it/tests/homepage
   - Right: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
2. Compare key areas:
   - Header (colors, spacing, alignment)
   - Navigation (link colors, layout)
   - Main content (typography, colors)
   - Footer (background, text colors, spacing)
3. Document differences (if any)
4. Rate visual parity percentage

**Sections to Compare**:
- [ ] Header/Navigation area
- [ ] Hero/Main content
- [ ] Cards/Components
- [ ] Footer area

**Rating Scale**:
- 100%: Identical
- 95-99%: Nearly identical (minor differences)
- 90-94%: Very similar (acceptable differences)
- <90%: Significant differences (needs fixes)

#### A3: Screenshot Documentation (20 min)
**Objective**: Capture visual state for documentation

**Screenshots to Capture** (1920x1080 viewport):
1. Local homepage full page
2. Reference homepage full page
3. Local header (close-up)
4. Reference header (close-up)
5. Local footer (close-up)
6. Reference footer (close-up)
7. Local link color sample
8. Reference link color sample

**Tools**:
- Browser: Print to PDF (Ctrl+P, Save as PDF)
- Or: Screenshot tool (built-in OS screenshot)
- Or: Browser DevTools screenshot (F12 → Console)

**Storage**: `/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/screenshots/phase-8/`

### Deliverables
- [ ] Visual verification checklist completed
- [ ] Comparison notes documented
- [ ] Screenshots captured (8 images)
- [ ] Parity rating determined
- [ ] Any issues logged

---

## Track B: Alpine.js Investigation (1-1.5h)

### Goals
- 🔍 Investigate view resolution blocker
- 🔍 Find root cause of blade template issue
- 🔍 Explore potential solutions
- 🔍 Plan fix for next session

### Background

**Problem**: Alpine.js directives not rendering in blade templates
```
Expected: <nav @click="toggle()" ...>
Actual: <nav data-bs-toggle="navbarcollapsible" ...>
```

**Root Cause**: Unknown - likely CMS view resolution via `pub_theme::` namespace

### Tasks

#### B1: View Resolution Investigation (30 min)
**Objective**: Trace how blade templates are resolved

**Investigation Steps**:

1. **Find view resolution location**
```bash
cd /var/www/_bases/base_fixcity_fila5

# Search for pub_theme namespace
grep -r "pub_theme" . --include="*.php" | head -20

# Find where pub_theme is registered
grep -r "pub_theme::" . --include="*.php" | head -10

# Check ServiceProvider
find . -name "*ServiceProvider.php" -exec grep -l "pub_theme" {} \;
```

2. **Locate blade template loading**
```php
// Look for view resolution in:
- config/view.php
- app/Providers/*.php
- laravel/Themes/Sixteen/ServiceProvider.php
```

3. **Check cached views**
```bash
# Laravel caches compiled views
find storage/framework/views -name "*.php" | head -5
# Compare with source blade files
```

4. **Trace the namespace**
```php
// Find: 'pub_theme' => ['path' => '...']
// And: view('pub_theme::components.blocks.header.nav-wrapper')
// Understand: How does it resolve to file?
```

#### B2: Blade Template Analysis (25 min)
**Objective**: Understand current template structure

**Steps**:

1. **Locate blade files with Alpine directives**
```bash
grep -r "x-data\|@click\|x-show" laravel/Themes/Sixteen/resources/views --include="*.blade.php"
```

2. **Check if directives are being stripped**
```bash
# Search in compiled views
grep -r "x-data\|@click" storage/framework/views
```

3. **Test Alpine.js presence**
```bash
# Check if Alpine.js is loaded in HTML
curl -s http://127.0.0.1:8000/it/tests/homepage | grep -i "alpine\|x-data"
```

4. **Compare blade source vs output**
```
Source: laravel/Themes/Sixteen/resources/views/components/blocks/header/nav-wrapper.blade.php
Output: curl http://127.0.0.1:8000 | grep "nav-wrapper" area
```

#### B3: Potential Solutions (20 min)
**Objective**: Explore fixes

**Option 1: JavaScript Runtime Injection**
```javascript
// Add Alpine directives dynamically after page load
document.querySelectorAll('[data-alpine-mobile-menu]').forEach(el => {
  el.setAttribute('x-data', 'mobileMenu()');
  el.setAttribute('@click', 'toggle()');
});
Alpine.init();
```

**Option 2: View Alias Investigation**
```php
// Check if there's a view alias override
// Maybe pub_theme resolves to a different directory
```

**Option 3: CMS Block Override**
```php
// Check if CMS is overriding views at render time
// Look in: laravel/config/local/fixcity/...
```

**Option 4: Blade Raw Directives**
```blade
{!! Blade::compileString($template) !!}
// Or use view composition to inject Alpine at runtime
```

### Deliverables
- [ ] Root cause identified (or narrowed down)
- [ ] View resolution path documented
- [ ] Potential solutions listed (with pros/cons)
- [ ] Recommended fix selected
- [ ] Implementation plan created for Phase 9

---

## Combined Workflow

### If Track A completes first:
→ Help with Track B investigation  
→ Or: Prepare Phase 9 detailed plan

### If Track B completes first:
→ Document findings comprehensively  
→ Or: Help with Phase 9 Alpine.js implementation

### Parallel execution:
- Both tracks can run independently
- Share findings in documentation
- Coordinate on any file modifications

---

## Success Criteria

### Track A: Visual Verification
- [x] All 5 CSS fixes confirmed visually
- [x] Link colors verified green
- [x] No layout regressions
- [x] Visual parity rating ≥ 95%
- [x] Screenshots captured

### Track B: Alpine.js Investigation
- [x] Root cause identified
- [x] Potential solutions documented
- [x] Recommended fix specified
- [x] Implementation complexity assessed
- [x] Next steps clear

---

## Documentation Outputs

### Track A Outputs
- `docs/PHASE-8-VISUAL-VERIFICATION-REPORT.md`
- Screenshot folder: `docs/screenshots/phase-8/`
- Updated main INDEX.md with verification status

### Track B Outputs
- `docs/PHASE-8-ALPINE-INVESTIGATION.md`
- Root cause analysis
- Solution comparison matrix
- Implementation plan for Phase 9

---

## Timeline Estimate

| Task | Duration | Status |
|------|----------|--------|
| A1: Browser Testing | 30 min | ⏳ Ready |
| A2: Comparison | 30 min | ⏳ Ready |
| A3: Screenshots | 20 min | ⏳ Ready |
| B1: Investigation | 30 min | ⏳ Ready |
| B2: Analysis | 25 min | ⏳ Ready |
| B3: Solutions | 20 min | ⏳ Ready |
| **Total** | **155 min (~2.5h)** | |

---

## Next Phase (Phase 9)

Based on Phase 8 findings:

### If Visual Parity ≥ 95%:
→ **Phase 9**: Alpine.js Implementation
- Implement chosen solution
- Fix blade templates
- Test interactivity
- Document results

### If Visual Issues Found:
→ **Phase 9**: CSS Refinement
- Fix identified issues
- Rebuild and deploy
- Reverify visually
- Then Alpine.js

---

## Risk Assessment

### Track A Risks
- ✅ Low risk - mostly observation and testing
- Minor: Browser cache issues (mitigated: use incognito)

### Track B Risks
- 🟡 Medium risk - code investigation required
- Issue: Root cause might be complex
- Mitigation: Document all findings for future investigation

---

## Resources Needed

### Track A
- Browser (Chrome/Firefox/Safari)
- DevTools (F12)
- Screenshot tool
- Reference page: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html

### Track B
- Code editor/IDE
- Terminal/Bash
- grep/find/sed tools
- Laravel documentation
- View.php config reference

---

## Notes

- Both tracks are INDEPENDENT - can work simultaneously
- Track A provides visual confirmation
- Track B provides technical insight for future fixes
- No code changes expected in Phase 8
- All work is exploratory and investigative

---

**Phase 8 Status**: ✅ READY TO START

Next: Execute Track A and Track B in parallel

