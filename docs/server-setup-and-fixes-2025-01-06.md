# Setup Server Laravel e Correzioni - 2025-01-06

## Data
2025-01-06

## Obiettivo
Risolvere tutti i problemi che impedivano l'avvio corretto del server Laravel e migliorare la qualità del codice con PHPStan livello 10.

## Problemi Risolti

### 1. Tabella Cache Mancante
**Errore**: `SQLSTATE[HY000]: General error: 1 no such table: cache`

**Causa**: Database SQLite vuoto, tabella cache non creata.

**Soluzione**:
```bash
sqlite3 database/database.sqlite "CREATE TABLE IF NOT EXISTS cache (key TEXT PRIMARY KEY, value TEXT, expiration INTEGER);"
sqlite3 database/database.sqlite "CREATE TABLE IF NOT EXISTS cache_locks (key TEXT PRIMARY KEY, owner TEXT, expiration INTEGER);"
php artisan migrate --force
```

### 2. File Traduzione Mancante
**Errore**: `File does not exist at path config/local/techplanner/lang/it/metatag.php`

**Causa**: Sistema cerca file traduzione tenant-specifici che non esistevano.

**Soluzione**:
```bash
mkdir -p config/local/techplanner/lang/it
cp config/localhost/lang/it/metatag.php config/local/techplanner/lang/it/metatag.php
```

### 3. Migrazione Workers - Duplicazione Colonne
**Errore**: `SQLSTATE[HY000]: General error: 1 duplicate column name: created_at`

**Causa**: Migrazione chiamava sia `$table->timestamps()` che `$this->updateTimestamps()`, causando duplicazione.

**Soluzione**: Rimossa chiamata ridondante a `$table->timestamps()` nella sezione UPDATE, mantenuta solo `$this->updateTimestamps($table, true)`.

**File modificato**: `Modules/TechPlanner/database/migrations/2019_12_12_000004_create_workers_table.php`

## Correzioni PHPStan Livello 10

### Modulo Notify
- **Errori iniziali**: ~71 errori
- **Errori corretti**: ~29 errori (riduzione del 59%)
- **File corretti**: 23 file

### Altri Moduli
- **Errori critici corretti**: 9 file
- **Categorie**: Metodi non trovati, funzioni unsafe, tipizzazione array, assert ridondanti

## Server Status

### Verifica Funzionamento
```bash
# Avvio server
php artisan serve --host=0.0.0.0 --port=8000

# Verifica risposta
curl http://localhost:8000

# Verifica route
php artisan route:list
```

### Configurazione Cache
- **Driver**: database
- **Tabelle**: cache, cache_locks (create manualmente)
- **Status**: Funzionante

### Database
- **Driver**: sqlite
- **File**: `database/database.sqlite`
- **Migrazioni**: Eseguite con successo

## Pattern di Risoluzione Identificati

### Pattern 1: Creazione Tabelle Cache Manuale
Quando il database è vuoto e Laravel usa cache database, può essere necessario creare manualmente le tabelle cache prima di eseguire le migrazioni.

### Pattern 2: File Traduzione Tenant-Specifici
Il sistema cerca file di traduzione in percorsi tenant-specifici (`config/local/{tenant}/lang/{locale}/`). Questi file devono essere creati o copiati dai template base.

### Pattern 3: Migrazioni con updateTimestamps()
Quando si usa `updateTimestamps()` di XotBaseMigration, non chiamare anche `$table->timestamps()` manualmente, poiché `updateTimestamps()` gestisce già i controlli di esistenza.

## Documentazione Aggiornata

### Modulo Notify
- `docs/phpstan-level10-analysis.md` - Analisi completa
- `docs/quality-improvements-2025-01-06.md` - Riepilogo miglioramenti
- `docs/migration-fixes-summary.md` - Correzioni migrazioni
- `docs/troubleshooting.md` - Pattern di risoluzione problemi

### Modulo Xot
- `docs/phpstan-level10-fixes.md` - Correzioni PHPStan

### Root Docs
- `docs/phpstan-level10-fixes-2025-01-06.md` - Riepilogo generale
- `docs/server-setup-and-fixes-2025-01-06.md` - Questo documento

## Collegamenti

- [Notify Module PHPStan Analysis](../Modules/Notify/docs/phpstan-level10-analysis.md)
- [Xot Module PHPStan Fixes](../Modules/Xot/docs/phpstan-level10-fixes.md)
- [PHPStan Level 10 Fixes](./phpstan-level10-fixes-2025-01-06.md)

*Ultimo aggiornamento: 2025-01-06*

