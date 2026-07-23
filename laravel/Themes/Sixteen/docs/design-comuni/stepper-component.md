# Stepper Component - Design Comuni Parity

**Page**: segnalazione-02-dati  
**Last Updated**: 2026-04-13  
**Status**: ✅ Mobile-first responsive CSS implemented

---

## Overview

The stepper component displays the multi-step progress indicator for the segnalazione (disruption report) flow. It shows completed, active, and pending steps with proper visual styling at all breakpoints.

**Reference**: [Design Comuni segnalazione-02-dati](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html)

---

## Implementation

### Blade Component

**Location**: `laravel/Themes/Sixteen/resources/views/components/blocks/tests/segnalazione-02-dati.blade.php`

**Features**:
- ✅ All text uses translation keys (NO hardcoded Italian)
- ✅ Mobile-first responsive design
- ✅ Semantic HTML with `visually-hidden` accessibility labels
- ✅ Bootstrap Italia class names for HTML parity

### CSS Styling

**Location**: `laravel/Themes/Sixteen/resources/css/segnalazione-parity.css` (lines 1025-1180)

**Breakpoints**:
| Breakpoint | Behavior |
|------------|----------|
| Mobile (<768px) | Wrapping stepper, smaller font (0.875rem), index at top, auto height |
| Tablet (768-1023px) | Medium stepper, nowrap text, restored icon sizes |
| Desktop (≥1024px) | Full horizontal stepper, 64px height, larger fonts |

**Key Classes**:
```css
.steppers              /* Wrapper */
.steppers-header       /* Header container (flex-wrap on mobile) */
.steppers-header ul    /* Step list (flex-wrap on mobile) */
.steppers-header li    /* Individual step (white-space: normal on mobile) */
.steppers-index        /* "2/3" indicator (order: -1 on mobile) */
```

**Mobile-First CSS**:
```css
/* Base: Mobile first */
.steppers-header {
  height: auto;
  flex-wrap: wrap;
  padding: 0 16px;
}

/* Tablet: 768px+ */
@media (min-width: 768px) {
  .steppers-header { padding: 0 20px; }
  .steppers-header li { white-space: nowrap; }
}

/* Desktop: 1024px+ */
@media (min-width: 1024px) {
  .steppers-header { height: 64px; padding: 0 24px; }
}
```

---

## Translation Keys

All stepper text uses the `fixcity::segnalazione.steps.*` namespace:

| Key | Italian | English |
|-----|---------|---------|
| `steps.privacy.label` | Autorizzazioni e condizioni | Authorizations and conditions |
| `steps.data.label` | Dati di segnalazione | Report Data |
| `steps.summary.label` | Riepilogo | Summary |
| `steps.confirmed.label` | Confermato | Confirmed |
| `steps.active.label` | Attivo | Active |
| `steps.step_number.label` | Passo :number | Step :number |
| `steps.current_of_total.label` | Passo :current di :total | Step :current of :total |

**Files**:
- Italian: `laravel/Modules/Fixcity/lang/it/segnalazione.php`
- English: `laravel/Modules/Fixcity/lang/en/segnalazione.php`

---

## Related Documentation

- **Story BMAD (allineamento modulo Fixcity: label step 1, CTA step 1 vs step 2+)** — [7-32](../../../../../_bmad-output/implementation-artifacts/7-32-segnalazione-crea-design-comuni-step1-cta-stepper-labels-header-parity.md)
- **[Body Plain Rule](./body-plain-rule.md)** — Body tag must remain plain (no classes)
- **[Design Comuni Replication Rules](../DESIGN_COMUNI_RULES.md)** — Body tag rules, translation format
- **[segnalazione-02-dati Page](./segnalazione-02-dati.md)** — Full page documentation
- **[segnalazione-parity.css](../../resources/css/segnalazione-parity.css)** — CSS file (lines 1025-1180)
- **[Block Implementation Guide](./BLOCK_IMPLEMENTATION_GUIDE.md)** — Universal block patterns

---

## Change History

| Date | Change | Commit |
|------|--------|--------|
| 2026-04-13 | Updated stepper CSS breakpoints (768px, 1024px) | `new` |
| 2026-04-13 | Added mobile-first media queries | `new` |
| 2026-04-12 | Added mobile-first responsive stepper CSS | `8f547e01d` |
| 2026-04-12 | Replaced hardcoded Italian with translation keys | `8f547e01d` |
| 2026-04-10 | Initial stepper component creation | `f717e62a` |

---

## 🔗 Cross-References

- → [Body Plain Rule](./body-plain-rule.md) — HTML parity policy
- → [00-INDEX](./00-INDEX.md) — Main documentation index
- → [segnalazione-02-dati Blade](../../resources/views/components/blocks/tests/segnalazione-02-dati.blade.php)
- → [Translation Files](../../../Modules/Fixcity/lang/it/segnalazione.php)
