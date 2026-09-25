---
title: "PRD — conversione Livewire HTTP → widget (piattaforma)"
type: prd
module: Xot
status: approved
related:
  - ./livewire-widget-product-brief.md
  - ./livewire-inventory.md
  - ./livewire-widget-architecture.md
  - ./livewire-widget-advantages.md
---

# PRD piattaforma

I FR di **identità** restano nel PRD User. Qui i FR di **legge**.

### FR-X001 — Base widget [MUST]

Nuova UI admin estende `XotBase*Widget`, non `XotBaseComponent`.

### FR-X002 — Hook FQCN [MUST]

`AdminPanelProvider` dei foglia non monta alias kebab. FQCN o niente.

### FR-X003 — Categoria [MUST]

Chrome → widget. Pagina FO → Page/Volt/tema. Gemello esistente → ritiro HTTP.

### FR-X004 — XotBaseComponent intatto [MUST]

Nessun “SuperAdmin” dentro Xot. Nessuna rinomina della base per questa campagna.

## Out of scope

Implementare i foglia da Xot. Convertire `rate.*` FO.
