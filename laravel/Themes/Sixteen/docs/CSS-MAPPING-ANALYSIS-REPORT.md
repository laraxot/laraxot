# CSS Mapping Analysis Report

**Date**: 2026-04-02  
**Status**: Bootstrap → Tailwind CSS mapping in progress

## Summary

- **Total unique classes**: 299
- **Mapped in CSS**: 218
- **Unmapped**: 163 (includes custom components + missed Bootstrap utilities)
- **Coverage**: 73%

## Unmapped Class Categories

### 1. Custom Italia Components (cmp-*, it-*, dc-*)
These are Design Comuni specific and need custom Tailwind styling:
- `cmp-radio-list`, `cmp-radio-list__item` - Radio button components
- `cmp-rating`, `cmp-steps-rating` - Rating components
- `cmp-input-search` - Search input wrapper
- `cmp-contacts` - Contact section component
- `it-brand-text`, `it-brand-tagline` - Branding components
- `dc-homepage-parity` - Homepage-specific class
- `evidence-section` - Section styling
- `home-carousel-title` - Carousel section

### 2. Bootstrap Display Utilities
Need @apply mappings:
- `d-lg-inline-flex` - Display flex at lg breakpoint
- `align-items-lg-center` - Alignment at lg breakpoint
- `align-top` - Vertical alignment

### 3. Bootstrap Form Components
- `form-check` - Checkbox/radio wrapper
- `form-control` - Input field styling
- `form-group` - Form group wrapper
- `form-text` - Helper text styling
- `input-group`, `input-group-append` - Input group styling
- `autocomplete`, `autocomplete-wrapper`, `autocomplete-icon` - Autocomplete styling
- `dropdown`, `dropdown-menu`, `dropdown-toggle`, `dropdown-item` - Dropdown styling

### 4. Bootstrap Utility Classes
- `g-4` - Gap/gutter utility (Bootstrap 5)
- `col`, `col-sm-6` - Grid column (missed from main mapping)
- `img-fluid` - Responsive image
- `button-shadow` - Shadow effect for buttons
- `drop-shadow` - Filter drop shadow

### 5. Icon Sizing
- `icon-md`, `icon-xs` - Missing icon size classes
- `icon-left` - Icon positioning

### 6. Miscellaneous
- `full` - Full width utility
- `close-div`, `close-menu` - Utility classes
- `h5` - Heading size (Bootstrap uses h1-h6 as classes)
- `has-megamenu` - Megamenu indicator
- `fade` - Transition effect

## Implementation Plan

1. **Add custom component classes** - Define Tailwind @layer components for all cmp-*, it-*, dc-* classes
2. **Add missing Bootstrap utilities** - Map form components, display utilities, alignment
3. **Add Bootstrap 5 specific** - Handle g-* gap utilities, newer patterns
4. **Verify with screenshots** - After adding mappings, rebuild and compare visually

## Next Steps

1. Update `tailwind-bootstrap-mapping.css` with unmapped classes
2. Rebuild: `npm run build && npm run copy`
3. Take visual screenshots to identify any remaining styling issues
4. Iterate on specific component styling
