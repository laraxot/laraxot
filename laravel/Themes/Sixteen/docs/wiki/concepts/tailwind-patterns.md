---
title: "Sixteen Theme — Tailwind CSS Patterns"
type: concept
tags: [sixteen, tailwind, css, design-system, patterns, daisyui, bootstrap-italia]
created: 2026-06-10
updated: 2026-06-10
related:
  - ./bootstrap-italia-tailwind-philosophy.md
  - ./design-comuni-class-mapping.md
  - ../entities/design-comuni-class-mapping.md
  - ../../../../docs/wiki/concepts/design-system-tailwind-parity.md
---

# Sixteen Theme — Tailwind CSS Patterns

## Overview

Tailwind CSS patterns for **Sixteen** theme, integrating:
- **DaisyUI v4** (components)
- **Bootstrap Italia** (PA design language)
- **Filament v5** (admin/form components)
- **Design Comuni** (Italian civic design standards)

## Core Philosophy

**@apply aliases** for consistency:

```css
/* ✅ GOOD: Reusable aliases */
.button-primary {
  @apply px-4 py-2 rounded bg-primary text-white font-semibold;
}

/* ✅ GOOD: DaisyUI base */
.card {
  @apply p-6 bg-base-100 rounded-lg shadow-md;
}

/* ✅ GOOD: Form components */
.form-control-wrapper {
  @apply mb-4 flex flex-col;
}

.form-input {
  @apply w-full px-3 py-2 border border-gray-300 rounded;
}
```

**No arbitrary hex colors in markup:**

```html
<!-- ✅ GOOD: Use semantic tokens -->
<div class="bg-primary text-primary-content">
  Civic Service Portal
</div>

<!-- ❌ BAD: Arbitrary colors -->
<div class="bg-[#FF5733] text-white">
  Civic Service Portal
</div>
```

## Component Patterns

### 1. Buttons (DaisyUI)

```html
<!-- Primary -->
<button class="btn btn-primary">
  Send Report
</button>

<!-- Secondary -->
<button class="btn btn-secondary">
  Cancel
</button>

<!-- Outline -->
<button class="btn btn-outline">
  Preview
</button>

<!-- Disabled -->
<button class="btn btn-disabled">
  Processing...
</button>

<!-- Size variants -->
<button class="btn btn-sm">Small</button>
<button class="btn">Default</button>
<button class="btn btn-lg">Large</button>

<!-- Full width -->
<button class="btn btn-block">Full Width Button</button>
```

### 2. Cards (DaisyUI)

```html
<div class="card bg-base-100 shadow-md">
  <div class="card-body">
    <h2 class="card-title text-2xl">Ticket #123</h2>
    <p class="text-base-content/70">Status: Open</p>
    <div class="card-actions justify-end">
      <button class="btn btn-primary">View Details</button>
    </div>
  </div>
</div>
```

### 3. Forms (Filament + DaisyUI)

```html
<form class="form-wrapper space-y-4">
  <!-- Text Input -->
  <div class="form-control">
    <label class="label">
      <span class="label-text">Email Address</span>
      <span class="label-text-alt text-red-500">*</span>
    </label>
    <input type="email" class="input input-bordered" required />
  </div>

  <!-- Checkbox -->
  <div class="form-control">
    <label class="label cursor-pointer">
      <span class="label-text">I agree to terms</span>
      <input type="checkbox" class="checkbox checkbox-primary" />
    </label>
  </div>

  <!-- Select -->
  <div class="form-control">
    <label class="label">
      <span class="label-text">Category</span>
    </label>
    <select class="select select-bordered">
      <option disabled selected>Choose one</option>
      <option>Infrastructure</option>
      <option>Parks</option>
    </select>
  </div>

  <!-- Textarea -->
  <div class="form-control">
    <label class="label">
      <span class="label-text">Description</span>
    </label>
    <textarea class="textarea textarea-bordered h-24"></textarea>
  </div>

  <!-- Submit -->
  <button type="submit" class="btn btn-primary btn-block">
    Submit Report
  </button>
</form>
```

### 4. Navigation (Bootstrap Italia + Design Comuni)

```html
<!-- Header Navigation -->
<header class="navbar bg-base-100 shadow-sm">
  <div class="navbar-start">
    <div class="dropdown">
      <label tabindex="0" class="btn btn-ghost btn-circle">
        <svg class="h-5 w-5" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" 
                stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
        </svg>
      </label>
    </div>
  </div>

  <div class="navbar-center">
    <a href="/" class="btn btn-ghost normal-case text-xl">
      Fixcity
    </a>
  </div>

  <div class="navbar-end">
    <button class="btn btn-ghost btn-circle">
      <svg class="h-5 w-5" fill="currentColor">
        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
      </svg>
    </button>
  </div>
</header>
```

