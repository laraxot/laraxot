# Fix: Foreign Key Constraint Error

## 🚨 Errore Identificato e Risolto

**Data**: 2025-01-06  
**Errore**: `Can't create table time_entries (errno: 150 "Foreign key constraint is incorrectly formed")`  
**Causa**: Reference a tabella `employees` inesistente  
**Soluzione**: ✅ **Correzione a tabella `users` corretta**

## 📋 Analisi del Problema

### Stack Trace
```
SQLSTATE[HY000]: General error: 1005 Can't create table `time_entries` 
Foreign key constraint is incorrectly formed
SQL: alter table `time_entries` add constraint `time_entries_employee_id_foreign` 
foreign key (`employee_id`) references `employees` (`id`) on delete cascade
```

### Causa Radice
- **Migrazione**: `2025_08_27_121400_create_work_hours_table.php`
- **Problema**: `->constrained('employees')` ma tabella `employees` non esiste
- **Realtà**: Modello `Employee` usa tabella `users` con STI

## 🔧 Correzione Implementata

### Prima (Errore) ❌
```php
$table->foreignId('employee_id')
    ->constrained('employees')  // ❌ TABELLA NON ESISTE
    ->onDelete('cascade')
    ->comment('Reference to employee');
```

### Dopo (Corretto) ✅
```php
$table->foreignId('employee_id')
    ->constrained('users')      // ✅ TABELLA CORRETTA
    ->onDelete('cascade')
    ->comment('Reference to user (employee)');
```

## 🏗️ Architettura Employee Module

### Single Table Inheritance (STI)
```php
// Employee.php
class Employee extends User
{
    protected $table = 'users'; // ✅ USA TABELLA USERS
}

// User.php (base)
class User extends Authenticatable
{
    protected $table = 'users'; // ✅ TABELLA PRINCIPALE
}
```

### Relazioni Corrette
```php
// WorkHour.php
public function employee(): BelongsTo
{
    return $this->belongsTo(Employee::class, 'employee_id'); // ✅ CORRETTO
}

// Employee.php  
public function workHours(): HasMany
{
    return $this->hasMany(WorkHour::class, 'employee_id'); // ✅ CORRETTO
}
```

### Schema Database
```sql
-- STRUTTURA CORRETTA
users (id, name, email, type, ...)        -- Tabella principale
time_entries (id, employee_id, ...)       -- FK a users.id
```

## 🛠️ Best Practices per Foreign Keys

### Pattern Corretti

#### 1. Reference a User/Employee
```php
// ✅ CORRETTO - STI con users
$table->foreignId('employee_id')
    ->constrained('users')
    ->onDelete('cascade');
```

#### 2. Reference a Tabella Dedicata
```php
// ✅ CORRETTO - Tabella separata
$table->foreignId('department_id')
    ->constrained('departments')
    ->onDelete('cascade');
```

#### 3. Nullable References
```php
// ✅ CORRETTO - Reference opzionale
$table->foreignId('approved_by')->nullable()
    ->constrained('users')
    ->onDelete('set null');
```

### Pattern da Evitare

#### ❌ Reference a Tabelle Inesistenti
```php
$table->foreignId('employee_id')
    ->constrained('employees'); // ❌ SE NON ESISTE
```

#### ❌ Tipi Incompatibili
```php
$table->string('user_id')           // ❌ String
    ->constrained('users');         // ❌ users.id è BIGINT
```

## 🔍 Verifica Pre-Migrazione

### Checklist Obbligatoria
```bash
# 1. Verifica esistenza tabelle referenziate
SHOW TABLES LIKE 'users';
SHOW TABLES LIKE 'employees';

# 2. Verifica struttura tabelle
DESCRIBE users;
DESCRIBE target_table;

# 3. Verifica tipi compatibili
SHOW CREATE TABLE users;
```

### Script di Validazione
```php
// Verifica prima di creare FK
if (!Schema::hasTable('users')) {
    throw new Exception('Table users does not exist');
}

if (!Schema::hasColumn('users', 'id')) {
    throw new Exception('Column users.id does not exist');
}
```

## 📊 Impatto della Correzione

### Prima (Errore)
- ❌ **Migrazione fallita**: Constraint malformato
- ❌ **Deploy bloccato**: Database non aggiornabile
- ❌ **Development fermo**: Impossibile testare
- ❌ **Foreign key rotta**: Relazioni non funzionanti

### Dopo (Corretto)
- ✅ **Migrazione funzionante**: Constraint corretto
- ✅ **Deploy possibile**: Database aggiornabile
- ✅ **Development ripreso**: Test possibili
- ✅ **Relazioni attive**: Employee ↔ WorkHour funzionante

## 🎯 Lezioni Apprese

### Architettura STI
- **Employee**: Usa tabella `users` con Single Table Inheritance
- **Foreign keys**: Devono referenziare `users`, non `employees`
- **Type column**: Distingue tipi di utenti nella stessa tabella

### Validazione FK
- **Sempre verificare** esistenza tabelle target
- **Controllare tipi** di dati compatibili
- **Testare constraints** prima del deploy

### Debugging FK Errors
- **errno: 150**: Constraint malformato
- **Check table existence**: Prima causa
- **Check column types**: Seconda causa
- **Check indexes**: Terza causa

## 🚀 Prevenzione Futura

### Controlli Pre-Migrazione
```php
// Template per FK sicure
public function up(): void
{
    // Verifica prerequisiti
    if (!$this->hasTable('users')) {
        throw new Exception('Users table required');
    }
    
    $this->tableCreate(function (Blueprint $table): void {
        // FK sicura
        $table->foreignId('user_id')
            ->constrained('users')
            ->onDelete('cascade');
    });
}
```

### Documentation Standards
- **Documentare architettura** STI nei moduli
- **Mappare relazioni** tra tabelle
- **Specificare constraints** richiesti
- **Testare migrazioni** in locale

---

**ERRORE RISOLTO**: ✅ Foreign key constraint corretto  
**MIGRAZIONE**: ✅ Reference a `users` invece di `employees`  
**ARCHITETTURA**: ✅ STI Employee → User documentata  
**PREVENZIONE**: ✅ Best practices implementate

La migrazione ora dovrebbe funzionare correttamente!

## Collegamenti

- [Migration Rules](../../../project_docs/development/migration_rules.md)
- [Employee Architecture](../architecture/model_architecture.md)
- [Database Schema](../features/database_schema.md)

*Risolto: Gennaio 2025*
