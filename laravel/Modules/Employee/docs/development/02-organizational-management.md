# 02 - Gestione Organizzativa (Organizational Management)

## 🎯 Obiettivo
Creare un sistema completo per gestire la struttura aziendale, replicando e migliorando le funzionalità di dipendentincloud.it.

## 📋 Cosa Dobbiamo Fare

### 1. **Modello Department (Dipartimento)**

#### Passo 1: Creare il Modello
**Dove:** `laravel/Modules/Employee/app/Models/Department.php`

**Cosa fare:**
- Creare il file se non esiste
- Definire tutti i campi necessari per un dipartimento
- Aggiungere le relazioni con altri modelli

**Campi da includere:**
```php
// Dati base
- id (chiave primaria)
- nome (nome del dipartimento)
- descrizione (descrizione del dipartimento)
- codice (codice interno del dipartimento)
- colore (colore per identificazione visiva)

// Organizzazione
- parent_id (dipartimento padre per gerarchia)
- manager_id (responsabile del dipartimento)
- location_id (sede del dipartimento)

// Stato
- stato (attivo/inattivo)
- created_at
- updated_at
```

#### Passo 2: Creare la Migrazione
**Dove:** `laravel/Modules/Employee/database/migrations/2025_07_30_000002_create_departments_table.php`

**Cosa fare:**
- Creare la migrazione per la tabella departments
- Definire tutti i campi con i tipi corretti
- Aggiungere indici per performance
- Aggiungere foreign keys per relazioni

**Campi della tabella:**
```sql
- id (bigint, primary key, auto increment)
- nome (varchar(100), not null)
- descrizione (text, nullable)
- codice (varchar(50), unique, nullable)
- colore (varchar(7), nullable) // formato hex #FFFFFF
- parent_id (bigint, foreign key, nullable)
- manager_id (bigint, foreign key, nullable)
- location_id (bigint, foreign key, not null)
- stato (enum('attivo','inattivo'), default 'attivo')
- created_at (timestamp)
- updated_at (timestamp)
```

#### Passo 3: Creare il Resource Filament
**Dove:** `laravel/Modules/Employee/app/Filament/Resources/DepartmentResource.php`

**Cosa fare:**
- Creare il resource che estende XotBaseResource
- Definire il form per inserimento/modifica
- Definire la tabella per visualizzazione
- Aggiungere filtri e azioni

**Form Schema:**
```php
// Sezione Dati Base
'sezione_base' => Forms\Components\Section::make('Dati Base')
    ->schema([
        'nome' => Forms\Components\TextInput::make('nome')
            ->required()
            ->maxLength(100),
        'descrizione' => Forms\Components\Textarea::make('descrizione')
            ->rows(3),
        'codice' => Forms\Components\TextInput::make('codice')
            ->maxLength(50)
            ->unique(ignoreRecord: true),
        'colore' => Forms\Components\ColorPicker::make('colore'),
    ])
    ->columns(2),

// Sezione Organizzazione
'sezione_organizzazione' => Forms\Components\Section::make('Organizzazione')
    ->schema([
        'parent_id' => Forms\Components\Select::make('parent_id')
            ->relationship('parent', 'nome')
            ->searchable()
            ->label('Dipartimento Padre'),
        'manager_id' => Forms\Components\Select::make('manager_id')
            ->relationship('manager', 'nome')
            ->searchable()
            ->label('Responsabile'),
        'location_id' => Forms\Components\Select::make('location_id')
            ->relationship('location', 'nome')
            ->searchable()
            ->required(),
        'stato' => Forms\Components\Select::make('stato')
            ->options([
                'attivo' => 'Attivo',
                'inattivo' => 'Inattivo'
            ])
            ->default('attivo')
            ->required(),
    ])
    ->columns(2),
```

### 2. **Modello Location (Sede)**

#### Passo 1: Creare il Modello
**Dove:** `laravel/Modules/Employee/app/Models/Location.php`

**Cosa fare:**
- Creare il file se non esiste
- Definire tutti i campi necessari per una sede
- Aggiungere le relazioni con altri modelli

**Campi da includere:**
```php
// Dati base
- id (chiave primaria)
- nome (nome della sede)
- descrizione (descrizione della sede)
- codice (codice interno della sede)

// Indirizzo
- indirizzo (indirizzo completo)
- città
- cap
- provincia
- paese

// Contatti
- telefono
- email
- fax

// Stato
- stato (attivo/inattivo)
- created_at
- updated_at
```

#### Passo 2: Creare la Migrazione
**Dove:** `laravel/Modules/Employee/database/migrations/2025_07_30_000003_create_locations_table.php`

