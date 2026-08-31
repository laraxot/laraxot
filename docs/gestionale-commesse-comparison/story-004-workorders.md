---
title: "STORY-004 — WorkOrders (gestionale_commesse) vs base_workorder_fila5: gap e proposta modulo"
type: story
created: 2026-07-23
tags: [gestionale-commesse, workorders, commessa, gap-analysis, laraxot]
related:
  - "./module-mapping.md"
  - "./glossary-align.md"
  - "./new-modules-candidates.md"
  - "./EPIC.md"
---

# STORY-004 — WorkOrders: gap e proposta modulo

## Perché questa storia

Il progetto target si chiama `base_workorder_fila5` — il nome stesso indica che "work order"
(commessa) dovrebbe essere il dominio centrale. Questa storia verifica cosa contiene
davvero il modulo `WorkOrders` nel sistema sorgente `gestionale_commesse` e conferma,
con evidenza concreta sui file, che **nel target tale concetto non esiste ancora**:
è solo il nome del repository, non un modulo implementato.

## 1. Cosa contiene `WorkOrders` in gestionale_commesse

Path sorgente: `/var/www/_bases/gestionale_commesse/Modules/WorkOrders`

Architettura DDD leggera: `Domain/` (DTO/eventi), `Application/Actions/` (use case),
`Infrastructure/Models|Policies/`, `Filament/Resources/`. Manifest:
`/var/www/_bases/gestionale_commesse/Modules/WorkOrders/module.json` — modulo autonomo,
un solo provider (`WorkOrdersServiceProvider`), nessuna dipendenza dichiarata in `module.json`
(le dipendenze reali sono verso `Customers`, `Compliance`, `Interventions`, `PublicProcurement`
via `use` nei Model, non nel manifest).

### 1.1 Modelli (`Infrastructure/Models/`)

| Modello | File | Ruolo |
|---|---|---|
| `WorkOrder` | `Infrastructure/Models/WorkOrder.php` | Aggregato principale: commessa |
| `WorkOrderStatus` | `Infrastructure/Models/WorkOrderStatus.php` | Lookup stati (tabella `work_order_statuses`) |
| `WorkOrderStatusEvent` | `Infrastructure/Models/WorkOrderStatusEvent.php` | Storico transizioni di stato (audit trail) |
| `WorkOrderActivity` | `Infrastructure/Models/WorkOrderActivity.php` | Note/attività testuali sulla commessa (soft delete) |
| `WorkOrderAssignment` | `Infrastructure/Models/WorkOrderAssignment.php` | Assegnazione utenti (tecnici) alla commessa, con ruolo |

`WorkOrder` (guarded `[]`, `SoftDeletes`) espone le relazioni:
- `customer()` → `Modules\Customers\Infrastructure\Models\Customer` (belongsTo)
- `site()` → `Modules\Customers\Infrastructure\Models\CustomerSite` (belongsTo `customer_site_id`)
- `primaryContact()` → `Modules\Customers\Infrastructure\Models\Contact` (belongsTo `primary_contact_id`)
- `status()` → `WorkOrderStatus` (belongsTo `status_id`)
- `activities()`, `assignments()`, `statusEvents()` → hasMany interni al modulo
- `interventions()` → `Modules\Interventions\Infrastructure\Models\Intervention` (hasMany `work_order_id`)
- `complianceDocuments()` → `Modules\Compliance\Infrastructure\Models\ComplianceDocument`
- `changeOrders()` → `Modules\Compliance\Infrastructure\Models\WorkOrderChangeOrder`
- `publicProcurementContracts()` → `Modules\PublicProcurement\Infrastructure\Models\PublicProcurementContract`

### 1.2 Schema DB

Migrazione: `Database/Migrations/2026_02_16_100047_create_work_orders_tables.php` crea 5 tabelle:

- `work_order_statuses`: `code` (unique), `name`, `color`, `is_terminal` (bool), `sort_order`
- `work_orders`: `code` (unique, nullable), FK `customer_id` (cascade), `customer_site_id` (nullable, nullOnDelete),
  `primary_contact_id` (nullable), `status_id` (nullable, nullOnDelete), `title`, `description`, `notes`,
  timestamp di ciclo vita (`opened_at`, `scheduled_at`, `started_at`, `completed_at`, `closed_at`), soft deletes
- `work_order_status_events`: `work_order_id`, `from_status_id`, `to_status_id`, `changed_by_user_id`, `note`, `changed_at`
- `work_order_activities`: `work_order_id`, `created_by_user_id`, `type` (default `note`), `body`, `occurred_at`, soft deletes
- `work_order_assignments`: `work_order_id`, `user_id`, `role`, `assigned_by_user_id`, `assigned_at`, unique `(work_order_id, user_id)`

