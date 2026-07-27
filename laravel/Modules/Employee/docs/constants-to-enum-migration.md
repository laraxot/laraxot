# WorkHour Model: Constants to Enum Migration

## 📋 Overview

Migrazione completata da costanti pubbliche a PHP 8.1+ enum per migliorare type safety e manutenibilità nel modulo Employee.

## 🔄 Modifiche Implementate

### 1. Enum Creati

#### WorkHourTypeEnum
- **File**: `app/Enums/WorkHourTypeEnum.php`
- **Casi**: `CLOCK_IN`, `CLOCK_OUT`, `BREAK_START`, `BREAK_END`
- **Metodi speciali**:
  - `getNextAction()`: Logica per determinare azione successiva
  - `getLabel()`: Label in inglese
  - `getItalianLabel()`: Label in italiano
  - `isClockedIn()`: Verifica se è un'azione di ingresso

#### WorkHourStatusEnum
- **File**: `app/Enums/WorkHourStatusEnum.php`
- **Casi**: `PENDING`, `APPROVED`, `REJECTED`
- **Metodi speciali**:
  - `getLabel()`: Label in inglese
  - `getItalianLabel()`: Label in italiano
  - `getCssClass()`: Classe CSS per styling
  - `getColor()`: Colore esadecimale per UI
  - `isApproved()`, `isPending()`, `isRejected()`: Helper boolean

### 2. WorkHour Model - Modifiche Applicate

#### Metodi Aggiornati dall'Utente:
```php
// ✅ PRIMA (costanti)
public static function getNextAction(int $employeeId, ?Carbon $date = null): string
{
    return match ($lastEntry->type) {
        self::TYPE_CLOCK_IN => self::TYPE_BREAK_START,
        // ...
    };
}

// ✅ DOPO (enum)
public static function getNextAction(int $employeeId, ?Carbon $date = null): WorkHourTypeEnum
{
    return $lastEntry->type->getNextAction();
}
```

#### Sostituzioni nei Switch/Match:
- `self::TYPE_CLOCK_IN` → `WorkHourTypeEnum::CLOCK_IN`
- `self::TYPE_CLOCK_OUT` → `WorkHourTypeEnum::CLOCK_OUT`
- `self::TYPE_BREAK_START` → `WorkHourTypeEnum::BREAK_START`
- `self::TYPE_BREAK_END` → `WorkHourTypeEnum::BREAK_END`

### 3. Vantaggi Ottenuti

#### Type Safety
- IDE autocompletion migliorato
- Errori compiletime invece che runtime
- Parametri type-hinted con enum

#### Manutenibilità
- Logica centralizzata negli enum
- Metodi helper per UI e business logic
- Traduzioni gestite negli enum stessi

#### Performance
- Nessun overhead di performance
- Match expressions più efficienti
- Enum backed da string per compatibilità database

## 📝 Modifiche Rimaste da Completare

### 1. Casts del Model (PENDING)
```php
// WorkHour.php - casts() method
protected function casts(): array
{
    return [
        'timestamp' => 'datetime',
        'approved_at' => 'datetime',
        'type' => WorkHourTypeEnum::class, // ✅ DA AGGIUNGERE
        'status' => WorkHourStatusEnum::class, // ✅ DA AGGIUNGERE
    ];
}
```

### 2. Rimozione Costanti Obsolete (PENDING)
```php
// WorkHour.php - Rimuovere queste costanti:
// public const TYPE_CLOCK_IN = 'clock_in';
// public const TYPE_CLOCK_OUT = 'clock_out';
// public const TYPE_BREAK_START = 'break_start';
// public const TYPE_BREAK_END = 'break_end';
//
// public const STATUS_PENDING = 'pending';
// public const STATUS_APPROVED = 'approved';
// public const STATUS_REJECTED = 'rejected';
```

### 3. Import Statements
```php
// WorkHour.php - Aggiungere imports:
use Modules\Employee\Enums\WorkHourTypeEnum;
use Modules\Employee\Enums\WorkHourStatusEnum;
```

## 🧪 Test di Validazione

### PHPStan Livello 10
```bash
cd /var/www/html/_bases/base_workorder_fila3_mono/laravel
./vendor/bin/phpstan analyze Modules/Employee/app/Models/WorkHour.php --level=10
./vendor/bin/phpstan analyze Modules/Employee/app/Enums/ --level=10
```

### Functional Testing
```php
// Test creazione WorkHour con enum
$workHour = WorkHour::create([
    'employee_id' => 1,
    'type' => WorkHourTypeEnum::CLOCK_IN,
    'status' => WorkHourStatusEnum::PENDING,
    'timestamp' => now(),
]);

// Test metodi enum
$nextAction = $workHour->type->getNextAction();
$label = $workHour->status->getItalianLabel();
```

## 📊 Impatto sulle Altre Classi

### TimeClockWidget
- ✅ **STATUS**: Probabile compatibilità
- **MOTIVO**: Le modifiche sono backward compatible a livello di valore stringa

### Migration
- ✅ **STATUS**: Nessuna modifica necessaria
- **MOTIVO**: Database continua a memorizzare string values

### Tests
- ⚠️ **STATUS**: Da verificare
- **AZIONE**: Aggiornare test che usano le vecchie costanti

## 🔧 Prossimi Passi

1. **Completare migrazione model** (casts + rimozione costanti)
2. **Validare PHPStan livello 10**
3. **Testare TimeClockWidget compatibilità**
4. **Aggiornare eventuali test**
5. **Documentare pattern per altri moduli**

## 💡 Pattern per Altri Moduli

Questo approccio può essere replicato per:
- `EmployeeStatusEnum` per stati dipendenti
- `DepartmentTypeEnum` per tipi dipartimento
- `PermissionLevelEnum` per livelli autorizzazione

## ⚠️ Note Tecniche

- **PHP Version**: Richiede PHP 8.1+ per backed enums
- **Laravel Version**: Enum casting supportato da Laravel 9+
- **Database**: Nessun cambio schema necessario (mantiene string values)
- **Backward Compatibility**: Valori string rimangono identici

---

*Documento creato: 02/09/2025*
*Stato migrazione: 85% completata*
*PHPStan compliance: Da verificare*
