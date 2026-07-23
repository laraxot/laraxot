# 04 - Gestione Permessi e Ferie (Leave Management)

## 🎯 Obiettivo
Creare un sistema completo per gestire permessi, ferie e assenze dei dipendenti, replicando e migliorando le funzionalità di dipendentincloud.it.

## 📋 Cosa Dobbiamo Fare

### 1. **Modello Leave (Permesso/Ferie)**

#### Passo 1: Creare il Modello
**Dove:** `laravel/Modules/Employee/app/Models/Leave.php`

**Cosa fare:**
- Creare il file se non esiste
- Definire tutti i campi necessari per un permesso
- Aggiungere le relazioni con altri modelli

**Campi da includere:**
```php
// Dati base
- id (chiave primaria)
- employee_id (dipendente)
- tipo_permesso (ferie, permesso, malattia, maternità, etc.)
- data_inizio (data inizio permesso)
- data_fine (data fine permesso)
- ore_richieste (ore richieste se permesso orario)

// Stato e Approvazione
- stato (richiesto, approvato, rifiutato, cancellato)
- approvato_da (chi ha approvato)
- approvato_il (quando è stato approvato)
- motivo_rifiuto (motivo del rifiuto se applicabile)

// Dettagli
- motivo (motivo del permesso)
- note (note aggiuntive)
- allegati (array di file allegati)
- created_at
- updated_at
```

#### Passo 2: Creare la Migrazione
**Dove:** `laravel/Modules/Employee/database/migrations/2025_07_30_000006_create_leaves_table.php`

**Cosa fare:**
- Creare la migrazione per la tabella leaves
- Definire tutti i campi con i tipi corretti
- Aggiungere indici per performance
- Aggiungere foreign keys per relazioni

**Campi della tabella:**
```sql
- id (bigint, primary key, auto increment)
- employee_id (bigint, foreign key, not null)
- tipo_permesso (enum('ferie','permesso','malattia','maternità','paternità','lutto','altro'), not null)
- data_inizio (date, not null)
- data_fine (date, not null)
- ore_richieste (decimal(5,2), nullable)
- stato (enum('richiesto','approvato','rifiutato','cancellato'), default 'richiesto')
- approvato_da (bigint, foreign key, nullable)
- approvato_il (timestamp, nullable)
- motivo_rifiuto (text, nullable)
- motivo (text, nullable)
- note (text, nullable)
- allegati (json, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

#### Passo 3: Creare il Resource Filament
**Dove:** `laravel/Modules/Employee/app/Filament/Resources/LeaveResource.php`

**Cosa fare:**
- Creare il resource che estende XotBaseResource
- Definire il form per inserimento/modifica
- Definire la tabella per visualizzazione
- Aggiungere filtri e azioni

**Form Schema:**
```php
// Sezione Dati Base
'sezione_base' => Forms\Components\Section::make('Dati Permesso')
    ->schema([
        'employee_id' => Forms\Components\Select::make('employee_id')
            ->relationship('employee', 'nome')
            ->searchable()
            ->required(),
        'tipo_permesso' => Forms\Components\Select::make('tipo_permesso')
            ->options([
                'ferie' => 'Ferie',
                'permesso' => 'Permesso',
                'malattia' => 'Malattia',
                'maternità' => 'Maternità',
                'paternità' => 'Paternità',
                'lutto' => 'Lutto',
                'altro' => 'Altro'
            ])
            ->required(),
        'data_inizio' => Forms\Components\DatePicker::make('data_inizio')
            ->required(),
        'data_fine' => Forms\Components\DatePicker::make('data_fine')
            ->required(),
        'ore_richieste' => Forms\Components\TextInput::make('ore_richieste')
            ->numeric()
            ->step(0.5)
            ->label('Ore richieste (se permesso orario)'),
    ])
    ->columns(2),

// Sezione Dettagli
'sezione_dettagli' => Forms\Components\Section::make('Dettagli')
    ->schema([
        'motivo' => Forms\Components\Textarea::make('motivo')
            ->rows(3)
            ->required(),
        'note' => Forms\Components\Textarea::make('note')
            ->rows(3),
        'allegati' => Forms\Components\FileUpload::make('allegati')
            ->multiple()
            ->directory('leave-attachments')
            ->visibility('public'),
    ])
    ->columns(1),

// Sezione Approvazione
'sezione_approvazione' => Forms\Components\Section::make('Approvazione')
    ->schema([
        'stato' => Forms\Components\Select::make('stato')
            ->options([
                'richiesto' => 'Richiesto',
                'approvato' => 'Approvato',
                'rifiutato' => 'Rifiutato',
                'cancellato' => 'Cancellato'
            ])
            ->default('richiesto')
            ->required(),
        'approvato_da' => Forms\Components\Select::make('approvato_da')
            ->relationship('approvatoDa', 'nome')
            ->searchable()
            ->label('Approvato da'),
        'motivo_rifiuto' => Forms\Components\Textarea::make('motivo_rifiuto')
            ->rows(2)
            ->label('Motivo rifiuto'),
    ])
    ->columns(2),
