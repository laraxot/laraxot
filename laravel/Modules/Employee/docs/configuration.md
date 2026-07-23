# Configurazione Modulo Employee

## Panoramica

Il file di configurazione del modulo Employee (`config/config.php`) fornisce un controllo granulare su tutte le funzionalità del sistema di gestione delle risorse umane. Questa documentazione descrive ogni sezione di configurazione e le relative variabili d'ambiente.

## Struttura della Configurazione

### 1. Informazioni Modulo

```php
'name' => 'Employee',
'description' => 'Modulo per la gestione completa delle risorse umane e dipendenti',
'version' => '1.0.0',
'icon' => 'heroicon-o-user-group',
```

**Scopo**: Metadati di base del modulo utilizzati per identificazione e visualizzazione nell'interfaccia amministrativa.

### 2. Configurazione Navigazione

```php
'navigation' => [
    'enabled' => true,
    'sort' => 50,
    'group' => 'Risorse Umane',
],
```

**Variabili d'Ambiente**: Nessuna (gestito automaticamente da XotBase)
**Scopo**: Controlla la visualizzazione del modulo nel menu di navigazione dell'admin panel.

### 3. Configurazione Route

```php
'routes' => [
    'enabled' => true,
    'middleware' => ['web', 'auth'],
    'prefix' => 'employee',
    'api' => [
        'enabled' => true,
        'prefix' => 'api/employee',
        'middleware' => ['api', 'auth:sanctum'],
    ],
],
```

**Scopo**: Definisce le route web e API del modulo con i relativi middleware di sicurezza.

### 4. Configurazione Permessi

```php
'permissions' => [
    'employee' => [
        'view' => 'employee.view',
        'create' => 'employee.create',
        'edit' => 'employee.edit',
        'delete' => 'employee.delete',
        'export' => 'employee.export',
    ],
    // Altri gruppi di permessi...
],
```

**Scopo**: Definisce tutti i permessi disponibili per il controllo degli accessi basato sui ruoli.

### 5. Configurazione Ore di Lavoro

```php
'work_hours' => [
    'default_hours_per_day' => env('EMPLOYEE_DEFAULT_HOURS_PER_DAY', 8),
    'default_hours_per_week' => env('EMPLOYEE_DEFAULT_HOURS_PER_WEEK', 40),
    'overtime_threshold' => env('EMPLOYEE_OVERTIME_THRESHOLD', 8),
    'break_duration' => env('EMPLOYEE_BREAK_DURATION', 60), // minutes
    // ...
],
```

**Variabili d'Ambiente**:
- `EMPLOYEE_DEFAULT_HOURS_PER_DAY`: Ore di lavoro standard per giorno (default: 8)
- `EMPLOYEE_DEFAULT_HOURS_PER_WEEK`: Ore di lavoro standard per settimana (default: 40)
- `EMPLOYEE_OVERTIME_THRESHOLD`: Soglia per straordinari (default: 8 ore)
- `EMPLOYEE_BREAK_DURATION`: Durata pausa in minuti (default: 60)
- `EMPLOYEE_TIME_ROUNDING_ENABLED`: Abilita arrotondamento orari (default: true)
- `EMPLOYEE_TIME_ROUNDING_MINUTES`: Minuti per arrotondamento (default: 15)

### 6. Configurazione Tracciamento Tempo

```php
'time_tracking' => [
    'methods' => [
        'manual' => env('EMPLOYEE_MANUAL_TIME_ENTRY', true),
        'gps' => env('EMPLOYEE_GPS_TRACKING', false),
        'ip_restriction' => env('EMPLOYEE_IP_RESTRICTION', false),
    ],
    // ...
],
```

**Variabili d'Ambiente**:
- `EMPLOYEE_MANUAL_TIME_ENTRY`: Abilita inserimento manuale orari (default: true)
- `EMPLOYEE_GPS_TRACKING`: Abilita tracciamento GPS (default: false)
- `EMPLOYEE_IP_RESTRICTION`: Abilita restrizioni IP (default: false)
- `EMPLOYEE_GPS_ACCURACY`: Soglia accuratezza GPS in metri (default: 100)
- `EMPLOYEE_ALLOWED_LOCATIONS`: Coordinate GPS consentite
- `EMPLOYEE_IP_WHITELIST`: Lista IP consentiti

