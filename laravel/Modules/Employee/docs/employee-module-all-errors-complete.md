# Employee Module - Tutti gli Errori Identificati

## 🚨 ERRORI CRITICI (PRIORITÀ ASSOLUTA)

### 1. Database Schema Mismatch ⚠️⚠️⚠️

**PROBLEMA**: Migration e Model utilizzano nomi tabella completamente diversi
- **Migration**: `2025_08_27_121400_create_work_hours_table.php` → crea tabella `work_hours`
- **Model**: `WorkHour.php` linea 66 → utilizza tabella `time_entries`

```php
// ❌ ERRORE CRITICO - WorkHour.php
protected $table = 'time_entries';

// ✅ CORREZIONE
protected $table = 'work_hours';
```

**IMPATTO**: Modulo completamente inutilizzabile - tutte le query falliscono.

---

### 2. Single Table Inheritance (STI) Non Implementato Correttamente

**PROBLEMA**: Employee model estende User ma non implementa correttamente tighten/parental

```php
// ❌ ERRORE - Employee.php linea 37-39
class Employee extends User
{
    protected $table = 'users'; // ✅ Corretto per STI
    // ❌ MANCA: use HasParent;
}

// ✅ CONFRONTO CORRETTO - Admin.php linea 194-196
class Admin extends User
{
    use HasParent; // ✅ PRESENTE
}
```

**CORREZIONE**:
```php
// Employee.php
use Parental\HasParent;

class Employee extends User
{
    use HasParent; // ✅ AGGIUNGERE
    
    // ✅ Rimuovere protected $table = 'users'; 
    // Parental lo gestisce automaticamente
}
```

---

### 3. Query STI Errate nel TimeClockWidget

**PROBLEMA**: TimeClockWidget usa query non compatibili con STI

```php
// ❌ ERRORE - TimeClockWidget.php linee 103, 150, 215
$employee = Employee::where('user_id', $user->id)->first();
```

**PERCHÉ È SBAGLIATO**:
- Employee estende User (STI)
- Employee **È** un User con `type` specifico
- Non esiste campo `user_id` in Employee (è lo stesso record User)

**CORREZIONI POSSIBILI**:

```php
// ✅ OPZIONE 1: Query diretta se Employee ha type discriminator
$employee = Employee::where('id', $user->id)->first();

// ✅ OPZIONE 2: Cast User a Employee 
$employee = $user->is_employee ? $user->employee : null;

// ✅ OPZIONE 3: Scope specifico se implementato
$employee = Employee::forUser($user->id)->first();
```

---

## 🔧 ERRORI DI DESIGN E IMPLEMENTAZIONE

### 4. Uso Costanti invece di Enum

**PROBLEMA**: WorkHour.php usa costanti pubbliche invece di enum separati

```php
// ❌ PATTERN DEPRECATO - WorkHour.php linee 34-59
public const TYPE_CLOCK_IN = 'clock_in';
public const TYPE_CLOCK_OUT = 'clock_out';
public const TYPE_BREAK_START = 'break_start';
public const TYPE_BREAK_END = 'break_end';

public const STATUS_PENDING = 'pending';
public const STATUS_APPROVED = 'approved';  
public const STATUS_REJECTED = 'rejected';
```

**CORREZIONE**: Creare enum dedicati

```php
// ✅ NUOVO FILE: app/Enums/WorkHourTypeEnum.php
enum WorkHourTypeEnum: string
{
    case CLOCK_IN = 'clock_in';
    case CLOCK_OUT = 'clock_out';
    case BREAK_START = 'break_start';
    case BREAK_END = 'break_end';
    
    public function label(): string
    {
        return match($this) {
            self::CLOCK_IN => __('employee::enums.work_hour_type.clock_in'),
            self::CLOCK_OUT => __('employee::enums.work_hour_type.clock_out'),
            self::BREAK_START => __('employee::enums.work_hour_type.break_start'),
            self::BREAK_END => __('employee::enums.work_hour_type.break_end'),
        };
    }
}

// ✅ NUOVO FILE: app/Enums/WorkHourStatusEnum.php  
enum WorkHourStatusEnum: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    
    public function label(): string
    {
        return match($this) {
            self::PENDING => __('employee::enums.work_hour_status.pending'),
            self::APPROVED => __('employee::enums.work_hour_status.approved'), 
            self::REJECTED => __('employee::enums.work_hour_status.rejected'),
        };
    }
}
```

**AGGIORNAMENTO MODEL**:
```php
// WorkHour.php - Aggiornare casts
protected function casts(): array
{
    return [
        'timestamp' => 'datetime',
        'type' => WorkHourTypeEnum::class, // ✅ NUOVO
        'status' => WorkHourStatusEnum::class, // ✅ NUOVO
        // ... altri cast
    ];
}
```

---

## ⚠️ ERRORI DI CONFIGURAZIONE

