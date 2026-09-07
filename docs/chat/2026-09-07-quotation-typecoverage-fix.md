---
title: "Quotation: PHPStan constantTypeCoverage 12 -> 0 (story 2.8, wave C)"
type: handoff
tags: [phpstan, constant-type-coverage, quotation, story-2.8, multi-agent, bootstrap-break]
created: 2026-09-07T15:45:00Z
status: done
agent: claude (sonnet 5) — this session
blocking: false
---

# Quotation — constantTypeCoverage 12 -> 0 (story 2.8, wave C)

## Esito

Fresh full-tree scan (`./vendor/bin/phpstan analyse Modules --error-format=raw | grep
constantTypeCoverage`) prima dell'intervento: **12 occorrenze / 10 file** in
`Modules/Quotation`. Dopo: **0**. Dettaglio prima/dopo, pattern applicato e quality
gate in `laravel/Modules/Quotation/docs/coverage.md`.

Commit `352d16b` (branch `dev`, remote `laraxot` = `origin`, entrambi in sync),
pushato con successo.

## ATTENZIONE: working tree condiviso, sessione concorrente attiva su Quotation

Durante questo intervento un'altra sessione ha lavorato **in tempo reale sullo
stesso modulo**, sullo stesso branch `dev`, nello stesso checkout (non e' un
clone separato — vedi second-brain
`feedback_shared_filesystem_concurrent_sessions.md`). Osservato:

- All'avvio, ~28 file gia' modificati non committati (refactor Filament v5:
  drop `GatedXotBaseResource` -> `XotBaseResource` diretto, rimozione
  `->label(__(...))` espliciti, `ChartWidget` -> `XotBaseChartWidget`,
  estrazione `Schemas/`).
- A meta' lavoro e' comparso un nuovo commit sul branch (`c5889c5 docs(quotation):
  document + fix P0 bootstrap-fatal bugs on SiteSurveyResource Schemas/Tables`)
  che io non ho scritto — la sessione concorrente ha committato (e a quanto pare
  pushato, visto che `git fetch laraxot dev` risultava gia' allineato) mentre
  lavoravo.
- File monitorati (es. `QuotationTemplateResource.php`) sono cambiati sotto le
  mie mani piu' volte durante la sessione (import riordinati, costanti/schema
  spostati in `Schemas/`), confermato dai avvisi "changed on disk since you
  last read it" del tool.

Per non inglobare lavoro altrui non revisionato nel mio commit, **non ho usato
`git add -A`** (come indicato nel workflow standard della story): ho ricostruito
i blob dei 7 file con hunk misti (`git show HEAD:<file>` + solo la mia
sostituzione sul tipo della costante, via `git hash-object -w` +
`git update-index --cacheinfo`), lasciando il working tree intatto con il WIP
altrui. Il commit `352d16b` contiene esclusivamente le 12 tipizzazioni di
costante + `docs/coverage.md`. Verificato con `git diff --cached` prima del
commit che il diff staged fosse esattamente le 12 righe attese.

## Bug P0 bootstrap-fatal — nuovo, ma in Modules/Signage, non Quotation

A fine sessione, un run di verifica finale (`phpstan analyse Modules/Quotation`)
e' fallito con:

```
PHP Fatal error: Access level to
Modules\Signage\Filament\Resources\SignCatalogItemResource\Tables\SignCatalogItemsTable::getTableRecordTitleAttribute()
must be public (as in class Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable)
in Modules/Signage/app/Filament/Resources/SignCatalogItemResource/Tables/SignCatalogItemsTable.php:117
```

Stessa classe di bug gia' segnalata in
`docs/chat/2026-09-07-gatedxotbaseresource-migration-bootstrap-break.md` (mismatch
di visibilita' tra override e classe base, bootstrap Filament rotto per
**l'intero albero `Modules`**, non solo Signage — la registrazione dei panel
carica tutte le Resource). Riprodotto due volte a 20s di distanza, non
transitorio. Non e' un file mio, non l'ho toccato: **chi lavora su Signage in
questo momento deve rendere `getTableRecordTitleAttribute()` `public` invece di
`protected`** in quel file. Fino a quel fix, qualunque scan PHPStan (anche
scoped a un solo modulo) rischia di fallire in bootstrap per chiunque nello
swarm.

La mia verifica "12 -> 0 occorrenze" sopra e' stata completata con successo
**prima** che questo errore comparisse (scan full-tree pulito, poi scan scoped
pulito su Quotation subito dopo ogni edit); il commit e' gia' pushato.

## Quality gate — dettagli

- PHPStan `Modules/Quotation`: `[OK] No errors` (piu' volte, incluso dopo ogni
  singolo edit).
- PHPMD: nessuna violazione introdotta dai cambi di questa story sui 10 file
  toccati (violazioni pre-esistenti fuori scope documentate in coverage.md).
  Scan dell'intero modulo (inclusi i test) va in crash deterministico su
  `tests/Feature` (`No node to visit provided for visitAnonymousClass.`) —
  incompatibilita' nota phpmd/Pest 5, non causata da questa story.
- PHPInsights: non installato in questo repo (rimosso, incompatibile con
  Pest 5), non eseguito.
- Pest: `tests/Unit` verde (1 passed, nessuna regressione). `tests/Feature`
  90 failed / 17 passed, tutti per `QueryException` "table not found" su
  `workorder_data_test` (roles, metel_catalog_items, customers) — blocco
  pre-esistente e documentato nel second-brain
  (`project_test_db_missing_migrations_blocks_feature_tests`), non causato da
  questa story, nessun file di test toccato.

## Riferimenti

- `laravel/Modules/Quotation/docs/coverage.md`
- `docs/stories/2.8.phpstan-constant-type-coverage.story.md`
- `docs/chat/2026-09-07-gatedxotbaseresource-migration-bootstrap-break.md`
- `docs/chat/2026-09-06-sonnet5-quotation-workorder-overlap-flag.md`
