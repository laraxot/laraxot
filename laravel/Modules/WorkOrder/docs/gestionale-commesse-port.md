---
title: "Test migrati da gestionale_commesse — WorkOrder"
type: concept
tags: [import, gestionale-commesse, workorder, test, pest]
created: 2026-07-24
updated: 2026-07-24
qmd: "WorkOrder test migrati sorgente gestionale_commesse pest ActivitiesTest AssignmentsTest ChangeStatusTest"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./import-status.md"
  - "../../../../docs/wiki/concepts/laraxot-module-canonical-structure.md"
  - "../../../../laravel/docs/gestionale-commesse-debito-tecnico.md"
---

# Test migrati dal sorgente — WorkOrder

Adattamento dei 14 file di `gestionale_commesse/tests/Feature/WorkOrders/` alla struttura
canonica Laraxot (`Modules\WorkOrder\Actions\*`, `Modules\WorkOrder\Models\*`), verificando
prima che `tests/Feature/ModelsTest.php` e `tests/Feature/ServiceProviderTest.php` (già
presenti) non coprissero già gli stessi casi — non li coprivano (erano solo test di
esistenza classi), quindi nessuna duplicazione.

## Portati integralmente

| File | Cosa copre | Adattamento |
|---|---|---|
| `ActivitiesTest.php` | `AddWorkOrderActivityAction` | Namespace `Modules\WorkOrders\Application\Actions` → `Modules\WorkOrder\Actions`; `DB::table()` raw → `WorkOrder::factory()`. |
| `AssignmentsTest.php` | `AssignUserToWorkOrderAction`/`UnassignUserFromWorkOrderAction` (idempotenza) | Idem. |
| `ChangeStatusTest.php` | `ChangeWorkOrderStatusAction` | Sorgente usa una tabella lookup `work_order_statuses`, non un enum: stessa scelta qui tramite il model `WorkOrderStatus` (il modulo non ha un `WorkOrderStatusEnum` — verificato, la commessa gestisce lo stato via FK a `work_order_statuses`). |
| `Unit/ComputeWorkOrderEffortTest.php` | `ComputeWorkOrderEffortAction`, `Domain\DTO\WorkOrderEffort` | Spostato in `tests/Unit/` (nessuna dipendenza da Filament/HTTP). DTO in `app/Domain/DTO/` conservato com'era nel modulo (debito pre-esistente, non introdotto qui: la regola canonica vieta `Domain/` ma non è stato toccato per restare nello scope "test", non "refactor architetturale"). |
| `CreateWorkOrderTest.php` | `CreateWorkOrderAction` | `Customer::factory()->create()` al posto di insert raw. |
| `DeleteWorkOrderTest.php` | `SoftDeleteWorkOrderAction` | `WorkOrder::factory()->create()`. |
| `RestoreWorkOrderCascadeNoopTest.php` | `RestoreWorkOrderCascadeAction` (no-op su record non cancellato) | 1:1. |
| `RestoreWorkOrderCascadeTest.php` | `RestoreWorkOrderCascadeAction` + cascade su `Intervention` | Il cascade sull'Intervention non è dentro l'action stessa (che ripristina solo le `WorkOrderActivity`), ma via evento `WorkOrderRestored` intercettato da `Modules\Intervention\Listeners\RestoreInterventionsForWorkOrderListener` — comportamento cross-modulo verificato e confermato equivalente al sorgente. |
| `ViewWorkOrderTest.php` | `GetWorkOrderDetailsAction` | 1:1. |
| `WorkOrderInterventionSearchTest.php` | Ricerca commesse per note/materiali intervento | 1:1, usa `Modules\Intervention\Models\{Intervention,InterventionMaterialLine}`. |
| `WorkOrderEffortAndPreInvoiceUiTest.php` | `WorkOrderResource::{effortColor,effortBadge,effortSummary}` + `Modules\Billing\Actions\BuildWorkOrderPreInvoiceAction` | Entrambi esistono nel modulo con la stessa semantica del sorgente (extra preventivo "pending" vs "to_bill"): portato 1:1. |

## Skippati (debito tecnico esplicito, non feature mancante)

`GenerateWorkOrderPdfTest.php`, `GenerateWorkOrderReportActionTest.php`,
`GenerateCustomerReportActionTest.php` (15 test totali) sono stati portati come stub con
`->skip('needs phpoffice/phpword and barryvdh/laravel-dompdf - see debito-tecnico.md #5')`:
le action `GenerateWorkOrderPdfAction`, `GenerateWorkOrderReportAction`,
`GenerateCustomerReportAction` referenziano `PhpOffice\PhpWord\IOFactory` e
`Barryvdh\DomPDF\Facade\Pdf`, nessuno dei due pacchetti installato nel monorepo — vedi
[gestionale-commesse-debito-tecnico.md §5](../../../docs/gestionale-commesse-debito-tecnico.md).
Da riportare con gli assert originali (incluso il caso di regressione "manual material line
senza inventory movements" in `GenerateCustomerReportActionTest`) non appena i pacchetti sono
installati e approvati.

## Esito esecuzione

`./vendor/bin/pest Modules/WorkOrder/tests --no-coverage` (24 luglio 2026): **20 failed, 16
skipped, 7 passed**. Tutti i 20 fallimenti sono `QueryException: no such table: X` — gap di
infrastruttura test **noto e documentato**, non introdotto da questa sessione: vedi
[gestionale-commesse-debito-tecnico.md §2](../../../docs/gestionale-commesse-debito-tecnico.md)
("nessun meccanismo esegue le migration sul DB SQLite `:memory:` dei test", responsabilità di
`Modules\Xot\Tests\XotBaseTestCase`). I 7 test passati sono quelli senza scrittura DB
(`Unit/ComputeWorkOrderEffortTest.php` — 5 dei quali puri DTO/azioni in-memory — più
`ModelsTest`/`ServiceProviderTest` preesistenti).
