# Current CSS State Analysis - FAQ Components

## Executive Summary

The FAQ page has multiple CSS files managing styles with a mix of Bootstrap Italia classes and Tailwind CSS utilities. Current state shows:
- **Status**: Partially implemented with Bootstrap Italia dependencies
- **Goal**: Transition to pure Tailwind CSS (NO Bootstrap Italia classes)
- **Files to Update**: Component templates, CSS rules
- **Key Challenge**: Custom colors, spacing, and interactive states

---

## Current CSS Files Inventory

| File | Size | Purpose | Status |
|------|------|---------|--------|
| `app.css` | 47KB | Main compiled Tailwind + overrides | **ACTIVE** |
| `faq-parity.css` | 16KB | FAQ-specific Design Comuni styling | **PARTIAL** |
| `style-apply.css` | 34KB | Component styles + utilities | **ACTIVE** |
| `design-comuni.css` | 38KB | Design Comuni framework | **ACTIVE** |
| `tailwind-bootstrap-mapping.css` | 22KB | Bootstrap → Tailwind mappings | **REFERENCE** |
| `bootstrap-italia.css` | 6.9KB | Bootstrap Italia framework | **DEPRECATED** |
| `agid.css` | 6.9KB | AGID color definitions | **ACTIVE** |

---

## Component-by-Component Current State

### 1. BREADCRUMB (.cmp-breadcrumbs)

**Current Implementation Status**: ✅ MOSTLY COMPLETE

**Current Classes Used**:
```css
.cmp-breadcrumbs {
  @apply mt-4;  /* margin-top: 1rem */
}

.breadcrumb {
  @apply flex items-center p-0 m-0 list-none text-sm;
}

.breadcrumb-item {
  @apply flex items-center;
}
```

**Bootstrap Italia Classes Still Used**:
- `.breadcrumb` - Standard list
- `.breadcrumb-item` - List items
- `.breadcrumb-item.active` - Last item styling
- `.separator` - Divider text

