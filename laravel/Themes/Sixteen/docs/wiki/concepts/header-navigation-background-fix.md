# Header Navigation Background Fix

## Issue
Navigation items in the header (`Amministrazione`, `Novità`, `Servizi`, `Vivere il Comune`, etc.) were rendered with a default **blue** background from Bootstrap Italia, breaking visual parity with the Design Comuni green theme used for the logo and header.

## Root Cause
Bootstrap Italia applies its own background color to `.navbar-nav .nav-link`. The Sixteen theme only set the header wrapper colors; individual navigation links inherited the default blue.

## Solution
- Added explicit CSS rules in `header-footer-colors.css` to force navigation links to use the theme's green variables:
  ```css
  .it-header-navbar-wrapper .navbar-nav .nav-link {
      background-color: var(--color-italia) !important;
      color: #FFFFFF !important;
  }
  .it-header-navbar-wrapper .navbar-nav .nav-link.active {
      background-color: var(--color-italia-dark) !important;
  }
  ```
- This ensures all primary navigation items share the same green background as the logo and slogan, while the active link uses the darker green for contrast.

## Verification
1. Open any page under the Sixteen theme (e.g., `/it/tests/amministrazione`).
2. Verify that the navigation bar items display a green background matching `var(--color-italia)`.
3. Confirm the active page link uses `var(--color-italia-dark)`.
4. No blue background remains.

## Related Rules
- **Design Comuni Theme CSS Build Workflow** – after CSS changes run `npm run build && npm run copy`.
- **No Page‑Specific CSS** – background color is applied globally to the component, not per‑page.

## References
- `header-footer-colors.css` – where the rule lives.
- Design Comuni reference: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-03-riepilogo.html

---
*Created by the Bmad‑Create‑Story skill (Story 8‑45).*