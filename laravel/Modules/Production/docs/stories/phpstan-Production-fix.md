---
id: phpstan-Production-fix
slug: phpstan-Production
scope: [module:Production, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-07
---

## Problema (risolto)
3 errori PHPStan: `Table::actions()`/`bulkActions()` deprecati (Filament v4->v5),
sostituiti da `recordActions()`/`toolbarActions()`.

## Fix
Rinomina meccanica in `ProductionOrderResource.php`,
`ProductionPhasesRelationManager.php`.

## Verifica
- PHPStan: 3 -> 0 errori
- PHPMD: nessuna segnalazione
- Dettaglio: `docs/coverage.md`
