# Visual parity check — Segnalazione (create)

Summary of findings (local vs design-comuni reference):

- Duplicate forward CTAs detected: "Successivo" (theme stepper / Filament footer) and "Avanti" (theme main files / custom nav). Keep a single primary CTA labeled "Avanti" per design-comuni parity. Hide Filament wizard footer or remove duplicate button on render.
- Templates with duplicates:
  - resources/views/components/utilities/stepper.blade.php → contains "Successivo"
  - resources/views/components/navigation/forward-back.blade.php → contains "Successivo"/"Avanti" markers
  - Main_files/five/index01.html → uses "Avanti" button with class `btn-next`
- Header: reference uses bootstrap-italia header where the slim header area has neutral background; theme uses bootstrap-italia CSS by default. Verify computed background-color for `.it-header-slim-wrapper` vs `.it-header-center-wrapper` in browser devtools. Documented rule: header background under "Amministrazione" and "Nome della Regione" must match reference (use theme variables or Tailwind mapping).
- Responsiveness: Ensure "Avanti" button is visible and tappable on mobile (mobile-first). Confirm via manual mobile and tablet viewports after building assets.

Recommended fixes and checklist:

1. Remove or visually hide Filament wizard footer that renders "Successivo" (preferred: hide via wrapper CSS `.filament-wizard-footer { display: none; }` when theme nav provides its own CTA).
2. Keep single CTA `btn-next` with label from lang files (`Avanti`) and ensure aria-label/role present for accessibility.
3. Replace Bootstrap-Italia runtime classes with Tailwind equivalents but keep semantic class names (e.g., `.btn-primary` mapped to Tailwind utilities via `.btn-primary { @apply bg-blue-600 text-white; }`).
4. Run `cd laravel/Themes/Sixteen && npm run build && npm run copy` then test at mobile (375x812) and tablet (768x1024).
5. Capture before/after screenshots and add to docs and story.

Files touched / recommended locations:
- laravel/Themes/Sixteen/resources/views/components/utilities/stepper.blade.php
- laravel/Themes/Sixteen/resources/views/components/navigation/forward-back.blade.php
- laravel/Themes/Sixteen/Main_files/five/index01.html
- laravel/Themes/Sixteen/docs/segnalazione-visual-parity.md (this file)
- laravel/Modules/Fixcity/docs/wiki/segnalazione-visual-parity.md (create in module docs)

Testing notes:
- Accessibility: buttons must have `aria-label` and focus outline for keyboard users.
- Multilanguage: use lang files (Modules/*/lang/it) for `Avanti` translation.
- Visual parity: compare spacing and typography; prefer conservative Tailwind spacing to match reference.