### 7. Configurazione Gestione Ferie

```php
'leave_management' => [
    'annual_leave_days' => env('EMPLOYEE_ANNUAL_LEAVE_DAYS', 22),
    'sick_leave_days' => env('EMPLOYEE_SICK_LEAVE_DAYS', 10),
    'personal_leave_days' => env('EMPLOYEE_PERSONAL_LEAVE_DAYS', 3),
    // ...
],
```

**Variabili d'Ambiente**:
- `EMPLOYEE_ANNUAL_LEAVE_DAYS`: Giorni di ferie annuali (default: 22)
- `EMPLOYEE_SICK_LEAVE_DAYS`: Giorni di malattia (default: 10)
- `EMPLOYEE_PERSONAL_LEAVE_DAYS`: Giorni di permesso personale (default: 3)
- `EMPLOYEE_LEAVE_APPROVAL_ENABLED`: Abilita workflow approvazioni (default: true)
- `EMPLOYEE_AUTO_APPROVE_THRESHOLD`: Soglia auto-approvazione in giorni (default: 1)

### 8. Configurazione Notifiche

```php
'notifications' => [
    'channels' => ['mail', 'database', 'broadcast'],
    'events' => [
        'employee_created' => env('EMPLOYEE_NOTIFY_CREATED', true),
        'work_hour_submitted' => env('EMPLOYEE_NOTIFY_WORK_HOUR', true),
        // ...
    ],
],
```

**Variabili d'Ambiente**:
- `EMPLOYEE_NOTIFY_CREATED`: Notifica creazione dipendente (default: true)
- `EMPLOYEE_NOTIFY_WORK_HOUR`: Notifica inserimento ore (default: true)
- `EMPLOYEE_NOTIFY_LEAVE_REQUEST`: Notifica richiesta ferie (default: true)
- `EMPLOYEE_NOTIFY_OVERTIME`: Notifica straordinari (default: true)
- `EMPLOYEE_HR_TEAM_EMAIL`: Email team HR
- `EMPLOYEE_MANAGERS_EMAIL`: Email manager

### 9. Configurazione Documenti

```php
'documents' => [
    'storage_disk' => env('EMPLOYEE_DOCUMENTS_DISK', 'local'),
    'max_file_size' => env('EMPLOYEE_MAX_FILE_SIZE', 10240), // KB
    'allowed_extensions' => ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'],
    // ...
],
```

**Variabili d'Ambiente**:
- `EMPLOYEE_DOCUMENTS_DISK`: Disco di storage per documenti (default: local)
- `EMPLOYEE_MAX_FILE_SIZE`: Dimensione massima file in KB (default: 10240)
- `EMPLOYEE_DOCUMENT_RETENTION`: Abilita retention documenti (default: true)
- `EMPLOYEE_DOCUMENT_RETENTION_YEARS`: Anni di retention (default: 10)

### 10. Configurazione Reporting

```php
'reporting' => [
    'enabled' => env('EMPLOYEE_REPORTING_ENABLED', true),
    'cache_ttl' => env('EMPLOYEE_REPORT_CACHE_TTL', 3600),
    'export_formats' => ['pdf', 'excel', 'csv'],
    // ...
],
```

**Variabili d'Ambiente**:
- `EMPLOYEE_REPORTING_ENABLED`: Abilita sistema reporting (default: true)
- `EMPLOYEE_REPORT_CACHE_TTL`: TTL cache report in secondi (default: 3600)
- `EMPLOYEE_DEFAULT_EXPORT_FORMAT`: Formato export default (default: pdf)
- `EMPLOYEE_CHARTS_ENABLED`: Abilita grafici (default: true)
- `EMPLOYEE_CHART_LIBRARY`: Libreria grafici (default: chartjs)

### 11. Configurazione Integrazioni

```php
'integrations' => [
    'payroll' => [
        'enabled' => env('EMPLOYEE_PAYROLL_INTEGRATION', false),
        'provider' => env('EMPLOYEE_PAYROLL_PROVIDER', ''),
        'api_key' => env('EMPLOYEE_PAYROLL_API_KEY', ''),
    ],
    // ...
],
```

