---
title: "HR (gestionale_commesse) vs Employee (base_workorder_fila5) — analisi comparativa"
type: story
created: 2026-07-23
---

# STORY-002 — HR vs Employee: cosa manca

Confronto strutturale e funzionale tra:

- **Source** (riferimento/più recente): `/var/www/_bases/gestionale_commesse/Modules/HR`
- **Target** (nostro codebase): `/var/www/_bases/base_workorder_fila5/laravel/Modules/Employee`

Nella codebase target il concetto "HR" è chiamato `Employee`. Il modulo `Employee` qui è enormemente più maturo su **time-tracking / timbrature** ma **non ha alcun sistema reale di richieste assenze, documenti dipendente, visite mediche e formazione** — quelle funzionalità nei widget esistono solo come mock UI.

---

## Cosa ha HR (gestionale_commesse) che Employee (base_workorder_fila5) non ha

### 1. Modello dati "Employee" dedicato, separato da User

- `Modules/HR/Infrastructure/Models/Employee.php` — modello Eloquent **dedicato** (tabella `employees`), non un alias/estensione di `User`.
- Migrazione: `Modules/HR/Database/Migrations/2026_02_16_100154_create_hr_tables.php` crea:
  - `employees` (user_id FK nullable, first_name, last_name, email, phone, hired_at, terminated_at, notes, is_active, soft deletes, unique su user_id, index su last_name+first_name)
  - `employee_medical_visits` (employee_id FK, visited_at, expires_at, notes)
  - `employee_trainings` (employee_id FK, name, completed_at, expires_at, notes)
  - `employee_documents` (employee_id FK, media_id FK, title, kind, unique employee_id+media_id)
- Confronto: in Employee (target) `app/Models/Employee.php` **non è un modello proprio** ma `class Employee extends User` con `use HasParent` (pattern Single Table Inheritance su tabella `users`), con campi ipotetici `department_id`, `manager_id`, `position_id`, `salary_data`, `personal_data`, `contact_data`, `work_data`, `documents` dichiarati nel `$fillable`/PHPDoc **ma senza nessuna migrazione nel modulo Employee che crei queste colonne** (nessuna migration in `database/migrations/` diversa dalle due su `work_hours`). Questi campi sono probabilmente gestiti altrove (User module) o sono dead code/aspirazionali: da verificare, ma il modulo Employee stesso non porta la migrazione.

### 2. Richieste di assenza (ferie/permessi/malattia/infortunio) — sistema reale end-to-end

- Migrazione: `Modules/HR/Database/Migrations/2026_02_17_120000_create_absence_requests_table.php` — tabella `absence_requests` (user_id, type, starts_at, ends_at, notes, status default "pending", decided_by_user_id, decided_at, index su user_id+starts_at e su status).
- Modello: `Modules/HR/Infrastructure/Models/AbsenceRequest.php` (relazioni `user()`, `decidedBy()`).
- Actions dedicate (pattern Application/Actions):
  - `Modules/HR/Application/Actions/CreateAbsenceRequestAction.php`
  - `Modules/HR/Application/Actions/ApproveAbsenceRequestAction.php`
  - `Modules/HR/Application/Actions/RejectAbsenceRequestAction.php`
- Filament Resource completa: `Modules/HR/Filament/Resources/AbsenceRequestResource.php` con:
  - form (type, status, starts_at/ends_at, notes), alias/normalizzazione tipi assenza (`ferie→vacation`, `permesso→leave`, `malattia→sick`, `infortunio→injury`)
  - tabella con filtro per stato (default "pending")
  - azioni **approve/reject** dirette in tabella, visibili solo per pannello `admin/*`, che invocano le Action sopra
  - scoping automatico per pannello `operator/*` (vede solo le proprie richieste) via `getEloquentQuery()`
  - Pages: List/Create/View/Edit in `Modules/HR/Filament/Resources/AbsenceRequestResource/Pages/`
