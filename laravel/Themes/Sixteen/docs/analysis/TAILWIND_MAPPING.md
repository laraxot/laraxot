# Bootstrap Italia → Tailwind CSS Mapping

## Overview

Complete mapping of Bootstrap Italia classes to Tailwind CSS equivalents for the FAQ page components. This document serves as the implementation reference.

---

## 1. LAYOUT & GRID SYSTEM

### Container & Grid

| Bootstrap Italia | Tailwind | Context | Example |
|-----------------|----------|---------|---------|
| `.container` | `max-w-5xl mx-auto` | Max-width 1200px centered | All page sections |
| `.container` + `.col-12 col-lg-10` | `max-w-3xl mx-auto` | 83.33% width on desktop | Content area |
| `.row` | `flex` or `grid` | Flex row layout | Section wrappers |
| `.justify-content-center` | `justify-center` | Center items horizontally | Hero, breadcrumb |
| `.col-12` | `w-full` | 100% width | All breakpoints |
| `.col-12 .col-lg-10` | `w-full lg:w-5/6` | 100% mobile, 83.33% desktop | Content wrapper |
| `.col-md-6` | `md:w-1/2` | 50% on medium screens | Contacts grid |
| `.col-lg-4` | `lg:w-1/3` | 33% on large screens | Contacts grid |
| `.col-12 col-md-6 col-lg-4` | `w-full md:w-1/2 lg:w-1/3` | Responsive columns | Contact cards |

### Alignment & Direction

| Bootstrap Italia | Tailwind | Context | Example |
|-----------------|----------|---------|---------|
| `.align-items-start` | `items-start` | Flex align to top | Hero wrapper |
| `.align-items-center` | `items-center` | Flex align center | Input group |
| `.flex-direction-column` | `flex-col` | Stack vertically | Accordion items |
| `.flex-wrap` | `flex-wrap` | Allow wrapping | Rating buttons |

---

## 2. SPACING (MARGIN & PADDING)

### Margin

| Bootstrap Italia | Tailwind | Value | Context |
|-----------------|----------|-------|---------|
| `.mt-4` | `mt-4` | 1rem (16px) | Breadcrumb top margin |
| `.mb-0` | `mb-0` | 0 | Remove margin |
| `.mb-2` | `mb-2` | 0.5rem (8px) | Search spacing |
| `.mb-3` | `mb-3` | 0.75rem (12px) | Paragraph margin |
| `.mb-4` | `mb-4` | 1rem (16px) | Section spacing |
| `.mb-8` | `mb-8` | 2rem (32px) | Large spacing |
| `.mb-lg-4` | `lg:mb-4` | 1rem on desktop | Responsive bottom |
| `.me-1` | `me-1` | 0.25rem (4px) | Icon margin-right |
| `.mx-2` | `mx-2` | 0.5rem (8px) | Separator margin-x |
| `.mx-auto` | `mx-auto` | Auto | Center horizontally |
| `.my-8` | `my-8` | 2rem (32px) | Search vertical margin |

### Padding

| Bootstrap Italia | Tailwind | Value | Context |
|-----------------|----------|-------|---------|
| `.p-0` | `p-0` | 0 | Remove padding |
| `.p-2` | `p-2` | 0.5rem (8px) | Icon background |
| `.px-4` | `px-4` | 1rem (16px) | Horizontal padding |
| `.py-3` | `py-3` | 0.75rem (12px) | Button padding |
| `.py-4` | `py-4` | 1rem (16px) | Accordion padding |
| `.ps-0` | `ps-0` | 0 | Padding-start (left) |
| `.pt-0` | `pt-0` | 0 | Padding-top |
| `.pb-4` | `pb-4` | 1rem (16px) | Hero padding-bottom mobile |
| `.pb-lg-60` | `lg:pb-12` | 3rem (48px) on desktop | Hero padding-bottom desktop |
| `.pb-12` | `pb-12` | 3rem (48px) | Large bottom padding |
| `.p-4` | `p-4` | 1rem (16px) | Content area padding |
| `.p-8` | `p-8` | 2rem (32px) | Card padding |
| `.p-12` | `p-12` | 3rem (48px) | Section padding |

