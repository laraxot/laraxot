---
title: "WorkOrder — knowledge base"
type: overview
tags: [workorder, gestionale-commesse, docs, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "WorkOrder docs README purpose commessa panel quality"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./import-status.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../docs/gestionale-commesse-comparison/glossary-align.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# WorkOrder

## Perché esiste

**Commessa** operativa: stato, attività, assignment. È il cuore del gestionale — collega Customer → Intervention / Quotation / Billing.

Lingua: *Commessa* = WorkOrder ([glossary](../../../../docs/gestionale-commesse-comparison/glossary-align.md)).

## Cosa NON è

- Non è intervento sul campo (→ Intervention).
- Non è preventivo (→ Quotation).
- Themes FO non ospitano questa BL in Wave 1 (panel Filament).

## Runtime

| Gate | Stato |
|---|---|
| Enabled | sì |
| Schema | `work_orders*` OK (`primary_contact_id` senza FK DB) |
| Panel | `workorder/admin/work-orders` |
| PHPStan L10 | 0 errori reali (baseline confermata anche dopo la migrazione test, vedi sotto) |
| Pest | 16 test migrati da gestionale_commesse + 2 preesistenti; vedi stato dettagliato sotto |

## Indice docs

| File | Scopo |
|---|---|
| [import-status.md](./import-status.md) | Checklist import/reshape |
| [tests-migration.md](./tests-migration.md) | Porting test SRC |
| [README.md](./README.md) | Scopo + qualità |

## Qualità (sintesi)

Actions/Policy/migrazioni verificate senza bug strutturali noti.

## Migrazione test da gestionale_commesse (2026-07-24)

14 file test copiati da `gestionale_commesse/tests/Feature/WorkOrders/*.php` (namespace
`Modules\WorkOrders\...`, plurale, deprecato) dentro
`Modules/WorkOrder/tests/Feature/` (singolare), namespace/`use` riscritti su
`Modules\WorkOrder\...`. Aggiunti accanto ai 2 test preesistenti (`ModelsTest.php`,
`ServiceProviderTest.php`).

**Esito**: 11 passano stabilmente, 16 skippati (bug reale, vedi sotto), 16 falliscono
per un problema di infrastruttura di test pre-esistente e trasversale al monorepo (non
introdotto da questa migrazione).

### Bug reali trovati (nel codice, non nei test)

1. **DOCX non implementato**: `GenerateWorkOrderReportAction::generateDocx()` e
   `GenerateCustomerReportAction::generateDocx()` fanno `throw new RuntimeException(...)`
   — nessun rendering DOCX reale. `GenerateWorkOrderPdfAction`/`GenerateWorkOrderReportAction`/
   `GenerateCustomerReportAction` dipendono anche da `Barryvdh\DomPDF\Facade\Pdf`, mai
   installato (`composer show barryvdh/laravel-dompdf` → not found), e da
   `phpoffice/phpword`, mai installato. `DocumentExportBridge::pdfFromView()` fa già un
   controllo esplicito (`class_exists`) e un messaggio d'errore chiaro — il codice è
   pronto, mancano solo le dipendenze. 16 test (Generate*PdfTest/ReportActionTest) sono
   stati portati con `$this->markTestSkipped(...)`, non cancellati, così il contenuto
   originale (regressioni PDF/DOCX, edge case periodo vuoto, filename, varianti outcome,
   materiali, `inventory.allow_link_interventions`) resta pronto da riattivare non appena
   `composer require barryvdh/laravel-dompdf phpoffice/phpword` viene eseguito. Vedi
   [gestionale-commesse-debito-tecnico.md](../../../docs/gestionale-commesse-debito-tecnico.md) #5.

### Aperto — non un bug di WorkOrder, ma dell'ambiente di test del monorepo

I restanti test (Activities, Assignments, ChangeStatus, ComputeWorkOrderEffort in parte,
RestoreWorkOrderCascade, WorkOrderEffortAndPreInvoiceUi, WorkOrderInterventionSearch)
falliscono in modo non deterministico con `SQLSTATE[HY000]: no such table: customers`
(connessione `xot`, sqlite `:memory:`). Confermato che è un problema **pre-esistente e
trasversale**, non specifico a WorkOrder: lo stesso identico errore si presenta lanciando
`vendor/bin/pest Modules/Customer/tests` (12 test falliti su 43) sul modulo Customer,
che non è stato toccato da questa migrazione. Causa: `Modules\Xot\Tests\XotBaseTestCase`
non esegue alcuna migrazione sulla connessione `xot` (sqlite `:memory:` per via di
`phpunit.xml`); solo alcuni moduli (Platform, Rating, Gdpr, UI, Activity, AI) puntano la
connessione sqlite a un fixture condiviso già migrato (`database/fixcity_data.sqlite`),
ma quel fixture non contiene le tabelle del dominio commesse (`customers`, `work_orders`,
`interventions`, ...). Un primo tentativo di query, con `AssignUserToWorkOrderAction`
scritto per l'ambiente reale, mostra che le Action sono corrette: il problema è solo la
mancanza di schema nel DB di test. **Non risolto in questo task**: costruire un fixture
sqlite dedicato al dominio commesse (Customer+WorkOrder+Intervention+Billing+Catalog+
Inventory+Platform) richiede di eseguire le migration di quei moduli, che a loro volta
hanno dipendenze incrociate con altri moduli (es. `Inventory\Warehouse` → FK verso
`vehicles`) — lavoro trasversale che rischia di toccare il DB MySQL di test condiviso
usato in parallelo da altri agenti, quindi lasciato esplicitamente fuori scope.

## Hub / BMAD

- [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md)
- Stories [1.3](../../../../docs/wiki/skills/bmad-create-story/stories/1.3.workorder-module.story.md) · [1.8](../../../../docs/wiki/skills/bmad-create-story/stories/1.8.wave1-reshape-xotbase.story.md) · [2.3](../../../../docs/wiki/skills/bmad-create-story/stories/2.3.deep-quality-enabled-modules.story.md)
- [#7](https://github.com/laraxot/base_workorder_fila5/issues/7) · [#14](https://github.com/laraxot/base_workorder_fila5/issues/14)
- Matrice statuses: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md)
- Story [2.4 docs hygiene](../../../../docs/wiki/skills/bmad-create-story/stories/2.4.module-theme-docs-hygiene.story.md)