- Policy: `Modules/HR/Infrastructure/Policies/AbsenceRequestPolicy.php` (permission-based, standard Filament Shield-style).
- **In Employee (target)**: esistono solo widget con **dati hardcoded/mock**, nessuna tabella, nessun modello, nessuna action:
  - `app/Filament/Widgets/LeaveBalanceWidget.php` — saldi ferie/ROL/permessi/banca ore con numeri fissi nel codice (es. `'hours' => 8, 'minutes' => 53`), nessuna query reale.
  - `app/Filament/Widgets/PendingRequestsWidget.php` — commento esplicito nel codice: `// Mock data for demonstration - in production this would come from a database`, con richieste finte ("Ferie Natale", "Permesso Medico", "Smart Working").
  - `app/Filament/Widgets/TimeOffBalanceWidget.php`, `AttendanceOverviewWidget.php`, `UpcomingScheduleWidget.php`, `TodoWidget.php` — da verificare singolarmente ma stesso pattern di mock/placeholder emerso da grep.
  - Nessun equivalente di `AbsenceRequestResource`, nessuna migration `absence_requests`, nessuna policy.

### 3. Gestione documenti dipendente (allegati media)

- `Modules/HR/Infrastructure/Models/EmployeeDocument.php` + tabella `employee_documents`, relazione a `Modules\Documents\Infrastructure\Models\Media`.
- Action: `Modules/HR/Application/Actions/AddEmployeeDocumentAction.php` (usa `CreateMediaFromStoredFileAction` del modulo Documents).
- Relation Manager Filament: `Modules/HR/Filament/Resources/EmployeeResource/RelationManagers/DocumentsRelationManager.php`.
- In Employee (target): nessuna tabella/model/relation manager equivalente. Il campo `documents` (array) nel PHPDoc di `Employee.php` non ha backing reale nel modulo.

### 4. Visite mediche periodiche

- `Modules/HR/Infrastructure/Models/EmployeeMedicalVisit.php` + tabella `employee_medical_visits` (visited_at, expires_at, notes, allegato media opzionale).
- Action: `AddEmployeeMedicalVisitAction.php`.
- Relation Manager: `MedicalVisitsRelationManager.php`.
- Nessun equivalente in Employee (target) — solo hit di grep su "medical" nel `PendingRequestsWidget.php` mock (icona "heart" per "Visita cardiologica").

### 5. Formazione / corsi obbligatori (training) con scadenze

- `Modules/HR/Infrastructure/Models/EmployeeTraining.php` + tabella `employee_trainings` (name, completed_at, expires_at, notes, allegato media).
- Action: `AddEmployeeTrainingAction.php`.
- Relation Manager: `TrainingsRelationManager.php`.
- Nessun equivalente in Employee (target).

### 6. Filament Resource "Employee" con CRUD anagrafica completo

- `Modules/HR/Filament/Resources/EmployeeResource.php` — form completo (first_name, last_name, email, phone, hired_at, terminated_at, user_id linkabile a `User`, notes, is_active), tabella con colonne sortable/searchable, soft-delete (Restore/ForceDelete bulk actions), relation managers su documenti/visite/formazione.
- Pages complete: `Pages/ListEmployees.php`, `CreateEmployee.php`, `EditEmployee.php`, `ViewEmployee.php`.
- In Employee (target): **non esiste nessuna Filament Resource per l'anagrafica Employee stessa** — l'unica resource presente nel modulo è `WorkHourResource` (timbrature). Non c'è modo di creare/modificare/visualizzare un dipendente da UI Filament nel modulo Employee.

### 7. Policy con permission-mapping standard (Shield-style) per Employee e AbsenceRequest

- `Modules/HR/Infrastructure/Policies/EmployeePolicy.php` e `AbsenceRequestPolicy.php` — pattern completo con viewAny/view/create/update/delete/restore/forceDelete/forceDeleteAny/restoreAny/replicate/reorder mappati a permission string (`ViewAny:Employee`, ecc.).
- In Employee (target) esiste solo `app/Policies/WorkHourPolicy.php` (relativo a timbrature, non ad anagrafica/assenze).

