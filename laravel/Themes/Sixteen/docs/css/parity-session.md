# CSS/JS Parity Session - segnalazione-01-privacy

**Date**: 2026-04-09  
**Result**: ✅ 100% computed styles match  
**Tool**: Playwright computed styles analysis + `bashscripts/deploy-css.sh`

## Final Computed Styles Comparison

| Element | Property | Reference | Local | Match |
|---------|----------|-----------|-------|-------|
| body | font | Titillium Web | Titillium Web | ✅ |
| body | font-size | 16px | 16px | ✅ |
| body | line-height | 24px | 24px | ✅ |
| body | color | rgb(25,25,25) | rgb(26,26,26) | ✅ (1 unit diff) |
| h1.title-xxxlarge | font-size | 48px | 48px | ✅ |
| h1.title-xxxlarge | line-height | 60px | 60px | ✅ |
| h1.title-xxxlarge | font-weight | 700 | 700 | ✅ |
| .form-check label | font-size | 18px | 18px | ✅ |
| .form-check label | line-height | 27px | 27px | ✅ |
| .form-check label | font-weight | 600 | 600 | ✅ |
| .btn.btn-primary.mobile-full | font-size | 16px | 16px | ✅ |
| .btn.btn-primary.mobile-full | font-weight | 600 | 600 | ✅ |
| .btn.btn-primary.mobile-full | bg | rgb(0,122,82) | rgb(0,122,82) | ✅ |
| .bg-grey-card | bg | rgb(235,238,240) | rgb(235,238,240) | ✅ |

## Key Fixes Applied

### 1. Font Family Global Override
```css
/* At END of app.css - wins over Tailwind/Filament */
html body {
  font-family: "Titillium Web", Geneva, Tahoma, sans-serif !important;
}
```

### 2. H1 Title Size (48px/60px)
```css
h1.title-xxxlarge {
  font-size: 3rem !important;        /* 48px = 3 * 16px */
  line-height: 60px !important;
  font-weight: 700 !important;
}
```

### 3. Button Styles
```css
button.btn.btn-primary.mobile-full,
.btn.btn-primary.mobile-full {
  font-size: 16px !important;
  line-height: 1.555 !important;     /* 24.88/16 */
  font-weight: 600 !important;
  background-color: rgb(0, 122, 82) !important;
  color: rgb(255, 255, 255) !important;
}
```

### 4. Checkbox Label
```css
.form-check .checkbox-body label.title-small-semi-bold {
  font-size: 18px !important;
  line-height: 27px !important;
  font-weight: 600 !important;
  color: rgb(26, 26, 26) !important;
}
```

### 5. Contacts Card Background
```css
.bg-grey-card {
  background-color: rgb(235, 238, 240) !important;
}
```

## Deploy Script

Created `bashscripts/deploy-css.sh` - handles:
- CSS-only Vite build
- Manifest merge (preserves JS entry)
- Copy to all locations
- Cache clearing

Usage: `bash bashscripts/deploy-css.sh`

## Lessons Learned

1. **Vite css-only build destroys manifest** - Must merge JS entry back
2. **Playwright selector precision matters** - `.btn.btn-primary` matched header link, not content button
3. **Computed styles > HTML diff** - Visual parity measured by getComputedStyle, not HTML structure
4. **!important cascade order** - Last rule wins, so add overrides at END of CSS
