---
name: obsolete-segnalazione-crea-blade
description: Blade file `segnalazione-crea.blade.php` should not exist; the page is rendered via JSON configuration and Livewire wizard.
type: concept
---

# Why `segnalazione-crea.blade.php` is obsolete

The *Segnalazione* wizard is a Filament wizard driven entirely by the **Livewire** component `CreateTicketWizardWidget` and the JSON page definition located at `laravel/config/local/fixcity/database/content/pages/tests.segnalazione-crea.json`.

* The JSON describes the wizard steps, fields, and validation logic.
* The Blade view would duplicate that definition, causing two sources of truth.
* Keeping the Blade file leads to:
  - **Stale markup** – changes in the JSON are not reflected in the Blade template.
  - **Unexpected rendering** – Blade tries to render a full page while the wizard expects a Livewire component, resulting in missing map components and UI glitches.
  - **Violation of the LLM‑Wiki rule** – page‑specific Blade files are discouraged; the system prefers a single source (JSON) with a *template‑as‑dress* approach.

## Recommended practice

1. **Remove** the Blade file (already done).
2. Ensure the route `tests.segnalazione-crea` points to the JSON‑driven page via the existing controller logic.
3. Update any documentation or references that mention the Blade file.

## Impact

Removing the Blade file eliminates the rendering conflict, allowing the wizard’s map component to initialize correctly and the page to respect the modular JSON configuration.

---
