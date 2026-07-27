---
title: "Registro tenant moduli — impatto sul tema"
type: concept
tags: [theme, tenant, modules, navigation, cross-reference]
updated: 2026-07-27
related:
  - ../../Modules/Tenant/docs/tenant-module-status-registry.md
  - ../../Modules/Xot/app/Actions/Filament/GetModulesNavigationItems.php
---

# Registro tenant moduli (`config/{tenant}/modules_statuses.json`)

Il tema **non** legge direttamente questo file, ma la navigazione Filament costruita da
`GetModulesNavigationItems` (che lo consuma via `GetTenantModulesAction`) determina quali
voci modulo appaiono nel pannello — se un tema/layout custom mostra un menu moduli o un
selettore, verificare che rifletta questo registro, non un elenco statico nel tema.

Canone completo (path risoluzione tenant, incidente reale, come rigenerare):
[Modules/Tenant/docs/tenant-module-status-registry.md](../../Modules/Tenant/docs/tenant-module-status-registry.md).
