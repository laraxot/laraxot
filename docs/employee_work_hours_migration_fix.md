# Employee - Work Hours Migration Fix

## Problema Identificato

La migrazione `2025_08_27_121401_create_work_hours_table` falliva con l'errore:

```
SQLSTATE[HY000]: General error: 1005 Can't create table `u345161458_sottana`.`work_hours` (errno: 150 "Foreign key constraint is incorrectly formed")
```

### Causa del Problema

Il problema era causato da un'incompatibilità tra i tipi di dati delle foreign key:

- **Tabella `users`**: La colonna `id` è di tipo `varchar(36)` (UUID)
- **Migrazione originale**: Usava `foreignId()` che crea colonne di tipo `bigint unsigned`

Questo causava l'errore di foreign key constraint perché i tipi di dati non corrispondevano.

## Soluzione Implementata

### 1. Analisi del Database

Prima di tutto, ho verificato la struttura del database:

```sql
-- Struttura tabella users
DESCRIBE users;
-- Risultato: id varchar(36) NOT NULL PRIMARY KEY

-- Struttura tabella work_hours (dopo rollback)
DESCRIBE work_hours;
-- Risultato: employee_id bigint(20) unsigned (INCOMPATIBILE)
```

### 2. Correzione della Migrazione

**File:** `laravel/Modules/Employee/database/migrations/2025_08_27_121401_create_work_hours_table.php`

#### Prima (ERRATO):
```php
$table->foreignId('employee_id')
    ->constrained('users')
    ->onDelete('cascade');

$table->foreignId('approved_by')->nullable()
    ->constrained('users')
    ->onDelete('set null');
```

#### Dopo (CORRETTO):
```php
$table->uuid('employee_id');
$table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');

$table->uuid('approved_by')->nullable();
$table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
```

### 3. Procedura di Risoluzione

1. **Rollback della migrazione fallita:**
   ```bash
   php artisan migrate:rollback --path=Modules/Employee/database/migrations/2025_08_27_121401_create_work_hours_table.php
   ```

2. **Eliminazione della tabella parzialmente creata:**
   ```sql
   DROP TABLE IF EXISTS work_hours;
   ```

3. **Correzione del codice della migrazione:**
   - Sostituito `foreignId()` con `uuid()`
   - Aggiunto `foreign()` esplicito per le foreign key

4. **Riesecuzione della migrazione:**
   ```bash
   php artisan migrate --path=Modules/Employee/database/migrations/2025_08_27_121401_create_work_hours_table.php
   ```

## Risultato Finale

### Struttura Tabella Corretta

```sql
CREATE TABLE `work_hours` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` uuid NOT NULL,
  `type` enum('clock_in','clock_out','break_start','break_end') NOT NULL,
  `timestamp` datetime NOT NULL,
  `location_lat` decimal(10,8) DEFAULT NULL,
  `location_lng` decimal(11,8) DEFAULT NULL,
  `location_name` varchar(191) DEFAULT NULL,
  `device_info` longtext DEFAULT NULL,
  `photo_path` varchar(191) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by` uuid DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `work_hours_employee_timestamp_idx` (`employee_id`,`timestamp`),
  KEY `work_hours_timestamp_type_idx` (`timestamp`,`type`),
  KEY `work_hours_status_idx` (`status`),
  UNIQUE KEY `work_hours_unique_entry` (`employee_id`,`timestamp`,`type`),
  CONSTRAINT `work_hours_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `work_hours_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
```

## Lezioni Apprese

### 1. **Compatibilità Tipi di Dati**
- Sempre verificare i tipi di dati delle tabelle di riferimento prima di creare foreign key
- La tabella `users` usa UUID (`varchar(36)`) non `bigint`

### 2. **Metodi Laravel per Foreign Key**
- `foreignId()` → crea `bigint unsigned` (per tabelle con `id` auto-increment)
- `uuid()` + `foreign()` → per tabelle con UUID come chiave primaria

### 3. **Debugging Migrazioni**
- Usare `DESCRIBE table_name` per verificare la struttura
- Controllare `SHOW CREATE TABLE` per vedere le foreign key
- Eseguire rollback e correggere invece di forzare

### 4. **Best Practice Laraxot**
- Seguire sempre le convenzioni del progetto per i tipi di dati
- Documentare le decisioni architetturali (UUID vs auto-increment)
- Testare le migrazioni in ambiente di sviluppo prima del deploy

## Collegamenti Correlati

- [Employee Module Documentation](../laravel/Modules/Employee/docs/)
- [Database Migration Standards](../laravel/Modules/Xot/docs/MIGRATION_STANDARDS.md)
- [UUID vs Auto-increment Guidelines](../laravel/Modules/User/docs/UUID_GUIDELINES.md)

---

**Data Fix:** 2025-01-06  
**Modulo:** Employee  
**Tipo:** Migration Fix  
**Stato:** ✅ Risolto