**Variabili d'Ambiente**:
- `EMPLOYEE_PAYROLL_INTEGRATION`: Abilita integrazione buste paga (default: false)
- `EMPLOYEE_PAYROLL_PROVIDER`: Provider servizio buste paga
- `EMPLOYEE_PAYROLL_API_KEY`: Chiave API per integrazione
- `EMPLOYEE_CALENDAR_INTEGRATION`: Abilita integrazione calendario (default: true)
- `EMPLOYEE_CALENDAR_PROVIDER`: Provider calendario (default: google)
- `EMPLOYEE_LDAP_INTEGRATION`: Abilita integrazione LDAP (default: false)

### 12. Configurazione Cache

```php
'cache' => [
    'enabled' => env('EMPLOYEE_CACHE_ENABLED', true),
    'ttl' => env('EMPLOYEE_CACHE_TTL', 3600),
    'prefix' => 'employee_',
    'tags' => ['employee', 'workhour', 'department'],
],
```

**Variabili d'Ambiente**:
- `EMPLOYEE_CACHE_ENABLED`: Abilita cache modulo (default: true)
- `EMPLOYEE_CACHE_TTL`: TTL cache in secondi (default: 3600)

### 13. Configurazione Sicurezza

```php
'security' => [
    'encrypt_sensitive_data' => env('EMPLOYEE_ENCRYPT_DATA', true),
    'audit_trail' => env('EMPLOYEE_AUDIT_TRAIL', true),
    'data_retention_days' => env('EMPLOYEE_DATA_RETENTION_DAYS', 2555), // 7 years
    'gdpr_compliance' => env('EMPLOYEE_GDPR_COMPLIANCE', true),
],
```

**Variabili d'Ambiente**:
- `EMPLOYEE_ENCRYPT_DATA`: Crittografia dati sensibili (default: true)
- `EMPLOYEE_AUDIT_TRAIL`: Abilita audit trail (default: true)
- `EMPLOYEE_DATA_RETENTION_DAYS`: Giorni retention dati (default: 2555 = 7 anni)
- `EMPLOYEE_GDPR_COMPLIANCE`: Conformità GDPR (default: true)
- `EMPLOYEE_PASSWORD_MIN_LENGTH`: Lunghezza minima password (default: 8)

### 14. Feature Flags

```php
'features' => [
    'self_service_portal' => env('EMPLOYEE_SELF_SERVICE', true),
    'mobile_app' => env('EMPLOYEE_MOBILE_APP', false),
    'ai_insights' => env('EMPLOYEE_AI_INSIGHTS', false),
    // ...
],
```

**Variabili d'Ambiente**:
- `EMPLOYEE_SELF_SERVICE`: Abilita portale self-service (default: true)
- `EMPLOYEE_MOBILE_APP`: Abilita app mobile (default: false)
- `EMPLOYEE_AI_INSIGHTS`: Abilita insights AI (default: false)
- `EMPLOYEE_ADVANCED_ANALYTICS`: Abilita analytics avanzate (default: false)
- `EMPLOYEE_MULTI_LOCATION`: Abilita multi-location (default: true)
- `EMPLOYEE_SHIFT_MANAGEMENT`: Abilita gestione turni (default: false)

## File .env di Esempio

