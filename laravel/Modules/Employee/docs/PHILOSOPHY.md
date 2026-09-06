# PHILOSOPHY — Employee Module

## RELIGIONE

Il modulo Employee del progetto FixCity incarna tre dogmi inviolabili:

1. **L'Employees come User di secondo livello**: Un dipendente non è un admin, non è un guest, è una creatura con identità doppia. Estende `User` via STI (Single Table Inheritance) perché storicamente Laravel fonde identity con role. Il modello `Employee` è figlio di `User` ma con semantica e ciclo di vita completamente diverso. È l'unico pattern STI ammesso in FixCity.

2. **Lo stato di transizione è legge**: Ogni cambio di condizione (assunzione, trasferimento, licenziamento, ferie) deve essere catturato atomicamente, tracciato, approvato dove richiesto. Non esiste "cambio di status" — esiste solo "transizione validata con audit trail". La state machine è il fondamento, non un'opzione.

3. **Le ore di lavoro sono il valore biologico del sistema**: WorkHour non è una tabella di log marginale; è il DNA di FixCity. Ogni timbratura (clock_in/clock_out/break) è una decisione economica: straordinari, assenza non autorizzata, sessioni multiple. L'integrità delle sequenze (next action) è sacra. Non potrai mai avere un CLOCK_OUT senza CLOCK_IN.

## FILOSOFIA

### Perché Employee ≠ User?

User è il gateway dell'identità — email, password, ruoli, autenticazione Sanctum/Passport.
Employee è il corpo dell'organizzazione — stipendio, dipartimento, manager, ore lavorate, documenti.

Se un membro del team HR cessa di essere dipendente (diventa contractor), rimane User (mantiene accesso) ma smette di essere Employee (no timesheet, no leave). Se assumiamo una nuova geisha di laboratorio, diventa sia User che Employee. Questo split salva queryabilità e semantica.

### Gerarchia vs Dipartimento

Employee ha TWO parent structures:
- `manager_id` → relazione ad albero (catena di comando, reporteria, span of control)
- `department_id` → relazione organizzativa (budget, compliance, skill pool)

Un dipendente può avere manager X ma essere assegnato a dipartimento Y. Se Y trasferisce il dipendente, il manager potrebbe non seguire. Se il manager cambia, il dipartimento rimane. Sono ortogonali per progettazione.

### Perché Role != Position?

`Role` (da spatie/laravel-permission) è un costrutto di accesso — "viewer", "editor", "moderator" → cosa puoi fare nel sistema.
`Position` è un titolo di business — "Field Technician", "Manager", "HR Specialist" → cosa sei nell'organizzazione.

Role viene da User ed è globale.
Position è specifico di Employee ed è vincolato al contesto (dipendente X è Field Tech nella branch Roma, ma potrebbe essere diverso se trasferito). Spesso una Position comporta default Role assignment, ma non è deterministica.

## POLITICA

### Employment Lifecycle

```
[ONBOARDING] → [ACTIVE] ↔ [INACTIVE] ↔ [ON_LEAVE] ↔ [SUSPENDED]
                                                      ↓
                                               [TERMINATED]
```

- **ACTIVE**: Dipendente a tempo pieno che lavora normalmente.
- **INACTIVE**: Sospensione temporanea (congedo non pagato, aspettativa).
- **ON_LEAVE**: Assente autorizzato (ferie, malattia, permessi).
- **SUSPENDED**: Sospensione disciplinare. HR/Manager lo ha tolto dalla rotazione ma mantiene contratto.
- **TERMINATED**: Stato finale irreversibile. Licenziamento, dimissioni, fine contratto. WorkHour smette.

Ogni transizione deve passare per:
1. Validazione delle regole (es: non puoi suspendere un TERMINATED)
2. Audit trail (chi ha fatto cosa, quando, perché)
3. Event dispatch (trigger notifiche, business logic)

### Assenza e Ferie