**What's Missing**:
- Link color (should be Italia Blue #0066cc)
- Active/disabled color (should be #757575)
- Separator styling
- Hover states for links

**Tailwind Classes Needed**:
- Link color: `text-blue-600` or CSS variable
- Active color: `text-gray-500`
- Separator margin: `mx-2`
- Link hover: `hover:underline`

---

### 2. HERO SECTION (.cmp-hero)

**Current Implementation Status**: ✅ BASIC COMPLETE

**Current Classes Used**:
```html
<div class="cmp-hero">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <section class="it-hero-wrapper bg-white align-items-start">
          <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
            <h1 class="text-black">...</h1>
            <div class="hero-text"><p>...</p></div>
          </div>
        </section>
      </div>
    </div>
  </div>
</div>
```

**Bootstrap Italia Classes Still Used**:
- `.container` - Max-width container
- `.row` - Flex row
- `.col-12`, `.col-lg-10` - Column sizing
- `.justify-content-center` - Flex centering
- `.it-hero-wrapper` - Hero container
- `.bg-white` - White background
- `.align-items-start` - Flex alignment
- `.it-hero-text-wrapper` - Text wrapper
- `.pt-0`, `.ps-0`, `.pb-4`, `.pb-lg-60` - Padding classes
- `.text-black` - Text color

**What's Missing**:
- No Tailwind equivalents used
- No responsive spacing per design spec
- No mobile-specific padding adjustments

**Tailwind Classes Needed**:
- Container: Custom component or `max-w-5xl mx-auto`
- Row centering: `flex justify-center`
- Column: `w-full lg:w-10/12`
- Padding: `pt-0 ps-0 pb-4 lg:pb-12`
- Typography: `text-black`, `text-gray-700`

---

### 3. SEARCH INPUT (.cmp-input-search)

**Current Implementation Status**: ⚠️ PARTIAL STYLES

**Current CSS Found**:
```css
.cmp-input-search {
  @apply my-8;
}

.cmp-input-search .input-group {
  @apply flex border border-gray-300 rounded-lg overflow-hidden;
}

.cmp-input-search .input-group .form-control {
  @apply flex-1 px-4 py-3 border-0 outline-none text-base;
}

.cmp-input-search .input-group .btn-primary {
  @apply px-6 py-3 bg-[#0066cc] border-0 text-white font-semibold;
}
```

**Bootstrap Italia Classes Still Used**:
- `.form-group` - Form wrapper
- `.autocomplete-wrapper` - Autocomplete styling
- `.mb-2`, `.mb-lg-4` - Margin-bottom responsive
- `.input-group` - Input + button group
- `.form-control` - Input styling
- `.autocomplete` - Search variant
- `.input-group-append` - Button wrapper
- `.btn`, `.btn-primary` - Button styling
- `.visually-hidden` - Screen reader only
- `.autocomplete-icon` - Search icon wrapper

**What's Missing**:
- Focus states (border color, box-shadow)
- Hover states
- Placeholder styling and color
- Icon positioning and sizing
- Button disabled state
- Full width responsive handling

**Tailwind Classes Needed**:
- Input focus: `focus:border-blue-600 focus:ring-2 focus:ring-blue-200`
- Placeholder: `placeholder-gray-400`
- Icon: Absolute positioning, sizing
- Button states: `hover:bg-blue-700 active:bg-blue-900`
- Responsive spacing: `mb-2 lg:mb-4`

---

### 4. ACCORDION (.cmp-accordion)

**Current Implementation Status**: ⚠️ PARTIAL STYLES

**Current CSS Found**:
```css
.cmp-accordion.faq .accordion-item {
  @apply border-0 mb-0;
}

.cmp-accordion.faq .accordion-button {
  @apply w-full px-4 py-4 text-left flex items-center justify-between cursor-pointer transition-colors duration-200 rounded-t-lg;
  font-size: 1.125rem;
  font-weight: 600;
  color: #FFFFFF !important;
  background-color: #004445 !important;
}

.cmp-accordion.faq .accordion-button:hover {
  background-color: #003334 !important;
}
```

**Bootstrap Italia Classes Still Used**:
- `.accordion` - Container
- `.accordion-item` - Item wrapper
- `.accordion-header` - Header element
- `.accordion-button` - Clickable element
- `.accordion-button.collapsed` - Closed state
- `.accordion-collapse` - Content collapse area
- `.collapse` - Collapse state marker
- `.accordion-body` - Answer content
- `.title-snall-semi-bold` - Typography class
- `.py-3` - Padding-y utility
- `.me-1` - Margin-end (right)
- `.mb-3` - Margin-bottom
- `.p-0` - Padding zero
- `.icon-xs`, `.icon-primary` - Icon utilities

**What's Missing**:
- Complete button styling (colors, hover, focus, active states)
- Collapse animation states
- Icon rotation animation
- Body content styling
- Border styling
- Shadow effects

**Tailwind Classes Needed**:
- Button: `bg-teal-900 text-white font-semibold`
- Hover: `hover:bg-teal-800`
- Focus: `focus:outline-none focus:ring-4 focus:ring-blue-300`
- Icon wrapper: `flex-shrink-0 ml-2`
- Animation: `transition-transform duration-300`
- Body: `p-4 bg-white border-t`

---

### 5. FEEDBACK RATING (.cmp-feedback)

**Current Implementation Status**: ⚠️ NEEDS COMPLETE REWRITE

**Current HTML**:
```html
<div class="bg-primary faq-rating-section">
  <div class="container">
    <div class="cmp-rating">
      <div class="card shadow">
        <h2 class="title-medium-2-semi-bold">...</h2>
        <div class="faq-rating-stars">
          <button class="faq-rating-star">★</button>
        </div>
      </div>
    </div>
  </div>
</div>
```

**Bootstrap Italia Classes Still Used**:
- `.bg-primary` - Primary color background
- `.container` - Max-width container
- `.card` - Card component
- `.shadow` - Shadow effect
- `.title-medium-2-semi-bold` - Typography
- `.faq-rating-stars` - Stars container
- `.faq-rating-star` - Individual star button

**What's Missing**:
- Background color (should be light gray #F2F2F2)
- Card styling (white background, subtle shadow)
- Star button styling (border, colors)
- Star rating interactivity
- Response message styling
- Responsive padding/spacing

**Tailwind Classes Needed**:
- Background: `bg-gray-100`
- Padding: `py-12 lg:py-16`
- Card: `bg-white rounded-lg shadow-md p-8`
- Stars: `flex justify-center gap-4`
- Button: `bg-white border-2 border-gray-300 rounded-full`

---

### 6. CONTACTS (.cmp-contacts)

**Current Implementation Status**: ⚠️ NEEDS REWRITE

**Current HTML**:
```html
<div class="it-footer-main py-12 bg-[#17324D] text-white">
  <div class="container">
    <div class="row">
      <div class="col-12 col-md-6 col-lg-4">
        <h3 class="text-xl font-bold">{{ $contactTitle }}</h3>
        <a href="{{ $contact['url'] }}" class="flex items-center gap-3">
          <div class="p-2 bg-white/10 rounded-lg">
            <svg>...</svg>
          </div>
          <span>{{ $contact['label'] }}</span>
        </a>
      </div>
    </div>
  </div>
</div>
```

**Bootstrap Italia Classes Still Used**:
- `.container` - Max-width container
- `.row` - Flex row
- `.col-12`, `.col-md-6`, `.col-lg-4` - Column sizing
- `.gap-3` - Gap between items
- `.bg-white/10` - Opacity modifier
- `.rounded-lg` - Border radius

**What's Mixed**:
- Some Tailwind classes used already
- Mixing Bootstrap grid with Tailwind
- Dark background color hardcoded

**What's Missing**:
- Reference design color scheme (light background, not dark footer)
- Grid layout per design (3 columns desktop, 1 mobile)
- Card styling (white background, border, shadow)
- Icon sizing and styling
- Link hover states
- Responsive behavior

**Tailwind Classes Needed**:
- Container: `max-w-5xl mx-auto`
- Grid: `grid grid-cols-1 md:grid-cols-3 gap-6`
- Card: `bg-white rounded-lg shadow border border-gray-200 p-6`
- Icon: `w-8 h-8 text-blue-600`
- Text: `text-gray-900`, `text-blue-600`

---

## Color Palette Summary

**Current Colors in Use**:
- **Primary Blue**: #0066cc (Italia Blue) - CSS variable
- **Dark Teal**: #004445 (Accordion button)
- **Darker Teal**: #003334 (Accordion hover)
- **White**: #FFFFFF
- **Black**: #000000
- **Gray variants**: #191919, #757575, #D0D0D0, #999999, #F2F2F2
- **Header Green**: #008758 or #17324D (Design Comuni)

**Tailwind Color Mapping Needed**:
- `text-blue-600` → #0066cc
- `bg-teal-900` → #004445
- `bg-teal-800` → #003334
- `bg-gray-100` → #F2F2F2
- `text-gray-700` → #191919

---

## Bootstrap Classes to Remove

| Class | Used In | Replacement |
|-------|---------|-------------|
| `.container` | All sections | `max-w-5xl mx-auto` |
| `.row` | All sections | `flex` or grid |
| `.col-*` | All sections | Tailwind grid/flex |
| `.justify-content-center` | Layout | `justify-center` |
| `.align-items-start` | Hero | `items-start` |
| `.pt-0`, `.ps-0`, `.pb-4` | Spacing | `pt-0 ps-0 pb-4` |
| `.mb-2`, `.mb-lg-4` | Spacing | `mb-2 lg:mb-4` |
| `.form-control` | Input | Custom input styles |
| `.btn`, `.btn-primary` | Buttons | Custom button styles |
| `.card`, `.shadow` | Feedback/Contacts | Custom card styles |
| `.text-black` | Typography | `text-black` |
| `.bg-white` | Background | `bg-white` |

---

## Typography Classes Current State

| Element | Current Classes | Font-Size | Weight | Color |
|---------|-----------------|-----------|--------|-------|
| **H1 (Page Title)** | `.text-black` | 2rem | 700 | #000000 |
| **H2 (Sections)** | `.title-medium-2-semi-bold` | 1.5rem | 700 | #000000 |
| **Accordion Button** | `.title-snall-semi-bold` | 1.125rem | 600 | #FFFFFF |
| **Body Text** | None (inherited) | 1rem | 400 | #191919 |
| **Placeholder** | None | 1rem | 400 | #999999 |

**Tailwind Equivalents**:
- `text-4xl font-bold` → H1
- `text-2xl font-bold` → H2
- `text-lg font-semibold` → Accordion button
- `text-base font-normal` → Body text

---

## Interactive States Summary

| Component | State | Current CSS | Missing |
|-----------|-------|-------------|---------|
| **Links** | Normal | Color #0066cc | ✓ Applied |
| | Hover | Underline | ⚠️ Missing hover:underline |
| **Input** | Focus | Border color change | ❌ Missing |
| | | Box shadow | ❌ Missing |
| **Button** | Hover | #0059b3 darker | ⚠️ Partial |
| | Active | #003366 darkest | ❌ Missing |
| | Disabled | opacity 0.65 | ❌ Missing |
| **Accordion** | Hover | Darker teal | ✓ Applied |
| | Expanded | Animation | ⚠️ Alpine.js only |

---

## Spacing System Review

**Current Gap/Padding in Use**:
- Container padding: 1rem (16px) all breakpoints
- Section vertical: 2rem (32px) mobile, 3rem (48px) desktop
- Component internal: 1rem (16px) average
- Between items: 0.5rem–2rem depending on component

**Tailwind Spacing Needed**:
- `px-4` → 1rem padding horizontal
- `py-8` → 2rem padding vertical
- `py-12` → 3rem padding vertical
- `gap-4` → 1rem gap between items
- `mb-2` → 0.5rem margin bottom
- `lg:mb-4` → 1.5rem on large screens

---

## Summary of Work Required

### High Priority (Breaking Changes)
1. ❌ Remove all Bootstrap grid system (`.container`, `.row`, `.col-*`)
2. ❌ Update breadcrumb component with Tailwind classes
3. ❌ Update hero section with Tailwind classes
4. ❌ Update search input with Tailwind + focus/hover states
5. ❌ Update accordion with Tailwind + animation states

### Medium Priority (Feature Complete)
6. ⚠️ Rewrite feedback component with proper styling
7. ⚠️ Rewrite contacts component with proper grid
8. ⚠️ Add all focus states for accessibility
9. ⚠️ Verify responsive breakpoints

### Low Priority (Polish)
10. ✓ Document custom color utilities
11. ✓ Verify all Typography matches spec
12. ✓ Test keyboard navigation and ARIA labels

---

## Next Steps

1. **Phase 1**: Analyze component templates and remove Bootstrap classes
2. **Phase 2**: Create TAILWIND_MAPPING.md with class replacements
3. **Phase 3**: Update component Blade templates with Tailwind classes
4. **Phase 4**: Create comprehensive CSS override file
5. **Phase 5**: Test all breakpoints and interactive states
6. **Phase 6**: Create implementation checklist

**Total Estimated CSS Lines to Change**: ~500 lines
**Total Components Affected**: 6 major + sub-components
**Bootstrap Classes to Remove**: 40+

---

**Analysis Date**: $(date)  
**Framework**: Laravel 12 + Filament 5 + Tailwind CSS  
**Reference**: Design Comuni Italia FAQ  
**Status**: Ready for implementation phase