### Responsive Spacing (Mobile-First)

| Bootstrap Italia | Tailwind | Mobile | Desktop | Context |
|-----------------|----------|--------|---------|---------|
| `.mb-2 .mb-lg-4` | `mb-2 lg:mb-4` | 0.5rem | 1rem | Search bottom |
| `.pb-4 .pb-lg-60` | `pb-4 lg:pb-12` | 1rem | 3rem | Hero bottom |
| `.py-3 .py-lg-4` | `py-3 lg:py-4` | 0.75rem | 1rem | Button padding |

---

## 3. TYPOGRAPHY & TEXT

### Font Family

| Bootstrap Italia | Tailwind | Applied To |
|-----------------|----------|------------|
| (inherited) | `font-sans` | All text (Titillium Web) |
| `.font-mono` | `font-mono` | Code (Roboto Mono) |

**Global Font Settings** (in `tailwind.config.js`):
```js
fontFamily: {
  sans: ['Titillium Web', 'Inter var', ...defaultTheme.fontFamily.sans],
  mono: ['Roboto Mono', ...defaultTheme.fontFamily.mono],
}
```

### Font Size

| Bootstrap Italia | Tailwind | Size (px) | Context |
|-----------------|----------|-----------|---------|
| `.text-sm` | `text-sm` | 14px | Breadcrumb |
| `.text-base` | `text-base` | 16px | Body text, input |
| `.text-lg` | `text-lg` | 18px | Accordion buttons |
| `.text-xl` | `text-xl` | 20px | Section headers |
| `.text-2xl` | `text-2xl` | 24px | Hero H1 mobile, H2 |
| `.text-3xl` | `text-3xl` | 30px | Hero H1 desktop |
| `.text-4xl` | `text-4xl` | 36px | Large titles |

### Font Weight

| Bootstrap Italia | Tailwind | Value | Context |
|-----------------|----------|-------|---------|
| `.font-normal` | `font-normal` | 400 | Body text |
| `.font-semibold` | `font-semibold` | 600 | Accordion buttons |
| `.font-bold` | `font-bold` | 700 | Headings |

### Text Color

| Bootstrap Italia | Tailwind | Hex | Context |
|-----------------|----------|-----|---------|
| `.text-black` | `text-black` | #000000 | Headings |
| `.text-white` | `text-white` | #FFFFFF | Accordion button text |
| `.text-gray-900` | `text-gray-900` | #111827 | Dark text |
| `.text-gray-700` | `text-gray-700` | #374151 | Body text |
| `.text-gray-600` | `text-gray-600` | #4B5563 | Secondary text |
| (CSS var) | `text-blue-600` | #0066cc | Links (Italia Blue) |

### Line Height

| Bootstrap Italia | Tailwind | Value | Context |
|-----------------|----------|-------|---------|
| (default) | `leading-relaxed` | 1.625 (26px) | Body text |
| (default) | `leading-tight` | 1.25 | Headings |
| (default) | `leading-snug` | 1.375 | Medium text |

### Text Alignment

| Bootstrap Italia | Tailwind | Context |
|-----------------|----------|---------|
| `.text-left` | `text-left` | Accordion questions |
| `.text-center` | `text-center` | Feedback section |

---

## 4. COLORS & BACKGROUNDS

### Primary Color (Italia Blue)

| Bootstrap Italia | Tailwind | Hex | Context |
|-----------------|----------|-----|---------|
| `var(--bs-primary)` | `bg-blue-600` | #0066cc | Button backgrounds |
| `var(--bs-primary)` | `text-blue-600` | #0066cc | Links, icons |
| `var(--bs-primary)` | `border-blue-600` | #0066cc | Input focus border |

### Background Colors