**Cosa fare:**
- Creare la migrazione per la tabella locations
- Definire tutti i campi con i tipi corretti
- Aggiungere indici per performance

#### Passo 3: Creare il Resource Filament
**Dove:** `laravel/Modules/Employee/app/Filament/Resources/LocationResource.php`

**Cosa fare:**
- Creare il resource che estende XotBaseResource
- Definire il form per inserimento/modifica
- Definire la tabella per visualizzazione
- Aggiungere filtri e azioni

### 3. **Modello Role (Ruolo)**

#### Passo 1: Creare il Modello
**Dove:** `laravel/Modules/Employee/app/Models/Role.php`

**Cosa fare:**
- Creare il file se non esiste
- Definire tutti i campi necessari per un ruolo
- Aggiungere le relazioni con altri modelli

**Campi da includere:**
```php
// Dati base
- id (chiave primaria)
- nome (nome del ruolo)
- descrizione (descrizione del ruolo)
- codice (codice interno del ruolo)

// Organizzazione
- department_id (dipartimento di appartenenza)
- livello (livello gerarchico)
- categoria (categoria del ruolo)

// Permessi
- permessi (JSON con permessi specifici)

// Stato
- stato (attivo/inattivo)
- created_at
- updated_at
```

#### Passo 2: Creare la Migrazione
**Dove:** `laravel/Modules/Employee/database/migrations/2025_07_30_000004_create_roles_table.php`

#### Passo 3: Creare il Resource Filament
**Dove:** `laravel/Modules/Employee/app/Filament/Resources/RoleResource.php`

### 4. **Organigramma Interattivo**

#### Passo 1: Creare Widget Organigramma
**Dove:** `laravel/Modules/Employee/app/Filament/Widgets/OrganizationChartWidget.php`

**Cosa fare:**
- Creare widget che mostra la struttura aziendale
- Utilizzare libreria JavaScript per organigramma
- Permettere espansione/collasso dei nodi
- Mostrare informazioni dipendenti al click

**Funzionalità da implementare:**
```php
// Nel widget
public function getViewData(): array
{
    return [
        'departments' => Department::with(['employees', 'manager', 'location'])
            ->whereNull('parent_id')
            ->get(),
        'total_employees' => Employee::count(),
        'total_departments' => Department::count(),
    ];
}
```

#### Passo 2: Creare Vista Organigramma
**Dove:** `laravel/Modules/Employee/resources/views/filament/widgets/organization-chart.blade.php`

**Cosa fare:**
- Creare vista con organigramma interattivo
- Utilizzare Chart.js o libreria simile
- Aggiungere tooltip con informazioni
- Permettere navigazione

### 5. **Miglioramenti Rispetto a dipendentincloud.it**

#### Funzionalità Avanzate da Aggiungere:

**A. Geolocalizzazione Sedi**
- Aggiungere campi lat/lng per ogni sede
- Mostrare mappa con tutte le sedi
- Calcolare distanze tra sedi
- Integrare con Google Maps

**B. Gestione Permessi Granulare**
- Sistema permessi per ruolo
- Permessi per dipartimento
- Permessi per sede
- Audit trail permessi

**C. Workflow Organizzativo**
- Approvazioni per cambi dipartimento
- Notifiche per cambi ruolo
- Storico movimenti organizzativi
- Predizione carriera

**D. Analytics Organizzativa**
- Distribuzione dipendenti per dipartimento
- Turnover per dipartimento
- Performance per sede
- Trend organizzativi

### 6. **Pagine Speciali da Creare**

#### A. Dashboard Organizzativa
**Dove:** `laravel/Modules/Employee/app/Filament/Pages/OrganizationDashboard.php`

**Cosa fare:**
- Creare dashboard con statistiche organizzative
- Mostrare organigramma principale
- Statistiche per dipartimento
- Alert organizzativi

#### B. Gestione Permessi
**Dove:** `laravel/Modules/Employee/app/Filament/Pages/PermissionManagement.php`

**Cosa fare:**
- Interfaccia per gestire permessi ruoli
- Assegnazione permessi granulare
- Test permessi
- Audit permessi

### 7. **Widget Dashboard**

#### A. Statistiche Organizzative
**Dove:** `laravel/Modules/Employee/app/Filament/Widgets/OrganizationStatsWidget.php`

**Cosa fare:**
- Numero totale dipartimenti
- Dipendenti per dipartimento
- Sedi attive
- Ruoli più comuni