`AbsenceRequest` è il modello per dichiarare assenze, malattia, permessi.
- Stato PENDING by default (manager approval required).
- Manager approva/rifiuta.
- Una volta APPROVED, l'Employee entra in stato ON_LEAVE per quelle date.
- La copertura WorkHour è bloccata per quelle date (non puoi timbrare quando sei in ferie).

Configurazione:
- `EMPLOYEE_ANNUAL_LEAVE_DAYS`: Giorni di ferie/anno (default 22).
- `EMPLOYEE_SICK_LEAVE_DAYS`: Giorni di malattia (default 10).
- `EMPLOYEE_LEAVE_APPROVAL_ENABLED`: Workflow approvazioni attivo?

### Document Management

Ogni Employee ha un archivio (`documents` JSON array + media library):
- Contratto firmato
- Attestato di assunzione
- Certificato medico
- CV, diplomi, certificazioni
- Evaluation form
- Separation agreement

Storage su disco configurabile (`EMPLOYEE_DOCUMENTS_DISK`), max size per file (`EMPLOYEE_MAX_FILE_SIZE`), retention automatica (`EMPLOYEE_DOCUMENT_RETENTION_YEARS`).

Sensibilità: I documenti contengono PII e salari. Sempre encryptati if `EMPLOYEE_ENCRYPT_DATA=true`. Accesso soggetto a RLS (Row Level Security) — solo manager, HR, employee stesso.

## SCOPO

Employee module serves **FixCity Field Operations**:

1. **Workforce Planning**: Quanti tecnici disponibili? Chi ha competenze X? Quali sono in ferie?
2. **Time Accountability**: Quali ore lavorate, dove, quando? Straordinari autorizzati?
3. **Compliance & Legality**: Documentazione contrattuale, consensi GDPR, audit trail per ispezioni.
4. **Cost Control**: Payroll integration, overtime cost, leave balance tracking.
5. **Shift Scheduling**: (Future) Assegnazione dinamica di turni in base a disponibilità e location.

Nel contesto di FixCity, un dipendente è geograficamente disperso — lavora in sites diversi, a volte senza ufficio fisso. WorkHour traccia location (lat/lng, name) e device info (phone type, app version) per validare presenze real-time.

## ZEN

Tre verità:

1. **Identità umana, identità digitale, identità organizzativa sono cose diverse**. User è digitale. Employee è organizzativa. Separarle=modularità.

2. **Il tempo è moneta di scambio tra dipendente e azienda**. Ogni ora timbrataè una promessa di compenso. La sequenza di clock_in/clock_out/break è un contratto micro-transazionale che non può essere rotta.

3. **La storia è legge**. Non sovrascrivi. Registri. Se sbagli a timbrare, crei un correction record, non una modifica. Audit trail è il codice morale del sistema.

## LIBRERIE DA INSTALLARE

Nessuna libreria HR esterna attualmente. Il modulo è autosufficiente:
- `spatie/laravel-permission` (User roles/permissions, già in stack).
- `spatie/laravel-model-states` (State transitions) — già in stack.
- `spatie/laravel-media-library` (Document management) — già in stack.

**Future candidate libraries** se volessi estendere:
- `maatwebsite/excel` per import/export bulk employee data.
- `barryvdh/laravel-dompdf` per generare contratti/slips.
- `intervention/image` per photo thumbnail caching.
- `google/apiclient` se integri Google Calendar (leave management).

Per payroll, dipende dal provider. EMPLOYEE_PAYROLL_INTEGRATION consente plugin architettura.

## FUTURE IMPLEMENTAZIONI

### Shift Management (P0)
Oggi: assegnazione manuale di dipendenti a progetti/locations.
Domani: motore di scheduling che rispetta:
- Skill matrix (chi sa fare cosa)
- Availability (in ON_LEAVE? unavailable)
- Workload balancing (non sovraccaricare uno)
- Location proximity (minimizza travel time)