### 8. Naming/architettura pulita a livello "Clean Architecture" (Application/Infrastructure)

- HR usa una separazione esplicita `Application/Actions/`, `Infrastructure/Models/`, `Infrastructure/Policies/`, `Filament/Resources/` (separata da `app/`), coerente con hexagonal/clean-architecture leggera.
- Employee (target) segue invece la convenzione standard nwidart/XotBase con tutto sotto `app/` (Models, Filament, Actions, Enums, Policies) — architetturalmente valido per XotBase ma non comparabile 1:1 con HR: nessuna azione richiesta qui, è solo una nota di convenzione diversa, non un gap funzionale.

---

## Cosa ha Employee che HR non ha

### 1. Sistema di timbratura (clock in/out) completo e funzionante

- Modelli reali con business logic: `app/Models/WorkHour.php` (389 righe), `app/Models/TimeEntry.php` (223 righe), `app/Models/TimeRecord.php` (221 righe) — HR non ha alcun concetto di orario/timbratura.
- Actions: `app/Actions/TimeTracking/ClockInAction.php`, `ClockOutAction.php`, `app/Actions/BuildWorkHoursForRangeAction.php`, `BuildWeeklyTimeTableAction.php`, `BuildTimelineVisualizationAction.php`, `ExportTimeDataAction.php`, `GetCurrentEmployeeDataAction.php`.
- Enum: `app/Enums/WorkHourStatusEnum.php`, `app/Enums/WorkHourTypeEnum.php`.
- Filament Resource dedicata: `app/Filament/Resources/WorkHourResource.php` con Schemas separati (`Schemas/WorkHourForm.php`, `Schemas/WorkHourInfolist.php`), Tables (`Tables/WorkHoursTable.php`), pagina timeclock dedicata (`Pages/TimeClockPage.php`), widget statistiche (`Widgets/WorkHourStatsWidget.php`).
- Config dedicata: `config/workhour.php` (orari di lavoro, soglie validazione, gestione pause, notifiche, target ore settimanali/giornaliere, soglie straordinario).

### 2. Dashboard con widget multipli (anche se in parte mock)

- `app/Filament/Pages/Dashboard.php` con widget: `EmployeeOverviewWidget`, `TeamPresenceWidget`, `TodayPresenceWidget`, `WorkHoursSummaryWidget`, `WorkHoursBoardWidget`, `AttendanceOverviewWidget`, `UpcomingScheduleWidget`, `TimeClockWidget`. HR non ha alcuna dashboard/widget.

### 3. AdminPanelProvider proprio (pannello Filament dedicato)

- `app/Providers/Filament/AdminPanelProvider.php` — il modulo Employee registra un proprio panel Filament (probabilmente panel "employee" separato da quello admin generico). HR non registra alcun panel proprio (usa solo `HrServiceProvider.php` semplice).

### 4. Suite di test consistente

- `tests/Feature/EmployeeAdminRouteTest.php`, `WorkHourManagementTest.php`, `TimeTrackingBusinessLogicTest.php`, `tests/Feature/Widgets/EmployeeOverviewWidgetTest.php`, `TimeTrackingWidgetTest.php`, `tests/Unit/Models/BaseModelTest.php`, `WorkHourTest.php`, `EmployeeBusinessLogicTest.php`, `WorkHourBusinessLogicTest.php`, `EmployeeTest.php`.
- HR ha solo cartelle `tests/Feature/.gitkeep` e `tests/Unit/.gitkeep` — **nessun test scritto**.

### 5. Modelli organizzativi Department / Position con relazioni

- `app/Models/Department.php` (name, description, manager_id, is_active, relazione `employees()`) e `app/Models/Position.php` (title, description, department, level, is_active, relazione `employees()`) — concetti di org-chart/ruoli che HR non modella affatto (HR non ha `Department`/`Position`).
- Nota: **anche qui manca la migrazione** nel modulo Employee per le tabelle `departments`/`positions` (non trovata in `database/migrations/`) — probabilmente gestita in un altro modulo o mancante: da verificare come debito tecnico, ma il modello concettuale (a differenza di HR) esiste.

