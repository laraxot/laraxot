---
title: "PHPStan zero — WorkOrder: standdown, modulo fortemente conteso da swarm Epic4 (story-2.15)"
type: coordination
tags: [phpstan, workorder, standdown, epic4, story-2.15, opencode]
created: 2026-09-07T19:00:00Z
status: standdown
agent: claude (sonnet 5) — this session
scope: Modules/WorkOrder
blocking: false
---

# Modules/WorkOrder — PHPStan zero campaign, standdown (nessuna modifica)

## Ricognizione (Fase 0)

`cd laravel/Modules/WorkOrder && git status` mostra 16 file modificati non miei
(`WorkOrderResource.php`, `WorkOrderResource/Pages/{ListWorkOrders,ViewWorkOrder}.php`,
6 RelationManagers, `WorkOrderStatusResource.php` + `ListWorkOrderStatuses.php`,
`Widgets/WorkOrdersStatusChart.php`, 4 `lang/it/*.php`, la migration
`2026_07_27_111500_create_profiles_table.php`) piu' directory nuove untracked
`WorkOrderResource/Schemas/`, `WorkOrderResource/Tables/`,
`WorkOrderStatusResource/Schemas/`, `WorkOrderStatusResource/Tables/`, e due file
`.lock`.

`bashscripts/lock/check.sh` conferma:
- `app/Filament/Resources/WorkOrderResource.php` → **LOCKED**, task `story-2.15`,
  agente `opencode`, `LOCKED at 2026-09-07T16:18:42+02:00`. Al momento della verifica
  (18:58) il file risultava modificato **17-18 minuti prima** — lock genuinamente
  vivo, non stale.
- `app/Filament/Resources/WorkOrderStatusResource.php` → stesso lock/task.
- Tutti gli altri file dirty → FREE, ma con mtime coerenti con la stessa sessione
  (le nuove cartelle `Schemas/`/`Tables/` hanno mtime scaglionati dalle 17:10 alle
  19:54, in progressione continua fino a 18 minuti prima della verifica).

Questo e' lo stesso pattern gia' documentato in
`docs/chat/2026-09-07-workorder-typecoverage-standdown.md` (standdown precedente,
oggi, altra sessione) e nella nota second-brain "Epic 4 filament-canonical-structure
swarm active": lo swarm opencode-driven sulla estrazione `Schemas/`/`Tables/` (story
2.10/2.15) ha ora raggiunto anche `WorkOrder` (non era ancora nella lista dei 14
moduli noti quando quella nota fu scritta oggi pomeriggio).

## PHPStan reale (Fase 0, comando richiesto dal task)

```
cd laravel && ./vendor/bin/phpstan analyse Modules/WorkOrder --no-progress --memory-limit=-1
```

Risultato: **7 errori**, tutti concentrati nei file della stessa estrazione in corso:

| File | Errori |
|---|---|
| `WorkOrderResource.php` (LOCKED) | 1× `method.unused` (helper statico orfano post-estrazione) |
| `WorkOrderResource/Schemas/WorkOrderForm.php` (nuovo, untracked) | 2× `method.childReturnType` (array\<int,Component\> vs array\<string,Component\> del genitore `XotBaseResourceForm`/`HasXotForm`) + 1× `staticMethod.private` (chiamata cross-classe a metodo privato non ancora reso visibile) |
| `WorkOrderResource/Tables/WorkOrdersTable.php` (nuovo, untracked) | 3× `return.type` su `getTableFilters()/getTableActions()/getTableBulkActions()` |

Zero errori fuori da questi tre file. Non esiste, allo stato attuale, alcun errore
PHPStan reale in `Modules/WorkOrder` che non ricada nell'area contesa.

## Decisione: standdown

Per il vincolo esplicito del task ("se il modulo ha ancora molti file dirty non tuoi
o un claim attivo di altri... se e' fortemente conteso, fermati e riporta come
stand-down invece di duplicare"): il modulo e' fortemente conteso (lock vivo su
2 file core + refactor esteso a tutta la struttura Filament, ancora in
scrittura attiva 18 minuti prima della verifica) e il 100% degli errori PHPStan
reali ricade esattamente nei file di quella stessa estrazione in corso. Non c'e'
alcun sottoinsieme di errori "non sovrapposti" su cui lavorare in sicurezza.

**Nessun file toccato in questa sessione. Nessun commit, nessun push.**

Gli errori riscontrati sono con ogni probabilita' artefatti transitori
dell'estrazione a meta' (le firme dei metodi verranno allineate da chi sta
completando `Schemas/`/`Tables/`), non un target stabile — pattern generalizzato in
`bashscripts/ai/wiki/memories/phpstan-transient-errors-schema-table-extraction.md`.

## Story BMAD esistenti (nessuna nuova creata)

- `Modules/WorkOrder/docs/stories/01.WorkOrder-phpstan-fix.story.md` +
  `phpstan-workorder-fix.md` — Done, pass precedente (75→0 errori, 2026-09-06/07),
  non riaperta da questa sessione: la regressione a 7 errori e' causata dal refactor
  Epic4 in corso, non da quel fix, e verra' verificata di nuovo quando lo swarm avra'
  finito e rilasciato il lock.
- `Modules/WorkOrder/docs/stories/5.71.phpstan-typecoverage-constants.story.md` —
  in_progress, ambito diverso (typeCoverage, metrica Pest, non un errore emesso da
  `phpstan analyse`); non toccata.

## Per chi riprende WorkOrder dopo lo swarm

1. Aspettare che `app/Filament/Resources/WorkOrderResource.php.lock` e
   `WorkOrderStatusResource.php.lock` siano rilasciati (o comunque stale, > 1h senza
   nuove modifiche al file).
2. Ri-eseguire `phpstan analyse Modules/WorkOrder --no-progress --memory-limit=-1` da
   zero: i 7 errori qui documentati potrebbero gia' essere risolti dallo swarm stesso.
3. Il fix segnalato in `docs/chat/2026-09-07-workorder-typecoverage-standdown.md`
   (docblock duplicato + `const array` mancante su `DATA_COLUMNS` nella migration
   profiles) resta aperto e indipendente da questo standdown.
