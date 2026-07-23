# Naming Standards - Employee Module

## Regole Critiche per Naming

### 1. Modelli Laravel
- **SEMPRE** usare nomi in inglese per i modelli
- **MAI** usare nomi italiani o localizzati
- **ESEMPIO CORRETTO**: `Attendance`, `Employee`, `TimeEntry`
- **ESEMPIO SBAGLIATO**: `Timbratura`, `Dipendente`, `RegistrazioneOre`

### 2. Tabelle Database
- **SEMPRE** usare nomi in inglese per le tabelle
- **MAI** usare nomi italiani o localizzati
- **ESEMPIO CORRETTO**: `attendances`, `employees`, `time_entries`
- **ESEMPIO SBAGLIATO**: `timbrature`, `dipendenti`, `registrazioni_ore`

### 3. Colonne Database
- **SEMPRE** usare nomi in inglese per le colonne
- **MAI** usare nomi italiani o localizzati
- **ESEMPIO CORRETTO**: `timestamp`, `type`, `method`, `status`
- **ESEMPIO SBAGLIATO**: `data_timbratura`, `tipo`, `metodo`, `stato`

### 4. Enum Values
- **SEMPRE** usare valori in inglese per gli enum
- **MAI** usare valori italiani o localizzati
- **ESEMPIO CORRETTO**: `['entry', 'exit']`, `['valid', 'corrected', 'cancelled']`
- **ESEMPIO SBAGLIATO**: `['entrata', 'uscita']`, `['valida', 'corretta', 'annullata']`

### 5. Relazioni e Metodi
- **SEMPRE** usare nomi in inglese per metodi e relazioni
- **MAI** usare nomi italiani o localizzati
- **ESEMPIO CORRETTO**: `user()`, `createdBy()`, `isEntry()`, `hasLocation()`
- **ESEMPIO SBAGLIATO**: `utente()`, `creatoDa()`, `isEntrata()`, `haPosizione()`

## Standard Laravel

### Convenzioni Modelli
- Nome singolare, PascalCase
- Estende `BaseModel` o `Model`
- Namespace: `Modules\Employee\Models`

### Convenzioni Tabelle
- Nome plurale, snake_case
- Chiave primaria: `id`
- Timestamp: `created_at`, `updated_at`
- Soft deletes: `deleted_at`

### Convenzioni Colonne
- snake_case
- Chiavi esterne: `{model}_id`
- Boolean: `is_*`, `has_*`
- Timestamp: `*_at`

## Esempi di Correzione

### Prima (SBAGLIATO)
```php
class Timbratura extends BaseModel
{
    protected $fillable = [
        'user_id',
        'data_timbratura',
        'tipo',
        'metodo',
        'stato',
    ];
}
```

### Dopo (CORRETTO)
```php
class Attendance extends BaseModel
{
    protected $fillable = [
        'user_id',
        'timestamp',
        'type',
        'method',
        'status',
    ];
}
```

## Controlli Pre-Commit

Prima di ogni commit, verificare:
1. Tutti i modelli hanno nomi in inglese
2. Tutte le tabelle hanno nomi in inglese
3. Tutte le colonne hanno nomi in inglese
4. Tutti gli enum hanno valori in inglese
5. Tutti i metodi hanno nomi in inglese

## Penalità per Violazioni

- **PRIMA VIOLAZIONE**: Warning e correzione immediata
- **SECONDA VIOLAZIONE**: Blocco del commit fino a correzione
- **TERZA VIOLAZIONE**: Review obbligatoria per tutti i file

## Riferimenti

- [Laravel Naming Conventions](https://laravel.com/docs/10.x/eloquent#model-conventions)
- [PSR-12 Coding Standards](https://www.php-fig.org/psr/psr-12/)
- [Laravel Best Practices](https://laravel.com/docs/10.x/best-practices) 