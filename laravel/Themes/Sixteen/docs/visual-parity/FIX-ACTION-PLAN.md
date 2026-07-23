# VISUAL PARITY - Fix Action Plan

## Current Status: ~70% Parity

### Key Differences Found

#### 1. Hero Section
- **Reference**: Has h1 with full title, subtitle in hero-text
- **Local**: Has h1 but different title from JSON content
- **Fix needed**: Ensure hero text styling matches

#### 2. Category Cards Grid
- **Reference**: Uses `col-md-6 col-xl-4`, `border border-light rounded shadow-sm`
- **Local**: Uses `col-md-6 col-lg-4`, similar classes
- **Status**: ~80% similar

#### 3. Missing "In evidenza" Section
- **Reference**: Has "In evidenza" section with featured items
- **Local**: Missing this section (comes from JSON data)
- **Fix**: Add featured items section if present in JSON

#### 4. Classes Used
- Reference uses Bootstrap Italia native classes
- Local uses mix of Tailwind + custom classes

## CSS Fixes to Implement

```css
/* lista-categorie parity fixes */

/* Hero text alignment */
.it-hero-text-wrapper {
    padding-left: 0 !important;
}

/* Category card grid */
.col-md-6.col-xl-4 {
    /* Ensure consistent grid */
}

/* Card shadow matching */
.card-wrapper.border.border-light.rounded.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
```

## Files to Modify

1. `resources/css/app.css` - Add parity fixes
2. `resources/css/listing-parity.css` - Specific listing fixes

## Verification Steps

1. Build: `npm run build`
2. Copy: `npm run copy`
3. Test: Visit `http://127.0.0.1:8000/it/tests/lista-categorie`
4. Compare with: `https://italia.github.io/design-comuni-pagine-statiche/sito/lista-categorie.html`