# Header Design Comuni Parity Mapping

## Overview
This document maps the Design Comuni header structure to our Tailwind+Alpine+Lit implementation in the Sixteen theme. We're not rebuilding from scratch - we're adapting the existing Bootstrap Italia patterns to use Tailwind utilities.

## Header Structure Analysis

From the Design Comuni base.hbs template (segnalazione-02-dati), we see:

```hbs
{{#>cmp-base/base title="Segnalazione disservizio - Nome del Comune" headerActive3=true}}
<main>
  <!-- page content -->
</main>
{{/cmp-base/base}}
```

The header is rendered by `cmp-base/base` partial.

## Current Implementation vs Design Comuni

### 1. Header Wrapper Structure

**Design Comuni (Bootstrap Italia):**
```html
<header class="it-header-wrapper" data-bs-target="#header-nav-wrapper">
  <div class="it-header-slim-wrapper">
    <!-- slim bar -->
  </div>
  <div class="it-nav-wrapper">
    <!-- navigation -->
  </div>
</header>
```

**Our Implementation (Bootstrap Italia classes):**
```html
<header class="it-header-wrapper" data-bs-target="#header-nav-wrapper">
  <div class="it-header-slim-wrapper">
    <!-- slim bar -->
  </div>
  <div class="it-nav-wrapper">
    <!-- navigation -->
  </div>
</header>
```

**Note:** We're currently using the same Bootstrap Italia classes. This needs to be mapped to Tailwind.

### 2. Key Components Mapping

| Bootstrap Italia Class | Tailwind Equivalent | Notes |
|----------------------|-------------------|-------|
| `it-header-wrapper` | `@apply it-header-wrapper;` | Keep for compatibility |
| `it-header-slim-wrapper` | `@apply it-header-slim-wrapper;` | Keep for compatibility |
| `it-header-center-wrapper` | `@apply it-header-center-wrapper;` | Keep for compatibility |
| `it-header-navbar-wrapper` | `@apply it-header-navbar-wrapper;` | Keep for compatibility |
| `container` | `container` | Tailwind default |
| `row` | `flex` | Tailwind default |
| `col-12` | `w-full` | Tailwind default |
| `col-lg-10` | `max-w-4xl mx-auto` | Tailwind equivalent |
| `btn btn-primary` | `@apply btn-primary;` | Custom class in app.css |
| `nav-link` | `@apply nav-link;` | Custom class in app.css |
| `dropdown-toggle` | `@apply dropdown-toggle;` | Custom class in app.css |

### 3. Color Mapping from Design Comuni

From the Design Comuni static pages, the header uses:
- Slim wrapper: `#00402B` (dark green)
- Center wrapper: `#007A52` (main green)
- Navbar wrapper: `#007A52` (main green)
- Active nav item: White bottom border, no background fill

### 4. Implementation Strategy

We have two approaches:

#### Option A: Full Tailwind Conversion
Replace all Bootstrap Italia classes with Tailwind utilities:
```html
<!-- Instead of -->
<header class="it-header-wrapper">

<!-- Use -->
<header class="bg-green-800">
```

#### Option B: Hybrid Approach (Recommended)
Keep Bootstrap Italia class names but implement them as Tailwind utilities:
```css
/* In app.css */
.it-header-wrapper {
  @apply bg-green-800;
}
.it-header-slim-wrapper {
  @apply bg-green-900;
}
```

This maintains HTML parity while using Tailwind under the hood.

## Next Steps

1. **Phase 1:** Implement hybrid approach - map Bootstrap Italia classes to Tailwind utilities in `app.css`
2. **Phase 2:** Replace `data-bs-*` attributes with Alpine.js directives
3. **Phase 3:** Gradually replace class names with Tailwind utilities where beneficial

## Files to Update

- `laravel/Themes/Sixteen/resources/css/app.css` - Add Tailwind mappings
- `laravel/Themes/Sixteen/resources/views/components/bootstrap-italia/header.blade.php` - Alpine.js integration
- `laravel/Themes/Sixteen/resources/css/components/header-footer-colors.css` - Color fixes

## Reference URLs

- Design Comuni base template: https://github.com/italia/design-comuni-pagine-statiche/blob/main/src/pages/sito/segnalazione-02-dati.hbs
- Live reference: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html
- Our header implementation: `laravel/Themes/Sixteen/resources/views/components/bootstrap-italia/header.blade.php`

## Task Reference

- Story 7-105: Design Comuni segnalazione static pages — Bootstrap Italia class inventory → Tailwind parity
- Implementation artifact: `_bmad-output/implementation-artifacts/7-107-header-visual-parity-design-comuni.md`

## Notes

- We're maintaining the same class names for HTML parity while using Tailwind for styling
- The `data-bs-*` attributes will be replaced with Alpine.js directives for interactivity
- Colors are defined in CSS custom properties (`--color-italia`, `--color-italia-dark`)