Model: `Shift` (assegnazione dipendente a job per data/ora), `ShiftSwap` (dipendenti si scambiano turni), `ShiftCoverage` (chi copre call-out).

### Performance Review (P1)
Modello `PerformanceReview` (probationary, quarterly, annual).
- Manager compila scorecard (skill vs expected level).
- HR analizza trend nel tempo.
- Trigger: aumento, promozione, PIP (performance improvement plan), licenziamento.
- Integration con valori WorkHour: assenze frequenti, straordinari costanti, etc.

### Predictive Analytics (P2)
- Churn risk: Chi è probabile si dimetta? (Machine learning su WorkHour, leave pattern, manager feedback).
- Workforce composition: Gap skills per 6 mesi?
- Cost optimization: Dove ridurre/assumere?

### Mobile App Time Tracking (P1)
Oggi: Web timbrature. Domani: app mobile che:
- Offre push notification "clock in/out" reminder.
- Cattura foto proof (face recognition, location foto).
- Funziona offline e sincronizza quando online.
- Integra geofencing per siti specifici.

### Payroll Engine (P1)
Calcolo stipendi basato su:
- Hours lavorato (WorkHour).
- Rate per tipo (standard, overtime, on-call).
- Deductions (tasse, contributi).
- Bonuses/penalties.
- Export per commercialista.

## COMPETITORS & INSPIRATIONS

### Competitors
- **Workday**: Enterprise HR beast (overkill per FixCity ora, but visionary).
- **BambooHR**: SaaS leggero, onboarding fluido, leave management intuitivo.
- **Guidepoint (formerly Domo)**: Analytics-first, compliance focus.

### Inspirations in Codebase
- **FixCity's own Gdpr module**: Best practice per PII encryption, consent flow, data retention. Employee copia lo stile.
- **User module's STI pattern**: Perché Employee estende User? Vedi User/Admin pattern già presente.
- **Activity module's audit trail**: EmployeeStatusHistory è ispirata da Activity log — event-sourced immutability.
- **Media module**: Document storage è powered da spatie/media-library, non reinventar ruota.

## BEST PRACTICES

### 1. Sempre transazionale
```php
DB::transaction(function () {
    $employee->update(['status' => EmployeeStatusEnum::ACTIVE]);
    EmployeeStatusHistory::create([...]);
    event(new EmployeeStatusChanged($employee, ...));
});
```
Non aggiornare Employee senza creare audit record.

### 2. Query relazioni con eager loading
```php
// ❌ BAD: N+1 query
$employees->each(fn($e) => $e->manager->name);

// ✅ GOOD
$employees->load('manager')->each(fn($e) => $e->manager->name);
```

### 3. Validare sequence prima di creare WorkHour
```php
$expectedType = WorkHour::getNextAction($employeeId, $date);
if ($requestedType !== $expectedType) {
    throw new InvalidTimeSequenceException(...);
}
```
Non permetti sequenze invalide. La state machine è salva.

### 4. Usare enums per status, type, standi
```php
// ✅ GOOD
$employee->status = EmployeeStatusEnum::ACTIVE;

// ❌ BAD
$employee->status = 'active'; // String magic — non type-safe
```

### 5. Separare read (query) da write (action)
```php
// ✅ GOOD — Action for side effects
$action = new ApproveAbsenceRequestAction();
$action->execute($request, $approver);

// ✅ GOOD — Query for read-only data
$metrics = CalculateEmployeeMetricsAction::execute($employee, $start, $end);

// ❌ BAD — Mixing concerns
public function approveAndCalculate() { ... }
```

### 6. JSON array for flexible data
Usare JSONB per `personal_data`, `contact_data`, `work_data` consente:
- Evoluzione dello schema senza migration.
- Diversity di dipendenti (alcuni campi facoltativi).
- Query indexing su chiavi frequenti.

