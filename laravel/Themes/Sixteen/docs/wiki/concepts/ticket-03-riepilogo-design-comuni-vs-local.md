---
title: "Segnalazione-03-Riepilogo: Design Comuni vs Local Wizard"
type: comparison
sources: 
  - "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-03-riepilogo.html"
  - "http://127.0.0.1:8000/it/tests/segnalazione-crea"
confidence: high
created: 2026-05-04
updated: 2026-05-04
tags: [segnalazione, riepilogo, design-comuni, comparison, visual-parity, tailwind, alpine, lit]
related:
  - ../../laravel/Modules/Fixcity/docs/wiki/concepts/segnalazione-design-comuni-comparison.md
  - ../../laravel/Themes/Sixteen/docs/wiki/concepts/segnalazione-visual-parity-correction-plan.md
  - segnalazione-01-privacy-design-comuni-vs-local.md
  - segnalazione-02-dati-design-comuni-vs-local.md
---

# Segnalazione-03-Riepilogo: Design Comuni vs Local Wizard

> **Purpose**: Compare Design Comuni Step 3 (Riepilogo) with local wizard implementation
> 
> **Goal**: Achieve **visual parity** using Tailwind + Alpine + Lit
> 
> **Key**: Use Filament Infolist entries (NOT SchemaView) for summary

## Reference Source

**Design Comuni**: `https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-03-riepilogo.html`

**Local Implementation**: `http://127.0.0.1:8000/it/tests/segnalazione-crea` (Step 3 = Riepilogo)

## Visual Comparison Matrix

### Step 3 Layout (Riepilogo)

| Element | Design Comuni (Reference) | Our Implementation | Status | Fix Action |
|---------|---------------------------|---------------------------|--------|------------|
| **Container** | `.container` Bootstrap | `container mx-auto px-4` (Tailwind) | ✅ Parity | - |
| **Stepper** | Step 3 active (full bar) | Filament Wizard Step 3 | ⚠️ Check | Verify step 3 is active |
| **Title** | `h1.mb-3` (1rem) | `h1.mb-4` (Tailwind = 1rem) | ✅ Parity | Same result! |
| **Summary DL** | `dl.row` with `dt.col-md-3`, `dd.col-md-9` | Filament Infolist `TextEntry` | ⚠️ Critical | Use Infolist, NOT SchemaView |
| **Privacy Check** | `.form-check` checkbox | Already confirmed in Step 1 | ✅ Done | - |
| **CTA Buttons** | `.btn.btn-primary` + `.btn.btn-outline-secondary` | Tailwind mapped buttons | ⚠️ Check | Verify `#0066CC` color |
| **Back Button** | "Indietro" text | "Indietro" with Alpine.js | ✅ Parity | - |

### Critical Implementation Rule ⚠️

**From Permanent Guardrails**:
- `getSummarySchema()` in Fixcity wizard summaries uses **Filament 5 Infolist entries**, not `SchemaView`
- Use `TextEntry`, `IconEntry` from `Filament\Infolists\Components\`
- NO `Filament\Infolists\Components\Infolist` as schema component

## Bootstrap → Tailwind Mapping (Step 3)

### Definition List (Summary)

| Bootstrap (Reference) | Tailwind (Our Implementation) |
|---------------------------|---------------------------|
| `dl.row` | `.flex.flex-col.gap-4` or custom CSS |
| `dt.col-md-3` | `.md:w-1/4.font-medium.text-gray-700` |
| `dd.col-md-9` | `.md:w-3/4` |
| `.mb-3` (1rem) | `.mb-4` (Tailwind = 1rem) ✅ |

### Buttons

| Bootstrap (Reference) | Tailwind (Our Implementation) |
|---------------------------|---------------------------|
| `.btn.btn-primary` | `.bg-[#0066CC].text-white.px-4.py-2.rounded` |
| `.btn.btn-outline-secondary` | `.border-2.border-[#6C7688].text-[#6C7688].px-4.py-2.rounded` |
| `.btn-lg` | `.px-6.py-3.text-lg` |

## Technical Implementation

### Correct: Filament Infolist Summary

```php
// In CreateTicketWizardWidget.php - Step 3
->schema([
    Infolist::make()->schema([
        Section::make('Riepilogo')->schema([
            TextEntry::make('type')->label('Tipo segnalazione'),
            TextEntry::make('location.address')->label('Indirizzo'),
            TextEntry::make('description')->label('Descrizione'),
        ]),
])
```

### WRONG (Do NOT use)

```php
// ❌ NEVER USE THIS:
SchemaView::make('summary')->view('filament::components.infolist.schema')
```

## Build & Verification Checklist

### Pre-Fix Checklist
- [x] Screenshot Design Comuni reference (Step 3 full page)
- [x] Screenshot local `/it/tests/segnalazione-crea` (Step 3)
- [ ] Compare side-by-side
- [ ] Verify Infolist used (NOT SchemaView)
- [ ] Check `laravel/Themes/Sixteen/tailwind.config.js` for correct color tokens

### Fix Checklist
- [ ] Verify step 3 is active in Filament Wizard
- [ ] Check summary uses `TextEntry` (NOT `SchemaView`)
- [ ] Verify button colors match `#0066CC` in `app.css`
- [ ] Ensure NO inline CSS in Blade files
- [x] Rebuild theme: `cd laravel/Themes/Sixteen && npm run build && npm run copy`

### Post-Fix Verification
- [ ] Screenshot local page again
- [ ] Compare with reference (visual diff)
- [ ] Check no console errors
- [ ] Test "Indietro" button (Alpine.js)
- [ ] Verify form submission works

## CSS Location Rules (NEVER FORGET)

### ✅ Correct
- **CSS**: `laravel/Themes/Sixteen/resources/css/app.css`
- **JS**: `laravel/Themes/Sixteen/resources/js/app.js`
- **Build**: `cd laravel/Themes/Sixteen && npm run build && npm run copy`
- **Verify**: `ls -la public_html/themes/Sixteen/`

### ❌ WRONG (FORBIDDEN)
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
- [[segnalazione-02-dati-design-comuni-vs-local]] — Step 2 comparison

### Root-Level (Project-Wide)
- [[segnalazione-visual-parity-mastery]] — Master tracking document
- [[visual-control-mastery]] — Playwright/Puppeteer mastery

---

**Last updated**: 2026-05-04 by LLM Wiki Maintainer
**Next step**: Fix Step 3 summary (use Infolist), then move to Step 4 (Conferma)
