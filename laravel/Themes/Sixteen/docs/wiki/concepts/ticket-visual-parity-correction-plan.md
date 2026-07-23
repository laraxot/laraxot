---
type: concept
created: 2026-05-04
tags: [design-comuni, plan, visual-parity, tailwind]
status: proposed
---

# Visual Parity Correction Plan: Segnalazione (Step 1)

Goal: Achieve high visual and HTML parity with [Design Comuni](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html) using TailwindCSS + Alpine + Lit.

## 1. Stepper Redesign
- **Issue:** Filament default stepper is too "admin-like".
- **Action:** Override the wizard stepper view in Sixteen theme to match the institutional "1/3" index and labeled progress bar.
- **Tools:** Tailwind for layout, Alpine for step transitions.

## 2. Privacy Checkbox Styling
- **Issue:** Standard checkbox vs Design Comuni toggle style.
- **Action:** Style the Filament checkbox using Tailwind to match the large, accessible institutional toggle.
- **Rule:** Use `fi-checkbox-input` class but extend with theme-specific Tailwind utilities.

## 3. Sidebar Implementation
- **Issue:** Missing assistance block.
- **Action:** Add a sidebar component to the wizard layout in `CreateTicketWizardWidget`.
- **Content:** FAQs, "Contatta il comune" links, and assistance phone numbers as per reference.

## 4. Code Discipline
- **Rule:** NO inline CSS in Blade files.
- **Process:**
  1. Update Tailwind config in theme if new tokens are needed.
  2. Add styles to `resources/css/app.css` in Sixteen theme.
  3. Run `npm run build` and `npm run copy` from the theme directory.

## 5. HTML Parity Audit
- **Task:** Verify `data-element` attributes (e.g., `data-element="breadcrumb"`) match the reference for automated accessibility and testing tools.

## 6. Bootstrap Italia class map (cross-page)
- **Story:** [`7-105`](../../../../../../_bmad-output/implementation-artifacts/7-105-design-comuni-segnalazione-static-pages-bootstrap-to-tailwind-class-map.md) — inventario classi sulle sette pagine statiche Design Comuni (dettaglio, wizard 01–04, area personale, elenco) e mapping in `resources/css/app.css` senza Bootstrap runtime.
- **Why:** un solo elenco deduplicato evita drift tra step e riduce duplicazioni tra Blade, Lit e Filament.

## 7. Header globale (slim + center + navbar)
- **Story:** [`7-107`](../../../../../../_bmad-output/implementation-artifacts/7-107-header-visual-parity-design-comuni.md) — parità visiva header con Design Comuni (D1–D6).
- **Analisi codice:** [header-design-comuni-code-analysis](./header-design-comuni-code-analysis.md) — mappa file CSS/Blade e nota su cascata vs AC «solo header-footer-colors.css».

## 8. SCSS ufficiale Design Comuni → asset Sixteen (Story 8-105, AC #1)

Mapping operativo (repo [design-comuni-pagine-statiche](https://github.com/italia/design-comuni-pagine-statiche), ramo `main`):

- **`src/stylesheets/bootstrap-italia-comuni.scss`** — entrypoint che importa Bootstrap Italia + override comuni; corrisponde a token e layer importati dal tema in `resources/css/components/header-footer-colors.css` (`:root` `--color-italia` / `--color-italia-dark`).
- **`src/stylesheets/_variables.scss`** — palette PA (verde istituzionale, contrasti); allineamento concettuale con variabili in `header-footer-colors.css` (slim scuro `#00402B`, center `#007A52`).
- **`src/stylesheets/base/_`*** — reset/tipografia di base; il tema replica markup BI (`it-header-slim-wrapper`, `it-header-center-wrapper`, `it-header-navbar-wrapper`) senza importare gli SCSS in runtime.
- **`src/stylesheets/general/_`*** — pattern layout header (zone slim/destra, navbar); riferimento per struttura `.it-search-wrapper` (etichetta + icona in stack) e `.nav-link.active`.
- **`src/stylesheets/custom/_`*** — override comuni-specifici (dropdown, megamenu); confronto con regole `#header-language-menu` e sottolineatura voce attiva in `header-footer-colors.css`.

---
*Phase 1 Implementation: Target completion by EOD.*