Migrazione successiva `2026_07_21_110000_add_planned_effort_to_work_orders.php` aggiunge
`planned_minutes` a `work_orders` per confrontare preventivo/consuntivo (il consuntivo
arriva da `interventions.work_minutes`, modulo `Interventions`).

### 1.3 Macchina a stati

Non è un enum/state-machine tipizzata a livello di codice, ma un **lookup table
guidato da seed** + un'action che orchestra la transizione:

- Stati seedati in `Modules/Platform/Database/Seeders/OperationalLookupsSeeder.php`
  (metodo `seedWorkOrderStatuses`): `draft` → `scheduled` → `in_progress` → `completed` (terminale) /
  `cancelled` (terminale), con `color` e `sort_order` per la UI.
- `Application/Actions/ChangeWorkOrderStatusAction.php`: esegue la transizione
  (`forceFill(['status_id' => ...])`), crea uno `WorkOrderStatusEvent` (from/to/nota/data)
  e, se richiesto, una `WorkOrderActivity` di tipo `status` con messaggio `"Status changed: X → Y"`.
- `Application/Actions/ChangeWorkOrderStatusByCodeAction.php`: variante che risolve lo stato per `code` invece che per id.

Quindi: nessun vincolo di transizione esplicito nel codice (qualsiasi stato può
andare a qualsiasi stato), la "macchina a stati" è solo convenzionale (ordine via `sort_order`
e flag `is_terminal`), con audit trail completo su `work_order_status_events`.

### 1.4 Altre Action rilevanti (`Application/Actions/`)

`AddWorkOrderActivityAction`, `AssignUserToWorkOrderAction`, `UnassignUserFromWorkOrderAction`,
`ComputeWorkOrderEffortAction` (calcola scostamento previsto/consuntivo, usato anche in Filament),
`CreateWorkOrderAction`, `GenerateCustomerReportAction`, `GenerateWorkOrderPdfAction`,
`GenerateWorkOrderReportAction` (report PDF/DOCX per intervallo date), `GetWorkOrderDetailsAction`,
`RestoreWorkOrderCascadeAction`/`SoftDeleteWorkOrderAction` (cascade su soft delete),
`SetWorkOrderPrimaryContactAction`.

### 1.5 Filament Resource

`Filament/Resources/WorkOrderResource.php` — resource condivisa fra **più pannelli**
(Admin/Operator/Customer) con visibilità/scoping dinamici via `request()->is('admin/*'|'operator/*'|'customer/*')`
e una classe di supporto `App\Support\AccountFeatures` per abilitare/disabilitare funzionalità per pannello
(pattern non presente nel target, che invece usa Themes Folio/Volt per il FO).

Punti notevoli:
- form con select a cascata Cliente → Sede → Referente (opzioni filtrate dinamicamente)
- colonna tabella con badge "scostamento effort" (colore success/danger in base a `isOverBudget()`)
- azione `exportReport` (PDF/DOCX) con form di intervallo date, verificata da `AccountFeatures::hasFeature`
- azione `preInvoice` che apre un modal con `Modules\Billing\Application\Actions\BuildWorkOrderPreInvoiceAction` (collegamento a fatturazione)
- `getRelations()` condizionale: `ActivitiesRelationManager`, `AssignmentsRelationManager`,
  `InterventionsRelationManager`, `PublicProcurementContractsRelationManager`, `StatusEventsRelationManager`
  sempre presenti; `ComplianceDocumentsRelationManager`, `ChangeOrdersRelationManager`,
  `ReadinessItemsRelationManager` solo se la feature "artisan readiness" è abilitata per l'utente
- Policy: `Infrastructure/Policies/WorkOrderPolicy.php`, standard Filament Shield-style (`ViewAny/View/Create/Update/Delete/Restore/ForceDelete/Reorder/Replicate`)

### 1.6 Relazioni con Customers / Interventions / Billing (riepilogo)

- **Customers**: `WorkOrder belongsTo Customer`, `CustomerSite`, `Contact` — relazione 1:N standard cliente→commesse
- **Interventions**: `WorkOrder hasMany Intervention` (via `work_order_id`); gli interventi portano il consuntivo
  ore (`work_minutes`) usato da `ComputeWorkOrderEffortAction` per lo scostamento vs `planned_minutes`
- **Billing**: non c'è FK diretta nello schema `WorkOrders`, ma un'integrazione applicativa:
  `WorkOrderResource::preInvoiceAction()` invoca `Modules\Billing\Application\Actions\BuildWorkOrderPreInvoiceAction`
  per generare una pre-fattura a partire dalla commessa (probabilmente aggregando gli interventi fatturabili)
