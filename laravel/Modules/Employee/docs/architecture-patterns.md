# Employee Module - Architecture Patterns Documentation

> **Documento generato**: 2024-09-03  
> **Versione**: 1.0  
> **Compliance**: Laraxot Philosophy, DRY+KISS+SOLID+Robust  

## Design Patterns Implementati

### 1. Single Table Inheritance (STI) con Parental

Il modulo Employee utilizza il pattern STI per estendere il modello User base:

```php
/**
 * Employee extends User tramite STI
 * - Condivide tabella 'users' 
 * - Utilizza discriminator column 'type'
 * - Trait Parental per gestione automatica
 */
class Employee extends User
{
    use HasParent;
    
    // Automaticamente filtra per type = 'employee'
    // Eredita tutte le funzionalità di User
    // Aggiunge comportamenti specifici dipendente
}
```

**Vantaggi**:
- Riutilizzo codice User esistente
- Queries ottimizzate (single table)
- Polimorfismo naturale
- Migrations condivise

### 2. Strategy Pattern per WorkHour Types

Ogni tipo di timbratura implementa comportamenti specifici:

```php
/**
 * Enum con Strategy Pattern integrato
 */
enum WorkHourTypeEnum: string
{
    case CLOCK_IN = 'clock_in';
    case CLOCK_OUT = 'clock_out';
    case BREAK_START = 'break_start';
    case BREAK_END = 'break_end';
    
    /**
     * Ogni enum case definisce la prossima azione valida
     */
    public function getNextAction(): WorkHourTypeEnum
    {
        return match($this) {
            self::CLOCK_IN => self::BREAK_START,
            self::BREAK_START => self::BREAK_END,
            self::BREAK_END => self::CLOCK_OUT,
            self::CLOCK_OUT => self::CLOCK_IN,
        };
    }
}
```

### 3. State Machine Pattern

Il sistema implementa una state machine per gestire gli stati delle sessioni:

```php
/**
 * Stati possibili della sessione dipendente:
 * 
 * not_clocked_in -> CLOCK_IN -> clocked_in
 * clocked_in -> BREAK_START -> on_break  
 * on_break -> BREAK_END -> clocked_in
 * clocked_in -> CLOCK_OUT -> clocked_out
 * clocked_out -> CLOCK_IN -> clocked_in
 */
public static function getCurrentStatus(int $employeeId, ?Carbon $date = null): string
{
    $lastEntry = static::getLastEntryForEmployee($employeeId, $date);
    
    if (!$lastEntry) {
        return 'not_clocked_in';
    }
    
    return match ($lastEntry->type) {
        WorkHourTypeEnum::CLOCK_IN => 'clocked_in',
        WorkHourTypeEnum::BREAK_START => 'on_break',
        WorkHourTypeEnum::BREAK_END => 'clocked_in', 
        WorkHourTypeEnum::CLOCK_OUT => 'clocked_out',
    };
}
```

### 4. Factory Pattern per Test Data

Factory complesse che generano sequenze realistiche:

```php
/**
 * WorkHourFactory implementa pattern per generazione
 * sequenze giornaliere complete e realistiche
 */
public function workDaySequence(Carbon $date, int $employeeId): array
{
    return [
        // 1. Clock In (8:00-9:30)
        $this->clockIn()->create(['timestamp' => $clockInTime]),
        
        // 2. Break Start (12:00-13:00) 
        $this->breakStart()->create(['timestamp' => $breakStartTime]),
        
        // 3. Break End (30-60 min dopo)
        $this->breakEnd()->create(['timestamp' => $breakEndTime]),
        
        // 4. Clock Out (17:00-19:00)
        $this->clockOut()->create(['timestamp' => $clockOutTime]),
    ];
}
```

### 5. Repository Pattern (Implicito via Eloquent)

I modelli fungono da repository con metodi business logic:

```php
/**
 * WorkHour model come repository con business methods
 */
class WorkHour extends BaseModel 
{
    // Query methods (repository-like)
    public static function getLastEntryForEmployee(int $employeeId, ?Carbon $date = null): ?WorkHour
    public static function getTodayEntries(int $employeeId, ?Carbon $date = null): Collection
    
    // Business logic methods
    public static function getNextAction(int $employeeId, ?Carbon $date = null): WorkHourTypeEnum
    public static function isValidNextEntry(int $employeeId, WorkHourTypeEnum $type): bool
    public static function calculateWorkedHours(int $employeeId, ?Carbon $date = null): float
    
    // State methods
    public static function getCurrentStatus(int $employeeId, ?Carbon $date = null): string
}
```

