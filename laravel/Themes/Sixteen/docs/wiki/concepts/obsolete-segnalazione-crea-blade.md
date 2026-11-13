---
title: "obsolete segnalazione crea blade"
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "obsolete segnalazione crea blade"
issues: []
discussions: []
name: obsolete-segnalazione-crea-blade
description: Blade file `segnalazione-crea.blade.php` should not exist; the page is rendered via JSON configuration and Livewire wizard.
type: concept
status: draft
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

Correzione verificata 2026-10-13 (DCP-35 S4): `tests.segnalazione-crea.json` è stato eliminato (commit 260f3bcc5, `git log --diff-filter=D`) e oggi non esiste in nessun tenant; la risposta di `/it/tests/segnalazione-crea` (200) non contiene il wizard (0 occorrenze di `fi-sc-wizard` e `$wire.save()`). Il flusso vive su `/it/tickets/create` (`tickets.create.json`, blocco `components/blocks/tests/ticket-crea.blade.php`). Questo documento descrive lo stato precedente; stato attuale in `Modules/Fixcity/docs/wiki/concepts/ticket-crea-url-routing-chain.md`.
Inoltre, correzione verificata 2026-10-13: il file Blade `Themes/Sixteen/resources/views/components/blocks/tests/segnalazione-crea.blade.php` esiste ancora (non è stato rimosso, `ls`).
