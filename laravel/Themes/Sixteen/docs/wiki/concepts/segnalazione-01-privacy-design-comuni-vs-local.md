---
title: "Segnalazione-01-Privacy: Design Comuni vs Local Wizard"
type: comparison
sources: 
  - "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html"
  - "http://127.0.0.1:8000/it/tests/segnalazione-crea"
confidence: high
created: 2026-05-04
updated: 2026-05-04
tags: [segnalazione, privacy, design-comuni, comparison, visual-parity, sixteeen, tailwind, alpine, lit]
related:
  - ../../laravel/Modules/Fixcity/docs/wiki/concepts/segnalazione-design-comuni-comparison.md
  - concepts/segnalazione-visual-parity-correction-plan.md
  - ../../docs/wiki/concepts/segnalazione-visual-parity-mastery.md
---

# Segnalazione-01-Privacy: Design Comuni vs Local Wizard

> **Purpose**: Compare Design Comuni reference (static HTML) with local `/it/tests/segnalazione-crea` wizard implementation
>
> **Goal**: Achieve **visual parity** using Tailwind + Alpine + Lit (NOT Bootstrap Italia)
>
> **Strategy**: Map Bootstrap classes to Tailwind utilities, preserve functionality

## Reference Source

**Design Comuni**: `https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html`

**Local Implementation**: `http://127.0.0.1:8000/it/tests/segnalazione-crea` (Step 1 = Privacy)

## Visual Comparison Matrix

### Privacy Step (Step 1)

| Element | Design Comuni (Reference) | Our Implementation | Status | Fix Action |
|---------|---------------------------|---------------------------|--------|------------|
| **Layout** | Container > Row > Col-md-12 | `container mx-auto px-4` (Tailwind) | ✅ Parity | - |
| **Breadcrumb** | `.breadcrumb`, `.breadcrumb-item` | Alpine.js x-data component | ✅ Parity | - |
| **Stepper** | `.stepper`, `.stepper-step` (3 steps) | Filament Wizard Stepper | ⚠️ Partial | Check labels match exactly |
| **Title** | `h1 .mb-3` (Bootstrap) | `h1 .mb-4` (Tailwind = same 1rem) | ✅ Parity | - |
| **Privacy Checkbox** | `.form-check`, `.form-check-input` | Filament Checkbox (Tailwind styled) | ⚠️ Check | Ensure same size + label |
| **Privacy Text** | `.form-text`, `.text-muted` (Bootstrap) | `text-sm text-gray-600` (Tailwind) | ✅ Parity | - |
| **CTA Button** | `.btn .btn-primary .btn-lg` (Bootstrap) | `btn-primary btn-lg` (Tailwind mapped) | ⚠️ Check | Verify color token `#0066CC` |
| **Back Button** | `.btn .btn-outline-secondary` | `btn-outline-secondary` (Tailwind) | ⚠️ Check | Verify border + text color |

### Key Differences Found

#### 1. Stepper Labels
- **Reference**: "1. Dati della segnalazione", "2. Riepilogo", "3. Conferma"
- **Local**: May have different labels
- **Fix**: Update Filament Wizard step labels in `CreateTicketWizardWidget.php`

#### 2. Button Styling
- **Reference**: Bootstrap `.btn-primary` → `background-color: #0066CC`
- **Local**: Tailwind `.bg-primary` → should map to same `#0066CC`
- **Fix**: Verify `tailwind.config.js` has `colors.primary = '#0066CC'`

#### 3. Checkbox Size
- **Reference**: Bootstrap `.form-check-input` (16px default)
- **Local**: Filament checkbox (may be larger/smaller)
- **Fix**: CSS custom size in `app.css` if needed

#### 4. Typography
- **Reference**: Bootstrap typography scale
- **Local**: Tailwind default (check if same font-family imported)

## Technical Implementation Differences

| Aspect | Design Comuni | Our Implementation |
|--------|---------------------------|---------------------------|
| **CSS Framework** | Bootstrap Italia (Bootstrap 5 + custom) | **Tailwind CSS v4** |
| **JS Behavior** | Bootstrap JS (`data-bs-toggle`) | **Alpine.js** (`x-data`, `x-show`) |
| **Form Handling** | Static HTML form | **Livewire + Filament** |
| **Stepper** | Static HTML `.stepper` | **Filament Wizard** (PHP-generated) |
| **Build** | Webpack (npm run build) | **Vite** (`npm run build && npm run copy`) |

## Bootstrap → Tailwind Mapping (Privacy Step)

