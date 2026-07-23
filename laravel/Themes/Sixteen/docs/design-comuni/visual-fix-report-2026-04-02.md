# Visual Fix Report - 2026-04-02

**Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
**Local**: http://127.0.0.1:8000/it/tests/homepage

## Analysis Results (Computed Styles + DOM)

### 1. Centering ✅ IDENTICAL
Both ref and fix have identical centering:
- Container: width 1320px, left 60px, right 60px
- All wrappers: width 1440px, left 0, right 1440

### 2. Social Icons ❌ INVISIBLE
- **Problem**: Icons have `color: rgb(0,122,82)` (green) on green header background
- **Reference**: Icons are white on green header
- **Fix**: Set social icon SVG color to white

### 3. Search Modal ⚠️ Partially Hidden
- `display: none` ✅
- `visibility: visible` ❌ (should be hidden)
- `opacity: 1` ❌ (should be 0)
- No `.show` class ✅

### 4. Footer ❌ Color Mismatch
| Property | Reference | FixCity |
|----------|-----------|---------|
| Footer bg | transparent | rgb(36,50,63) |
| Footer-main bg | rgb(32,42,46) | rgb(36,50,63) |
| Footer-main color | rgb(255,255,255) | rgba(255,255,255,0.82) |
| Height | 775px | 756px |

## Fixes Required

1. Social icons: `color: #fff !important` + SVG fill white
2. Search modal: `visibility: hidden` + `opacity: 0` when not shown
3. Footer bg: `rgb(32,42,46)` (#202a2e)
4. Footer text: `#fff` full opacity

## Screenshots
- `docs/screenshots/2026-04-02/ref-fullpage-analysis.png`
- `docs/screenshots/2026-04-02/fix-fullpage-analysis.png`
- `docs/screenshots/2026-04-02/ref-footer-detailed.png`
- `docs/screenshots/2026-04-02/fix-footer-detailed.png`
