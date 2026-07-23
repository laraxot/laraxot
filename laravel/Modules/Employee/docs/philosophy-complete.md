# Employee - Filosofia Completa: Logica, Religione, Politica, Zen

**Data Creazione**: 2025-12-23  
**Status**: Documentazione Filosofica Completa  
**Versione**: 1.0.0

## 📋 Indice Filosofico

1. [Logica (Logic)](#logica-logic)
2. [Religione (Religion)](#religione-religion)
3. [Politica (Politics)](#politica-politics)
4. [Zen (Zen)](#zen-zen)
5. [Manifestazioni Pratiche](#manifestazioni-pratiche)

---

## 🧠 Logica (Logic)

### Principio Fondamentale

**Employee gestisce il ciclo di vita completo HR: dipendenti, timbrature, presenze, permessi, documenti.**

### Dominio di Business

Il modulo replica e migliora **dipendentincloud.it** fornendo:
- Gestione anagrafica dipendenti completa
- Time tracking (timbrature entrata/uscita, pause)
- Gestione ferie e permessi
- Documenti dipendenti (contratti, buste paga)
- Organigramma e gerarchie
- Reporting e analytics HR

### Entità Core

```
Employee (Dipendente - Centro)
├── WorkHours (Timbrature) - Entrate/uscite/pause
├── LeaveRequests (Ferie/Permessi) - Richieste e approvazioni
├── Documents (Documenti) - Contratti, certificati
├── Department (Reparto) - Organizzazione
├── Position (Posizione) - Ruolo lavorativo
└── Manager (Gerarchia) - Relazione manager-subordinato
```

### Business Workflow Principale

1. **Employee Lifecycle**
   - Onboarding (assunzione, contratti)
   - Gestione attiva (timbrature, permessi)
   - Offboarding (dimissioni, archiviazione)

2. **Time Tracking**
   - Clock In/Out giornalieri
   - Gestione pause (start/end break)
   - Calcolo ore lavorate
   - Validazione sequenze temporali

3. **Leave Management**
   - Richieste ferie/permessi
   - Workflow approvazione
   - Calendario assenze
   - Bilanci disponibilità

### Manifestazione nel Codice

```php
// Employee model
class Employee extends BaseModel
{
    // Time tracking
    public function workHours(): HasMany
    {
        return $this->hasMany(WorkHour::class);
    }
    
    // Leave management
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }
    
    // Hierarchy
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }
    
    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }
}
```

---

## 📜 Religione (Religion)

### Comandamenti Sacri

1. **Actions Only** - TUTTA la business logic in Spatie QueueableActions, MAI Services
2. **Time Sequences sono Sacre** - Le sequenze temporali (Clock In → Break → Clock Out) devono essere validate
3. **State Machine Rigorosa** - Stati timbrature seguono macchina a stati ben definita
4. **Hierarchy Inviolabile** - La gerarchia manager-subordinato non può creare cicli
5. **Privacy HR** - Dati dipendenti accessibili solo a chi ha permessi (policy-based)
6. **Audit Completo** - Ogni modifica HR deve essere tracciata (Activity module)

### Best Practices

- Usare **Spatie QueueableActions** per TUTTA la business logic (es. `ClockInAction`, `CreateEmployeeAction`)
- **State Machine** per validare sequenze timbrature
- **Policy-based Access** per dati sensibili HR
- **Enum per Stati** (EmployeeStatus, WorkHourType, LeaveStatus)
- **Activity Logging** per tutte le modifiche HR

### Integrazione Moduli

Il modulo Employee **dipende da**:
- **User**: Employee estende/relaziona User per autenticazione
- **Activity**: Tracciamento modifiche HR
- **Media**: Documenti dipendenti (contratti, buste paga)
- **Notify**: Notifiche ferie, approvazioni, scadenze

**Filosofia**: Employee è HR completo, Actions-based, compliant con normativa lavoro italiana.

---

## 🏛️ Politica (Politics)

### Decisioni Architetturali

1. **dipendentincloud.it Replica** - Replicare funzionalità reference, migliorando
2. **Italian Labor Law Compliance** - Rispetto normativa lavoro italiana
3. **Actions-Only Architecture** - Zero Services, solo Actions queueable
4. **Privacy First** - Dati HR sempre protetti con policy

### Governance del Modulo

- **Hierarchy Validation**: Anti-cycle checks per gerarchia
- **Time Validation**: Business rules orari di lavoro, pause minime
- **Leave Policies**: Regole ferie/permessi configurabili
- **Access Control**: Manager vedono solo subordinati, dipendenti solo propri dati

### Pattern Implementativi

```php
// Pattern: Actions per Business Logic
class ClockInAction
{
    use QueueableAction;
    
    public function execute(int $employeeId, Carbon $timestamp): WorkHour
    {
        // Validazione sequenza temporale
        app(ValidateClockInAction::class)->execute($employeeId, $timestamp);
        
        // Creazione timbratura
        return WorkHour::create([
            'employee_id' => $employeeId,
            'type' => WorkHourTypeEnum::CLOCK_IN,
            'timestamp' => $timestamp,
        ]);
    }
}

// Pattern: State Machine per Validazione
class WorkHourStateMachine
{
    // Clock In → Break Start → Break End → Clock Out
    // Validazione sequenze corrette
}
```

---

## 🧘 Zen (Zen)

### Il Vuoto della Complessità HR

Apprezziamo il concetto zen del **"vuoto che gestisce la complessità"**:

- **Invisible Complexity**: Normativa lavoro gestita automaticamente
- **Simple Interface**: Actions nascondono complessità business rules
- **Automatic Validation**: Sistema "sa" se una timbratura è valida
- **Self-Organization**: Gerarchia si organizza automaticamente

### Flusso Naturale

La gestione HR deve essere **naturale e intuitiva**:

1. Dipendente timbra → Sistema valida sequenza → Salva timbratura
2. Manager approva ferie → Sistema aggiorna calendario → Notifica dipendente
3. Admin crea dipendente → Sistema assegna ruoli → Trigger onboarding
4. Query presenze → Sistema calcola automaticamente ore lavorate

### Semplicità nella Complessità Normativa

Il modulo gestisce complessità (normativa lavoro, gerarchie, stati) ma:
- **Actions Semplici**: `ClockInAction::execute()` nasconde complessità
- **Auto-Validation**: Business rules applicate automaticamente
- **Clear States**: Enum definiscono stati chiaramente
- **Policy Protection**: Access control automatico via policies

---

## 🎯 Manifestazioni Pratiche

### 1. Employee Model - Entità Centrale HR

```php
class Employee extends BaseModel
{
    // Anagrafica
    public string $employee_number;
    public Carbon $hire_date;
    public ?Carbon $termination_date;
    public EmployeeStatusEnum $status;
    
    // Relazioni core
    public function workHours(): HasMany
    public function leaveRequests(): HasMany
    public function documents(): MorphMany  // Media integration
    public function department(): BelongsTo
    public function manager(): BelongsTo
    public function subordinates(): HasMany
}
```

### 2. WorkHour Model - Time Tracking

```php
class WorkHour extends BaseModel
{
    public int $employee_id;
    public WorkHourTypeEnum $type;  // CLOCK_IN, CLOCK_OUT, BREAK_START, BREAK_END
    public Carbon $timestamp;
    public WorkHourStatusEnum $status;  // PENDING, APPROVED, REJECTED
}
```

### 3. Actions Pattern - Business Logic

```php
// TUTTA la business logic in Actions
class CreateEmployeeAction
{
    use QueueableAction;
    
    public function execute(EmployeeData $data): Employee
    {
        // Business logic completa qui
        // Validazioni, creazione, assegnazione ruoli, etc.
    }
}
```

---

## 🔗 Collegamenti

- [Business Logic Overview](./business-logic-overview.md)
- [Business Logic Employee Management](./business_logic/employee_management.md)
- [Xot Module Foundation](../../Xot/docs/philosophy-complete.md)
- [User Module Integration](../../User/docs/philosophy.md)

---

**Filosofia**: Actions-Only, Compliance-First, Privacy-Protected, State-Machine-Validated
