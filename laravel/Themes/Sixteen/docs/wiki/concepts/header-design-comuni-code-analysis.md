---
type: concept
created: 2026-05-04
tags: [design-comuni, visual-parity, header, analysis]
status: active
---

# Header Visual Parity Analysis (Design Comuni)

Comparison between current Sixteen theme header and the [Design Comuni official reference](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html).

## Technical Mapping

| Reference Element | Implementation | Status |
|-------------------|----------------|--------|
| Slim Bar Dark Box | `.btn-full` | 🔴 Needs dark green background `#00402B`. |
| Logo Shield Size | `.it-brand-wrapper svg` | 🔴 Local is 82px, reference feels more padded. |
| Nav Active State | `border-bottom: 3px solid #fff` | 🔴 Local uses background fill (blue/dark). |
| Nav Font Weight | `font-weight: 700` | 🔴 Local is `400`. |
| Blue Strip (Left) | Custom Rule | 🔴 Needs removal from `.it-header-navbar-wrapper`. |

## CSS Cascading Issues
Current rules are scattered between:
1. `header-footer-colors.css`
2. `header-fix.css`
3. `app.css`

**Rule**: All header-specific visual parity fixes MUST be consolidated in `header-footer-colors.css`.

## Interactivity Note
Design Comuni uses Bootstrap JS (`data-bs-toggle`). Sixteen uses **Alpine.js** for mobile navigation and **Lit** for custom components. This is a deliberate choice to avoid heavy Bootstrap JS dependencies while maintaining visual parity.

## Actionable Tasks
- [ ] Set `.btn-full` bg to `#00402B`.
- [ ] Set `.nav-link.active` border and remove background.
- [ ] Increase `.it-header-center-wrapper` padding.
- [ ] Update `.nav-link` font weight to `700`.

---
*Reference Story: [[../../../../_bmad-output/implementation-artifacts/7-107-header-visual-parity-design-comuni.md]]*
