---
type: comparison
created: 2026-05-04
tags: [design-comuni, visual-parity, segnalazione, privacy]
---

# Comparison: Design Comuni vs Local Segnalazione (Step 1)

Comparison between the official [Design Comuni Privacy Step](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html) and the local implementation at `http://127.0.0.1:8000/it/tests/segnalazione-crea`.

## Structural Analysis

| Feature | Design Comuni (Reference) | Local Implementation | Parity Status |
|---------|---------------------------|----------------------|---------------|
| **Header** | Institutional (Bootstrap Italia) | Sixteen Theme (Tailwind) | 🟡 Aligned Visuals |
| **Stepper** | Index "1/3" + Stages | Filament Wizard Stepper | 🔴 Needs Styling |
| **Legal Text** | Paragraph with GDPR 2016/679 | Present (Firenze specific) | ✅ Aligned |
| **Checkbox** | Custom Bootstrap Italia toggle | Filament Checkbox | 🟡 Functional Parity |
| **Sidebar** | Assistance/FAQ block | Missing or different layout | 🔴 Missing |
| **Breadcrumbs** | `cmp-breadcrumbs` | `cmp-breadcrumbs` (Volt) | ✅ Aligned |

## Key Differences & Issues
- **Stepper:** The Filament default stepper doesn't match the institutional "Index" look of Design Comuni.
- **Sidebar:** The reference has a "Contatta il comune" sidebar which is currently not present in the local wizard view.
- **HTML Parity:** While structure is similar, the nesting and specific component classes (e.g. `cmp-header`) need to be ensured without bringing in Bootstrap Italia CSS.
- **Inline Styles:** Local version contains some Livewire-injected styles which are acceptable, but we must ensure no custom `<style>` blocks are in the Blade templates.

## Strategy
- **Visual Parity:** Match the look exactly using **TailwindCSS**.
- **HTML Parity:** Replicate the div structure and semantic classes (aria-labels, data-elements).
- **Tooling:** Use Alpine.js for interactivity and Lit for complex components (if any).

---
*Generated via automated visual audit on 2026-05-04.*
