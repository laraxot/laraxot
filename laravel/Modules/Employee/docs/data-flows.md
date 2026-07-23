# Employee Module - Data Flows Documentation

> **Documento generato**: 2024-09-03  
> **Versione**: 1.0  
> **Compliance**: Laraxot Philosophy  

## Workflow Principale: Time Tracking

### 1. Flusso Timbratura Standard

```mermaid
graph TD
    A[Employee Dashboard] --> B{Ultima Timbratura?}
    B -->|Nessuna| C[Mostra: Timbra Entrata]
    B -->|Clock In| D[Mostra: Inizia Pausa]
    B -->|Break Start| E[Mostra: Fine Pausa]
    B -->|Break End| F[Mostra: Timbra Uscita]
    B -->|Clock Out| C
    
    C --> G[Click Timbra Entrata]
    G --> H[WorkHour::create CLOCK_IN]
    H --> I[Validazione Sequence]
    I --> J[Salvataggio Database]
    J --> K[Notifica Success]
    K --> L[Refresh Widget]
    
    D --> M[Click Inizia Pausa]
    M --> N[WorkHour::create BREAK_START]
    N --> I
    
    E --> O[Click Fine Pausa]
    O --> P[WorkHour::create BREAK_END]
    P --> I
    
    F --> Q[Click Timbra Uscita]
    Q --> R[WorkHour::create CLOCK_OUT]
    R --> I
```

### 2. Flusso Validazione Business Logic

```mermaid
graph TD
    A[Richiesta Timbratura] --> B[WorkHour::getNextAction]
    B --> C{Azione Valida?}
    C -->|Sì| D[Crea WorkHour Record]
    C -->|No| E[Errore Validazione]
    
    D --> F[Salva in Database]
    F --> G[Trigger Model Events]
    G --> H[Update Cache Status]
    H --> I[Calcola Ore Lavorate]
    I --> J[Notifica UI Success]
    
    E --> K[Log Errore]
    K --> L[Notifica UI Error]
```

### 3. Flusso Calcolo Ore Lavorate

```mermaid
graph TD
    A[Richiesta Calcolo Ore] --> B[WorkHour::getTodayEntries]
    B --> C[Ordina per Timestamp]
    C --> D[Inizializza Contatori]
    D --> E{Scorri Entries}
    
    E --> F{Tipo Entry?}
    F -->|CLOCK_IN| G[Imposta Clock In Time]
    F -->|BREAK_START| H[Calcola Minuti + Imposta Break Start]
    F -->|BREAK_END| I[Resume Clock In Time]
    F -->|CLOCK_OUT| J[Calcola Minuti Finali]
    
    G --> E
    H --> E
    I --> E
    J --> K[Converti in Ore]
    K --> L[Arrotonda a 2 Decimali]
    L --> M[Return Ore Lavorate]
```

## Data Flow Patterns

### 1. Request-Response Pattern

```php
/**
 * Pattern standard per azioni timbratura
 */
[UI Widget] --> [Livewire Action] --> [Business Logic] --> [Database] --> [Cache Update] --> [UI Refresh]

// Esempio: Clock In Action
TimeClockWidget::clockIn() 
    -> WorkHour::create(['type' => CLOCK_IN]) 
    -> Database INSERT 
    -> Cache::forget('status_' . $employeeId)
    -> $this->refresh()
```

### 2. Event-Driven Pattern

```php
/**
 * Eventi automatici per audit trail
 */
WorkHour::created() --> [
    LogActivity::create(),
    NotifyManager::dispatch(),
    CacheInvalidation::handle(),
    StatisticsUpdate::queue()
]
```

### 3. State Synchronization Pattern

```php
/**
 * Sincronizzazione stato UI con database
 */
Database State <--> Cache Layer <--> Widget State <--> UI Display

// Polling automatico ogni 30 secondi
setInterval(() => {
    Livewire.emit('refresh-status');
}, 30000);
```

## Flussi di Integrazione

### 1. Employee Creation Flow

