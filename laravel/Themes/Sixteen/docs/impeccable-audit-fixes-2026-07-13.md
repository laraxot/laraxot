# Impeccable Audit Fixes — Sixteen Theme

**Date:** 2026-07-13  
**Scope:** public-facing home page (`resources/views/home.blade.php`)  
**Tool:** Impeccable v3.9.1 (manual application in Cascade)

## Issues fixed

### P1 — Form input without label
- **Location:** `resources/views/home.blade.php:49-52`
- **Fix:** Added `<label for="search" class="sr-only">Cerca segnalazioni</label>`, added `id="search"` to input, added `role="search"` to form.
- **Impact:** WCAG 2.1 A compliant labels; screen readers now announce the search purpose.

### P1 — External CDN dependency for Leaflet
- **Location:** `resources/views/home.blade.php:192-207`
- **Fix:** Replaced unpkg CDN links and inline script with `resources/js/pages/home.js` bundled by Vite.
- **Details:**
  - Import `L` from `leaflet` and `leaflet/dist/leaflet.css`.
  - Tile attribution includes proper `&copy;` copyright link.
  - Map respects `prefers-reduced-motion` by disabling zoom, marker and fade animations when requested.
  - Added `resources/js/pages/home.js` to `vite.config.js` input array.
- **Impact:** No external CDN blocking risk; asset is hashed, cached and version-controlled through the build.

### P2 — Inline scripts in Blade templates
- **Location:** `resources/views/home.blade.php:192-207`
- **Fix:** Moved map initialization into `resources/js/pages/home.js` compiled by Vite.
- **Impact:** CSP-friendly, cacheable, separates JS from markup.

### P2 — No visible `prefers-reduced-motion` handling
- **Location:** project-wide (`resources/css/app.css`)
- **Fix:** Added `@media (prefers-reduced-motion: reduce)` rule that nullifies `animation-duration`, `animation-iteration-count`, `transition-duration` and `scroll-behavior` globally.
- **Impact:** Users with vestibular disorders get a motion-safe experience.

### P3 — Nav links use `href="#"` in template stub
- **Location:** `resources/views/home.blade.php:24-27`
- **Fix:** Replaced stubs with real Folio page paths:
  - Amministrazione → `/administration`
  - Novità → `/news`
  - Servizi → `/services`
  - Vivere il Comune → `/`
- **Also:** Added `aria-label="Navigazione principale"` to the nav element and fixed typo (`Vivere il Comune1` → `Vivere il Comune`).

## Build verification

```bash
cd /var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen
npm install
npm run build
# ✓ built in 4.33s
```

Asset generated: `public_html/themes/Sixteen/assets/home-BamhdYSA.js`

## PHPStan verification

```bash
cd /var/www/_bases/base_fixcity_fila5/laravel
php -d memory_limit=2048M ./vendor/bin/phpstan analyse Modules --no-progress
# [OK] No errors
```

## Remaining work

- Reduce `!important` specificity debt in civic CSS files (P1 from original audit).
- Replace placeholder category counts in sidebar with dynamic data.
- Add visible focus styles for keyboard navigation on map toggle buttons.
- Run Impeccable `critique` and `polish` passes on remaining pages (services, administration, news).

## Lock file

- `laravel/Themes/Sixteen/.impeccable-home.lock` was used during edits and removed after verification.
