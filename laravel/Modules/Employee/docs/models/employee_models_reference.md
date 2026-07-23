# Employee Module - Models Reference

## 🚨 MODELLI CORRETTI DA UTILIZZARE

**IMPORTANTE**: Per la gestione delle timbrature, lo standard del modulo Employee è `WorkHour`.

`TimeEntry` esiste nel codebase come modello **legacy** (retrocompatibilità) e non deve essere usato per nuovo sviluppo.

## ✅ Modelli Esistenti

### 1. WorkHour (Modello Principale per Timbrature)

**Path**: `Modules\Employee\Models\WorkHour`
**Tabella**: `work_hours`

```php
use Modules\Employee\Models\WorkHour;
use Modules\Employee\Enums\WorkHourTypeEnum;
use Modules\Employee\Enums\WorkHourStatusEnum;

// Creazione timbratura
$workHour = WorkHour::create([
    'employee_id' => $userId,
    'type' => WorkHourTypeEnum::CLOCK_IN,
    'timestamp' => now(),
    'status' => WorkHourStatusEnum::PENDING,
    'notes' => 'Timbratura da widget',
]);

// Query ottimizzate con scope
$todayEntries = WorkHour::getTodayEntries($employeeId);
$lastEntry = WorkHour::getLastEntryForEmployee($employeeId);
$workedHours = WorkHour::calculateWorkedHours($employeeId);
$currentStatus = WorkHour::getCurrentStatus($employeeId);
```

#### Proprietà Principali

- `employee_id`: ID dipendente ⚠️ NON `user_id`
- `type`: Tipo timbratura (`WorkHourTypeEnum`)
- `timestamp`: Data/ora timbratura ⚠️ NON `start_time`
- `status`: Stato approvazione (`WorkHourStatusEnum`)
- `notes`: Note opzionali
- `location_lat/lng`: Coordinate GPS
- `device_info`: Info dispositivo (JSON)
- `photo_path`: Percorso foto

**⚠️ ERRORI COMUNI DA EVITARE:**

```php
// ❌ PROPRIETÀ INESISTENTI - NON USARE
$workHour->start_time    // Non esiste - usa 'timestamp'
$workHour->hours_worked  // Non esiste - usa WorkHour::calculateWorkedHours()
$workHour->user_id       // Non esiste - usa 'employee_id'

// ✅ PROPRIETÀ CORRETTE - USA SEMPRE QUESTE
$workHour->timestamp     // ✅ Per data/ora
$workHour->employee_id   // ✅ Per ID dipendente
WorkHour::calculateWorkedHours($employeeId, $date) // ✅ Per calcolo ore

// ❌ QUERY ERRATE
WorkHour::where('user_id', $id)        // Non esiste 'user_id'
WorkHour::where('start_time', $date)   // Non esiste 'start_time'  
WorkHour::sum('hours_worked')          // Non esiste 'hours_worked'

// ✅ QUERY CORRETTE
WorkHour::where('employee_id', $id)    // ✅ Corretto
WorkHour::whereBetween('timestamp', [$start, $end]) // ✅ Corretto
WorkHour::calculateWorkedHours($id, $date)          // ✅ Per totale ore
```

### 2. Employee

**Path**: `Modules\Employee\Models\Employee`

```php
use Modules\Employee\Models\Employee;

$employee = Employee::find($id);
$workHours = $employee->workHours(); // Relazione
```

### 3. User

**Path**: `Modules\Employee\Models\User`

### 4. Department  

**Path**: `Modules\Employee\Models\Department`

### 5. Position

**Path**: `Modules\Employee\Models\Position`

## 🚫 Modelli NON Esistenti

❌ **TimeRecord** - NON ESISTE, usare `WorkHour`
❌ **Attendance** - NON ESISTE, usare `WorkHour`

## ⚠️ Modelli Legacy (da non usare per nuovo sviluppo)

⚠️ **TimeEntry** - modello legacy: usalo solo se devi mantenere retrocompatibilità di codice esistente. Per tutto il nuovo codice usare `WorkHour`.
❌ **TimeRecord** - NON ESISTE, usare `WorkHour`  
❌ **Attendance** - NON ESISTE, usare `WorkHour`

## 📋 Enum Corretti

### WorkHourTypeEnum

```php
use Modules\Employee\Enums\WorkHourTypeEnum;

WorkHourTypeEnum::CLOCK_IN      // 'clock_in'
WorkHourTypeEnum::CLOCK_OUT     // 'clock_out'  
WorkHourTypeEnum::BREAK_START   // 'break_start'
WorkHourTypeEnum::BREAK_END     // 'break_end'
```

