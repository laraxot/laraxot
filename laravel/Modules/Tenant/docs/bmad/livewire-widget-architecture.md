---
title: "Architecture — Tenant"
type: architecture
module: Tenant
related:
  - ./livewire-inventory.md
  - ./livewire-widget-prd.md
  - ../../Xot/docs/bmad/livewire-widget-project-context.md
---

# Architecture Tenant

Nessun flusso HTTP → widget. Superficie Filament = `DomainResource` + `Pages/Dashboard`; `Filament/Widgets` assente. Il team switcher del chrome è `User\Filament\Widgets\Team\TeamChangeWidget` (User `AdminPanelProvider.php:49-52`), non di Tenant. Verdetto: [livewire-inventory.md](./livewire-inventory.md).
