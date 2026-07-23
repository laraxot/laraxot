# Design Comuni Replication - Strategic Implementation Plan

## Status: HTML Structure ✅ VERIFIED (99% match across all 32 pages)

### Current State
- All 32 pages have nearly identical HTML structure to reference
- Block system working correctly (breadcrumb, hero, search, accordion, etc.)
- JSON config driving content properly
- Bootstrap Italia classes visible in output (need Tailwind replacement)

### Work Required: CSS + Alpine Only

**NOT** modifying HTML structure or blocks  
**FOCUS**: Tailwind CSS + Alpine.js styling and interactions

---

## Phase 1: CSS Mapping & Analysis

### Bootstrap Italia → Tailwind Mapping
Extract from REFERENCE_DESIGN_ANALYSIS.md and create mapping:

```
Bootstrap Italia Class | Tailwind Equivalent | Applied Component | Status
.container            | max-w-[1200px] mx-auto px-4 | All containers | ⏳
.row                  | flex flex-wrap     | All rows | ⏳
.col-12               | w-full             | All columns | ⏳
.col-lg-10            | lg:w-10/12         | Content areas | ⏳
.justify-content-center | justify-center   | Centering | ⏳
.pt-0, .pb-4, etc.    | pt-0, pb-4, etc.   | Spacing | ⏳
.bg-white             | bg-white           | Backgrounds | ⏳
.text-black           | text-black         | Typography | ⏳
.btn-primary          | bg-blue-600 text-white hover:bg-blue-700 | Buttons | ⏳
```

### Component-Level CSS Tasks

| Component | Bootstrap Classes | Tailwind Target | Files to Update | Priority |
|-----------|------------------|-----------------|-----------------|----------|
| Breadcrumb | .breadcrumb, .breadcrumb-item | flex items-center text-sm | breadcrumb.blade.php | 1 |
| Hero | .it-hero-wrapper, .pt-0, .pb-lg-60 | py-12 lg:py-16 | hero.blade.php | 1 |
| Search | .form-control, .btn-primary | border rounded focus:ring | search.blade.php | 1 |
| Accordion | .accordion, .accordion-item | [accordion-alpine-state] | accordion.blade.php | 2 |
| Grid/Cards | .row, .col-* | grid grid-cols-1 md:grid-cols-2 | grid.blade.php | 2 |
| Form | .form-group, .form-control | [form-components] | form.blade.php | 2 |
| Footer | .footer | footer component | footer.blade.php | 3 |

---

## Phase 2: Alpine.js Implementation

### Interaction Patterns Needed

1. **Accordion Toggle**
   - Toggle expand/collapse on button click
   - Smooth height animation
   - Update aria-expanded

2. **Search/Filter**
   - Real-time search filtering (if applicable)
   - Autocomplete dropdown
   - Clear button functionality

3. **Modal/Dialog**
   - Show/hide on button click
   - Dismiss on outside click
   - Focus trap

4. **Form Handling**
   - Input validation
   - Form submission
   - Success/error states

5. **Responsive Toggle**
   - Mobile menu toggle
   - Sidebar toggle
   - Responsive visibility

---

## Phase 3: Implementation Workflow

### Option A: Focused per-Page (Slow but Safe)
1. Pick one page
2. Update all Bootstrap classes to Tailwind
3. Test in browser
4. Document approach
5. Repeat for each page

**Timeline**: 32 pages × 30 min = 16 hours (not viable)

### Option B: Component-Driven Batch (Fast and Smart) ✅ RECOMMENDED
1. Identify 12 core components
2. Update component template once
3. Apply to ALL pages automatically
4. Test all pages simultaneously
5. Document component approach

**Timeline**: 12 components × 45 min = 9 hours (viable)

### Option C: CSS-Global + Template Updates (Hybrid)
1. Create global Tailwind stylesheet override for common classes
2. Update component templates selectively
3. Use @apply directives to centralize styles
4. Progressive enhancement per component

**Timeline**: 4-6 hours (most practical)

---

## Implementation Tasks

### CSS Tasks
- [ ] Create `faq-replication.css` with Tailwind @apply rules
- [ ] Map all Bootstrap Italia classes
- [ ] Create responsive utilities
- [ ] Test across breakpoints

### Alpine Tasks
- [ ] Implement accordion toggle (`x-data`, `@click`)
- [ ] Add smooth transitions
- [ ] Test keyboard navigation (Tab, Enter, Escape)
- [ ] Verify focus management

### Testing Tasks
- [ ] Desktop screenshot (1440px)
- [ ] Tablet screenshot (768px)
- [ ] Mobile screenshot (375px)
- [ ] Compare to reference
- [ ] Browser compatibility

### Documentation Tasks
- [ ] CSS implementation guide
- [ ] Alpine component patterns
- [ ] Screenshot comparisons
- [ ] Per-page status matrix

---

## File Organization

```
laravel/Themes/Sixteen/
├── resources/css/
│   ├── app.css (main)
│   ├── faq-replication.css (NEW - Tailwind overrides)
│   └── components.css (component styles)
├── resources/js/
│   ├── app.js (main)
│   └── components/ (Alpine components)
└── docs/
    ├── implementation/
    │   ├── STRATEGY.md (this file)
    │   ├── CSS_MAPPING.md (Bootstrap → Tailwind)
    │   ├── COMPONENT_CHECKLIST.md (per-component status)
    │   └── SCREENSHOTS/ (comparisons)
    └── analysis/
        ├── REFERENCE_DESIGN_ANALYSIS.md
        └── CURRENT_CSS_ANALYSIS.md
```

---

## Build & Test Cycle

```bash
# 1. Make CSS changes
vim laravel/Themes/Sixteen/resources/css/faq-replication.css

# 2. Make Alpine changes
vim laravel/Themes/Sixteen/resources/js/components/accordion.js

# 3. Build
cd laravel/Themes/Sixteen
npm run build

# 4. Copy assets
npm run copy

# 5. Test in browser
open http://127.0.0.1:8000/it/tests/domande-frequenti

# 6. Screenshot
# (manual or automated)

# 7. Document findings
```

---

## Success Criteria

✅ All 32 pages visually match reference  
✅ No Bootstrap Italia classes in output  
✅ All CSS via Tailwind  
✅ All interactions via Alpine.js  
✅ Responsive: 320px, 768px, 1200px+  
✅ Accessibility: WCAG AA minimum  
✅ Performance: Lighthouse 90+  

---

## Next Immediate Steps

1. **Now**: Focus on CSS mapping (30 min)
2. **Then**: Update first component template (30 min)
3. **Then**: Test build process (15 min)
4. **Then**: Screenshot comparison (15 min)
5. **Then**: Document approach and iterate

---

**Prepared**: 2026-04-03 | **By**: Copilot CLI  
**For**: All 32 Design Comuni pages  
**Method**: Component-driven batch implementation (Option C recommended)