```

### 2. **Modello LeaveBalance (Saldo Permessi)**

#### Passo 1: Creare il Modello
**Dove:** `laravel/Modules/Employee/app/Models/LeaveBalance.php`

**Cosa fare:**
- Creare il file se non esiste
- Definire tutti i campi necessari per il saldo permessi
- Aggiungere le relazioni con altri modelli

**Campi da includere:**
```php
// Dati base
- id (chiave primaria)
- employee_id (dipendente)
- anno (anno di riferimento)
- tipo_permesso (tipo di permesso)

// Saldi
- giorni_totali (giorni totali disponibili)
- giorni_utilizzati (giorni già utilizzati)
- giorni_rimanenti (giorni ancora disponibili)
- ore_totali (ore totali disponibili)
- ore_utilizzate (ore già utilizzate)
- ore_rimanenti (ore ancora disponibili)

// Stato
- created_at
- updated_at
```

#### Passo 2: Creare la Migrazione
**Dove:** `laravel/Modules/Employee/database/migrations/2025_07_30_000007_create_leave_balances_table.php`

#### Passo 3: Creare il Resource Filament
**Dove:** `laravel/Modules/Employee/app/Filament/Resources/LeaveBalanceResource.php`

### 3. **Workflow Approvazioni**

#### Passo 1: Creare Sistema Approvazioni Multi-livello
**Dove:** `laravel/Modules/Employee/app/Models/LeaveApproval.php`

**Cosa fare:**
- Creare modello per workflow approvazioni
- Gestire approvazioni per livello gerarchico
- Notifiche automatiche per ogni step
- Storico completo approvazioni

**Campi del modello:**
```php
- id (chiave primaria)
- leave_id (permesso da approvare)
- approver_id (chi deve approvare)
- livello (livello di approvazione)
- stato (in_attesa, approvato, rifiutato)
- commento (commento approvazione)
- approvato_il (quando è stato approvato)
- created_at
- updated_at
```

#### Passo 2: Creare Notifiche Automatiche
**Cosa fare:**
- Notifiche per richieste in attesa
- Alert per approvazioni scadute
- Reminder per manager
- Report approvazioni

### 4. **Calendario Permessi**

#### Passo 1: Creare Pagina Calendario
**Dove:** `laravel/Modules/Employee/app/Filament/Pages/LeaveCalendar.php`

**Cosa fare:**
- Creare pagina con calendario interattivo
- Mostrare permessi per mese/anno
- Codifica colori per tipo permesso
- Filtri per dipendente/dipartimento

#### Passo 2: Creare Vista Calendario
**Dove:** `laravel/Modules/Employee/resources/views/filament/pages/leave-calendar.blade.php`

**Cosa fare:**
- Integrare FullCalendar.js
- Mostrare permessi come eventi colorati
- Tooltip con dettagli permesso
- Permettere creazione rapida

### 5. **Miglioramenti Rispetto a dipendentincloud.it**

#### Funzionalità Avanzate da Aggiungere:

**A. Calcolo Automatico Saldi**
- Calcolo automatico ferie maturate
- Gestione permessi speciali
- Controllo sovrapposizioni
- Validazione automatica

**B. Integrazione Calendario Aziendale**
- Sincronizzazione con Google Calendar
- Outlook integration
- Notifiche automatiche colleghi
- Gestione sostituzioni

**C. Analytics Permessi**
- Predizione assenze
- Analisi pattern permessi
- Ottimizzazione coperture
- Report produttività

**D. Self-Service Avanzato**
- Richiesta permessi mobile
- Approvazione via app
- Notifiche push
- Dashboard personale

### 6. **Report e Analytics**

#### Passo 1: Creare Widget Analytics
**Dove:** `laravel/Modules/Employee/app/Filament/Widgets/LeaveAnalyticsWidget.php`

**Cosa fare:**
- Grafici permessi per periodo
- Confronto con anni precedenti
- Analisi tipologie permessi
- Trend assenze

#### Passo 2: Creare Report Personalizzati
**Dove:** `laravel/Modules/Employee/app/Filament/Pages/LeaveReports.php`

**Cosa fare:**
- Report per dipendente
- Report per dipartimento
- Report per sede
- Export Excel/PDF

### 7. **Validazioni e Regole**

#### Validazioni da Implementare:
```php
// Nel modello Leave
protected static function boot()
{
    parent::boot();
    
    static::saving(function ($leave) {
        // Validazione date
        if ($leave->data_inizio > $leave->data_fine) {
            throw new \Exception('La data di inizio deve essere precedente alla data di fine');
        }
        
        // Controllo sovrapposizioni
        $overlapping = Leave::where('employee_id', $leave->employee_id)
            ->where('stato', '!=', 'cancellato')
            ->where(function ($query) use ($leave) {
                $query->whereBetween('data_inizio', [$leave->data_inizio, $leave->data_fine])
                    ->orWhereBetween('data_fine', [$leave->data_inizio, $leave->data_fine])
                    ->orWhere(function ($q) use ($leave) {
                        $q->where('data_inizio', '<=', $leave->data_inizio)
                            ->where('data_fine', '>=', $leave->data_fine);
                    });
            })
            ->where('id', '!=', $leave->id)
            ->exists();
            
        if ($overlapping) {
            throw new \Exception('Esiste già un permesso per questo periodo');
        }
        
        // Controllo saldo disponibile
        if ($leave->tipo_permesso === 'ferie') {
            $giorniRichiesti = $leave->data_inizio->diffInDays($leave->data_fine) + 1;
            $saldoDisponibile = LeaveBalance::where('employee_id', $leave->employee_id)
                ->where('anno', $leave->data_inizio->year)
                ->where('tipo_permesso', 'ferie')
                ->value('giorni_rimanenti') ?? 0;
                
            if ($giorniRichiesti > $saldoDisponibile) {
                throw new \Exception("Saldo ferie insufficiente. Disponibili: {$saldoDisponibile} giorni");
            }
        }
    });
}
```

### 8. **Traduzioni da Aggiungere**

#### File di Traduzione:
**Dove:** `laravel/Modules/Employee/lang/it/leave.php`

```php
return [
    'title' => 'Permessi e Ferie',
    'fields' => [
        'employee_id' => 'Dipendente',
        'tipo_permesso' => 'Tipo Permesso',
        'data_inizio' => 'Data Inizio',
        'data_fine' => 'Data Fine',
        'ore_richieste' => 'Ore Richieste',
        'stato' => 'Stato',
        'approvato_da' => 'Approvato da',
        'motivo_rifiuto' => 'Motivo Rifiuto',
        'motivo' => 'Motivo',
        'note' => 'Note',
        'allegati' => 'Allegati',
    ],
    'types' => [
        'ferie' => 'Ferie',
        'permesso' => 'Permesso',
        'malattia' => 'Malattia',
        'maternità' => 'Maternità',
        'paternità' => 'Paternità',
        'lutto' => 'Lutto',
        'altro' => 'Altro',
    ],
    'status' => [
        'richiesto' => 'Richiesto',
        'approvato' => 'Approvato',
        'rifiutato' => 'Rifiutato',
        'cancellato' => 'Cancellato',
    ],
    'messages' => [
        'request_sent' => 'Richiesta inviata con successo',
        'approved' => 'Permesso approvato',
        'rejected' => 'Permesso rifiutato',
        'insufficient_balance' => 'Saldo insufficiente',
        'overlapping_dates' => 'Date sovrapposte',
    ],
];
```

### 9. **Test da Implementare**

#### Test Unitari:
```php
// Test creazione permesso
public function test_can_create_leave()
{
    $employee = Employee::factory()->create();
    
    $leaveData = [
        'employee_id' => $employee->id,
        'tipo_permesso' => 'ferie',
        'data_inizio' => now()->addDays(1),
        'data_fine' => now()->addDays(3),
        'motivo' => 'Vacanze estive',
    ];
    
    $leave = Leave::create($leaveData);
    
    $this->assertDatabaseHas('leaves', $leaveData);
}

