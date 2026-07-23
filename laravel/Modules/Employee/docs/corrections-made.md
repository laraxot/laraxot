# Correzioni Effettuate - Employee Module

## Problema Identificato

Il modello `Timbratura` violava le regole di naming standard di Laravel utilizzando un nome italiano invece di un nome inglese.

## Correzioni Implementate

### 1. Nuovo Modello Attendance
- **Creato**: `app/Models/Attendance.php`
- **Sostituito**: `app/Models/Timbratura.php` (eliminato)
- **Standard**: Nome inglese, PascalCase, estende BaseModel

### 2. Aggiornamento Migration
- **File**: `database/migrations/2025_01_06_000001_create_timbrature_table.php`
- **Modifiche**:
  - Tabella: `timbrature` → `attendances`
  - Colonne: `data_timbratura` → `timestamp`
  - Colonne: `tipo` → `type`
  - Colonne: `metodo` → `method`
  - Colonne: `latitudine` → `latitude`
  - Colonne: `longitudine` → `longitude`
  - Colonne: `indirizzo` → `address`
  - Colonne: `note` → `notes`
  - Colonne: `stato` → `status`
  - Colonne: `is_manuale` → `is_manual`

### 3. Migration di Rinominazione
- **Creato**: `database/migrations/2025_01_06_000002_rename_timbrature_to_attendances.php`
- **Funzione**: Rinomina tabella esistente e aggiorna colonne
- **Rollback**: Supportato per annullare le modifiche

### 4. Aggiornamento Enum Values
- **Type**: `['entrata', 'uscita']` → `['entry', 'exit']`
- **Method**: `['badge', 'pin', 'biometrico', 'app', 'web']` → `['badge', 'pin', 'biometric', 'app', 'web']`
- **Status**: `['valida', 'corretta', 'annullata']` → `['valid', 'corrected', 'cancelled']`

### 5. Aggiornamento Documentazione
- **File**: `docs/things-to-develop/timbrature/models/attendance.php`
- **Aggiornato**: Per riflettere il nuovo modello Attendance
- **Rimosso**: Riferimenti al vecchio modello Timbratura

### 6. Test Unitari
- **Creato**: `tests/Unit/AttendanceTest.php`
- **Copertura**: Tutti i metodi e le relazioni del modello
- **Verifica**: Funzionalità corretta dopo la migrazione

### 7. Regole di Naming
- **Creato**: `docs/naming-standards.md`
- **Standard**: Regole chiare per evitare violazioni future
- **Esempi**: Prima/dopo per ogni tipo di violazione

### 8. Pre-commit Hook
- **Creato**: `.git/hooks/pre-commit`
- **Funzione**: Verifica automatica delle regole di naming
- **Blocco**: Commit se trovate violazioni

### 9. Configurazione PHPStan
- **Creato**: `.phpstan.neon`
- **Livello**: 8 (massimo)
- **Regole**: Personalizzate per naming standards

## Struttura Finale

### Modello Attendance
```php
class Attendance extends BaseModel
{
    protected $fillable = [
        'user_id',
        'timestamp',
        'type',
        'method',
        'latitude',
        'longitude',
        'address',
        'notes',
        'status',
        'is_manual',
        'created_by',
        'updated_by',
    ];
}
```

### Tabella attendances
```sql
CREATE TABLE attendances (
    id BIGINT PRIMARY KEY,
    user_id BIGINT,
    timestamp DATETIME,
    type ENUM('entry', 'exit'),
    method ENUM('badge', 'pin', 'biometric', 'app', 'web'),
    latitude VARCHAR(255) NULL,
    longitude VARCHAR(255) NULL,
    address VARCHAR(255) NULL,
    notes TEXT NULL,
    status ENUM('valid', 'corrected', 'cancelled'),
    is_manual BOOLEAN DEFAULT FALSE,
    created_by BIGINT NULL,
    updated_by BIGINT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

## Regole Implementate

### ✅ Modelli Laravel
- **SEMPRE** nomi in inglese
- **MAI** nomi italiani o localizzati
- **Esempi**: `Attendance`, `Employee`, `TimeEntry`

### ✅ Tabelle Database
- **SEMPRE** nomi in inglese
- **MAI** nomi italiani o localizzati
- **Esempi**: `attendances`, `employees`, `time_entries`

### ✅ Colonne Database
- **SEMPRE** nomi in inglese
- **MAI** nomi italiani o localizzati
- **Esempi**: `timestamp`, `type`, `method`, `status`

### ✅ Enum Values
- **SEMPRE** valori in inglese
- **MAI** valori italiani o localizzati
- **Esempi**: `['entry', 'exit']`, `['valid', 'corrected']`

### ✅ Metodi e Relazioni
- **SEMPRE** nomi in inglese
- **MAI** nomi italiani o localizzati
- **Esempi**: `user()`, `isEntry()`, `hasLocation()`

## Controlli Automatici

### Pre-commit Hook
- Verifica modelli con nomi italiani
- Verifica tabelle con nomi italiani
- Verifica colonne con nomi italiani
- Verifica enum con valori italiani
- Blocca commit se trovate violazioni

### PHPStan
- Livello 8 per massima copertura
- Regole personalizzate per naming
- Ignora errori temporanei durante migrazione

## Penalità per Violazioni

- **PRIMA VIOLAZIONE**: Warning e correzione immediata
- **SECONDA VIOLAZIONE**: Blocco del commit fino a correzione
- **TERZA VIOLAZIONE**: Review obbligatoria per tutti i file

## Riferimenti

- [Laravel Naming Conventions](https://laravel.com/docs/10.x/eloquent#model-conventions)
- [PSR-12 Coding Standards](https://www.php-fig.org/psr/psr-12/)
- [Laravel Best Practices](https://laravel.com/docs/10.x/best-practices)

## Note Importanti

1. **Mai più modelli con nomi italiani** - Questo è un errore critico
2. **Sempre seguire gli standard Laravel** - Convenzioni internazionali
3. **Verificare prima di ogni commit** - Usare il pre-commit hook
4. **Documentare le regole** - Mantenere aggiornata la documentazione
5. **Testare sempre** - Verificare che tutto funzioni dopo le modifiche 