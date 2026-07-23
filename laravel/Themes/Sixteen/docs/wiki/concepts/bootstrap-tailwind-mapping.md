---
title: "Bootstrap → Tailwind Mapping for Design Comuni Parity"
type: reference
sources: ["https://italia.github.io/design-comuni-pagine-statiche/"]
confidence: high
created: 2026-05-04
updated: 2026-05-04
tags: [bootstrap, tailwind, mapping, design-comuni, parity, css, alpine, lit]
related:
  - ../../laravel/Modules/Fixcity/docs/wiki/concepts/segnalazione-design-comuni-comparison.md
  - ../../laravel/Themes/Sixteen/docs/wiki/concepts/segnalazione-visual-parity-correction-plan.md
  - concepts/theme-owned-css-parity-rule.md
  - concepts/segnalazione-visual-parity-mastery.md
---

# Bootstrap → Tailwind Mapping for Design Comuni Parity

> **Purpose**: Complete mapping of Bootstrap Italia classes to Tailwind CSS v4 equivalents for Design Comuni parity.
>
> **Goal**: Achieve **visual parity** (same rendered look) using Tailwind + Alpine + Lit (NOT Bootstrap Italia JS).
>
> **Key Insight**: Bootstrap uses 0.25rem per unit, Tailwind ALSO uses 0.25rem per unit → **SAME SPACING SCALE!**

## Critical Spacing Scale (SAME for Both!)

| Bootstrap Class | Pixel Value | Tailwind Class | Pixel Value | Match? |
|----------------|-------------|----------------|-------------|--------|
| `.mb-0` | 0 | `.mb-0` | 0 | ✅ |
| `.mb-1` | 0.25rem (4px) | `.mb-1` | 0.25rem (4px) | ✅ |
| `.mb-2` | 0.5rem (8px) | `.mb-2` | 0.5rem (8px) | ✅ |
| `.mb-3` | 1rem (16px) | `.mb-4` | 1rem (16px) | ✅ **Different number, SAME result!** |
| `.mb-4` | 1.5rem (24px) | `.mb-6` | 1.5rem (24px) | ✅ **Different number, SAME result!** |
| `.mb-5` | 3rem (48px) | `.mb-12` | 3rem (48px) | ✅ |
| `.p-3` | 1rem (16px) | `.p-4` | 1rem (16px) | ✅ |
| `.p-4` | 1.5rem (24px) | `.p-6` | 1.5rem (24px) | ✅ |
| `.pt-3` | 1rem (16px) | `.pt-4` | 1rem (16px) | ✅ |
| `.pb-3` | 1rem (16px) | `.pb-4` | 1rem (16px) | ✅ |

**⚠️ IMPORTANT**: Bootstrap `.mb-3` = Tailwind `.mb-4` (BOTH = 1rem!)
**⚠️ IMPORTANT**: Bootstrap `.mb-4` = Tailwind `.mb-6` (BOTH = 1.5rem!)

## Container & Grid

| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `.container` | `.container` (with Tailwind container plugin) or `.max-w-7xl .mx-auto .px-4` | Use Tailwind's container plugin |
| `.container-fluid` | `.w-full .px-4` | Full width with padding |
| `.row` | `.flex .flex-wrap .-mx-4` | Rows are flex containers |
| `.col-12` | `.w-full .px-4` | Full width column |
| `.col-md-6` | `.md:w-1/2 .px-4` | Half width on medium+ screens |
| `.col-md-4` | `.md:w-1/3 .px-4` | Third width |
| `.col-md-3` | `.md:w-1/4 .px-4` | Quarter width |
| `.col-lg-8` | `.lg:w-2/3 .px-4` | Two-thirds width |
| `.col-lg-4` | `.lg:w-1/3 .px-4` | Third width |
| `.gx-4` | `.gap-x-6` (1.5rem) | Horizontal gap |
| `.gy-4` | `.gap-y-6` (1.5rem) | Vertical gap |

## Typography

| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `.h1` | `.text-4xl .font-bold` | 2.25rem (36px) |
| `.h2` | `.text-3xl .font-bold` | 1.875rem (30px) |
| `.h3` | `.text-2xl .font-bold` | 1.5rem (24px) |
| `.h4` | `.text-xl .font-bold` | 1.25rem (20px) |
| `.h5` | `.text-lg .font-bold` | 1.125rem (18px) |
| `.h6` | `.text-base .font-bold` | 1rem (16px) |
| `.fw-bold` | `.font-bold` | ✅ Same concept |
| `.fw-normal` | `.font-normal` | ✅ |
| `.fw-light` | `.font-light` | ✅ |
| `.text-center` | `.text-center` | ✅ SAME! |
| `.text-start` | `.text-left` | ✅ |
| `.text-end` | `.text-right` | ✅ |
| `.text-muted` | `.text-gray-600` | Muted text |
| `.lead` | `.text-xl .text-gray-700` | Lead paragraph |

## Colors (CRITICAL: Check Tailwind Config!)

