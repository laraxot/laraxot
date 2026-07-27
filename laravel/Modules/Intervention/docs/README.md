---
title: "Intervention — knowledge base"
type: overview
tags: [intervention, gestionale-commesse, docs, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "Intervention docs README purpose timbratura field service"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./import-status.md"
  - "./gestionale-commesse-port.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../laravel/Themes/docs/wave1-fo-operator-note.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Intervention

## Perché esiste

Esecuzione **sul campo**: interventi, timbrature, note giornaliere, materiali, calendar, voice report. Chiude il ciclo operativo della commessa.

Lingua: *Intervento* / *Timbratura* ([glossary](../../../../docs/gestionale-commesse-comparison/glossary-align.md)).

## Cosa NON è

- Non è WorkHour Employee (altro dominio).
- Operator FO Folio **non** migrato in Wave 1 → resta Filament (`intervention/admin`). Vedi [wave1-fo-operator-note.md](../../../../laravel/Themes/docs/wave1-fo-operator-note.md).

## Runtime

| Gate | Stato |
|---|---|
| Enabled | sì |
| Schema | interventi + satellite OK |
| Panel | `intervention/admin` (resources + pages) |
| PHPStan L10 (app/) | 0 errori reali |
| Pest | 25 test migrati da SRC (`gestionale_commesse`), struttura corretta ma non eseguibili nell'ambiente attuale (vedi sotto) |

## Migrazione test SRC → Intervention (2026-07-24)

25 file migrati da `gestionale_commesse/tests/{Feature,Unit}/Interventions/` a
`Modules/Intervention/tests/{Feature,Unit}/` (23 Feature + 2 Unit). Namespace/`use`
riscritti dalla vecchia struttura DDD-lite (`Application/Domain/Infrastructure`) alla
struttura reale del modulo (flat: `Actions`, `Models`, `Data`, `Exceptions`,
`ValueObjects`, `Contracts`, `Queries`, `GoogleCalendar\*`), incluse le rinomine
interfaccia→contract (`InterventionReportExtractorInterface` → `Contracts\InterventionReportExtractorContract`,
`ReportPaymentGatewayInterface` → `Contracts\ReportPaymentGatewayContract`) e i
riferimenti cross-modulo (`Modules\User\Models\User`, `Modules\Catalog\Models\Product`,
`Modules\Document\Models\Media`, `Modules\Signature\Models\Signature`,
`Modules\Customer\Models\Customer`, `Modules\WorkOrder\Models\WorkOrder(Status)`,
`Modules\Quotation\Models\Quotation`, `Modules\Inventory\Models\*`). Ogni file ora
dichiara `namespace Modules\Intervention\Tests\{Feature,Unit}` + `uses(TestCase::class)`,
seguendo il pattern già in uso in `Modules/WorkOrder/tests` (nessun `uses()->in()`
globale in `Pest.php`, come richiesto dal commento in quel file).

**Verificato**: sintassi PHP valida per tutti i 25 file (`php -l`); namespace/`use`
risolvono a classi realmente esistenti nel modulo target.

**Bug reali trovati nel codice applicativo**: nessuno — `phpstan analyse
Modules/Intervention/app` resta a 0 errori reali (solo il warning preesistente,
non correlato, sul pattern `@mixin` non matchato).

**Resta aperto — blocco ambientale, non del modulo**: `vendor/bin/pest
Modules/Intervention/tests` non riesce a completare l'esecuzione in questo
ambiente. `phpunit.xml` forza `DB_CONNECTION=sqlite` + `DB_DATABASE=:memory:`,
ma nessuna migrazione viene eseguita per quel DB in-memory (i `TestCase` dei
moduli usano solo `DatabaseTransactions`, non `RefreshDatabase`/migrate).
Risultato: `SQLSTATE... no such table: customers/products/daily_notes`. Non è
un problema introdotto da questa migrazione: lo stesso identico fallimento si
riproduce lanciando `vendor/bin/pest Modules/WorkOrder/tests` (modulo già
migrato in precedenza, invariato). Provando a puntare al DB mysql di test già
migrato (`workorder_data_test`, da `.env.testing`) la tabella richiesta
(`customers`) non esiste comunque (lo schema reale usa `clients`), quindi
nemmeno quella via sblocca l'esecuzione. Serve un intervento infra a monte
(fixture DB/migrate per i test, fuori scope di questo task) prima che sia
possibile verificare a runtime i 25 test migrati.

Analogamente, `phpstan analyse Modules/Intervention --memory-limit=-1` (che
include `tests/`) segnala ~670 errori sui nuovi file di test — in maggioranza
(342) `method.internalClass` dovuti a `Pest\Mixins\Expectation`/`TestCall`
richiamati da un file con `namespace` dichiarato: lo stesso identico rumore è
presente in `Modules/WorkOrder` (115 pest-internal + 101 altri, su un modulo
già considerato a baseline accettata) e in altri moduli già migrati. Non sono
stati usati `@phpstan-ignore` né `assert()/@var` per mascherarli, come da
istruzioni: restano visibili e allineati al comportamento già tollerato per i
moduli gemelli già migrati.

## Indice docs

| File | Scopo |
|---|---|
| [import-status.md](./import-status.md) | Checklist |
| [tests-migration.md](./tests-migration.md) | Porting test SRC |
| [gestionale-commesse-port.md](./gestionale-commesse-port.md) | Note port SRC |
| [README.md](./README.md) | Scopo + runtime |

## Hub / BMAD

- [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md)
- Stories [1.4](../../../../docs/stories/1.4.intervention-module.story.md) · [2.3](../../../../docs/stories/2.3.deep-quality-enabled-modules.story.md)
- [#7](https://github.com/laraxot/base_workorder_fila5/issues/7) · [#14](https://github.com/laraxot/base_workorder_fila5/issues/14)
- Matrice statuses: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md)
- Story [2.4 docs hygiene](../../../../docs/stories/2.4.module-theme-docs-hygiene.story.md)
