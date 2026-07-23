# Font Fix Report - 2026-04-09

**Page**: `segnalazione-01-privacy`  
**Issue**: All 2,864 elements rendered monospace instead of Titillium Web  
**Resolution**: Font parity 100% achieved

## Root Causes (4 issues)

### 1. `@vite()` Manifest Incomplete
The CSS-only build (`vite.config.css-only.js`) excluded `resources/js/app.js` from the manifest. When `@vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')` couldn't find the JS entry, it rendered NOTHING - no CSS link, no script tag.

**Fix**: Copy the full manifest (with both CSS and JS entries) to `public_html/themes/Sixteen/manifest.json`.

### 2. Build Artifacts Not Deployed
`npm run copy` only copies specific asset files (SVGs, images) but NOT new CSS/JS build outputs. The new `app-BGyW13-7.css` was built but never copied to `public_html/`.

**Fix**: Manual copy of CSS and JS files to `public_html/themes/Sixteen/assets/`.

### 3. Tailwind v4 Strips Complex `:not()` Selectors
The selector `body *:not(pre):not(code):not(kbd):not(samp):not(.sf-dump):not(.phpdebugbar):not([class*="debugbar"]):not([class*="monospace"])` was completely removed during compilation.

**Fix**: Use simpler selectors:
```css
body, body *, html body, main, main *, .container, .container *, 
.row, .row *, [class*="col-"], [class*="col-"] * {
  font-family: "Titillium Web", Geneva, Tahoma, sans-serif !important;
}
```

### 4. `font-sans` Tailwind Utility Conflict
`@apply font-sans` in `style-apply.css` injected Tailwind's system-ui font stack, competing with the explicit `font-family: "Titillium Web"` declaration.

**Fix**: Remove `font-sans` from `@apply` in body rules:
```css
/* BEFORE */
html, body { @apply m-0 p-0 w-full h-auto font-sans leading-normal; }

/* AFTER */
html, body { @apply m-0 p-0 w-full h-auto leading-normal; }
```

## CSS Files Modified

1. `resources/css/style-apply.css` - Removed `font-sans` from body `@apply`
2. `resources/css/design-comuni-global.css` - Added font-family override with `!important`

## Build Process Fix

```bash
# Build CSS only (JS has syntax issues)
npx vite build --config vite.config.css-only.js

# Copy artifacts manually
cp public/assets/app-*.css ../../../public_html/themes/Sixteen/assets/
cp public/assets/app-*.js ../../../public_html/themes/Sixteen/assets/
cp public/manifest.json ../../../public_html/themes/Sixteen/manifest.json
```

## Verification

- **Before**: 2,864 monospace elements, 0 Titillium Web
- **After**: 0 monospace elements, ALL Titillium Web
- **HTML Parity**: ~90%+ (structural differences in href/svg use paths only)

## Files Changed

| File | Change |
|------|--------|
| `resources/css/style-apply.css` | Removed `font-sans` from body @apply (2 instances) |
| `resources/css/design-comuni-global.css` | Added font-family override with broad selectors |