```php
'personal_data' => ['dob' => '1990-01-01', 'citizenship' => 'IT', 'ssn' => '...encrypted...']
'contact_data' => ['phone' => '...', 'address' => '...']
'work_data' => ['hire_date' => '2020-01-01', 'contract_type' => 'FT']
'salary_data' => ['base_salary' => 50000, 'bonus_pct' => 0.05] // Sempre encrypted
```

### 7. Usar location data dall'app
WorkHour cattura:
- `location_lat`, `location_lng` (coordinate GPS).
- `location_name` (reverse geocoding, cached).
- `device_info` (app version, OS, device ID per fraud detection).

Usa questi per validare "è realmente in cantiere X?".

### 8. Time rounding per usability
Molti sistemi arrotondano entrata/uscita a multipli di 15 min. Configurabile:
```env
EMPLOYEE_TIME_ROUNDING_ENABLED=true
EMPLOYEE_TIME_ROUNDING_MINUTES=15
```
Protegge da clock disputes ("ero entrato alle 08:47, non 08:50").

### 9. Documentazione contrattuale è immutable
Una volta firmato contratto, mai cancellarlo o editarlo. Crea version:
```php
$contract = Contract::create(['employee_id' => ..., 'pdf_path' => '...', 'signed_at' => now()]);
// Più tardi: nuovo contratto (promozione, salary increase)
$newContract = Contract::create(['employee_id' => ..., 'pdf_path' => '...', 'parent_id' => $contract->id]);
```

## BAD PRACTICES

### ❌ 1. Modificare status direttamente
```php
$employee->status = 'terminated'; // WRONG
$employee->save();
```
Nessun audit trail. Nessun validation. Nessun event. Disaster.

Corretto:
```php
$action = new TerminateEmployeeAction();
$action->execute($employee, $reason, $actor);
```

### ❌ 2. Permessi non RLS-backed
```php
// ❌ WRONG — Trust client
if ($request->user()->is_admin) { ... }

// ✅ GOOD — RLS policy in database
// Only HR, manager, o employee stesso possono leggere record
// Database enforces questo via RLS, non codice.
$employees = Employee::where('manager_id', auth()->id())
    ->orWhere('id', auth()->id())
    ->get();
```

### ❌ 3. Criptare salari nel codebase
```php
// ❌ WRONG
'salary' => 50000 // Plain in database, visibile a chiunque query

// ✅ GOOD
'salary_data' => encrypt(['base' => 50000, 'currency' => 'EUR'])
// + RLS: solo HR, dipendente stesso, manager (maybe) possono leggere
```

### ❌ 4. Fixture hardcoded per test
```php
// ❌ WRONG
$employee = Employee::create(['name' => 'John', 'email' => 'john@test.local']);

// ✅ GOOD
$employee = Employee::factory()->create();
```
Factory rispetta tutte le relazioni, casts, constraints.

### ❌ 5. Ignoring device_info
```php
// ❌ WRONG — Accetti qualsiasi device, qualsiasi location
WorkHour::create(['employee_id' => ..., 'timestamp' => now()]);

// ✅ GOOD
// Valida location vs allowlist, device vs known devices
$this->validateDevice($deviceInfo);
$this->validateLocation($lat, $lng);
WorkHour::create([...]);
```

### ❌ 6. N+1 query su manager chain
```php
// ❌ WRONG
$employee->manager->manager->manager->name // 3 queries

// ✅ GOOD
$employee->load('manager.manager.manager')->manager->manager->manager->name
// Oppure caccia tutto in getReportingChain() che eager load una sola volta
```

### ❌ 7. Trusting client timestamp
```php
// ❌ WRONG
$timestamp = $request->input('timestamp');
WorkHour::create(['timestamp' => $timestamp]);

// ✅ GOOD
$timestamp = $request->input('timestamp');
$now = now();
// Tolera skew max 5 min (orologio fuori sync)
if ($timestamp->diffInMinutes($now) > 5) {
    throw new InvalidTimestampException(...);
}
WorkHour::create(['timestamp' => $timestamp]);
```

## FALSE FRIENDS