### Spacing (CRITICAL: Check Scale)
| Bootstrap Class | Pixel Value | Tailwind Class | Pixel Value | Match? |
|----------------|-------------|----------------|-------------|--------|
| `.mb-3` | 1rem | `.mb-4` | 1rem | ✅ YES |
| `.p-4` | 1.5rem | `.p-6` | 1.5rem | ✅ YES |
| `.py-2` | 0.5rem | `.py-2` | 0.5rem | ✅ YES |
| `.mt-4` | 1.5rem | `.mt-6` | 1.5rem | ✅ YES |

**Important**: Bootstrap uses 0.25rem per unit, Tailwind also uses 0.25rem per unit. Same scale!

### Colors
| Bootstrap Token | Hex Value | Tailwind Token | Match? |
|----------------|-----------|----------------|--------|
| `--bs-primary` | `#0066CC` | `colors.primary` | ✅ Set in config |
| `--bs-secondary` | `#6C7688` | `colors.secondary` | ⚠️ Verify |
| `--bs-success` | `#198754` | `colors.success` | ⚠️ Verify |

### Typography
| Bootstrap Class | Tailwind Equivalent | Notes |
|----------------|----------------------|-------|
| `.h1`, `.h2`, etc. | `.text-4xl`, `.text-3xl` | Map heading scale |
| `.fw-bold` | `.font-bold` | ✅ Same |
| `.text-muted` | `.text-gray-600` | ⚠️ Check contrast ratio |

## Build & Verification Checklist

### Pre-Fix Checklist
- [ ] Screenshot Design Comuni reference (full page)
- [ ] Screenshot local `/it/tests/segnalazione-crea` (step 1)
- [ ] Compare side-by-side
- [ ] Note all visual differences
- [ ] Check `laravel/Themes/Sixteen/tailwind.config.js` for correct color tokens

### Fix Checklist
- [ ] Update Filament Wizard step labels in `CreateTicketWizardWidget.php`
- [ ] Verify button colors match `#0066CC` in `app.css`
- [ ] Check checkbox size (should be 16px × 16px)
- [ ] Ensure no inline CSS in Blade files
- [ ] Rebuild theme: `cd laravel/Themes/Sixteen && npm run build && npm run copy`

### Post-Fix Verification
- [ ] Screenshot local page again
- [ ] Compare with reference (visual diff)
- [ ] Check no console errors
- [ ] Test checkbox interaction (Alpine.js)
- [ ] Verify form submission works

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

## Reference Screenshots

### Design Comuni (Reference)
```
URL: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html
Save to: laravel/Themes/Sixteen/docs/comparisons/screenshots/reference/segnalazione-01-privacy.png
```

### Local Implementation
```
URL: http://127.0.0.1:8000/it/tests/segnalazione-crea
Save to: laravel/Themes/Sixteen/docs/comparisons/screenshots/local/segnalazione-01-privacy.png
```

## Related Documents

### Module-Level (Fixcity)
- [[segnalazione-design-comuni-comparison]] — Full diff matrix (all steps)
- [[segnalazione-visual-parity]] — Module-level rules

### Theme-Level (Sixteen)
- [[segnalazione-visual-parity-correction-plan]] — Detailed fix plan
- [[segnalazione-visual-parity]] — Theme-level rules
- [[segnalazione-02-dati-design-comuni-vs-local-wizard]] — Next step comparison

### Stories
- [[../../../../../../_bmad-output/implementation-artifacts/8-110-segnalazione-crea-step1-privacy-design-comuni-parity]] — Active story for step 1 parity

### Root-Level (Project-Wide)
- [[visual-control-mastery]] — Playwright/Puppeteer mastery
- [[segnalazione-visual-parity-mastery]] — Master tracking document
- [[theme-owned-css-parity-rule]] — CSS parity in theme, not Blade
- [[no-page-specific-css]] — No `.ticket-wizard-root` or `[data-slug="..."]` scoped CSS

---

## Success Criteria (Visual Parity Achieved When)

1. ✅ Stepper labels match reference exactly
2. ✅ Primary button color = `#0066CC` (same as Bootstrap)
3. ✅ Checkbox size = 16px × 16px (standard)
4. ✅ No inline CSS in any Blade file
5. ✅ Theme build copied to `public_html/themes/Sixteen/`
6. ✅ Screenshot comparison shows no visible differences

---

**Last updated**: 2026-05-04 by LLM Wiki Maintainer
**Next step**: Fix privacy step differences, then move to Step 2 (Dati)
