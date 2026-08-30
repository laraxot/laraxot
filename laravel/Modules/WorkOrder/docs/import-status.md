---
title: "Import status — WorkOrder"
type: concept
tags: [import, workorder, gestionale-commesse, phpstan, filament]
created: 2026-07-23
updated: 2026-07-24
qmd: "import status WorkOrder phpstan panel migrate"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../docs/gestionale-commesse-comparison/copy-then-normalize.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — WorkOrder

**Perché esiste:** commessa operativa (stato, attività, assignment) — cuore del gestionale.

- [x] Rename SRC `WorkOrders` → `WorkOrder`
- [x] Layout `app/` + XotBase SP + Resources
- [x] **PHPStan L10** — 0 errori reali
- [x] `modules_statuses=true`
- [x] Migrazioni path applicate (`work_orders` …); `primary_contact_id` **senza** FK DB (compat schema contacts)
- [x] `AdminPanelProvider` + rotte `workorder/admin/work-orders`
- [ ] Opzionale: `barryvdh/laravel-dompdf` / `phpoffice/phpword` in root
- [ ] Pest smoke CRUD

SSoT: [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md).

## Delta seconda tranche (2026-08-30)

Portato "distinta base da preventivo, margini, SAL e consegne" + "resoconto periodico":

- **Migrazioni** (bump create + guard): work_orders +progress_percent/+delivery_due_at;
  nuova work_order_materials (FK products nullable).
- **Modelli**: WorkOrderMaterial; WorkOrder.materials(); WorkOrderStatus.workOrders().
- **Actions**: CreateWorkOrderFromQuotationAction (commessa da preventivo con distinta
  copiata dalle righe), BuildPeriodicSummaryAction, GeneratePeriodicSummaryPdfAction
  (via DocumentExportBridge/dompdf).
- **Filament**: WorkOrderResource gated + SAL/consegna (form, infolist, colonne con
  deliveryColour), MaterialsRelationManager (XotBaseRelationManager, line_total
  derivato), WorkOrderStatusResource + 3 pagine XotBase, PeriodicSummaryPage
  (GatedXotBasePage) + 2 blade.
- **Test**: +3 file (23 test) tutti verdi.

Fix dest: view namespace unificato a `workorder::` (hint = nameLower; il codice
usava `work_orders::` mai registrato → PDF report erano rotti anche prima);
seeder Shield sostituito con grant diretto nel test; sweep `App\Models\User`
esteso ai tests di tutti i moduli.

### Debiti sistemici del modulo risolti in questo ciclo

- **Chiavi lang senza namespace** (242 riferimenti in 12 file + 1 test): il modulo usava
  `__('work_orders.…')` mentre non esiste `lang/it/` di root, quindi *ogni* etichetta
  rendeva la chiave grezza. Ora `workorder::work_orders.…` (idem `work_order`,
  `material`, `work_order_status`; `billing.` → `billing::billing.`).
  Aggiunte `effort.planned/actual/summary` mancanti in it/en.
- **UUID troncati a int** (memoria `uuid-int-cast-truncation`): le Actions WorkOrder
  dichiaravano `int $userId` / `?int $assignedByUserId` / `?int $changedByUserId` →
  TypeError con gli id utente UUID. Allargati a `int|string`.
- **`DB::table('users')->insertGetId()`** in 7 test → `UserFactory::new()->createOne()`
  (users.id è varchar UUID, non autoincrement: violava NOT NULL).
- **PHPDoc modelli**: aggiunte `@property` per progress_percent/delivery_due_at/materials
  su WorkOrder e docblock completo su WorkOrderMaterial.

Suite modulo: **50 passed, 16 skipped, 0 failed** (era 16 failed).
PHPStan: zero errori sul codice di produzione; i 68 residui sono test-only
(pattern `mixed` di Pest/Livewire, debito toolchain condiviso col resto della repo).

### Debiti cross-modulo rimasti (fuori scope, Fase 4)

- `__('compliance.…')` (37) e `__('public_procurement.…')` (17) nei RelationManagers:
  i moduli Compliance e PublicProcurement hanno solo `lang/it/module.php`, le chiavi
  non esistono da nessuna parte.
- `__('interventions.…')` (6): il modulo Intervention espone `intervention.php` (singolare).
- `__('quotations.…')` (2) in Quotation.
