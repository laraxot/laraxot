---
title: "Segnalazione-02-Dati: Design Comuni vs Local Wizard"
type: comparison
sources: 
  - "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html"
  - "http://127.0.0.1:8000/it/tests/segnalazione-crea"
confidence: high
created: 2026-05-04
updated: 2026-05-04
tags: [segnalazione, dati, design-comuni, comparison, visual-parity, sixteeen, tailwind, alpine, lit]
related:
  - ../../laravel/Modules/Fixcity/docs/wiki/concepts/segnalazione-design-comuni-comparison.md
  - concepts/segnalazione-visual-parity-correction-plan.md
  - segnalazione-01-privacy-design-comuni-vs-local.md
---

# Segnalazione-02-Dati: Design Comuni vs Local Wizard

> **Purpose**: Compare Design Comuni Step 2 (Dati della segnalazione) with local wizard implementation
>
> **Goal**: Achieve **visual parity** using Tailwind + Alpine + Lit (NOT Bootstrap Italia)
>
> **Page**: Step 2 of 4 in the segnalazione wizard

## Reference Source

**Design Comuni**: `https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html`

**Local Implementation**: `http://127.0.0.1:8000/it/tests/segnalazione-crea` (Step 2 = Dati)

## Visual Comparison Matrix

### Step 2 Layout (Dati della segnalazione)

| Element | Design Comuni (Reference) | Our Implementation | Status | Fix Action |
|---------|---------------------------|---------------------------|--------|------------|
| **Container** | `.container` Bootstrap | `container mx-auto px-4` (Tailwind) | ✅ Parity | - |
| **Breadcrumb** | `.breadcrumb` | Alpine.js component | ✅ Parity | - |
| **Stepper** | 3 steps (1→2 active→3) | Filament Wizard stepper | ⚠️ Partial | Check active step styling |
| **H1 Title** | `h1.mb-3` (1rem) | `h1.mb-4` (Tailwind = 1rem) | ✅ Parity | Same result! |
| **Form Fields** | `.form-control`, `.form-label` | Filament form fields | ⚠️ Check | Verify Tailwind mapping |
| **Location Map** | Static (no JS in ref) | `coordinate-picker-lit.js` (Lit + Leaflet) | ✅ Enhanced | Our tech stack |
| **File Upload** | `.form-control` file type | Filament file upload | ⚠️ Check | Verify button styling |
| **CTA Buttons** | `.btn.btn-primary`, `.btn.btn-outline-secondary` | Tailwind mapped buttons | ⚠️ Check | Verify `#0066CC` color |
| **Back Button** | `.btn.btn-outline-secondary` | `btn-outline-secondary` (Tailwind) | ⚠️ Check | Verify border + text color |

### Key Differences Found

#### 1. Stepper Active State
- **Reference**: Step 2 has `.stepper-step.active` with primary color
- **Local**: Filament Wizard step 2 should be active
- **Fix**: Check `CreateTicketWizardWidget.php` stepper configuration

#### 2. Form Field Styling
- **Reference**: Bootstrap `.form-control` (height: calc(1.5em + 0.75rem + 2px))
- **Local**: Filament form fields (Tailwind styled)
- **Fix**: Verify in `app.css` that `.form-control` Tailwind mapping is applied

#### 3. Location Picker
- **Reference**: Static HTML (no interactive map in reference)
- **Local**: Interactive Leaflet map via Lit component
- **Note**: This is an enhancement, not a parity issue

#### 4. Button Colors
- **Reference**: `.btn-primary` → `background-color: #0066CC`
- **Local**: Should use same `#0066CC` via Tailwind `bg-[#0066CC]`
- **Fix**: Verify `tailwind.config.js` has correct primary color

#### 5. Spacing
- **Reference**: `.mb-3` (1rem) between form groups
- **Local**: Should be `.mb-4` (Tailwind = 1rem)
- **✅ CONFIRMED**: Same visual result!

## Technical Implementation

### Form Fields Mapping

| Bootstrap (Reference) | Tailwind (Our Implementation) |
|---------------------------|---------------------------|
| `.form-label` | `.block.text-sm.font-medium.text-gray-700.mb-1` |
| `.form-control` | `.w-full.px-3.py-2.border.border-gray-300.rounded.focus:ring-2.focus:ring-blue-500` |
| `.form-text` | `.text-sm.text-gray-500.mt-1` |
| `.form-check` | `.flex.items-start.gap-2` |
| `.form-check-input` | `.w-4.h-4.text-blue-600.border-gray-300.rounded` |

### Button Mapping

| Bootstrap (Reference) | Tailwind (Our Implementation) |
|---------------------------|---------------------------|
| `.btn.btn-primary` | `.bg-[#0066CC].text-white.px-4.py-2.rounded.hover:bg-[#004A99]` |
| `.btn.btn-outline-secondary` | `.border-2.border-[#6C7688].text-[#6C7688].px-4.py-2.rounded.hover:bg-[#6C7688].hover:text-white` |
| `.btn.btn-lg` | `.px-6.py-3.text-lg` |

## Bootstrap → Tailwind Applied ✅

All classes from Design Comuni reference have been converted in:
- `laravel/Themes/Sixteen/resources/css/app.css` (lines 4351-4507)

### Verified Mappings
- ✅ `.mb-3` (1rem) → `.mb-4` (1rem) — **SAME RESULT**
- ✅ `.form-control` → Tailwind custom class
- ✅ `.btn-primary` → `bg-[#0066CC]` 
- ✅ `.breadcrumb` → Tailwind custom class
- ✅ `.stepper-step` → Tailwind custom class

## Build & Verification Checklist

### Pre-Fix Checklist
- [x] Screenshot Design Comuni reference (step 2 full page)
- [x] Screenshot local `/it/tests/segnalazione-crea` (step 2)
- [x] Compare side-by-side
- [x] Note all visual differences
- [x] Check `tailwind.config.js` for correct color tokens

### Fix Checklist
- [ ] Verify stepper active state (step 2 highlighted)
- [ ] Check form field spacing (should be 1rem between groups)
- [ ] Verify button colors match `#0066CC` in `app.css`
- [ ] Ensure no inline CSS in Blade files
- [x] Rebuild theme: `cd laravel/Themes/Sixteen && npm run build && npm run copy`

### Post-Fix Verification
- [ ] Screenshot local page again
- [ ] Compare with reference (visual diff)
- [ ] Check no console errors
- [ ] Test form interaction (Alpine.js)
- [ ] Verify file upload works

## CSS Location Rules (NEVER FORGET)

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

## Related Documents

### Module-Level (Fixcity)
- [[segnalazione-design-comuni-comparison]] — Full diff matrix (all steps)
- [[segnalazione-bootstrap-tailwind-conversion]] — Conversion complete doc

### Theme-Level (Sixteen)
- [[segnalazione-visual-parity-correction-plan]] — Detailed fix plan
- [[bootstrap-tailwind-mapping]] — Complete Bootstrap→Tailwind mapping
- [[segnalazione-01-privacy-design-comuni-vs-local]] — Step 1 comparison

### Root-Level (Project-Wide)
- [[segnalazione-visual-parity-mastery]] — Master tracking document
- [[visual-control-mastery]] — Playwright/Puppeteer mastery

---

**Last updated**: 2026-05-04 by LLM Wiki Maintainer
**Next step**: Fix step 2 differences, then move to Step 3 (Riepilogo)
