# 01 - Gestione Dipendenti (Employee Management)

## 🎯 Obiettivo
Creare un sistema completo per gestire tutti i dati dei dipendenti, replicando e migliorando le funzionalità di dipendentincloud.it.

## 📋 Cosa Dobbiamo Fare

### 1. **Modello Employee (Dipendente)**

#### Passo 1: Creare il Modello
**Dove:** `laravel/Modules/Employee/app/Models/Employee.php`

**Cosa fare:**
- Creare il file se non esiste
- Definire tutti i campi necessari per un dipendente
- Aggiungere le relazioni con altri modelli

**Campi da includere:**
```php
// Dati personali
- id (chiave primaria)
- matricola (codice dipendente, unico)
- nome
- cognome
- email (unico)
- telefono
- data_nascita
- luogo_nascita
- sesso (M/F)
- stato_civile

// Dati di contatto
- indirizzo
- città
- cap
- provincia
- paese

// Dati lavorativi
- data_assunzione
- data_cessazione (null se attivo)
- stato (attivo/inattivo/licenziato)
- tipo_contratto
- livello
- categoria

// Relazioni
- department_id (dipartimento)
- location_id (sede)
- role_id (ruolo)
- manager_id (responsabile diretto)
- user_id (collegamento con User)

// Dati aggiuntivi
- foto_url
- note
- created_at
- updated_at
```

#### Passo 2: Creare la Migrazione
**Dove:** `laravel/Modules/Employee/database/migrations/2025_07_30_000001_create_employees_table.php`

**Cosa fare:**
- Creare la migrazione per la tabella employees
- Definire tutti i campi con i tipi corretti
- Aggiungere indici per performance
- Aggiungere foreign keys per relazioni

#### Passo 3: Creare il Resource Filament
**Dove:** `laravel/Modules/Employee/app/Filament/Resources/EmployeeResource.php`

**Cosa fare:**
- Creare il resource che estende XotBaseResource
- Definire il form per inserimento/modifica
- Definire la tabella per visualizzazione
- Aggiungere filtri e azioni

### 2. **Miglioramenti Rispetto a dipendentincloud.it**

#### Funzionalità Avanzate da Aggiungere:

**A. Geolocalizzazione**
- Aggiungere campi per lat/lng dell'indirizzo
- Integrare con Google Maps per validazione
- Mostrare mappa nella scheda dipendente

**B. Storico Modifiche**
- Creare tabella employee_history
- Tracciare tutte le modifiche ai dati
- Mostrare timeline delle modifiche

**C. Import/Export**
- Funzionalità import da Excel/CSV
- Export dati per backup
- Template Excel per import

**D. Validazione Avanzata**
- Validazione codice fiscale
- Validazione email aziendale
- Controllo duplicati automatico

**E. Foto Profilo Avanzata**
- Cropping automatico
- Compressione immagini
- Generazione thumbnail

### 3. **Test da Implementare**

#### Test Unitari:
```php
// Test creazione dipendente
public function test_can_create_employee()
{
    $employeeData = [
        'matricola' => 'EMP001',
        'nome' => 'Mario',
        'cognome' => 'Rossi',
        'email' => 'mario.rossi@azienda.it',
    ];
    
    $employee = Employee::create($employeeData);
    
    $this->assertDatabaseHas('employees', $employeeData);
}
```

### 4. **Pagine Speciali da Creare**

#### A. Profilo Dipendente Dettagliato
**Dove:** `laravel/Modules/Employee/app/Filament/Pages/EmployeeProfile.php`

#### B. Import Dipendenti
**Dove:** `laravel/Modules/Employee/app/Filament/Pages/ImportEmployees.php`

### 5. **Widget Dashboard**

#### A. Statistiche Dipendenti
**Dove:** `laravel/Modules/Employee/app/Filament/Widgets/EmployeeStatsWidget.php`

#### B. Dipendenti Recenti
**Dove:** `laravel/Modules/Employee/app/Filament/Widgets/RecentEmployeesWidget.php`

### 6. **Comandi Artisan da Creare**

#### A. Comando Import Dipendenti
**Dove:** `laravel/Modules/Employee/app/Console/Commands/ImportEmployees.php`

#### B. Comando Backup Dipendenti
**Dove:** `laravel/Modules/Employee/app/Console/Commands/ExportEmployees.php`

### 7. **Validazioni e Regole**

#### Validazioni da Implementare:
```php
// Nel modello Employee
protected static function boot()
{
    parent::boot();
    
    static::creating(function ($employee) {
        // Genera matricola automatica se non fornita
        if (empty($employee->matricola)) {
            $employee->matricola = 'EMP' . str_pad(Employee::count() + 1, 6, '0', STR_PAD_LEFT);
        }
    });
}
```

### 8. **Traduzioni da Aggiungere**

#### File di Traduzione:
**Dove:** `laravel/Modules/Employee/lang/it/employee.php`

```php
return [
    'fields' => [
        'matricola' => 'Matricola',
        'nome' => 'Nome',
        'cognome' => 'Cognome',
        'email' => 'Email',
        'telefono' => 'Telefono',
        'data_nascita' => 'Data di nascita',
        'luogo_nascita' => 'Luogo di nascita',
        'sesso' => 'Sesso',
        'stato_civile' => 'Stato civile',
        'indirizzo' => 'Indirizzo',
        'città' => 'Città',
        'cap' => 'CAP',
        'provincia' => 'Provincia',
        'paese' => 'Paese',
        'data_assunzione' => 'Data assunzione',
        'data_cessazione' => 'Data cessazione',
        'stato' => 'Stato',
        'tipo_contratto' => 'Tipo contratto',
        'livello' => 'Livello',
        'categoria' => 'Categoria',
        'foto_url' => 'Foto profilo',
        'note' => 'Note',
    ],
    'messages' => [
        'created' => 'Dipendente creato con successo',
        'updated' => 'Dipendente aggiornato con successo',
        'deleted' => 'Dipendente eliminato con successo',
    ],
];
```

## ✅ Checklist Completamento

- [ ] Modello Employee creato con tutti i campi
- [ ] Migrazione database creata e testata
- [ ] Resource Filament implementato
- [ ] Form multi-sezione creato
- [ ] Tabella con colonne e filtri
- [ ] Test unitari implementati
- [ ] Pagine speciali create
- [ ] Widget dashboard implementati
- [ ] Comandi Artisan creati
- [ ] Validazioni avanzate implementate
- [ ] Traduzioni aggiunte
- [ ] Test funzionali completati

## 🎯 Risultato Finale

Alla fine di questo sviluppo avrai:
1. **Sistema completo di gestione dipendenti** che replica dipendentincloud.it
2. **Interfaccia moderna** con Filament 3
3. **Validazioni avanzate** per dati italiani
4. **Import/Export** per gestione dati
5. **Dashboard interattive** con statistiche
6. **Sistema di backup** automatico
7. **Traduzioni complete** in italiano
8. **Test coverage** completo

---

*File creato il: 2025-07-30*
*Modulo: Employee*
*Funzionalità: Gestione Dipendenti*
*Priorità: ALTA*