| Bootstrap Italia | Tailwind | Hex | Context |
|-----------------|----------|-----|---------|
| `.bg-white` | `bg-white` | #FFFFFF | Cards, sections |
| `.bg-primary` | `bg-blue-600` | #0066cc | Feedback section background |
| (custom) | `bg-gray-100` | #F3F4F6 | Light gray sections |
| (custom) | `bg-gray-50` | #F9FAFB | Very light background |
| (custom) | `bg-teal-900` | #004445 | Accordion button (Design Comuni) |
| (custom) | `bg-teal-800` | #003334 | Accordion hover (Design Comuni) |

### Text Colors

| Bootstrap Italia | Tailwind | Hex | Context |
|-----------------|----------|-----|---------|
| `.text-black` | `text-black` | #000000 | Page titles |
| `.text-white` | `text-white` | #FFFFFF | Light text on dark |
| `.text-gray-700` | `text-gray-700` | #374151 | Body text (#191919) |
| `.text-gray-600` | `text-gray-600` | #4B5563 | Secondary text |
| `.text-gray-500` | `text-gray-500` | #6B7280 | Inactive text |
| (CSS var) | `text-blue-600` | #0066cc | Links |

### Border Colors

| Bootstrap Italia | Tailwind | Hex | Context |
|-----------------|----------|-----|---------|
| (custom) | `border-gray-300` | #D1D5DB | Input border |
| (custom) | `border-gray-200` | #E5E7EB | Card border |
| (custom) | `border-blue-600` | #0066cc | Input focus border |

### Opacity

| Bootstrap Italia | Tailwind | Value | Context |
|-----------------|----------|-------|---------|
| `bg-white/10` | `bg-white/10` | 10% opacity | Icon backgrounds |
| `bg-white/20` | `bg-white/20` | 20% opacity | Hover states |

---

## 5. BORDERS & SHADOWS

### Border Radius

| Bootstrap Italia | Tailwind | Value | Context |
|-----------------|----------|-------|---------|
| (default) | `rounded-none` | 0 | Search input |
| `.rounded` | `rounded` | 0.25rem (4px) | Buttons |
| `.rounded-md` | `rounded-md` | 0.375rem (6px) | Cards |
| `.rounded-lg` | `rounded-lg` | 0.5rem (8px) | Accordion items |
| `.rounded-xl` | `rounded-xl` | 0.75rem (12px) | Large cards |
| `.rounded-full` | `rounded-full` | 9999px | Circular buttons |

### Borders

| Bootstrap Italia | Tailwind | Value | Context |
|-----------------|----------|-------|---------|
| `.border-0` | `border-0` | None | Remove border |
| `.border` | `border` | 1px | Standard border |
| `.border-2` | `border-2` | 2px | Thicker border |
| `.border-b` | `border-b` | Bottom only | Breadcrumb separator |
| `.border-t` | `border-t` | Top only | Accordion body top |

### Shadows

| Bootstrap Italia | Tailwind | Value | Context |
|-----------------|----------|-------|---------|
| `.shadow` | `shadow` | 0 1px 3px rgba(0,0,0,0.1) | Card shadow |
| `.shadow-lg` | `shadow-lg` | 0 10px 15px rgba(0,0,0,0.1) | Large card shadow |
| `.shadow-xl` | `shadow-xl` | 0 20px 25px rgba(0,0,0,0.1) | Extra large shadow |
| `.shadow-2xl` | `shadow-2xl` | 0 25px 50px rgba(0,0,0,0.15) | Contact cards |

### Box Shadows (Custom Focus Rings)

| Bootstrap Italia | Tailwind | CSS | Context |
|-----------------|----------|-----|---------|
| (custom) | `focus:ring-4 focus:ring-blue-300` | `0 0 0 4px rgba(59, 130, 246, 0.5)` | Focus indicator |
| (custom) | `focus:ring-offset-2` | Offset ring | Button focus |

---

## 6. FORMS & INPUTS

### Form Controls