```mermaid
graph TD
    A[User Registration] --> B{User Type?}
    B -->|Employee| C[Create Employee Record]
    B -->|Other| D[Standard User Creation]
    
    C --> E[Set Employee Type in STI]
    E --> F[Assign Default Position]
    F --> G[Create Employee Profile]
    G --> H[Send Welcome Email]
    H --> I[Grant Employee Permissions]
    I --> J[Employee Dashboard Access]
```

### 2. Approval Workflow

```mermaid
graph TD
    A[Time Entry Created] --> B{Auto-Approve?}
    B -->|Yes| C[Set Status: Approved]
    B -->|No| D[Set Status: Pending]
    
    D --> E[Notify Manager]
    E --> F[Manager Review]
    F --> G{Manager Decision?}
    G -->|Approve| H[Set Status: Approved]
    G -->|Reject| I[Set Status: Rejected]
    
    H --> J[Notify Employee: Approved]
    I --> K[Notify Employee: Rejected]
    
    C --> L[Include in Payroll]
    J --> L
```

### 3. Reporting Data Flow

```mermaid
graph TD
    A[Report Request] --> B[Define Date Range]
    B --> C[Query WorkHour Records]
    C --> D[Group by Employee]
    D --> E[Calculate Hours per Day]
    E --> F[Apply Business Rules]
    F --> G[Generate Statistics]
    G --> H[Format for Export]
    H --> I[Cache Results]
    I --> J[Return to UI]
```

## Database Transaction Flows

### 1. Time Entry Transaction

```sql
BEGIN TRANSACTION;

-- 1. Validate previous entry
SELECT * FROM work_hours 
WHERE employee_id = ? 
ORDER BY timestamp DESC 
LIMIT 1;

-- 2. Insert new entry
INSERT INTO work_hours (
    employee_id, type, timestamp, 
    location_lat, location_lng, device_info
) VALUES (?, ?, ?, ?, ?, ?);

-- 3. Update cache table (if exists)
UPDATE employee_daily_stats 
SET last_action = ?, last_timestamp = ?
WHERE employee_id = ? AND date = ?;

COMMIT;
```

### 2. Daily Rollup Transaction

```sql
BEGIN TRANSACTION;

-- Aggregate daily hours
INSERT INTO daily_work_summaries (
    employee_id, work_date, 
    total_hours, total_breaks, 
    first_entry, last_entry
)
SELECT 
    employee_id,
    DATE(timestamp) as work_date,
    calculateWorkedHours(employee_id, DATE(timestamp)) as total_hours,
    COUNT(*) FILTER (WHERE type IN ('break_start', 'break_end')) / 2 as total_breaks,
    MIN(timestamp) as first_entry,
    MAX(timestamp) as last_entry
FROM work_hours
WHERE DATE(timestamp) = ?
GROUP BY employee_id, DATE(timestamp)
ON CONFLICT (employee_id, work_date) 
DO UPDATE SET 
    total_hours = EXCLUDED.total_hours,
    total_breaks = EXCLUDED.total_breaks,
    last_entry = EXCLUDED.last_entry;

COMMIT;
```

## Cache Data Flows

### 1. Multi-Level Caching Strategy

```php
/**
 * L1: Widget Cache (30 seconds)
 */
$widgetData = cache()->remember("widget_data_{$employeeId}", 30, function() {
    return [
        'currentStatus' => WorkHour::getCurrentStatus($employeeId),
        'todayHours' => WorkHour::getCachedWorkedHours($employeeId, today()),
        'todayEntries' => WorkHour::getTodayEntries($employeeId),
    ];
});

/**
 * L2: Daily Statistics Cache (5 minutes)
 */
$dailyStats = cache()->remember("daily_stats_{$employeeId}_{$date}", 300, function() {
    return WorkHour::calculateWorkedHours($employeeId, $date);
});

/**
 * L3: Monthly Reports Cache (1 hour)
 */
$monthlyReport = cache()->remember("monthly_report_{$employeeId}_{$month}", 3600, function() {
    return WorkHour::generateMonthlyReport($employeeId, $month);
});
```