### 5. Tabs (Filament + DaisyUI)

```html
<div class="tabs tabs-bordered">
  <input type="radio" name="ticket_tabs" class="tab" 
         aria-label="Map" checked />
  <div class="tab-content bg-base-100 border-base-300 rounded-b-box p-6">
    <!-- Map content -->
  </div>

  <input type="radio" name="ticket_tabs" class="tab" 
         aria-label="List" />
  <div class="tab-content bg-base-100 border-base-300 rounded-b-box p-6">
    <!-- List content -->
  </div>

  <input type="radio" name="ticket_tabs" class="tab" 
         aria-label="Details" />
  <div class="tab-content bg-base-100 border-base-300 rounded-b-box p-6">
    <!-- Details content -->
  </div>
</div>
```

### 6. Tables (Filament)

```html
<div class="overflow-x-auto">
  <table class="table table-zebra">
    <thead>
      <tr class="bg-base-200">
        <th>ID</th>
        <th>Title</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>#001</td>
        <td>Pothole on Main Street</td>
        <td>
          <div class="badge badge-primary">Open</div>
        </td>
        <td>
          <button class="btn btn-sm btn-ghost">View</button>
        </td>
      </tr>
    </tbody>
  </table>
</div>
```

### 7. Modals (DaisyUI)

```html
<!-- Modal trigger -->
<button class="btn btn-primary" onclick="modal_1.showModal()">
  Open Modal
</button>

<!-- Modal -->
<dialog id="modal_1" class="modal">
  <div class="modal-box">
    <h3 class="font-bold text-lg">Confirm Action</h3>
    <p class="py-4">Are you sure you want to delete this ticket?</p>
    <div class="modal-action">
      <form method="dialog" class="space-x-2">
        <button class="btn">Cancel</button>
        <button class="btn btn-primary">Delete</button>
      </form>
    </div>
  </div>
  <form method="dialog" class="modal-backdrop">
    <button>Close</button>
  </form>
</dialog>
```

### 8. Alerts (DaisyUI)

```html
<!-- Info -->
<div class="alert alert-info">
  <svg class="stroke-current shrink-0 h-6 w-6" fill="none" 
       viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" 
          stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
  </svg>
  <span>Your report has been submitted successfully!</span>
</div>

<!-- Warning -->
<div class="alert alert-warning">
  <svg class="stroke-current shrink-0 h-6 w-6" fill="none" 
       viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" 
          stroke-width="2" d="M12 9v2m0 4v2m0 0l7-7M5 12a7 7 0 1114 0 7 7 0 01-14 0z"></path>
  </svg>
  <span>Please verify the location before submitting.</span>
</div>

<!-- Error -->
<div class="alert alert-error">
  <svg class="stroke-current shrink-0 h-6 w-6" fill="none" 
       viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" 
          stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
  </svg>
  <span>An error occurred. Please try again.</span>
</div>

<!-- Success -->
<div class="alert alert-success">
  <svg class="stroke-current shrink-0 h-6 w-6" fill="none" 
       viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" 
          stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
  </svg>
  <span>Changes saved successfully!</span>
</div>
```

## Grid & Layout

### Container

```html
<!-- Max width with padding -->
<div class="container mx-auto px-4 lg:px-0">
  <h1>Page Content</h1>
</div>
```

### Flex Layout

```html
<!-- Row with gap -->
<div class="flex gap-4">
  <div class="flex-1">Column 1</div>
  <div class="flex-1">Column 2</div>
  <div class="flex-1">Column 3</div>
</div>

<!-- Space between -->
<div class="flex justify-between items-center">
  <h1>Title</h1>
  <button class="btn btn-sm">Action</button>
</div>

<!-- Centered -->
<div class="flex justify-center items-center h-96">
  <span>Centered content</span>
</div>
```

### Grid Layout

```html
<!-- Responsive grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
  <div class="card bg-base-100 shadow-md">
    <div class="card-body">
      <h2 class="card-title">Card 1</h2>
      <p>Content here</p>
    </div>
  </div>
  <!-- More cards -->
</div>
```

## Typography

### Text Colors