#### B. Organigramma Mini
**Dove:** `laravel/Modules/Employee/app/Filament/Widgets/MiniOrgChartWidget.php`

**Cosa fare:**
- Organigramma compatto per dashboard
- Mostrare solo primi livelli
- Link a organigramma completo
- Statistiche rapide

### 8. **Validazioni e Regole**

#### Validazioni da Implementare:
```php
// Nel modello Department
protected static function boot()
{
    parent::boot();
    
    static::creating(function ($department) {
        // Genera codice automatico se non fornito
        if (empty($department->codice)) {
            $department->codice = 'DEPT' . str_pad(Department::count() + 1, 4, '0', STR_PAD_LEFT);
        }
    });
    
    static::saving(function ($department) {
        // Evita loop infiniti nella gerarchia
        if ($department->parent_id == $department->id) {
            throw new \Exception('Un dipartimento non può essere padre di se stesso');
        }
    });
}
```

### 9. **Traduzioni da Aggiungere**

#### File di Traduzione:
**Dove:** `laravel/Modules/Employee/lang/it/organization.php`

```php
return [
    'departments' => [
        'title' => 'Dipartimenti',
        'fields' => [
            'nome' => 'Nome',
            'descrizione' => 'Descrizione',
            'codice' => 'Codice',
            'colore' => 'Colore',
            'parent_id' => 'Dipartimento Padre',
            'manager_id' => 'Responsabile',
            'location_id' => 'Sede',
            'stato' => 'Stato',
        ],
        'messages' => [
            'created' => 'Dipartimento creato con successo',
            'updated' => 'Dipartimento aggiornato con successo',
            'deleted' => 'Dipartimento eliminato con successo',
        ],
    ],
    'locations' => [
        'title' => 'Sedi',
        'fields' => [
            'nome' => 'Nome',
            'descrizione' => 'Descrizione',
            'codice' => 'Codice',
            'indirizzo' => 'Indirizzo',
            'città' => 'Città',
            'cap' => 'CAP',
            'provincia' => 'Provincia',
            'paese' => 'Paese',
            'telefono' => 'Telefono',
            'email' => 'Email',
            'fax' => 'Fax',
            'stato' => 'Stato',
        ],
    ],
    'roles' => [
        'title' => 'Ruoli',
        'fields' => [
            'nome' => 'Nome',
            'descrizione' => 'Descrizione',
            'codice' => 'Codice',
            'department_id' => 'Dipartimento',
            'livello' => 'Livello',
            'categoria' => 'Categoria',
            'permessi' => 'Permessi',
            'stato' => 'Stato',
        ],
    ],
];
```

### 10. **Test da Implementare**

#### Test Unitari:
```php
// Test creazione dipartimento
public function test_can_create_department()
{
    $departmentData = [
        'nome' => 'Risorse Umane',
        'descrizione' => 'Gestione del personale',
        'codice' => 'HR001',
    ];
    
    $department = Department::create($departmentData);
    
    $this->assertDatabaseHas('departments', $departmentData);
}

// Test gerarchia dipartimenti
public function test_department_hierarchy()
{
    $parent = Department::create(['nome' => 'IT']);
    $child = Department::create([
        'nome' => 'Sviluppo',
        'parent_id' => $parent->id
    ]);
    
    $this->assertEquals($parent->id, $child->parent_id);
    $this->assertTrue($parent->children->contains($child));
}
```

## ✅ Checklist Completamento

- [ ] Modelli Department, Location, Role creati
- [ ] Migrazioni database create e testate
- [ ] Resources Filament implementati
- [ ] Form multi-sezione creati
- [ ] Tabelle con colonne e filtri
- [ ] Widget organigramma implementato
- [ ] Pagine speciali create
- [ ] Widget dashboard implementati
- [ ] Validazioni avanzate implementate
- [ ] Traduzioni aggiunte
- [ ] Test funzionali completati

## 🎯 Risultato Finale

Alla fine di questo sviluppo avrai:
1. **Sistema completo di gestione organizzativa** che replica dipendentincloud.it
2. **Organigramma interattivo** con navigazione
3. **Gestione sedi e ruoli** avanzata
4. **Workflow organizzativi** automatizzati
5. **Analytics organizzative** con statistiche
6. **Sistema permessi granulare** per ruoli
7. **Geolocalizzazione sedi** con mappe
8. **Test coverage** completo

Il sistema sarà pronto per gestire organizzazioni complesse con centinaia di dipartimenti e migliaia di dipendenti.

---

*File creato il: 2025-07-30*
*Modulo: Employee*
*Funzionalità: Gestione Organizzativa*
*Priorità: ALTA* 