### Trappola #1: Employee come shortcut per User
Non fare:
```php
// ❌ WRONG — Employee non è User alias
$employees = User::where('type', 'employee')->get(); // Technically works via STI, but confusing

// ✅ GOOD
$employees = Employee::all(); // Explicit, clear intent
```

### Trappola #2: Status come stringa senza enum
```php
// ❌ WRONG
if ($employee->status === 'active') // String comparison, typo-prone

// ✅ GOOD
if ($employee->status === EmployeeStatusEnum::ACTIVE) // Type-safe
```

### Trappola #3: Department vs manager = same thing
```php
// ❌ WRONG — Confondere hierarchies
$employee->department->manager; // Maybe right, but ambiguous

// ✅ CLEAR
$directManager = $employee->manager;
$departmentHead = $employee->department->manager;
```

### Trappola #4: WorkHour come simple log
WorkHour non è un log di debug. Ogni record è una micro-contract che influisce payroll, compliance, analytics. Trattalo come immutable (insertion only), mai delete senza audit.

### Trappola #5: Ignoring leave balance
```php
// ❌ WRONG
$leave = AbsenceRequest::create([...]);
// Non verifica se employee ha giorni disponibili

// ✅ GOOD
if ($employee->getRemainingLeafDays() < $request->days) {
    throw new InsufficientLeaveBalanceException(...);
}
AbsenceRequest::create([...]);
```

### Trappola #6: Editing old WorkHour entries
WorkHour sono immutable post-creation. Se sbagli a timbrare, crei correction entry, non edit.
```php
// ❌ WRONG
$workHour->timestamp = now();
$workHour->save(); // Changes history

// ✅ GOOD
// Crea nuovo record con note: "Correction for 2025-01-06 08:00"
// Original rimane immutato, per audit trail
WorkHour::create([
    'employee_id' => ...,
    'type' => WorkHourTypeEnum::CLOCK_IN,
    'timestamp' => Carbon::parse('2025-01-06 08:00'),
    'notes' => 'Correction (original time was 07:55)',
    'status' => WorkHourStatusEnum::PENDING, // Needs approval
]);
```

## COME USARLO

### Scenario 1: Assumere un Nuovo Dipendente

```php
use Modules\Employee\Actions\CreateEmployeeWithOnboardingAction;

$action = new CreateEmployeeWithOnboardingAction();
$employee = $action->execute(
    data: [
        'name' => 'Marco Rossi',
        'email' => 'marco@fixcity.local',
        'department_id' => 3, // Engineering
        'manager_id' => 1,    // CTO
        'position_id' => 5,   // Senior Field Tech
        'hire_date' => now(),
    ],
    creator: auth()->user()
);

// Risultato: Employee creato, User generato, onboarding tasks pendenti
```

### Scenario 2: Timbrare Entrata

```php
use Modules\Employee\Models\WorkHour;
use Modules\Employee\Enums\WorkHourTypeEnum;

$expectedType = WorkHour::getNextAction(auth()->id(), now());
if ($expectedType !== WorkHourTypeEnum::CLOCK_IN) {
    abort(422, "Expected {$expectedType->value}, not clock_in");
}

$workHour = WorkHour::create([
    'employee_id' => auth()->id(),
    'type' => WorkHourTypeEnum::CLOCK_IN,
    'timestamp' => now(),
    'location_lat' => $request->lat,
    'location_lng' => $request->lng,
    'location_name' => 'Site Roma, Via X',
    'device_info' => [
        'app_version' => '1.2.3',
        'os' => 'iOS',
        'device_id' => $request->device_id,
    ],
    'status' => WorkHourStatusEnum::PENDING, // Requires HR approval
]);

// Automatically dispatch WorkHourCreated event (notifications, etc.)
```

### Scenario 3: Richiedere Ferie