### 5. Foreign Key Constraints (VERIFICA NECESSARIA)

**SITUAZIONE**: Migration usa foreign key constraints corretti per STI

```php
// ✅ CORRETTO - 2025_08_27_121400_create_work_hours_table.php
$table->foreignId('employee_id')
    ->constrained('users') // ✅ Corretto per STI
    ->onDelete('cascade');

$table->foreignId('approved_by')->nullable()
    ->constrained('users') // ✅ Corretto per STI 
    ->onDelete('set null');
```

**STATUS**: Questi constraint sono corretti per STI, ma dipendono dalla correzione del nome tabella.

---

### 6. Relazioni Model Non Ottimali

**PROBLEMA**: WorkHour.php relazioni potrebbero essere ottimizzate per STI

```php
// 🤔 ATTUALE - WorkHour.php linea 111-114
public function employee(): BelongsTo
{
    return $this->belongsTo(Employee::class, 'employee_id');
}

// ✅ POSSIBILE MIGLIORAMENTO per STI
public function employee(): BelongsTo
{
    return $this->belongsTo(Employee::class, 'employee_id');
}

// ✅ ALTERNATIVA: Se Employee è sempre User con type
public function user(): BelongsTo
{
    return $this->belongsTo(User::class, 'employee_id');
}
```

---

## 📋 PIANO CORREZIONE PRIORITIZZATO

### FASE 1: Errori Bloccanti (IMMEDIATO)

1. **Fix nome tabella** - WorkHour.php
   ```php
   // Linea 66: da 'time_entries' a 'work_hours'
   protected $table = 'work_hours';
   ```

2. **Implementare STI completo** - Employee.php
   ```php
   use Parental\HasParent;
   
   class Employee extends User
   {
       use HasParent;
       // Rimuovere: protected $table = 'users';
   }
   ```

3. **Fix query STI** - TimeClockWidget.php
   ```php
   // Linee 103, 150, 215: Sostituire logica Employee::where('user_id')
   $employee = Employee::where('id', $user->id)->first();
   // O implementare logica corretta per STI
   ```

### FASE 2: Miglioramenti Architetturali (SETTIMANA 2)

4. **Creare Enum** - Sostituire costanti con WorkHourTypeEnum e WorkHourStatusEnum
5. **Ottimizzare relazioni** - Verificare relazioni Employee/User per STI
6. **Aggiornare test** - Verificare che i test funzionino con STI

### FASE 3: Ottimizzazioni (DOPO FASE 1-2)

7. **Performance query** - Ottimizzare query STI
8. **Caching** - Implementare caching per widget
9. **API endpoints** - Creare API per mobile integration

---

## 🧪 TEST DI VERIFICA POST-FIX

```bash
# 1. Verifica database schema
php artisan migrate:fresh --seed

# 2. Test model WorkHour
php artisan tinker
>>> \Modules\Employee\Models\WorkHour::count()

# 3. Test STI Employee
>>> \Modules\Employee\Models\Employee::count()  
>>> $user = \Modules\User\Models\User::first()
>>> $employee = \Modules\Employee\Models\Employee::find($user->id)

# 4. Test widget (accedere dashboard)
# Verificare TimeClockWidget non genera errori

# 5. Test relazioni
>>> $workHour = \Modules\Employee\Models\WorkHour::first()
>>> $workHour->employee

# 6. Run test suite
vendor/bin/pest tests/Feature/Employee/ --stop-on-failure
```

---

## 📈 IMPATTO STIMATO

| Errore | Severità | Effort Fix | Rischio se non corretto |
|--------|----------|------------|-------------------------|
| Database Schema | CRITICO | 5 min | Modulo inutilizzabile |
| STI Implementation | ALTO | 30 min | Logica business errata |
| Query STI | ALTO | 20 min | Runtime errors |
| Costanti → Enum | MEDIO | 45 min | Manutenibilità |
| Relazioni | BASSO | 15 min | Performance sub-ottimale |

**TOTALE EFFORT**: ~2 ore per rendere il modulo completamente funzionale.

---

## 🔍 PATTERN IDENTIFICATI PER PREVENZIONE

### ❌ Anti-Pattern da Evitare
- Migration crea tabella X, Model usa tabella Y
- Estendere User senza implementare HasParent trait
- Query STI con pattern non-STI
- Costanti pubbliche invece di enum dedicati
- Non documentare Single Table Inheritance

### ✅ Best Pattern da Seguire  
- Verificare sempre coerenza migration ↔ model
- STI completo: estensione + HasParent trait
- Query compatibili con STI (direct ID o scopes)
- Enum dedicati con label() method  
- Documentare architettura STI

---

*Documento creato: 02/09/2025*
*Analisi basata su: tighten/parental STI, Laraxot best practices*
*Compatibilità: Laravel 11+, Filament 3+, PHP 8.2+*