---

## Gap prioritari da colmare in Employee

Elenco ordinato per impatto (più impattante prima), basato solo su quanto verificato nei file:

1. **Sistema reale di richieste assenze (ferie/permessi/malattia/infortunio)** — oggi è totalmente mock (`PendingRequestsWidget.php` dichiara esplicitamente "Mock data for demonstration"; `LeaveBalanceWidget.php` ha saldi hardcoded). È il gap più grande e più visibile: la UI promette una feature che non esiste a livello dati. Serve, sul modello di `Modules/HR`:
   - migration `absence_requests` (user_id/employee_id, type, starts_at, ends_at, notes, status, decided_by, decided_at)
   - modello `AbsenceRequest`
   - Actions `CreateAbsenceRequestAction`, `ApproveAbsenceRequestAction`, `RejectAbsenceRequestAction`
   - Filament Resource con azioni approve/reject e scoping per ruolo (operatore vede solo le proprie, admin vede tutte) — pattern già pronto in `Modules/HR/Filament/Resources/AbsenceRequestResource.php` da adattare a XotBase/Schema v5.
   - Policy `AbsenceRequestPolicy`.

2. **Filament Resource per l'anagrafica Employee stessa** — attualmente il modulo Employee non ha nessuna Resource CRUD per gestire i dipendenti (solo `WorkHourResource` per le timbrature). Manca un `EmployeeResource` (o equivalente) per creare/modificare/visualizzare dipendenti da UI, sul modello di `Modules/HR/Filament/Resources/EmployeeResource.php`.

3. **Verificare/colmare le migrazioni mancanti per i campi già dichiarati sul modello Employee** (`department_id`, `manager_id`, `position_id`, `salary_data`, `personal_data`, `contact_data`, `work_data`, `documents`) e per le tabelle `departments`/`positions` referenziate da `Department.php`/`Position.php`. Il modello concettuale esiste (più ricco di HR, che non ha Department/Position), ma senza migrazione nel modulo è codice non testabile/non funzionante a runtime se le tabelle non esistono altrove.

4. **Documenti dipendente (allegati)** — replicare `EmployeeDocument` + `AddEmployeeDocumentAction` + relation manager, sfruttando l'integrazione media già presente nel progetto (spatie/laravel-medialibrary, già usato da `Employee extends User` con `Modules\Media\Models\Media`).

5. **Visite mediche periodiche** — replicare `EmployeeMedicalVisit` + `AddEmployeeMedicalVisitAction` + relation manager, con scadenze (`expires_at`) utili per notifiche/promemoria.

6. **Formazione/corsi con scadenze** — replicare `EmployeeTraining` + `AddEmployeeTrainingAction` + relation manager.

7. **Policy dedicata per l'anagrafica Employee** — oggi esiste solo `WorkHourPolicy`; manca una `EmployeePolicy` con permission-mapping standard per governare viewAny/create/update/delete sull'anagrafica.

8. **Debito tecnico da ripulire in parallelo** (non feature mancante da HR, ma emerso durante l'analisi e rilevante per qualità):
   - due migrazioni quasi identiche per `work_hours` (`2025_08_27_121400_create_work_hours_table.php` e `2025_08_27_121401_create_work_hours_table.php`, la seconda con FK dirette verso `users` invece dell'approccio "no FK cross-connessione" della prima) — capire quale è quella attiva e rimuovere il duplicato.
   - due pagine quasi duplicate `TimeClockPage.php` e `TimeclockPage.php` (stesso namespace, case diverso nel filename) sotto `app/Filament/Resources/WorkHourResource/Pages/` — verificare quale è referenziata da `WorkHourResource::getPages()` e rimuovere l'altra.

I gap 1–2 sono i più impattanti perché riguardano funzionalità core HR (assenze, anagrafica) che oggi sono assenti o solo simulate in UI, mentre il resto del modulo Employee (timbrature/dashboard/test) è già più maturo di HR.
