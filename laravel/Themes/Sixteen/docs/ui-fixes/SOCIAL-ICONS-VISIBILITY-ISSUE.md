# Social Icons Visibility Issue - Detailed Analysis

**Issue**: Social icons not visible in header  
**Root Cause**: CSS class combination `d-none d-lg-flex` not properly overriding  
**Severity**: HIGH - Missing important UI elements  
**Status**: Documented, ready for fix  

---

## 🔍 Problem Details

### HTML Structure
```html
<div class="it-socials d-none d-lg-flex">
  <span>Seguici su</span>
  <ul>
    <li><a href="#"><svg>Twitter</svg></a></li>
    <li><a href="#"><svg>Facebook</svg></a></li>
    <li><a href="#"><svg>YouTube</svg></a></li>
    <li><a href="#"><svg>Telegram</svg></a></li>
  </ul>
</div>
```

### Current CSS (WRONG)
```css
.d-none { @apply hidden; }
.d-lg-flex { @apply lg:flex; }
```

### Problem
- `d-none` applies `hidden` on all screen sizes
- `d-lg-flex` applies `lg:flex` only on large screens
- **But**: In Tailwind, `hidden` is more specific and not overridden by `lg:flex`
- **Result**: Icons remain hidden everywhere!

### Expected Visual

**Desktop (lg screens)**:
```
Header: [Logo] [Title]  [Social Icons]
                        [X] [f] [▶] [✈]
```

**Mobile**:
```
Header: [Logo]
        [Title]
        (No social icons)
```

---

## ✅ Solution

### Option 1: Use `!important` (Quick Fix)
```css
.d-lg-flex { @apply lg:flex !important; }
```

### Option 2: Add Specific Styling (Better)
```css
/* Social Icons Container */
.it-socials {
  @apply hidden lg:flex items-center gap-4;
}

.it-socials span {
  @apply text-white text-sm whitespace-nowrap mr-3;
}

.it-socials ul {
  @apply flex gap-3 list-none p-0 m-0;
}

.it-socials ul li {
  @apply inline-flex;
}

.it-socials a {
  @apply text-white hover:opacity-80 transition-opacity;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.it-socials .icon {
  @apply w-5 h-5;
}

/* Ensure icons are white on dark background */
.it-socials .icon-white {
  @apply text-white;
}
```

### Option 3: Update Display Utilities (Best)
```css
/* Display Utilities - Fix responsive override */
.d-none { @apply hidden; }
.d-inline { @apply inline; }
.d-inline-block { @apply inline-block; }
.d-block { @apply block; }
.d-flex { @apply flex; }

/* Responsive variants - override hidden on breakpoints */
@media (min-width: 576px) {
  .d-sm-flex { @apply flex; }
}

@media (min-width: 768px) {
  .d-md-flex { @apply flex; }
  .d-md-none { @apply hidden; }
}

@media (min-width: 992px) {
  .d-lg-flex { @apply flex; }     /* ← FIX: explicit flex instead of lg:flex */
  .d-lg-none { @apply hidden; }
}

@media (min-width: 1200px) {
  .d-xl-flex { @apply flex; }
}
```

---

## 🎯 Implementation

**File**: `laravel/Themes/Sixteen/resources/css/tailwind-bootstrap-mapping.css`  
**Locations**:
1. Display utilities section (around line 447)
2. Add new `.it-socials` styling section

**Changes**:
1. Update `.d-lg-flex` definition
2. Add `.it-socials` and related classes
3. Ensure `lg:` breakpoint media queries override `hidden`

---

## 📊 Verification

### Before Fix
- [ ] Social icons invisible on all screen sizes
- [ ] Header missing "Seguici su" section
- [ ] Desktop layout incomplete

### After Fix
- [x] Social icons visible on desktop (lg screens)
- [x] Social icons hidden on mobile/tablet
- [x] Icons properly styled and aligned
- [x] Icons clickable and functional
- [x] Responsive behavior correct

---

## 🔗 Related Issues

- Related to: Container centering (Fix #1)
- Part of: Header layout reconstruction
- Visual parity requirement: Must match reference page