### 6. Observer Pattern (Laravel Events)

Eventi automatici per audit trail e notifiche:

```php
/**
 * Model Events per tracking automatico
 */
class WorkHour extends BaseModel
{
    protected static function booted()
    {
        // Auto-log ogni creazione timbratura
        static::created(function ($workHour) {
            // Audit trail automatico
            // Notifiche supervisore se necessario
            // Invalidazione cache statistiche
        });
        
        static::updated(function ($workHour) {
            // Tracking modifiche approvazioni
            // Notifiche dipendente su stato change
        });
    }
}
```

### 7. Template Method Pattern nei Widget

Widget base con template methods per customizzazione:

```php
/**
 * TimeClockWidget implementa template method
 */
abstract class TimeClockWidget extends XotBaseWidget
{
    // Template method
    public function render(): View
    {
        return view($this->getViewName(), [
            'currentTime' => $this->getCurrentTime(),
            'sessionStatus' => $this->getSessionStatus(), 
            'todayEntries' => $this->getTodayEntries(),
            'actionButton' => $this->getActionButton(),
        ]);
    }
    
    // Customizable methods
    protected function getSessionStatus(): string { /* implementazione */ }
    protected function getActionButton(): array { /* implementazione */ }
    protected function getTodayEntries(): Collection { /* implementazione */ }
}
```

## Principi SOLID Applicati

### 1. Single Responsibility Principle (SRP)

Ogni classe ha una responsabilità specifica:

- **WorkHour**: Gestione timbrature e calcoli orari
- **Employee**: Rappresentazione dipendente e relazioni
- **TimeClockWidget**: UI per timbrature real-time
- **WorkHourFactory**: Generazione dati test
- **WorkHourSeeder**: Popolazione database

### 2. Open/Closed Principle (OCP)

Classi aperte per estensione, chiuse per modifica:

```php
/**
 * WorkHour aperto per estensione via scopes e custom methods
 */
class WorkHour extends BaseModel
{
    // Core methods non modificabili
    
    // Extensible via scopes
    public function scopeForEmployee(Builder $query, int $employeeId): Builder
    public function scopeOfType(Builder $query, string $type): Builder
    
    // Extensible via custom accessors
    public function getFormattedTimeAttribute(): string
    public function getFormattedDateAttribute(): string
}
```

### 3. Liskov Substitution Principle (LSP)

Employee può sostituire User in ogni contesto:

```php
/**
 * Employee può essere usato ovunque User sia richiesto
 */
function processUser(User $user): void 
{
    // Funziona con Employee senza modifiche
}

function processEmployee(Employee $employee): void
{
    // Employee è-un User con funzionalità aggiuntive
    processUser($employee); // LSP rispettato
}
```

### 4. Interface Segregation Principle (ISP)

Interfacce specifiche per funzionalità specifiche:

```php
/**
 * Interfaces segregate per responsabilità
 */
interface TimeTrackable 
{
    public function clockIn(Carbon $timestamp): WorkHour;
    public function clockOut(Carbon $timestamp): WorkHour;
}

interface BreakManageable
{
    public function startBreak(Carbon $timestamp): WorkHour;
    public function endBreak(Carbon $timestamp): WorkHour;
}

interface HoursCalculable
{
    public function calculateWorkedHours(?Carbon $date = null): float;
    public function getTotalHoursForPeriod(Carbon $start, Carbon $end): float;
}
```

### 5. Dependency Inversion Principle (DIP)

Dipendenza da astrazioni, non da implementazioni concrete:

```php
/**
 * TimeClockWidget dipende da astrazioni
 */
class TimeClockWidget extends XotBaseWidget
{
    // Dipende da interface, non da implementazione concreta
    public function __construct(
        protected TimeTrackable $timeTracker,
        protected HoursCalculable $hoursCalculator
    ) {}
}
```

## DRY Principle Implementation

### 1. Eliminazione Duplicazione Codice

```php
/**
 * Prima: Duplicazione nei widget
 */
// TimeClockWidget
private function getCurrentEmployee() { /* duplicated logic */ }
private function createTimeEntry() { /* duplicated logic */ }

// TimbratureWidget  
private function getCurrentEmployee() { /* duplicated logic */ }
private function createTimeEntry() { /* duplicated logic */ }

/**
 * Dopo: Centralizzazione in trait/base class
 */
trait TimeTrackingLogic 
{
    protected function getCurrentEmployee(): Employee
    {
        return Employee::find(auth()->id());
    }
    
    protected function createTimeEntry(WorkHourTypeEnum $type): WorkHour
    {
        return WorkHour::create([
            'employee_id' => auth()->id(),
            'type' => $type,
            'timestamp' => now(),
        ]);
    }
}
```