| Bootstrap Italia | Tailwind | Context |
|-----------------|----------|---------|
| `.form-group` | `flex flex-col` | Wrapper for inputs |
| `.form-control` | `w-full px-4 py-3 border border-gray-300 rounded` | Input styling |
| `.form-control:focus` | `focus:border-blue-600 focus:ring-2 focus:ring-blue-200` | Focus state |
| `.autocomplete` | Variant of `.form-control` | Search input |
| `.autocomplete-wrapper` | `relative` | Positioning context |
| `.autocomplete-icon` | `absolute right-4 top-1/2 transform -translate-y-1/2` | Icon positioning |
| `.visually-hidden` | `sr-only` | Screen reader only |

### Input Placeholder

| Bootstrap Italia | Tailwind | Context |
|-----------------|----------|---------|
| `.form-control::placeholder` | `placeholder-gray-400` | Placeholder text color |
| (CSS) | `opacity-100` | Full opacity for placeholder |

### Input Group

| Bootstrap Italia | Tailwind | Context |
|-----------------|----------|---------|
| `.input-group` | `flex items-center relative` | Button + input wrapper |
| `.input-group-append` | `absolute right-0 top-0 h-full flex items-center` | Button positioning |

---

## 7. BUTTONS

### Button Base

| Bootstrap Italia | Tailwind | Context |
|-----------------|----------|---------|
| `.btn` | `px-6 py-3 font-semibold text-center cursor-pointer transition-colors duration-200` | Base button |
| `.btn` | `inline-block rounded border-0` | Display and shape |

### Button Primary

| Bootstrap Italia | Tailwind | Color | Context |
|-----------------|----------|-------|---------|
| `.btn-primary` | `bg-blue-600 text-white` | #0066cc | Default |
| `.btn-primary:hover` | `hover:bg-blue-700` | Darker blue | Hover state |
| `.btn-primary:active` | `active:bg-blue-900` | Dark blue | Active state |
| `.btn-primary:focus` | `focus:ring-4 focus:ring-blue-300` | Blue ring | Focus state |
| `.btn-primary:disabled` | `disabled:opacity-50 disabled:cursor-not-allowed` | Disabled | Disabled state |

---

## 8. COMPONENTS

### Breadcrumb

| Bootstrap Italia | Tailwind | Context |
|-----------------|----------|---------|
| `.breadcrumb` | `flex items-center p-0 m-0 list-none text-sm` | List styling |
| `.breadcrumb-item` | `flex items-center` | Item styling |
| `.breadcrumb-item a` | `text-blue-600 no-underline hover:underline` | Link styling |
| `.breadcrumb-item.active` | `text-gray-600` | Current/last item |
| `.separator` | `mx-2 text-gray-500` | Separator styling |

### Hero Section

| Bootstrap Italia | Tailwind | Context |
|-----------------|----------|---------|
| `.cmp-hero` | `w-full bg-white py-8 lg:py-12` | Hero container |
| `.it-hero-wrapper` | `flex flex-col items-start bg-white` | Content wrapper |
| `.it-hero-text-wrapper` | `w-full pt-0 ps-0 pb-4 lg:pb-12` | Text wrapper |
| `.it-hero-text-wrapper h1` | `text-3xl lg:text-4xl font-bold text-black mb-4` | Title styling |
| `.hero-text` | `text-base text-gray-700 leading-relaxed` | Subtitle styling |

### Accordion

| Bootstrap Italia | Tailwind | Context |
|-----------------|----------|---------|
| `.cmp-accordion` | `w-full` | Container |
| `.accordion-item` | `border-0 mb-2 rounded-lg overflow-hidden bg-white shadow` | Item styling |
| `.accordion-button` | `w-full px-4 py-4 text-left flex items-center justify-between cursor-pointer transition-colors duration-200 font-semibold text-lg text-white bg-teal-900 hover:bg-teal-800` | Button styling |
| `.accordion-collapse` | `overflow-hidden transition-all duration-300` | Collapse animation |
| `.accordion-body` | `p-4 bg-white border-t border-gray-200` | Body content |

### Feedback/Rating