### WorkHourStatusEnum  

```php
use Modules\Employee\Enums\WorkHourStatusEnum;

WorkHourStatusEnum::PENDING    // 'pending'
WorkHourStatusEnum::APPROVED   // 'approved'
WorkHourStatusEnum::REJECTED   // 'rejected'
```

## 🎯 Esempi Pratici

### Widget TimeClockWidget (CORRETTO)

```php
use Modules\Employee\Models\WorkHour;
use Modules\Employee\Enums\WorkHourTypeEnum;
use Modules\Employee\Enums\WorkHourStatusEnum;

class TimeClockWidget extends XotBaseWidget
{
    private function loadTodayEntries(int $userId): void
    {
        // ✅ CORRETTO - usa WorkHour
        $entries = WorkHour::getTodayEntries($userId);
        
        $this->todayEntries = $entries->map(function (WorkHour $entry): array {
            return [
                'time' => $entry->timestamp->format('H:i'),
                'type' => $entry->type->value,
                'status' => $entry->status->value,
            ];
        })->values()->all();
    }

    private function createWorkHour(WorkHourTypeEnum $type, string $message): void
    {
        // ✅ CORRETTO - usa WorkHour::create()
        WorkHour::create([
            'employee_id' => Auth::id(),
            'type' => $type,
            'timestamp' => now(),
            'status' => WorkHourStatusEnum::PENDING,
            'notes' => $message,
        ]);
    }
}
```

### Action Pattern (CORRETTO)

```php
use Spatie\QueueableAction\QueueableAction;
use Modules\Employee\Models\WorkHour;
use Modules\Employee\Enums\WorkHourTypeEnum;

class CreateWorkHourAction
{
    use QueueableAction;

    public function execute(int $employeeId, WorkHourTypeEnum $type): WorkHour
    {
        // ✅ CORRETTO - usa WorkHour model
        return WorkHour::create([
            'employee_id' => $employeeId,
            'type' => $type,
            'timestamp' => now(),
            'status' => WorkHourStatusEnum::PENDING,
        ]);
    }
}
```

## 🔄 Migration da TimeEntry a WorkHour

### PRIMA (❌ LEGACY)

```php
use Modules\Employee\Models\TimeEntry;          // ⚠️ LEGACY

// Nota: TimeEntryTypeEnum/TimeEntryStatusEnum non esistono nel modulo.

$entries = TimeEntry::where('employee_id', $userId)->get(); // ⚠️ LEGACY
```

### DOPO (✅ CORRETTO)

```php
use Modules\Employee\Models\WorkHour;           // ✅ CORRETTO
use Modules\Employee\Enums\WorkHourTypeEnum;    // ✅ CORRETTO
use Modules\Employee\Enums\WorkHourStatusEnum;  // ✅ CORRETTO

$entries = WorkHour::where('employee_id', $userId)->get(); // ✅ CORRETTO
```

## 📚 Metodi Utili WorkHour

### Metodi Statici

```php
// Ottieni entries di oggi per dipendente
WorkHour::getTodayEntries($employeeId);

// Ultima entry per dipendente
WorkHour::getLastEntryForEmployee($employeeId);

// Prossima azione attesa
WorkHour::getNextAction($employeeId);

// Valida se entry è valida
WorkHour::isValidNextEntry($employeeId, $type);

// Calcola ore lavorate
WorkHour::calculateWorkedHours($employeeId);

// Stato corrente
WorkHour::getCurrentStatus($employeeId);
```

### Scope Queries

```php
// Per dipendente specifico
WorkHour::forEmployee($employeeId)->get();

// Per tipo specifico  
WorkHour::ofType('clock_in')->get();

// Per data specifica
WorkHour::forDate(Carbon::today())->get();

// Solo oggi
WorkHour::today()->get();
```

### Attributi Formattati

```php
$workHour = WorkHour::find($id);

$workHour->formatted_time;      // "14:30:00"
$workHour->formatted_date;      // "03/09/2025"
$workHour->formatted_date_time; // "03/09/2025 14:30:00"
```

---

**RICORDA**: SEMPRE usare `WorkHour` per nuovo sviluppo; `TimeEntry` è legacy.
**RICORDA**: Utilizzare gli enum `WorkHourTypeEnum` e `WorkHourStatusEnum`.

---

*Documento aggiornato*: Settembre 2025
*Basato su*: Correzione TimeClockWidget.php
*Stato*: Validato e testato