| Bootstrap Token | Hex Value | Tailwind Equivalent | Notes |
|----------------|-----------|-------------------|-------|
| `--bs-primary` | `#0066CC` | `.bg-primary` or `.bg-[#0066CC]` | **VERIFY** in `tailwind.config.js` |
| `--bs-secondary` | `#6C7688` | `.bg-secondary` or `.bg-[#6C7688]` | **VERIFY** in config |
| `--bs-success` | `#198754` | `.bg-success` or `.bg-[#198754]` | ✅ |
| `--bs-danger` | `#DC3545` | `.bg-danger` or `.bg-[#DC3545]` | ✅ |
| `--bs-warning` | `#FFC107` | `.bg-warning` or `.bg-[#FFC107]` | ✅ |
| `--bs-info` | `#0DCAF0` | `.bg-info` or `.bg-[#0DCAF0]` | ✅ |
| `.text-primary` | `#0066CC` | `.text-primary` or `.text-[#0066CC]` | ✅ |
| `.bg-primary` | `#0066CC` | `.bg-primary` or `.bg-[#0066CC]` | ✅ |
| `.border-primary` | `#0066CC` | `.border-primary` or `.border-[#0066CC]` | ✅ |

**⚠️ VERIFY**: Run `cd laravel/Themes/Sixteen && cat tailwind.config.js` to check if `colors.primary = '#0066CC'` is set!

## Buttons

| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `.btn .btn-primary` | `.bg-[#0066CC] .text-white .px-4 .py-2 .rounded .hover:bg-[#004A99]` | Use exact hex |
| `.btn .btn-secondary` | `.bg-[#6C7688] .text-white .px-4 .py-2 .rounded` | ✅ |
| `.btn .btn-success` | `.bg-[#198754] .text-white .px-4 .py-2 .rounded` | ✅ |
| `.btn .btn-outline-primary` | `.border-2 .border-[#0066CC] .text-[#0066CC] .px-4 .py-2 .rounded .hover:bg-[#0066CC] .hover:text-white` | ✅ |
| `.btn-lg` | `.px-6 .py-3 .text-lg` | Large button |
| `.btn-sm` | `.px-3 .py-1.5 .text-sm` | Small button |
| `.btn-link` | `.text-[#0066CC] .underline .hover:text-[#004A99]` | Link button |

## Forms

| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `.form-control` | `.w-full .px-3 .py-2 .border .border-gray-300 .rounded .focus:ring-2 .focus:ring-blue-500` | Input styling |
| `.form-check` | `.flex .items-start .gap-2` | Checkbox/radio wrapper |
| `.form-check-input` | `.w-4 .h-4 .text-[#0066CC] .border-gray-300 .rounded` | Checkbox/radio input |
| `.form-check-label` | `.text-sm .text-gray-700` | Label for checkbox |
| `.form-label` | `.block .text-sm .font-medium .text-gray-700 .mb-1` | Form label |
| `.form-text` | `.text-sm .text-gray-500 .mt-1` | Form help text |
| `.form-select` | `.w-full .px-3 .py-2 .border .border-gray-300 .rounded .pr-8 .bg-white` | Select dropdown |
| `.form-control-lg` | `.px-4 .py-3 .text-lg` | Large input |
| `.was-validated` | N/A (use Alpine.js state) | Validation state |

## Components

### Breadcrumb
| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `.breadcrumb` | `.flex .items-center .space-x-2 .text-sm .text-gray-500` | Breadcrumb wrapper |
| `.breadcrumb-item` | `.inline-flex .items-center` | Individual item |
| `.breadcrumb-item.active` | `.text-gray-700 .font-medium` | Active item |

### Stepper (Design Comuni Custom)
| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `.stepper` | `.flex .items-center .justify-between` | Stepper container |
| `.stepper-step` | `.flex .items-center .gap-2` | Individual step |
| `.stepper-step.active` | `.text-[#0066CC] .font-bold` | Active step |
| `.stepper-step.completed` | `.text-green-600` | Completed step |

### Alert
| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `.alert .alert-info` | `.bg-blue-50 .border-l-4 .border-blue-500 .p-4` | Info alert |
| `.alert .alert-success` | `.bg-green-50 .border-l-4 .border-green-500 .p-4` | Success alert |
| `.alert .alert-warning` | `.bg-yellow-50 .border-l-4 .border-yellow-500 .p-4` | Warning alert |
| `.alert .alert-danger` | `.bg-red-50 .border-l-4 .border-red-500 .p-4` | Danger alert |

### Card
| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `.card` | `.bg-white .rounded-lg .shadow-md .overflow-hidden` | Card wrapper |
| `.card-body` | `.p-4` | Card body |
| `.card-title` | `.text-xl .font-bold .mb-2` | Card title |
| `.card-text` | `.text-gray-600` | Card text |

### Modal
| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `.modal-content` | `.bg-white .rounded-lg .shadow-xl .max-w-lg .mx-auto` | Modal content |
| `.modal-header` | `.px-4 .py-3 .border-b .border-gray-200` | Modal header |
| `.modal-body` | `.p-4` | Modal body |
| `.modal-footer` | `.px-4 .py-3 .border-t .border-gray-200 .flex .justify-end .gap-2` | Modal footer |