- **Compliance**: `ComplianceDocument`, `WorkOrderChangeOrder`, `WorkOrderReadinessItem` sono modellati nel modulo
  `Compliance` ma referenziano `work_order_id` — quindi `Compliance` dipende da `WorkOrders`, non viceversa
- **PublicProcurement**: `PublicProcurementContract` referenzia `work_order_id` — verticale per commesse legate ad appalti pubblici

## 2. Cosa esiste nel target `base_workorder_fila5`

Ricerca esaustiva in `laravel/Modules` (grep case-insensitive su `work_order|workorder|WorkOrder`
in tutti i `.php`): **zero risultati**. Il concetto non esiste in nessuna forma, nemmeno come
stub o TODO.

Moduli presenti (`ls laravel/Modules/`): `Activity, AI, Blog, Cms, Comment, Employee, Gdpr, Geo,
Job, Lang, Media, Notify, Rating, Seo, Tenant, TestModule, UI, User, Xot`.

I due candidati più vicini per nome, verificati e **scartati** come equivalenti:

- **`Job`** (`laravel/Modules/Job/`, alias `job`, priority 20): modelli in
  `laravel/Modules/Job/app/Models/` sono `Job.php`, `FailedJob.php`, `JobBatch.php`,
  `Export.php`, `Import.php`, `FailedImportRow.php`, `Task.php`, `Schedule.php`,
  `ScheduleHistory.php`, `Frequency.php`, `Result.php`, `Parameter.php`, `JobsWaiting.php`,
  `JobManager.php` — è infrastruttura di **queue/scheduler/import-export** Laravel
  (Spatie schedule monitor, laravel-excel, queue jobs), non un dominio di commessa/lavoro
  per un cliente. Il file `module.json` conferma: nessuna menzione di clienti/interventi.
- **`Activity`** (`laravel/Modules/Activity/`, alias `activity`, priority 0): modelli
  `Activity.php`, `StoredEvent.php`, `Snapshot.php`, `TestModel.php` — è il modulo di
  **audit log / event sourcing** (Spatie activitylog + event-sourcing), usato per tracciare
  azioni degli utenti nel sistema, non per rappresentare interventi tecnici su una commessa.
- **`Tenant`**: multi-tenancy (Domain, TenantSetting, TenantSubscription) — nessuna relazione con commesse.

Questo conferma quanto già registrato in
`docs/gestionale-commesse-comparison/module-mapping.md` (riga `WorkOrders` → `—` (**non** Job) →
**nuovo** → `WorkOrder`), qui verificato con evidenza diretta sui file sorgenti di entrambi i progetti
anziché per deduzione.

Va notato che **anche `Customers` e `Interventions`** (da cui `WorkOrder` dipende direttamente
nel sorgente) sono assenti nel target — sono anch'essi "nuovo" nella tabella di mapping.
Questo significa che portare `WorkOrder` da solo, senza `Customer` e `Intervention`, non è praticabile:
tre moduli vanno pianificati insieme (vedi § 4).

## 3. Conclusione

Il nome del repository (`base_workorder_fila5`) è **aspirazionale**: descrive l'obiettivo finale
del porting, non uno stato già implementato. Ad oggi il dominio "work order / commessa" è
**completamente assente** dal codice del target; esiste solo come voce di pianificazione nei
documenti di gap-analysis (`module-mapping.md`, `new-modules-candidates.md`, `EPIC.md`).

## 4. Raccomandazione

**Creare un nuovo modulo `Modules/WorkOrder`** (singolare, coerente con la convenzione Laraxot
già adottata per `Employee`, `Media`, `User` — vedi regola naming in `module-mapping.md` § "Regola naming").
Non riusare `Job` (queue interno) né `Activity` (audit log generico): userebbe nomi già occupati
da concetti tecnici diversi e creerebbe confusione semantica grave (es. "WorkOrderActivity" dentro
il modulo `Activity` di audit-log sarebbe frainteso come voce di log di sistema anziché nota tecnica).

### 4.1 Dipendenze da pianificare insieme

`WorkOrder` nel sorgente dipende da `Customers` e produce dati per `Interventions` e `Billing`.
Nel target, questi non esistono ancora. Sequenza minima proposta (coerente con
`module-mapping.md` § "Priorità porting"):

1. `Customer` (cliente, sede, referente) — prerequisito hard per `belongsTo`
2. `WorkOrder` (questo modulo)
3. `Intervention` (consuntivo ore/lavori sulla commessa)
4. Integrazione con `Billing` (da creare) solo per la pre-fatturazione, non bloccante per il MVP