| Bootstrap Italia | Tailwind | Context |
|-----------------|----------|---------|
| `.cmp-feedback` | `w-full bg-gray-100 py-12 lg:py-16 my-12` | Container |
| `.cmp-feedback h2` | `text-2xl lg:text-3xl font-bold text-black mb-4` | Title |
| `.cmp-feedback p` | `text-base text-gray-700 mb-8` | Subtitle |
| `.rating-options` | `flex justify-center gap-4 flex-wrap` | Button group |
| `.rating-btn` | `px-6 py-3 bg-white border-2 border-gray-300 rounded-lg cursor-pointer transition-all` | Button styling |
| `.rating-btn:hover` | `hover:border-blue-600 hover:shadow-md` | Hover state |
| `.rating-btn.active` | `bg-blue-600 border-blue-600 text-white` | Active state |

### Contacts/Cards

| Bootstrap Italia | Tailwind | Context |
|-----------------|----------|---------|
| `.cmp-contacts` | `w-full bg-white py-8 lg:py-12 border-t border-gray-200` | Container |
| `.contacts-grid` | `grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8` | Grid layout |
| `.contact-item` | `flex flex-col items-start p-6 bg-gray-50 rounded-lg border border-gray-200 transition-all hover:shadow-lg hover:border-gray-300` | Card styling |
| `.contact-item .icon` | `w-8 h-8 text-blue-600 mb-4` | Icon styling |
| `.contact-item h3` | `text-lg font-semibold text-black mb-2` | Title |
| `.contact-item a` | `text-blue-600 no-underline font-semibold hover:underline` | Link |

---

## 9. UTILITY CLASSES

### Display & Visibility

| Bootstrap Italia | Tailwind | Context |
|-----------------|----------|---------|
| `.visually-hidden` | `sr-only` | Screen reader only |
| `.d-flex` | `flex` | Display flex |
| `.d-block` | `block` | Display block |
| `.d-none` | `hidden` | Display none |

### Position

| Bootstrap Italia | Tailwind | Context |
|-----------------|----------|---------|
| `.position-absolute` | `absolute` | Absolute positioning |
| `.position-relative` | `relative` | Relative positioning |
| (inline style) | `right-0 top-0` | Specific positioning |

### Transform

| Bootstrap Italia | Tailwind | Value | Context |
|-----------------|----------|-------|---------|
| `.transform` | `transform` | Enable transforms | Icon rotation |
| (CSS) | `rotate-180` | 180° rotation | Accordion expand icon |
| (CSS) | `-translate-y-1/2` | Vertical center | Icon centering |

### Transitions

| Bootstrap Italia | Tailwind | Duration | Context |
|-----------------|----------|----------|---------|
| (default) | `transition-colors` | 150ms | Color changes |
| (default) | `transition-all` | 300ms | All properties |
| (Alpine.js) | `duration-300` | 300ms | Accordion height |

### Cursor

| Bootstrap Italia | Tailwind | Context |
|-----------------|----------|---------|
| `cursor: pointer` | `cursor-pointer` | Clickable elements |
| `cursor: not-allowed` | `cursor-not-allowed` | Disabled state |

---

## 10. RESPONSIVE BREAKPOINTS

### Tailwind Breakpoints (Mobile-First)

| Breakpoint | Size | Bootstrap Equiv | Usage |
|-----------|------|-----------------|-------|
| (base) | < 640px | < 576px | Mobile defaults |
| `sm:` | ≥ 640px | ≥ 576px | Small tablets |
| `md:` | ≥ 768px | ≥ 768px | Medium tablets |
| `lg:` | ≥ 1024px | ≥ 992px | Desktops |
| `xl:` | ≥ 1280px | ≥ 1200px | Large desktops |

### Bootstrap → Tailwind Responsive Mapping

| Bootstrap Italia | Tailwind | Context |
|-----------------|----------|---------|
| `-lg-` (lg breakpoint) | `lg:` | Large screens |
| `-md-` (md breakpoint) | `md:` | Medium screens |
| `-sm-` (sm breakpoint) | `sm:` | Small screens |

