---
id: phpstan-Vehicle-fix
slug: phpstan-Vehicle
scope: [module:Vehicle, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-06
---

## Problema
PHPStan errors in Modules/Vehicle (5 errori a livello max: 2× `method.deprecated` su
`Table::actions()`/`bulkActions()`, 2× `cast.string` su `mixed` in
`DocumentsRelationManager::getTableHeaderActions()`, 1× `cast.int` su `mixed` in
`VehicleDocumentFactory::definition()`).

## Solution
1. Analyze with phpstan → 5 errori confermati (re-verificato, baseline precedente non affidabile).
2. Fix pattern errors:
   - `VehicleResource.php`: `->actions()/->bulkActions()` → `->recordActions()/->toolbarActions()` (API Filament v4).
   - `DocumentsRelationManager.php`: cast diretto `(string) $data[...]` → `Modules\Xot\Actions\Cast\SafeStringCastAction`/`SafeNullableStringCastAction`.
   - `VehicleDocumentFactory.php`: cast diretto `(int) ...->getKey()` → `Modules\Xot\Actions\Cast\SafeIntCastAction`.
3. Verify: phpstan 0 errori; phpmd invariato sui file toccati (nessun nuovo finding); pest 4/6 pass (2 fail pre-esistenti per DB di test non migrato, fuori scope — vedi `docs/coverage.md`); phpinsights non disponibile in questo repo (rimosso, incompatibile Pest 5).
4. Git sync: commit + push su `laraxot` (repo indipendente del modulo).

## Acceptance criteria
- [x] `./vendor/bin/phpstan analyse Modules/Vehicle` → 0 errori
- [x] Nessuna modifica a `phpstan.neon`, nessun `@phpstan-ignore`/baseline
- [x] Nessun uso diretto di `User`/`Profile` introdotto (nessuno presente nello scope toccato)
- [x] `docs/coverage.md` aggiornato con before/after
- [x] Git sync su repo indipendente Vehicle

Dettaglio completo: `docs/coverage.md`.
