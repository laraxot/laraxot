---
type: decision-log
github_issue: #101
decision: D11
title: "D11 — Filament Wizard per tickets/create (parità con segnalazione-01-privacy)"
status: draft
updated: 2026-10-13
tags: [docs, sixteen, decision-log]
qmd: "d11   filament wizard per tickets/create (parità con segnalazione-01-privacy) sixteen"
---
# D11 — Filament Wizard per tickets/create (parità con segnalazione-01-privacy)

**Reason**: `segnalazione-01-privacy.html` è un wizard 3-step (autorizzazioni → dati → riepilogo). `tickets/create` deve avere alta visual parity e lo stack AGENTS.md richiede Filament. Blade raw non supporta validazione per step, progress-state tra pagine, o submit finale coerente; Filament `Wizard` gestisce tutto nativamente con DaisyUI/Flowbite styling.

**Evidence**: Ref indicizzato (4 sezioni, 1.1KB, step 1/3). Local JSON `tests.tickets.create.json` mancante — deve essere creato con `layout: "filament"`, `view: "filament.pages.tickets.create"`.

**Implementation**: `Filament\Pages\TicketCreateWizard` con 4 step (titolo, descrizione, foto, posizione); componenti Blade `pub_theme::components.filament.*`; CSS parity con `segnalazione-01-privacy` (palette verde #007A52, tipografia 1.25, elevazione 5 livelli).

**Second brain**: `kp-...` (ticket wizard), `docs/decision-log.md` D11.
**Correction**: Livewire rejected Filament's `callSchemaComponentMethod` during
nested schema requests because the method inherited from the schema trait was
not present at the Xot wizard boundary used by the component whitelist. The
shared `XotBaseWizardWidget` now redeclares the method and delegates to
Filament, preserving the vendor implementation. The Sixteen widget shell also
uses Tailwind utility classes for its layout and error state; Bootstrap Italia
classes are not used in this view.

**Verification**: `CreateTicketWizardWidgetViewTest` passes, including a
reflection regression assertion for the public method. A browser interaction
check remains part of the release gate once a local Chromium/Playwright runtime
is available.

**Status**: In progress — runtime interaction verification pending.

Correzione verificata 2026-10-13 (DCP-35 S4): (1) il JSON di `/it/tickets/create` esiste: `laravel/config/local/fixcity/database/content/pages/tickets.create.json` (slug `tickets.create`); `tests.tickets.create.json` non va creato (vedi D12). (2) La classe `Filament\Pages\TicketCreateWizard` e i componenti `pub_theme::components.filament.*` non esistono (nessun file e nessun riferimento in `Modules/*/app`, `Themes/Sixteen/app`, `Themes/Sixteen/resources`, verificato con `find`/`grep`); il wizard è `Modules\Fixcity\Filament\Widgets\CreateTicketWizardWidget` con 3 step (`privacy`, `data`, `summary` da `TicketForm::getSteps()`) seguiti dalla pagina di conferma. (3) La causa di `MethodNotFoundException: callSchemaComponentMethod` non era l'assenza del metodo al confine Xot: il metodo è già `public` in `InteractsWithSchemas` (`vendor/filament/schemas/src/Concerns/InteractsWithSchemas.php`, riga 83) e l'override in `XotBaseWizardWidget` è un no-op; la causa è il `wire:id` del Volt della pagina caduto dentro il widget, risolta in [D15 (Volt con radice esplicita)](D15-volt-fragment-explicit-root.md).