// Test controllo sovrapposizioni
public function test_overlapping_dates_validation()
{
    $employee = Employee::factory()->create();
    
    // Crea primo permesso
    Leave::create([
        'employee_id' => $employee->id,
        'tipo_permesso' => 'ferie',
        'data_inizio' => '2025-08-01',
        'data_fine' => '2025-08-05',
        'motivo' => 'Primo permesso',
    ]);
    
    // Prova a creare permesso sovrapposto
    $this->expectException(\Exception::class);
    
    Leave::create([
        'employee_id' => $employee->id,
        'tipo_permesso' => 'ferie',
        'data_inizio' => '2025-08-03',
        'data_fine' => '2025-08-07',
        'motivo' => 'Secondo permesso',
    ]);
}
```

## ✅ Checklist Completamento

- [ ] Modelli Leave e LeaveBalance creati
- [ ] Migrazioni database create e testate
- [ ] Resources Filament implementati
- [ ] Workflow approvazioni creato
- [ ] Calendario permessi implementato
- [ ] Widget analytics implementati
- [ ] Report personalizzati creati
- [ ] Validazioni avanzate implementate
- [ ] Traduzioni aggiunte
- [ ] Test funzionali completati

## 🎯 Risultato Finale

Alla fine di questo sviluppo avrai:
1. **Sistema completo di gestione permessi** che replica dipendentincloud.it
2. **Workflow approvazioni multi-livello** automatizzato
3. **Calendario permessi interattivo** con codifica colori
4. **Calcolo automatico saldi** ferie e permessi
5. **Integrazione calendario aziendale** con Google/Outlook
6. **Analytics permessi** con predizioni
7. **Self-service avanzato** per dipendenti
8. **Test coverage** completo

Il sistema sarà pronto per gestire permessi di centinaia di dipendenti con workflow automatizzati e analytics predittive.

---

*File creato il: 2025-07-30*
*Modulo: Employee*
*Funzionalità: Gestione Permessi e Ferie*
*Priorità: ALTA* 