**Examples**:
- `.mb-lg-4` → `lg:mb-4` (1rem margin-bottom on large screens)
- `.pb-lg-60` → `lg:pb-12` (3rem padding-bottom on large screens)
- `.col-lg-10` → `lg:w-5/6` (83.33% width on large screens)

---

## 11. CUSTOM CSS VARIABLES

### Design Comuni Colors (Available in Tailwind)

```css
:root {
  --bs-primary: #0066cc;         /* Italia Blue */
  --bs-primary-dark: #0052a3;    /* Darker blue */
  --bs-secondary: #757575;       /* Gray */
  --bs-gray-600: #4B5563;        /* Gray-600 */
}
```

**Usage in Tailwind**:
- `text-blue-600` → #0066cc
- `bg-blue-600` → #0066cc
- `border-blue-600` → #0066cc

---

## 12. QUICK REFERENCE TABLE

### Most Used Class Mappings

| Use Case | Bootstrap Italia | Tailwind |
|----------|-----------------|----------|
| **Full width** | `.w-100` | `w-full` |
| **Centered container** | `.container .row .col-12 .col-lg-10` | `max-w-3xl mx-auto` |
| **Flex center** | `.d-flex .justify-content-center` | `flex justify-center` |
| **Flex items center** | `.d-flex .align-items-center` | `flex items-center` |
| **Grid 3 columns** | `.row .col-lg-4 (3×)` | `grid grid-cols-3` |
| **Grid responsive** | `.col-12 .col-md-6 .col-lg-4` | `w-full md:w-1/2 lg:w-1/3` |
| **Blue text** | (CSS var) | `text-blue-600` |
| **Blue button** | `.btn .btn-primary` | `bg-blue-600 text-white` |
| **Padding all** | `.p-4` | `p-4` |
| **Padding horizontal** | `.px-4` | `px-4` |
| **Margin bottom** | `.mb-4` | `mb-4` |
| **Responsive margin** | `.mb-2 .mb-lg-4` | `mb-2 lg:mb-4` |
| **Rounded corners** | `.rounded` | `rounded-lg` |
| **Box shadow** | `.shadow` | `shadow-lg` |
| **Input field** | `.form-control` | `px-4 py-3 border border-gray-300 rounded` |
| **Large heading** | `.h1` | `text-4xl font-bold` |

---

## Implementation Notes

### Rules for Substitution

1. **Direct Mapping**: Some classes map 1:1 (e.g., `mb-4` → `mb-4`)
2. **Composite Mapping**: Some Bootstrap classes require multiple Tailwind classes (e.g., `.container .row .col-lg-10` → `max-w-3xl mx-auto flex justify-center`)
3. **CSS Variables**: Bootstrap colors use CSS variables; Tailwind uses color names and custom values
4. **Responsive Syntax**: Bootstrap uses suffixes (e.g., `-lg-`); Tailwind uses prefixes (e.g., `lg:`)
5. **States**: Tailwind uses pseudo-selectors in markup (e.g., `hover:`, `focus:`); Bootstrap relies on CSS

### Conversion Steps

1. Replace grid system (`.container`, `.row`, `.col-*`) with Tailwind grid/flex
2. Replace spacing classes (`.p-*`, `.m-*`) with Tailwind equivalents
3. Replace color classes (`.bg-*`, `.text-*`) with Tailwind colors
4. Replace component classes (`.btn`, `.card`) with Tailwind component styles
5. Replace Bootstrap utilities with Tailwind utilities
6. Add responsive prefixes where needed (`.mb-lg-4` → `lg:mb-4`)
7. Update state selectors (`:hover`, `:focus`) to Tailwind syntax
8. Remove all Bootstrap Italia imports and CSS files

---

**Document Version**: 1.0  
**Last Updated**: Implementation Phase 1  
**Framework**: Tailwind CSS v3 + Tailwind Forms Plugin  
**Status**: Ready for component template updates
