---
id: phpstan-Inventory-fix
slug: phpstan-Inventory
scope: [module:Inventory, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-06
---

## Problema
PHPStan errors in Modules/Inventory (52 errori: cast `mixed`->`int`/`string` su closure Filament `$get()`,
`Model::getAttribute()`, `Collection::pluck()->all()`; deprecazioni `Table::actions()`/`bulkActions()` e
`Filament\Forms\Components\Placeholder`).

## Solution
1. Analyze with phpstan — confermati 52 errori su 7 file.
2. Fix pattern errors — sostituiti i cast diretti con `Modules\Xot\Actions\Cast\SafeIntCastAction`/
   `SafeStringCastAction` (pattern canonico gia' in uso in Geo/Blog/Catalog); `Placeholder` -> `TextEntry`
   + `state()`; `actions()/bulkActions()` -> `recordActions()/toolbarActions()`.
3. Verify with phpmd + phpinsights + pest — phpmd pulito su file toccati (violazioni residue preesistenti
   altrove); phpinsights non installato nel repo (rimosso, incompatibile Pest 5); pest bloccato da
   migrazioni pendenti sul DB di test condiviso (preesistente, non causato da questo fix — vedi
   `docs/coverage.md`).
4. Git sync — vedi `docs/chat/2026-09-06-sonnet5-pest-fixes-plus-unclaimed-modules-wave.md#inventory`.

## Acceptance criteria
- [x] 0 errori PHPStan su `Modules/Inventory` (era 52).
- [x] Nessuna modifica a `phpstan.neon`.
- [x] Nessun uso di `mixed` cieco: tutti i cast tracciano il tipo reale via SafeCast actions.
- [x] Nessuna nuova violazione PHPMD sui file modificati.
- [x] `docs/coverage.md` aggiornato con stato prima/dopo e blocker Pest documentato.

Dettaglio completo: `docs/coverage.md`. Story duplicata gia' presente
(`01.Inventory-phpstan-fix.story.md`) collegata qui, non cancellata.
