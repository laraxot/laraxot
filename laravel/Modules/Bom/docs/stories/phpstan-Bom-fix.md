---
id: phpstan-Bom-fix
slug: phpstan-Bom
scope: [module:Bom, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-07
---

## Problema (risolto)
3 errori PHPStan: `Table::actions()`/`bulkActions()` deprecati (Filament v4->v5),
sostituiti da `recordActions()`/`toolbarActions()`.

## Fix
Rinomina meccanica in `BomResource.php`, `BomItemsRelationManager.php`.

## Verifica
- PHPStan: 3 -> 0 errori
- PHPMD: nessuna nuova segnalazione
- Dettaglio: `docs/coverage.md`