## Display & Flexbox

| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `.d-flex` | `.flex` | ✅ Same concept |
| `.d-none` | `.hidden` | ✅ |
| `.d-block` | `.block` | ✅ |
| `.d-inline` | `.inline` | ✅ |
| `.d-inline-block` | `.inline-block` | ✅ |
| `.justify-content-between` | `.justify-between` | ✅ Tailwind shorthand |
| `.justify-content-center` | `.justify-center` | ✅ |
| `.justify-content-end` | `.justify-end` | ✅ |
| `.align-items-center` | `.items-center` | ✅ Tailwind shorthand |
| `.align-items-start` | `.items-start` | ✅ |
| `.align-items-end` | `.items-end` | ✅ |
| `.flex-column` | `.flex-col` | ✅ |
| `.flex-wrap` | `.flex-wrap` | ✅ |
| `.flex-grow-1` | `.flex-grow` | ✅ |

## Sizing

| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `.w-100` | `.w-full` | ✅ |
| `.h-100` | `.h-full` | ✅ |
| `.mw-100` | `.max-w-full` | ✅ |
| `.vw-100` | `.w-screen` | ✅ |
| `.min-vh-100` | `.min-h-screen` | ✅ |

## Tables

| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `.table` | `.w-full .border-collapse` | Table base |
| `.table-striped` | `.divide-y .divide-gray-200` | Striped rows |
| `.table-bordered` | `.border .border-gray-300` | Bordered table |
| `.table-hover` | `.hover:bg-gray-50` | Hover effect |

## Badges & Pills

| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `.badge .bg-primary` | `.inline-flex .items-center .px-2.5 .py-0.5 .rounded-full .text-xs .font-medium .bg-blue-100 .text-blue-800` | Primary badge |
| `.badge .bg-success` | `.inline-flex .items-center .px-2.5 .py-0.5 .rounded-full .text-xs .font-medium .bg-green-100 .text-green-800` | Success badge |

## Implementation Location (NEVER FORGET!)

### ✅ Correct
- **CSS**: `laravel/Themes/Sixteen/resources/css/app.css`
- **JS**: `laravel/Themes/Sixteen/resources/js/app.js`
- **Build**: `cd laravel/Themes/Sixteen && npm run build && npm run copy`
- **Verify**: `ls -la public_html/themes/Sixteen/`

### ❌ WRONG (VIETATO)
- **NO** `<style>` blocks in Blade files
- **NO** `style="..."` inline styles in Blade
- **NO** CSS in `laravel/Modules/Fixcity/` (that's PHP logic)
- **NO** Forgetting to run `npm run copy` after build

## Example: Converting a Bootstrap Component

### Bootstrap (Design Comuni Reference)
```html
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Titolo</h5>
          <p class="card-text">Testo</p>
          <button class="btn btn-primary">Azione</button>
        </div>
      </div>
    </div>
  </div>
</div>
```

### Tailwind (Our Implementation in `app.css` + Blade)
```css
/* In app.css */
.card-custom {
  @apply bg-white rounded-lg shadow-md overflow-hidden;
}
.card-body-custom {
  @apply p-4;
}
```

```html
<!-- In Blade file -->
<div class="container mx-auto px-4">
  <div class="flex flex-wrap -mx-4">
    <div class="md:w-1/2 px-4">
      <div class="card-custom">
        <div class="card-body-custom">
          <h5 class="text-lg font-bold mb-2">Titolo</h5>
          <p class="text-gray-600 mb-4">Testo</p>
          <button class="bg-[#0066CC] text-white px-4 py-2 rounded hover:bg-[#004A99]">
            Azione
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
```

## Bootstrap Italia JS → Alpine.js Mapping

| Bootstrap JS | Alpine.js | Notes |
|--------------|----------|-------|
| `data-bs-toggle="dropdown"` | `x-data="{ open: false }" @click="open = !open"` | Dropdown toggle |
| `data-bs-dismiss="modal"` | `@click="$dispatch('close-modal')"` | Modal close |
| `data-bs-target="#myModal"` | `x-show="open"` | Modal show/hide |
| `class="collapse"` | `x-show="open"` | Collapse toggle |
| `data-bs-toggle="tooltip"` | Custom Alpine component | Tooltip (use `@mouseenter`/`@mouseleave`) |

## Verification Checklist

After applying any mapping:
- [ ] Run `cd laravel/Themes/Sixteen && npm run build`
- [ ] Run `npm run copy`
- [ ] Verify `public_html/themes/Sixteen/` has latest build
- [ ] Screenshot and compare with Design Comuni reference
- [ ] **NO inline CSS** in Blade (verify with `grep -r "style=" resources/views/`)
- [ ] **NO `<style>` blocks** in Blade (verify with `grep -r "<style>" resources/views/`)

---

**Last updated**: 2026-05-04 by LLM Wiki Maintainer
**Next action**: Apply mapping to `app.css`, build, copy, verify visual parity