### 2. Cache Invalidation Flow

```mermaid
graph TD
    A[WorkHour Created/Updated] --> B[Invalidate L1 Cache]
    B --> C[Invalidate Daily Stats]
    C --> D{End of Day?}
    D -->|Yes| E[Invalidate Monthly Cache]
    D -->|No| F[Keep Monthly Cache]
    
    E --> G[Trigger Daily Rollup]
    F --> H[End Process]
    G --> H
```

## Error Handling Flows

### 1. Validation Error Flow

```mermaid
graph TD
    A[Invalid Time Entry] --> B[Business Logic Validation]
    B --> C[Log Validation Error]
    C --> D[Return User-Friendly Message]
    D --> E[Highlight UI Field]
    E --> F[Suggest Correction]
    F --> G[Allow Retry]
```

### 2. System Error Flow

```mermaid
graph TD
    A[System Exception] --> B[Catch Exception]
    B --> C[Log Full Stack Trace]
    C --> D[Send Admin Alert]
    D --> E[Return Generic Error Message]
    E --> F[Graceful UI Degradation]
    F --> G[Offer Manual Fallback]
```

## Performance Optimization Flows

### 1. Query Optimization

```php
/**
 * Ottimizzazione query per dashboard
 */
// Before: N+1 Query Problem
foreach ($employees as $employee) {
    $employee->getTodayHours(); // Separate query each time
}

// After: Eager Loading + Bulk Calculation
$employees = Employee::with(['workHours' => function($query) {
    $query->whereDate('timestamp', today());
}])->get();

$employees->each(function($employee) {
    $employee->today_hours = $employee->workHours->calculateHours();
});
```

### 2. Background Job Flow

```mermaid
graph TD
    A[Daily Cron Job] --> B[Queue Daily Rollup Jobs]
    B --> C[Process Each Employee]
    C --> D[Calculate Daily Statistics]
    D --> E[Update Summary Tables]
    E --> F[Generate Reports if Needed]
    F --> G[Send Notifications]
    G --> H[Clean Old Cache]
```

## API Data Flows (Future)

### 1. Mobile App Integration

```mermaid
graph TD
    A[Mobile App Request] --> B[API Authentication]
    B --> C[Rate Limiting Check]
    C --> D[Business Logic Processing]
    D --> E[Database Operations]
    E --> F[Response Formatting]
    F --> G[Response Caching]
    G --> H[Return JSON Response]
```

### 2. External System Integration

```mermaid
graph TD
    A[Payroll System Request] --> B[API Key Validation]
    B --> C[Request Time Range]
    C --> D[Aggregate Work Hours]
    D --> E[Apply Business Rules]
    E --> F[Format for External System]
    F --> G[Log API Call]
    G --> H[Return Structured Data]
```

## Data Consistency Patterns

### 1. Eventually Consistent Cache

```php
/**
 * Pattern per eventual consistency
 */
// 1. Update database immediately
WorkHour::create($data);

// 2. Update cache asynchronously  
dispatch(new UpdateCacheJob($employeeId, $date));

// 3. UI shows stale data briefly, then refreshes
// Acceptable for non-critical display data
```

### 2. Strict Consistency for Business Logic

```php
/**
 * Pattern per strict consistency su validazioni
 */
DB::transaction(function() use ($employeeId, $type) {
    // 1. Lock per consistency
    $lastEntry = WorkHour::where('employee_id', $employeeId)
        ->lockForUpdate()
        ->latest('timestamp')
        ->first();
    
    // 2. Validate within transaction
    if (!WorkHour::isValidNextEntry($employeeId, $type, $lastEntry)) {
        throw new InvalidSequenceException();
    }
    
    // 3. Create entry atomically
    WorkHour::create([...]);
});
```

---

*Documento tecnico per sviluppatori - Allineato con Laraxot Architecture Patterns*