### 2. Metodi Helper Centralizzati

```php
/**
 * WorkHour model centralizza business logic
 * evitando duplicazione in controller/widget
 */
class WorkHour extends BaseModel
{
    // Single point of truth per calcoli
    public static function calculateWorkedHours(int $employeeId, ?Carbon $date = null): float
    
    // Single point of truth per validazioni
    public static function isValidNextEntry(int $employeeId, WorkHourTypeEnum $type): bool
    
    // Single point of truth per status
    public static function getCurrentStatus(int $employeeId, ?Carbon $date = null): string
}
```

## KISS Principle Implementation

### 1. Semplificazione Logica Validazione

```php
/**
 * Prima: Validazione complessa e verbosa
 */
private function validateTimeEntry($userId, $type) 
{
    if (!$userId) throw new Exception("User ID required");
    if (!in_array($type, ['clock_in', 'clock_out'])) throw new Exception("Invalid type");
    
    $user = User::find($userId);
    if (!$user) throw new Exception("User not found");
    if (!$user instanceof Employee) throw new Exception("Not an employee");
    
    // ... più validazioni verbose
}

/**
 * Dopo: Logica semplificata e trust-based
 */
private function createTimeEntry(WorkHourTypeEnum $type): WorkHour
{
    // Trust: se è nel panel admin = è autenticato
    // Trust: Laravel valida enum automaticamente  
    // Trust: Foreign key constraint valida employee_id
    
    return WorkHour::create([
        'employee_id' => auth()->id(),
        'type' => $type,
        'timestamp' => now(),
    ]);
}
```

### 2. Semplificazione UI Logic

```php
/**
 * Widget logic semplificata
 */
public function getNextAction(): array
{
    $employeeId = auth()->id();
    $nextAction = WorkHour::getNextAction($employeeId);
    
    return [
        'type' => $nextAction,
        'label' => trans("employee::time_tracking.{$nextAction->value}"),
        'color' => $this->getActionColor($nextAction),
    ];
}
```

## Performance Patterns

### 1. Query Optimization

```php
/**
 * Eager loading per evitare N+1
 */
public function getTodayEntriesWithEmployee(): Collection
{
    return WorkHour::with(['employee', 'approvedBy'])
        ->whereDate('timestamp', Carbon::today())
        ->orderBy('timestamp', 'desc')
        ->get();
}

/**
 * Indexed queries per performance
 */
public function scopeOptimizedForEmployee(Builder $query, int $employeeId): Builder
{
    return $query->where('employee_id', $employeeId) // Uses index
        ->orderBy('timestamp', 'desc'); // Uses composite index
}
```

### 2. Caching Strategy

```php
/**
 * Cache per statistiche frequenti
 */
public static function getCachedWorkedHours(int $employeeId, Carbon $date): float
{
    $cacheKey = "worked_hours:{$employeeId}:{$date->format('Y-m-d')}";
    
    return cache()->remember($cacheKey, 300, function() use ($employeeId, $date) {
        return static::calculateWorkedHours($employeeId, $date);
    });
}
```

## Error Handling Patterns

### 1. Graceful Degradation

```php
/**
 * Widget graceful degradation
 */
public function getCurrentEmployee(): ?Employee
{
    try {
        return Employee::find(auth()->id());
    } catch (Exception $e) {
        // Log error ma non bloccare UI
        \Log::warning('Employee retrieval failed', ['error' => $e->getMessage()]);
        return null;
    }
}
```

### 2. Business Logic Validation

```php
/**
 * Validation centralizzata nel model
 */
public static function validateTimeEntry(int $employeeId, WorkHourTypeEnum $type): bool
{
    // Solo validazioni business logic essenziali
    return static::isValidNextEntry($employeeId, $type);
}
```

## Architectural Benefits

### 1. Maintainability
- Codice organizzato in patterns riconoscibili
- Responsabilità chiare e separate
- Business logic centralizzata

### 2. Testability  
- Ogni pattern è facilmente testabile
- Mock/stub interfaces per unit testing
- Factory per integration testing

### 3. Extensibility
- Nuovo tipi timbratura via enum extension
- Nuovi widget via template method override
- Nuove validazioni via strategy pattern

### 4. Performance
- Query ottimizzate via scope pattern
- Caching strategico per operazioni frequenti
- Lazy loading dove appropriato

---

*Documento allineato con Laraxot Philosophy e principi SOLID+DRY+KISS+Robust*