```php
use Modules\Employee\Models\AbsenceRequest;

$absence = AbsenceRequest::create([
    'employee_id' => auth()->id(),
    'type' => 'annual_leave',
    'start_date' => now()->addDays(7),
    'end_date' => now()->addDays(10),
    'reason' => 'Vacation',
    'status' => 'pending', // Manager must approve
]);

// Event dispatched, manager gets notified
```

### Scenario 4: Approvare Ferie come Manager

```php
use Modules\Employee\Actions\ApproveAbsenceRequestAction;

$action = new ApproveAbsenceRequestAction();
$action->execute(
    request: $absence,
    approver: auth()->user(),
    reason: 'Approved'
);

// Resultado: AbsenceRequest → APPROVED
// Employee status → ON_LEAVE for those dates
// WorkHour blocked for those dates (cannot clock in/out)
```

### Scenario 5: Cercare Straordinari in Dipartimento

```php
$department = Department::find(3);
$employees = $department->allEmployees()->load('workHours');

$overtimeMap = $employees->mapWithKeys(function ($employee) {
    $overtimeHours = $employee->workHours()
        ->whereBetween('timestamp', [now()->startOfMonth(), now()->endOfMonth()])
        ->get()
        ->groupBy('timestamp->date') // Group by day
        ->map(function ($dayEntries) {
            $totalHours = $this->calculateDayHours($dayEntries);
            return max(0, $totalHours - 8); // Anything over 8 = OT
        })
        ->sum();
    
    return [$employee->id => $overtimeHours];
});

// $overtimeMap = [1 => 5.5, 2 => 0, 3 => 12.25] (hours)
```

### Scenario 6: Visualizzare Timeline Giornaliera

```php
use Modules\Employee\Actions\BuildTimelineVisualizationAction;

$action = new BuildTimelineVisualizationAction();
$timeline = $action->execute(
    employee: $employee,
    date: now(),
);

// Risultato: [{
//   type: 'CLOCK_IN', 
//   timestamp: '08:00', 
//   location: 'Site Roma',
//   status: 'approved',
// }, ...]
```

## COME INSTALLARLO

### Setup Modulo

1. **Modulo già in stack**: Employee è uno dei moduli core in FixCity. Non occorre installazione separata.

2. **Database migrations**:
   ```bash
   php artisan migrate
   ```
   Crea tabelle: `users` (STI base), `work_hours`, `departments`, `positions`, `absence_requests`, etc.

3. **Seeders (opzionale, dev only)**:
   ```bash
   php artisan db:seed EmployeeSeeder
   ```
   Popola dati fake per testing.

4. **Configurazione .env**:
   ```env
   EMPLOYEE_DEFAULT_HOURS_PER_DAY=8
   EMPLOYEE_ANNUAL_LEAVE_DAYS=22
   EMPLOYEE_ENCRYPT_DATA=true
   EMPLOYEE_AUDIT_TRAIL=true
   EMPLOYEE_GDPR_COMPLIANCE=true
   ```

5. **Filament admin panel**:
   Employee module espone risorse in `/admin`:
   - `/admin/employees` (CRUD, bulk actions, exports)
   - `/admin/work-hours` (Timeline, approval workflow)
   - `/admin/absence-requests` (Leave management)
   - `/admin/departments` (Org structure)

6. **API routes**:
   ```
   POST /api/employee/work-hours — Clock in/out
   GET /api/employee/work-hours — List my hours
   POST /api/employee/absence-requests — Request leave
   GET /api/employee/me — Current employee data
   ```
   Tutte protected con Sanctum auth.

7. **Permissions seedata**:
   ```bash
   php artisan module:seed Employee PermissionSeeder
   ```
   Assegna permessi a ruoli (hr, manager, employee, admin).

### Personalizzazione

Se vuoi estendere Employee:

1. **Aggiungi colonne custom**:
   ```php
   // Database migration
   Schema::table('users', function (Blueprint $table) {
       $table->string('employee_badge_id')->nullable(); // RFID, ID card
   });
   ```

