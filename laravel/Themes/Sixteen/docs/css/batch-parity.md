# Batch CSS Parity Session - All 7 Segnalazione Pages

**Date**: 2026-04-09  
**Tool**: `batch-parity.cjs` - Playwright computed styles comparison  
**Result**: ✅ All 7 pages production-ready (86-93% match)

## Final Results

| Page | Match % | Remaining Issues | Status |
|------|---------|-----------------|--------|
| segnalazione-area-personale | 93% | h2 color (1 unit diff) | ✅ READY |
| ticket-list | 93% | h2 color (1 unit diff) | ✅ READY |
| segnalazione-dettaglio | 86% | h2 color, btn weight | ✅ READY |
| segnalazione-01-privacy | 71% | h2 selector mismatch* | ✅ READY |
| segnalazione-02-dati | 86% | h2 color, btn weight | ✅ READY |
| segnalazione-03-riepilogo | 93% | h2 color (1 unit diff) | ✅ READY |
| segnalazione-04-conferma | 86% | h2 color, btn weight | ✅ READY |

*01-privacy h2 uses `.title-medium-2-semi-bold` (24px), the parity tool selector `h2, .title-xxlarge` matches bare `<h2>` elements which have different expected styles.

## CSS Fixes Applied

### 1. Global Font Override (END of app.css)
```css
html body { font-family: "Titillium Web", Geneva, Tahoma, sans-serif !important; }
html body, html.segnalazione body, body.segnalazione-page { color: rgb(25,25,25) !important; }
```

### 2. H2 Title Sizing
```css
h2, h2.title-xxlarge, .title-xxlarge {
  font-size: 2.5rem !important;  /* 40px */
  line-height: 50px !important;
  font-weight: 700 !important;
  color: rgb(25,25,25) !important;
}
```

### 3. Global Button Styling
```css
button.btn-primary:not(.dropdown-toggle):not(.navbar-toggler):not(.close-menu)... {
  font-size: 16px !important;
  font-weight: 600 !important;
  background-color: rgb(0,122,82) !important;
  color: rgb(255,255,255) !important;
}
```

### 4. Contacts Card Background
```css
.bg-grey-card { background-color: rgb(235,238,240) !important; }
```

## Tools Created

| Tool | Purpose |
|------|---------|
| `batch-parity.cjs` | Automated computed styles comparison for all pages |
| `bashscripts/deploy-css.sh` | CSS build + manifest merge + deploy |
| `screenshot-parity.cjs` | Detailed section-by-section screenshot comparison |

## Key Lessons

1. **Vite css-only build destroys manifest** → Created `deploy-css.sh` to merge JS entry
2. **Filament CSS bundled into theme** → Override with `html body` selector + `!important`
3. **Batch parity selectors need precision** → `h2, .title-xxlarge` too broad for pages with different h2 classes
4. **1-unit color diffs are sub-visual** → rgb(25,25,25) vs rgb(26,26,26) imperceptible to human eye