### 4.2 Struttura proposta per `Modules/WorkOrder` (allineata a XotBase/Laraxot, non a `Domain/Application/Infrastructure` come nel sorgente)

```
laravel/Modules/WorkOrder/
  app/
    Models/
      WorkOrder.php            # estende XotBaseModel/base del progetto, non Model puro
      WorkOrderStatus.php       # lookup stati (o Enum se si adotta pattern Enum+trait invece di tabella)
      WorkOrderStatusEvent.php
      WorkOrderActivity.php     # nota bene: nome interno al modulo, non confonde con Modules\Activity
      WorkOrderAssignment.php
    Filament/Resources/WorkOrderResource/
      Pages/ (List, Create, Edit, View)
      RelationManagers/ (Activities, Assignments, StatusEvents, Interventions*)
    Actions/
      ChangeWorkOrderStatusAction.php
      ComputeWorkOrderEffortAction.php
      ...
    Policies/WorkOrderPolicy.php
  database/migrations/
    xxxx_create_work_order_statuses_table.php
    xxxx_create_work_orders_table.php
    xxxx_create_work_order_status_events_table.php
    xxxx_create_work_order_activities_table.php
    xxxx_create_work_order_assignments_table.php
  database/seeders/WorkOrderStatusSeeder.php   # draft/scheduled/in_progress/completed/cancelled
  module.json  (requires: ["Xot", "User", "Customer"])
```

Note di adattamento rispetto al sorgente:
- Rispettare le convenzioni già in uso nel target (XotBase*, no `app/Interfaces`, no cartelle
  `Domain/Application/Infrastructure` — il target usa la struttura piatta `nwidart/laravel-modules`
  standard con `app/Models`, `app/Filament`, ecc., come si vede in `Job`, `Activity`, `Tenant`).
- Verificare se adottare enum PHP nativo per lo stato invece della tabella lookup
  `work_order_statuses` del sorgente — è una scelta architetturale da validare con
  `bmad-architecture`, non assunta qui.
- Il campo `planned_minutes` e l'azione `ComputeWorkOrderEffortAction` vanno portati solo
  dopo che `Intervention` esiste (dipendenza dati per il consuntivo).

## Fonti consultate

- `/var/www/_bases/gestionale_commesse/Modules/WorkOrders/Infrastructure/Models/WorkOrder.php`
- `/var/www/_bases/gestionale_commesse/Modules/WorkOrders/Infrastructure/Models/WorkOrderStatus.php`
- `/var/www/_bases/gestionale_commesse/Modules/WorkOrders/Infrastructure/Models/WorkOrderStatusEvent.php`
- `/var/www/_bases/gestionale_commesse/Modules/WorkOrders/Infrastructure/Models/WorkOrderActivity.php`
- `/var/www/_bases/gestionale_commesse/Modules/WorkOrders/Infrastructure/Models/WorkOrderAssignment.php`
- `/var/www/_bases/gestionale_commesse/Modules/WorkOrders/Infrastructure/Policies/WorkOrderPolicy.php`
- `/var/www/_bases/gestionale_commesse/Modules/WorkOrders/Database/Migrations/2026_02_16_100047_create_work_orders_tables.php`
- `/var/www/_bases/gestionale_commesse/Modules/WorkOrders/Database/Migrations/2026_07_21_110000_add_planned_effort_to_work_orders.php`
- `/var/www/_bases/gestionale_commesse/Modules/WorkOrders/Application/Actions/ChangeWorkOrderStatusAction.php`
- `/var/www/_bases/gestionale_commesse/Modules/WorkOrders/Filament/Resources/WorkOrderResource.php`
- `/var/www/_bases/gestionale_commesse/Modules/WorkOrders/module.json`
- `/var/www/_bases/gestionale_commesse/Modules/Platform/Database/Seeders/OperationalLookupsSeeder.php`
- `/var/www/_bases/base_workorder_fila5/laravel/Modules/Job/module.json`
- `/var/www/_bases/base_workorder_fila5/laravel/Modules/Job/app/Models/` (listato)
- `/var/www/_bases/base_workorder_fila5/laravel/Modules/Activity/module.json`
- `/var/www/_bases/base_workorder_fila5/laravel/Modules/Activity/app/Models/` (listato)
- `/var/www/_bases/base_workorder_fila5/laravel/Modules/Tenant/module.json`
- `/var/www/_bases/base_workorder_fila5/docs/gestionale-commesse-comparison/module-mapping.md`