```html
<!-- Text content (70% opacity for secondary) -->
<p class="text-base-content">Primary text</p>
<p class="text-base-content/70">Secondary text</p>
<p class="text-base-content/50">Tertiary text</p>

<!-- Semantic colors -->
<p class="text-success">Success message</p>
<p class="text-warning">Warning message</p>
<p class="text-error">Error message</p>
<p class="text-info">Info message</p>
```

### Headings

```html
<h1 class="text-4xl font-bold">Main Heading</h1>
<h2 class="text-3xl font-bold">Section Heading</h2>
<h3 class="text-2xl font-semibold">Subsection</h3>
<h4 class="text-xl font-semibold">Minor Heading</h4>

<!-- Divider -->
<div class="divider">Section</div>
```

## Responsive Design

### Breakpoints

```html
<!-- Hide on mobile, show on tablet and up -->
<div class="hidden md:block">
  Desktop navigation
</div>

<!-- Show on mobile, hide on tablet and up -->
<div class="md:hidden">
  Mobile navigation
</div>

<!-- Responsive text size -->
<h1 class="text-2xl md:text-3xl lg:text-4xl">
  Responsive Heading
</h1>

<!-- Responsive columns -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
  <!-- Grid items -->
</div>
```

## Design Tokens (DaisyUI Configuration)

### Theme Colors

```javascript
// tailwind.config.js
module.exports = {
  daisyui: {
    themes: [
      {
        light: {
          primary: "#0064cd",        // Bootstrap blue
          secondary: "#6c757d",      // Bootstrap gray
          accent: "#ffc107",         // Bootstrap yellow
          neutral: "#f8f9fa",        // Bootstrap light
          "base-100": "#ffffff",
          "base-200": "#f8f9fa",
          "base-300": "#e9ecef",
          "base-content": "#212529",
          success: "#198754",
          warning: "#ffc107",
          error: "#dc3545",
          info: "#0dcaf0",
        },
      },
    ],
  },
};
```

## Custom CSS

### Best Practices

```css
/* ✅ GOOD: Use @apply for component aliases */
.civic-button {
  @apply px-4 py-2 rounded font-semibold;
  @apply bg-primary text-primary-content;
  @apply hover:opacity-90 transition-opacity;
}

/* ✅ GOOD: Scoped utility classes */
.ticket-card {
  @apply bg-base-100 rounded-lg shadow-md p-6;
  @apply border-l-4 border-primary;
}

/* ✅ GOOD: Responsive utilities */
@media (max-width: 768px) {
  .card-grid {
    @apply grid-cols-1;
  }
}

/* ❌ BAD: Arbitrary inline styles in templates */
<div style="color: #FF5733; font-size: 16px;">
  <!-- Unmaintainable, breaks design system -->
</div>
```

## Integration Points

### With Filament

Filament components inherit Tailwind/DaisyUI styling:

```php
// forms/components/TextInput.php
TextInput::make('name')
    ->required()
    ->autofocus()
    ->placeholder('Enter name'),

// Uses Tailwind via Filament CSS
```

### With Bootstrap Italia

CSS class mappings for PA design language:

```html
<!-- Design Comuni semantic classes -->
<div class="form-group">        <!-- Bootstrap Italia -->
  <label class="form-label">    <!-- Maps to Tailwind -->
    Email
  </label>
  <input class="form-control" /> <!-- Uses DaisyUI input -->
</div>
```

See [design-comuni-class-mapping.md](../entities/design-comuni-class-mapping.md) for full mapping.

## Testing & Validation

### Visual Regression Testing

```bash
# Playwright visual testing
npm run test:visual
```

### Tailwind Analysis

```bash
# Analyze bundle size
npm run build
ls -lh dist/theme.min.css
```

## Resources

- [Tailwind CSS Docs](https://tailwindcss.com/docs)
- [DaisyUI Components](https://daisyui.com/docs/)
- [DaisyUI Themes](https://daisyui.com/docs/themes/)
- [Bootstrap Italia Design](https://designer.italia.it/)
- [Filament Documentation](https://filamentphp.com/)

## Related Documentation

- [Bootstrap Italia Tailwind Philosophy](./bootstrap-italia-tailwind-philosophy.md)
- [Design Comuni Class Mapping](../entities/design-comuni-class-mapping.md)
- [Sixteen Theme Index](../index.md)
- [Frontend Design Fixcity Overlay](./frontend-design-fixcity-overlay.md)

---

**Last Updated**: 2026-06-10  
**Maintenance**: Theme Developer  
**Review Cycle**: Quarterly or after major Tailwind/DaisyUI updates
