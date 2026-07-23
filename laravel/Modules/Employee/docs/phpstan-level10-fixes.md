# PHPStan Level 10 Fixes - Employee Module

## Obiettivo
Correggere tutti i 212 errori PHPStan per raggiungere la compatibilità con livello 10, seguendo le best practice Laraxot e mantenendo la qualità del codice.

## Strategia di Correzione

### 1. Priorità di Correzione
1. **CRITICA**: Errori che impediscono il funzionamento del sistema
2. **ALTA**: Errori di tipizzazione nelle classi principali (Models, Resources, Widgets)
3. **MEDIA**: Errori nei Controllers e Livewire Components
4. **BASSA**: Errori nelle Factories, Migrations e Seeders

### 2. Approccio Sistematico
- Un file alla volta, con validazione immediata
- Mantenere coerenza con architettura Laraxot
- Documentare ogni correzione significativa
- Non modificare phpstan.neon, risolvere gli errori

## Errori Identificati per Categoria

### Resource Pages (4 errori)
**File**: `app/Filament/Resources/WorkHourResource/Pages/CreateWorkHour.php`
- Linea 19: `getRedirectUrl()` ritorna `mixed` invece di `string`
- Linea 37: `Carbon::parse()` riceve `mixed` invece di tipo valido
- Linea 38: `getLastEntryForEmployee()` riceve `mixed` invece di `int`
- Linea 39: `getNextAction()` riceve `mixed` invece di `int`

**File**: `app/Filament/Resources/WorkHourResource/Pages/EditWorkHour.php`
- Linea 27: `getRedirectUrl()` ritorna `mixed` invece di `string`
- Linee 37-39: Accesso a proprietà su `mixed` invece di oggetto tipizzato
- Linea 45: `Carbon::parse()` riceve `mixed`
- Linea 49: Accesso a proprietà `$id` su `mixed`

**File**: `app/Filament/Resources/WorkHourResource/Pages/TimeClockPage.php`
- Linea 28: Funzione anonima ritorna `mixed` invece di `string`

### Widget Errors (35+ errori)
**AttendanceOverviewWidget**: Problemi con tipizzazione Select options e query builder
**TeamPresenceWidget**: Problemi con accesso a proprietà e metodi su `mixed`
**TimeClockWidget**: Problemi con tipizzazione array e metodi Carbon
**TodayPresenceWidget**: Classe Employee non trovata, accesso a proprietà indefinite
**UpcomingScheduleWidget**: Classe Employee non trovata, problemi di tipizzazione

### Controller Errors (12 errori)
**File**: `app/Http/Controllers/EmployeeController.php`
- Tutti i metodi mancano di tipi di ritorno
- Parametri senza tipizzazione
- Uso di `view()` con stringa invece di view-string

### Livewire Components (8 errori)
**TimeClock**: Proprietà senza tipizzazione, assegnazione di `mixed`
**WorkHourDashboard**: Accesso a proprietà su oggetti nullable

### Model Errors (50+ errori)
**Employee**: Classi correlate non trovate, problemi con relazioni e generics
**WorkHour**: Problemi di covarianza nelle relazioni BelongsTo
**TimeRecord**: Problemi di covarianza nelle relazioni
**User/Admin**: PHPDoc con classi inesistenti

### Policy Errors (15 errori)
**WorkHourPolicy**: Metodo `hasPermissionTo()` non trovato su UserContract

### Factory/Migration/Seeder Errors (30+ errori)
**Factories**: Classi modello non trovate, problemi con Faker
**Migrations**: Metodi non esistenti su ForeignKeyDefinition
**Seeders**: Problemi con tipizzazione e accesso a proprietà

## Piano di Correzione

### Fase 1: Correzioni Critiche (PRIORITÀ ASSOLUTA)
1. **Fix Table Name Mismatch**: Correggere `WorkHour.php` per usare tabella `work_hours`
2. **Fix Missing Employee Model References**: Verificare esistenza classe Employee
3. **Fix Resource Page Type Issues**: Correggere tipizzazione in Create/Edit pages

### Fase 2: Correzioni Models e Relations
1. **Rimuovere PHPDoc problematici**: Eliminare riferimenti a classi inesistenti
2. **Fix Relazioni Eloquent**: Correggere problemi di covarianza
3. **Aggiornare Property Annotations**: Correggere @property con classi esistenti

### Fase 3: Correzioni Widget e Components
1. **Fix Widget Query Issues**: Tipizzare correttamente query builder
2. **Fix Missing Classes**: Risolvere riferimenti a classi Employee non trovate
3. **Fix Property Access**: Correggere accesso a proprietà su mixed

### Fase 4: Correzioni Controllers e Policies
1. **Add Return Types**: Aggiungere tipi di ritorno a tutti i metodi
2. **Fix Parameter Types**: Tipizzare tutti i parametri
3. **Fix Policy Methods**: Correggere metodi inesistenti su UserContract

### Fase 5: Correzioni Factory/Migration/Seeder
1. **Fix Factory Model References**: Correggere riferimenti a modelli
2. **Fix Migration Methods**: Correggere metodi inesistenti
3. **Fix Seeder Type Issues**: Correggere problemi di tipizzazione

## Best Practice da Seguire

### Tipizzazione Rigorosa
```php
// ✅ CORRETTO
public function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}

// ❌ ERRATO  
public function getRedirectUrl()
{
    return $this->getResource()::getUrl('index');
}
```

### Gestione Mixed Types
```php
// ✅ CORRETTO
$config = config('app.name');
$appName = is_string($config) ? $config : 'Default';

// ❌ ERRATO
$appName = config('app.name');
```

### Relazioni Eloquent
```php
// ✅ CORRETTO - Senza PHPDoc problematici
public function employee(): BelongsTo
{
    return $this->belongsTo(Employee::class);
}

// ❌ ERRATO - Con PHPDoc che causa errori di covarianza
/**
 * @return BelongsTo<Employee, WorkHour>
 */
public function employee(): BelongsTo
{
    return $this->belongsTo(Employee::class);
}
```

### Property Access Safety
```php
// ✅ CORRETTO
if ($this->record instanceof WorkHour) {
    $employeeId = $this->record->employee_id;
}

// ❌ ERRATO
$employeeId = $this->record->employee_id; // $record potrebbe essere mixed
```

## Checklist Pre-Correzione
- [ ] Backup della documentazione esistente
- [ ] Identificazione dipendenze tra file
- [ ] Verifica esistenza classi referenziate
- [ ] Piano di rollback in caso di problemi

## Checklist Post-Correzione
- [ ] Esecuzione PHPStan level 10 senza errori
- [ ] Test funzionale dei componenti modificati
- [ ] Aggiornamento documentazione
- [ ] Commit con messaggio descrittivo

## Note Importanti
1. **Non modificare phpstan.neon**: Tutti gli errori devono essere risolti nel codice
2. **Mantenere compatibilità**: Non rompere funzionalità esistenti
3. **Seguire convenzioni Laraxot**: Usare sempre XotBase classes
4. **Documentare decisioni**: Spiegare scelte architetturali importanti

---

*Documento creato: 2025-01-06*  
*Stato: In corso*  
*Target: PHPStan Level 10 compliance*
