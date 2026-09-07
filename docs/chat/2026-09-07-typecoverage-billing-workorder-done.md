---
title: "constantTypeCoverage — Billing (16) + WorkOrder (1) done"
type: coordination
tags: [phpstan, typecoverage, billing, workorder, story-5.71, story-2.8]
created: 2026-09-07T22:40:00Z
agent: claude sonnet 5
status: done
blocking: false
related:
  - 2026-09-07-claim-typecoverage-final-push-swarm.md
  - ../stories/2.8.phpstan-constant-type-coverage.story.md
  - ../stories/5.1.phpstan-constant-type-coverage-remaining.story.md
---

Continuazione della claim `2026-09-07-claim-typecoverage-final-push-swarm.md`
(batch Billing 16 + WorkOrder 1, dei 100 residui rilevati a ~22:11).

## Billing (16 → 0)

File: `app/Actions/ReconcileSupplierInvoiceAction.php` (1), `app/Datas/AgingBuckets.php`
(5), `app/Datas/PreInvoiceLine.php` (4, aveva un docblock `@var string` duplicato due
volte per costante — bug cosmetico gia' notato in
`2026-09-07-phpstan-billing-standdown-recheck.md` e non toccato allora), `app/Datas/
ReconciliationDiscrepancy.php` (4), `app/Queries/GetJobMarginsQuery.php` (2).

Tutte avevano gia' un docblock `/** @var string|int */` ma nessun tipo nativo
PHP 8.3+ sulla dichiarazione: la metrica bleeding-edge conta solo il tipo nativo.
Convertite a `public/private const string|int|float FOO = ...`, rimossi i docblock
`@var` ridondanti, mantenuti i commenti descrittivi di dominio.

Verifica: `php -l` su tutti i file, `phpstan analyse Modules/Billing` invariato a 0
(la soglia e' project-wide, non si vedeva da scope singolo modulo), full-tree
`phpstan analyse Modules --error-format=raw | grep '/Billing/'` → 0 righe
`constantTypeCoverage` (era 16), pest mirato
`Modules/Billing/tests/Unit/ReconcileSupplierInvoiceActionTest.php` → 3 passed,
PHPMD sui 5 file → 0 violazioni. Story `Modules/Billing/docs/stories/
5.71.phpstan-typecoverage-constants.story.md` aggiornata a done (esisteva gia' come
stub in_progress, non duplicata).

Commit modulo: `46484f9` (`laraxot/module_billing_fila5`, push su `dev` ok,
fast-forward su `3c7fdee`).

## WorkOrder (1 → 0)

File: `database/migrations/2026_07_27_111500_create_profiles_table.php:19`,
costante `DATA_COLUMNS`. Il fix (`private const array DATA_COLUMNS = [...]`, con
`/** @var list<string> */` mantenuto per il generic piu' preciso) era gia' presente
come modifica non committata nel working tree condiviso quando ho iniziato — verificato
`git diff HEAD` sul contenuto (coerente col pattern della story 2.8), non ho dovuto
scrivere altro codice, solo verificare e chiudere.

Verifica: `php -l`, `phpstan analyse Modules/WorkOrder` invariato a 0, full-tree
`phpstan analyse Modules --error-format=raw | grep '/WorkOrder/'` → 0 righe
`constantTypeCoverage` (era 1), PHPMD sul file → 0 violazioni. Nessuna migrazione DB
eseguita (regola "data sacra"). Story `Modules/WorkOrder/docs/stories/
5.71.phpstan-typecoverage-constants.story.md` aggiornata a done (esisteva gia' come
stub in_progress con testo leggermente disallineato — 2 errori/migration generico —
corretto a 1 errore/costante specifica).

Commit modulo: `efac702` (`laraxot/module_workorder_fila5`, push su `dev` ok,
fast-forward su `e968254`).

## Root monorepo

Le 5 modifiche PHP di Billing + la migration di WorkOrder erano gia' presenti in
`laraxot/base_workorder_fila5` HEAD (commit `ee91599e5`, batch di un'altra sessione
concorrente — stesso working tree condiviso, non ho dovuto ricommittarle). Ho
committato solo l'aggiornamento dei due file story (`5.71.*.story.md`, da
`in_progress`/testo stub a `done`/testo completo): commit `2d28632cd`, push su
`laraxot/dev` ok, fast-forward su `15f559052`.

## Verifica full-tree finale (al momento di questo batch)

`phpstan analyse Modules --error-format=raw`: 48 righe `constantTypeCoverage`
residue project-wide, nessun altro identifier. Percentuale nell'header degli
errori: 254/302 (84.1%), su da 204/302 (67.5%) rilevato nella claim iniziale — il
salto oltre le 17 costanti di questo batch (16+1) e' dovuto ad altri agenti dello
swarm che lavoravano in parallelo sugli altri moduli della stessa claim (Catalog,
Compliance, Customer, HR, Intervention, Platform, Production, Quotation, Signage).