```env
# Employee Module Configuration

# Work Hours
EMPLOYEE_DEFAULT_HOURS_PER_DAY=8
EMPLOYEE_DEFAULT_HOURS_PER_WEEK=40
EMPLOYEE_OVERTIME_THRESHOLD=8
EMPLOYEE_BREAK_DURATION=60
EMPLOYEE_TIME_ROUNDING_ENABLED=true
EMPLOYEE_TIME_ROUNDING_MINUTES=15

# Time Tracking
EMPLOYEE_MANUAL_TIME_ENTRY=true
EMPLOYEE_GPS_TRACKING=false
EMPLOYEE_IP_RESTRICTION=false
EMPLOYEE_GPS_ACCURACY=100
EMPLOYEE_ALLOWED_LOCATIONS=""
EMPLOYEE_IP_WHITELIST=""

# Leave Management
EMPLOYEE_ANNUAL_LEAVE_DAYS=22
EMPLOYEE_SICK_LEAVE_DAYS=10
EMPLOYEE_PERSONAL_LEAVE_DAYS=3
EMPLOYEE_LEAVE_APPROVAL_ENABLED=true
EMPLOYEE_AUTO_APPROVE_THRESHOLD=1

# Notifications
EMPLOYEE_NOTIFY_CREATED=true
EMPLOYEE_NOTIFY_WORK_HOUR=true
EMPLOYEE_NOTIFY_LEAVE_REQUEST=true
EMPLOYEE_NOTIFY_OVERTIME=true
EMPLOYEE_HR_TEAM_EMAIL=""
EMPLOYEE_MANAGERS_EMAIL=""

# Documents
EMPLOYEE_DOCUMENTS_DISK=local
EMPLOYEE_MAX_FILE_SIZE=10240
EMPLOYEE_DOCUMENT_RETENTION=true
EMPLOYEE_DOCUMENT_RETENTION_YEARS=10

# Reporting
EMPLOYEE_REPORTING_ENABLED=true
EMPLOYEE_REPORT_CACHE_TTL=3600
EMPLOYEE_DEFAULT_EXPORT_FORMAT=pdf
EMPLOYEE_CHARTS_ENABLED=true
EMPLOYEE_CHART_LIBRARY=chartjs

# Integrations
EMPLOYEE_PAYROLL_INTEGRATION=false
EMPLOYEE_PAYROLL_PROVIDER=""
EMPLOYEE_PAYROLL_API_KEY=""
EMPLOYEE_CALENDAR_INTEGRATION=true
EMPLOYEE_CALENDAR_PROVIDER=google
EMPLOYEE_LDAP_INTEGRATION=false

# Cache
EMPLOYEE_CACHE_ENABLED=true
EMPLOYEE_CACHE_TTL=3600

# Security
EMPLOYEE_ENCRYPT_DATA=true
EMPLOYEE_AUDIT_TRAIL=true
EMPLOYEE_DATA_RETENTION_DAYS=2555
EMPLOYEE_GDPR_COMPLIANCE=true
EMPLOYEE_PASSWORD_MIN_LENGTH=8

# Features
EMPLOYEE_SELF_SERVICE=true
EMPLOYEE_MOBILE_APP=false
EMPLOYEE_AI_INSIGHTS=false
EMPLOYEE_ADVANCED_ANALYTICS=false
EMPLOYEE_MULTI_LOCATION=true
EMPLOYEE_SHIFT_MANAGEMENT=false
EMPLOYEE_PERFORMANCE_REVIEWS=false
```

## Best Practices

### 1. Configurazione Ambiente di Sviluppo

- Utilizzare valori permissivi per testing e sviluppo
- Abilitare logging e debug per troubleshooting
- Disabilitare features avanzate non necessarie

### 2. Configurazione Ambiente di Produzione

- Abilitare tutte le misure di sicurezza
- Configurare correttamente le integrazioni esterne
- Ottimizzare cache e performance
- Abilitare audit trail e compliance

### 3. Gestione Configurazioni Sensibili

- Non committare mai chiavi API nel codice
- Utilizzare sempre variabili d'ambiente per dati sensibili
- Implementare rotazione periodica delle chiavi
- Monitorare accessi alle configurazioni

### 4. Monitoraggio e Manutenzione

- Verificare regolarmente le configurazioni attive
- Monitorare l'utilizzo delle features abilitate
- Aggiornare le configurazioni in base alle esigenze
- Documentare ogni modifica di configurazione

## Troubleshooting

### Problemi Comuni

1. **Cache non funzionante**: Verificare `EMPLOYEE_CACHE_ENABLED` e configurazione Redis/Memcached
2. **Notifiche non inviate**: Controllare configurazione mail e variabili notifiche
3. **GPS non preciso**: Regolare `EMPLOYEE_GPS_ACCURACY` in base all'ambiente
4. **Upload documenti falliti**: Verificare `EMPLOYEE_MAX_FILE_SIZE` e permessi storage

### Log e Debug

- Abilitare logging dettagliato in ambiente di sviluppo
- Monitorare log di sistema per errori di configurazione
- Utilizzare strumenti di profiling per ottimizzazioni

## Collegamenti

- [README.md](README.md) - Documentazione generale del modulo
- [model_architecture.md](model_architecture.md) - Architettura dei modelli
- [xotbase_extension_rules.md](xotbase_extension_rules.md) - Regole XotBase
- [work_hour.md](work_hour.md) - Implementazione WorkHour

---

*Documentazione aggiornata: 2025-08-27*
*Versione configurazione: 1.0.0*
*Compatibilità: Laravel 11+, Filament 3+*
