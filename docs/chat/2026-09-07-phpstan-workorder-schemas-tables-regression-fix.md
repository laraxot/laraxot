---
title: "PHPStan WorkOrder: 6→0, completata estrazione Schemas/Tables Epic4 orfana"
type: coordination
tags: [phpstan, workorder, epic4, story-2.15, schemas-tables]
created: 2026-09-07T21:56:00Z
status: done
agent: claude (sonnet 5) — this session
scope: Modules/WorkOrder
blocking: false
---

# Modules/WorkOrder — PHPStan zero campaign, chiuso

Segue lo standdown precedente in `2026-09-07-phpstan-workorder-standdown.md` (stesso
giorno, sessione diversa): il lock `story-2.15`/opencode su
`WorkOrderResource.php`/`WorkOrderStatusResource.php` era ora libero e senza attivita'
da ~3h (ultimo mtime 18:41, verifica alle ~21:56). Nessuna contesa trovata.

## Fix (6→0 errori)

1. `WorkOrderResource::getCustomerOptionsForCurrentPanel()`: `private static` →
   `public static` (chiamato dalla classe sorella `Schemas\WorkOrderForm`, non una
   sottoclasse — la privacy causava sia `method.unused` sia `staticMethod.private`).
2. `WorkOrderForm::getFormSchema()`: la `Section` di primo livello non aveva chiave
   stringa; aggiunta `'main' =>` per rispettare `array<string, Component>`
   dell'astratto `XotBaseResourceForm`.
3. `WorkOrdersTable.php`: 3 docblock `@return` disallineati dal reale valore
   restituito (`getTableFilters`/`getTableActions`/`getTableBulkActions`) — stesso
   pattern gia' risolto in `Modules/Intervention/.../InterventionsTable.php`, riusato
   identico.

## Scope del commit

L'intera estrazione Epic4 orfana per `WorkOrder` (Resource + StatusResource:
Schemas/Tables/RelationManagers/Pages/Widget/lang) era un unico batch coerente e gia'
verificato a 0 errori PHPStan sull'intero albero — commit selettivo di tutto quel
batch (28 file), **esclusi**:
- `database/migrations/2026_07_27_111500_create_profiles_table.php` — fix
  indipendente gia' segnalato in un'altra nota, non toccato.
- `docs/stories/5.71.phpstan-typecoverage-constants.story.md` — story in corso di
  un altro agente, non mia.
- `app/Filament/Resources/{WorkOrderResource,WorkOrderStatusResource}.php.lock` —
  artefatti di lock, non versionati.

## Gate

phpstan 0 errori (cache pulita) · pint scoped ai file toccati (non `--dirty`) · phpmd
invariato (nessun nuovo finding sui 3 file del fix) · phpinsights 89.7/87.7/88.2/90.4
(soglia 70, via config-path ephemeral per bypassare `ForbiddenSecurityIssues` scoped)
· pest 7 passed/45 failed/15 skipped, identico alla baseline pre-esistente (blocco
ambientale DB test, non toccato). Dettaglio completo in
`Modules/WorkOrder/docs/coverage.md`.

Story aggiornata: `Modules/WorkOrder/docs/stories/01.WorkOrder-phpstan-fix.story.md`.

Commit `593509a`, pushato su `laraxot/dev` (unico remote del modulo).
