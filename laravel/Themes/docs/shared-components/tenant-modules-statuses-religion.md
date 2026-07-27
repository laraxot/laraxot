---
title: "Modules Statuses — root vs tenant-scoped (temi)"
type: concept
tags: [theme, tenant, modules, navigation, shared-components]
updated: 2026-07-27
related:
  - ../../Modules/Tenant/docs/it/config/modules-statuses.md
---

# Modules Statuses (temi)

I temi non definiscono moduli, ma il menu di navigazione admin che alcuni temi
compongono/ereditano dipende da `config/{tenant}/modules_statuses.json`
(risolto da `GetTenantNameAction` in base all'hostname) — **file diverso** dal
root `laravel/modules_statuses.json` (quello che avvia/disattiva un modulo).

Se un modulo compare in `Modules/` ed è attivo (root `modules_statuses.json`) ma
non nel menu admin, verificare `config/{tenant}/modules_statuses.json` prima di
sospettare un bug nel tema — nessun errore viene sollevato, la voce sparisce e
basta.

Canon: [Tenant — modules-statuses](../../Modules/Tenant/docs/it/config/modules-statuses.md)