2. **Override model**:
   ```php
   // app/Models/Employee.php in tuo progetto
   namespace App\Models;
   use Modules\Employee\Models\Employee as BaseEmployee;
   
   class Employee extends BaseEmployee {
       protected $fillable = [
           ...parent::$fillable,
           'badge_id',
       ];
   }
   ```

3. **Custom Actions**:
   ```php
   // app/Actions/YourCustomAction.php
   class YourCustomAction {
       use QueueableAction;
       
       public function execute(Employee $employee, ...): void {
           // Your logic
       }
   }
   ```

4. **Custom Filament Resources**:
   ```php
   // app/Filament/Resources/EmployeeResource.php extends base resource
   ```

## COVERAGE ANALYSIS

### Implemented (Produzione Pronta)

| Feature | Model | Status | Notes |
|---------|-------|--------|-------|
| Employee CRUD | Employee | ✅ | Filament Resource completo |
| User ↔ Employee relation | STI | ✅ | HasParent trait |
| Manager hierarchy | manager_id self-FK | ✅ | Recursion proof |
| Department structure | department_id FK | ✅ | Parent-child nesting |
| Position catalog | Position model | ✅ | Job title management |
| WorkHour tracking | WorkHour model | ✅ | Clock in/out sequencing |
| Work/Break state machine | Enums (Type, Status) | ✅ | Validation engine in place |
| Absence requests | AbsenceRequest model | ✅ | Approval workflow |
| Leave balance | Calculated property | ✅ | Annual/sick/personal tracking |
| Document storage | JSON + MediaLibrary | ✅ | Contracts, certs, PDFs |
| Audit trail | EmployeeStatusHistory | ✅ | Who/what/when/why logging |
| Status transitions | Enum state machine | ✅ | ACTIVE → INACTIVE → TERMINATED |
| Reporting & export | ExportTimeDataAction | ✅ | PDF, Excel, CSV |
| Time timeline viz | BuildTimelineVisualizationAction | ✅ | Daily sessions, breaks |
| Weekly timetable | BuildWeeklyTimeTableAction | ✅ | Calendar view |
| Analytics (metrics) | CalculateEmployeeMetricsAction | ✅ | Attendance, punctuality, OT |
| Notifications | Event listeners | ✅ | Status changed, leave approved, etc. |
| RLS (Row Level Security) | PostgreSQL policies | ✅ | Employee owns their data |
| GDPR compliance | HasGdpr trait | ✅ | Consent tracking, data deletion |
| Encryption | Model casts | ✅ | salary_data, SSN, PII |

### Partial / In Progress

| Feature | Model | Status | Notes |
|---------|-------|--------|-------|
| Payroll integration | Config flag | 🟡 | Hook ready, no processor yet |
| Mobile time tracking | WorkHour | 🟡 | Web API ready, no native app |
| Shift scheduling | Not started | 🟡 | Design approved, code pending |
| Performance reviews | Not started | 🟡 | Spec written, no model yet |
| Predictive churn | Not started | 🟡 | ML experiment, not prod |
| Geofencing validation | device_info | 🟡 | Captured, not enforced |
| GPS accuracy check | WorkHour | 🟡 | Config exists, validation optional |

### Not Planned / Out of Scope

| Feature | Reason |
|---------|--------|
| Benefits/401k | Finance domain, separate module |
| Training/LMS | Learning domain, separate module |
| Recruiting | Hiring domain, not HR operations |
| Org chart 3D viz | Visualization nice-to-have, not core |
| Compensation benchmarking | Market data integration too broad |

### Tech Debt

- **TimeEntry model**: Duplicate? Consider merging with WorkHour.
- **Photo storage**: Inline app or external S3? Currently local.
- **Notification channels**: Broadcast missing, SMS missing (add if mobile.io grows).
- **Cache invalidation**: Tag-based cache done, but seldom tested.

---

**Document Version**: 1.0  
**Date**: 2025-09-06  
**Author**: Employee Module Philosophers  
**Status**: Vision + Theology Complete. Implementation 85%. Production-ready core.
