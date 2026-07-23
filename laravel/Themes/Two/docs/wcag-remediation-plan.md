# WCAG 2.1 AA Remediation Plan - Theme Two

## Overview
This document outlines the specific steps to remediate WCAG 2.1 AA failures identified in Theme Two.

## Affected Components & Fixes

### 1. Hero Section
**File:** `Themes/Two/resources/views/components/blocks/hero/main.blade.php`
- **Issues:**
    - G18 (Contrast): Text over image/gradient might be insufficient.
    - H67 (Decorative Images): Backgrounds/icons need proper attributes.
    - ARIA6: "Learn More" type buttons need descriptive `aria-label`.
- **Action:**
    - Strengthen bg-gradient opacity.
    - Add `aria-label="{{ $ctaPrimary['label'] }}"` if label is generic.
    - Ensure `role="img"` or `aria-hidden="true"` on SVGs.

### 2. Services Grid
**File:** `Themes/Two/resources/views/components/blocks/services/grid.blade.php`
- **Issues:**
    - G195 (Focus): Hover effects exist, but focus rings might be missing.
    - H67 (Icons): Decorative icons need `aria-hidden="true"`.
- **Action:**
    - Add `focus:ring-2 focus:ring-offset-2` to links.
    - Add `aria-hidden="true"` to icon SVGs.

### 3. Sectors Split
**File:** `Themes/Two/resources/views/components/blocks/sectors/split.blade.php`
- **Issues:**
    - G18 (Contrast): White text on light brand colors?
    - Structure: Verify hierarchy.
- **Action:**
    - Review text colors on `bg-brand-blue/10`.
    - Ensure correct heading levels ($sector['title']).

### 4. Navbar
**File:** `Themes/Two/resources/views/components/navbar.blade.php`
- **Issues:**
    - ARIA6/F96: Mobile menu button needs proper state management.
    - G18: Link contrast.
- **Action:**
    - Verify `aria-expanded` toggling.
    - Check contrast of gray text.

### 5. Footers
**File:** `Themes/Two/resources/views/components/sections/footer/v1.blade.php` (and v2)
- **Issues:**
    - H30 (Link Purpose): Social icons need text alternatives.
    - G162 (Labels): Newsletter input needs label.
    - G18: Contrast of footer text.
- **Action:**
    - Add `aria-label="Facebook"`, etc. to social links.
    - Add `<label class="sr-only">` for newsletter input.
    - darken footer text colors or lighten background.

## Global Fixes
**File:** `Themes/Two/resources/css/app.css`
- **Issues:**
    - C12 (Units): Ensure `rem` is used.
    - C21 (Line Height): Global line-height check.
- **Action:**
    - Verify Tailwind config implies reasonable defaults.
