---
title: "Modules Statuses — root vs tenant-scoped (temi)"
type: concept
tags: [theme, tenant, modules, navigation, shared-components]
updated: 2026-07-27
related:
  - ../../Modules/Tenant/docs/tenant-module-status-registry.md
  - ./runtime-config-religion-hub.md
---

# Modules Statuses (temi)

I temi non definiscono moduli, ma il menu di navigazione admin che alcuni temi
compongono/ereditano dipende da `config/local/{tenant}/modules_statuses.json`
(per workorder: `config/local/workorder/`, da hostname `workorder.local`) —
**file diverso** dal root `laravel/modules_statuses.json` (activator nwidart).

Se un modulo è attivo in root ma assente dal menu admin, verificare l'overlay tenant
prima di sospettare un bug nel tema — nessun errore viene sollevato, la voce sparisce.

**Rigenera** (dopo nuovi moduli):  
`bash bashscripts/tools/sync-tenant-modules-statuses.sh local/workorder`

Canon: [Tenant — tenant-module-status-registry](../../Modules/Tenant/docs/tenant-module-status-registry.md) · [WorkOrder — modules-statuses-workorder](../../Modules/WorkOrder/docs/modules-statuses-workorder.md)
