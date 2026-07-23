# 06 - Gestione Contratti (Contract Management)

## 🎯 Obiettivo
Creare un sistema completo per gestire i contratti di lavoro dei dipendenti, replicando e migliorando le funzionalità di dipendentincloud.it.

## 📋 Cosa Dobbiamo Fare

### 1. **Modello Contract (Contratto)**

#### Passo 1: Creare il Modello
**Dove:** `laravel/Modules/Employee/app/Models/Contract.php`

**Cosa fare:**
- Creare il file se non esiste
- Definire tutti i campi necessari per un contratto
- Aggiungere le relazioni con altri modelli

**Campi da includere:**
```php
// Dati base
- id (chiave primaria)
- employee_id (dipendente)
- numero_contratto (numero interno contratto)
- tipo_contratto (tempo_indeterminato, tempo_determinato, apprendistato, etc.)
- categoria_contratto (operai, impiegati, dirigenti, etc.)

// Date
- data_inizio (data inizio contratto)
- data_fine (data fine contratto se determinato)
- data_prova_inizio (inizio periodo di prova)
- data_prova_fine (fine periodo di prova)

// Condizioni economiche
- retribuzione_lorda (retribuzione lorda mensile)
- retribuzione_netta (retribuzione netta mensile)
- indennità (indennità aggiuntive)
- benefit (benefit aziendali)

// Orario di lavoro
- ore_settimanali (ore settimanali)
- giorni_settimanali (giorni settimanali)
- turno_lavoro (turno di lavoro)
- sede_lavoro (sede di lavoro)

// Stato e Approvazione
- stato (attivo, sospeso, terminato, scaduto)
- approvato (se approvato)
- approvato_da (chi ha approvato)
- approvato_il (quando è stato approvato)

// Dettagli
- note (note aggiuntive)
- allegati (array di file allegati)
- created_at
- updated_at
```

#### Passo 2: Creare la Migrazione
**Dove:** `laravel/Modules/Employee/database/migrations/2025_07_30_000011_create_contracts_table.php`

**Cosa fare:**
- Creare la migrazione per la tabella contracts
- Definire tutti i campi con i tipi corretti
- Aggiungere indici per performance
- Aggiungere foreign keys per relazioni

**Campi della tabella:**
```sql
- id (bigint, primary key, auto increment)
- employee_id (bigint, foreign key, not null)
- numero_contratto (varchar(50), unique, not null)
- tipo_contratto (enum('tempo_indeterminato','tempo_determinato','apprendistato','collaborazione','stage'), not null)
- categoria_contratto (enum('operai','impiegati','dirigenti','quadri'), not null)
- data_inizio (date, not null)
- data_fine (date, nullable)
- data_prova_inizio (date, nullable)
- data_prova_fine (date, nullable)
- retribuzione_lorda (decimal(10,2), not null)
- retribuzione_netta (decimal(10,2), nullable)
- indennità (decimal(10,2), default 0.00)
- benefit (json, nullable)
- ore_settimanali (decimal(5,2), not null)
- giorni_settimanali (int, not null)
- turno_lavoro (varchar(100), nullable)
- sede_lavoro (varchar(255), nullable)
- stato (enum('attivo','sospeso','terminato','scaduto'), default 'attivo')
- approvato (boolean, default false)
- approvato_da (bigint, foreign key, nullable)
- approvato_il (timestamp, nullable)
- note (text, nullable)
- allegati (json, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

#### Passo 3: Creare il Resource Filament
**Dove:** `laravel/Modules/Employee/app/Filament/Resources/ContractResource.php`

**Cosa fare:**
- Creare il resource che estende XotBaseResource
- Definire il form per inserimento/modifica
- Definire la tabella per visualizzazione
- Aggiungere filtri e azioni

**Form Schema:**
```php
// Sezione Dati Base
'sezione_base' => Forms\Components\Section::make('Dati Contratto')
    ->schema([
        'employee_id' => Forms\Components\Select::make('employee_id')
            ->relationship('employee', 'nome')
            ->searchable()
            ->required(),
        'numero_contratto' => Forms\Components\TextInput::make('numero_contratto')
            ->required()
            ->unique(ignoreRecord: true)
            ->maxLength(50),
        'tipo_contratto' => Forms\Components\Select::make('tipo_contratto')
            ->options([
                'tempo_indeterminato' => 'Tempo Indeterminato',
                'tempo_determinato' => 'Tempo Determinato',
                'apprendistato' => 'Apprendistato',
                'collaborazione' => 'Collaborazione',
                'stage' => 'Stage'
            ])
            ->required(),
        'categoria_contratto' => Forms\Components\Select::make('categoria_contratto')
            ->options([
                'operai' => 'Operai',
                'impiegati' => 'Impiegati',
                'dirigenti' => 'Dirigenti',
                'quadri' => 'Quadri'
            ])
            ->required(),
    ])
    ->columns(2),

// Sezione Date
'sezione_date' => Forms\Components\Section::make('Date Contratto')
    ->schema([
        'data_inizio' => Forms\Components\DatePicker::make('data_inizio')
            ->required(),
        'data_fine' => Forms\Components\DatePicker::make('data_fine')
            ->label('Data fine (se determinato)'),
        'data_prova_inizio' => Forms\Components\DatePicker::make('data_prova_inizio')
            ->label('Inizio periodo di prova'),
        'data_prova_fine' => Forms\Components\DatePicker::make('data_prova_fine')
            ->label('Fine periodo di prova'),
    ])
    ->columns(2),

// Sezione Economica
'sezione_economica' => Forms\Components\Section::make('Condizioni Economiche')
    ->schema([
        'retribuzione_lorda' => Forms\Components\TextInput::make('retribuzione_lorda')
            ->numeric()
            ->prefix('€')
            ->required(),
        'retribuzione_netta' => Forms\Components\TextInput::make('retribuzione_netta')
            ->numeric()
            ->prefix('€'),
        'indennità' => Forms\Components\TextInput::make('indennità')
            ->numeric()
            ->prefix('€')
            ->default(0.00),
        'benefit' => Forms\Components\KeyValue::make('benefit')
            ->label('Benefit aziendali')
            ->keyLabel('Benefit')
            ->valueLabel('Valore'),
    ])
    ->columns(2),

// Sezione Orario
'sezione_orario' => Forms\Components\Section::make('Orario di Lavoro')
    ->schema([
        'ore_settimanali' => Forms\Components\TextInput::make('ore_settimanali')
            ->numeric()
            ->suffix('ore')
            ->required(),
        'giorni_settimanali' => Forms\Components\TextInput::make('giorni_settimanali')
            ->numeric()
            ->suffix('giorni')
            ->required(),
        'turno_lavoro' => Forms\Components\TextInput::make('turno_lavoro')
            ->maxLength(100),
        'sede_lavoro' => Forms\Components\TextInput::make('sede_lavoro')
            ->maxLength(255),
    ])
    ->columns(2),

// Sezione Stato
'sezione_stato' => Forms\Components\Section::make('Stato e Approvazione')
    ->schema([
        'stato' => Forms\Components\Select::make('stato')
            ->options([
                'attivo' => 'Attivo',
                'sospeso' => 'Sospeso',
                'terminato' => 'Terminato',
                'scaduto' => 'Scaduto'
            ])
            ->default('attivo')
            ->required(),
        'approvato' => Forms\Components\Toggle::make('approvato')
            ->label('Contratto approvato'),
        'approvato_da' => Forms\Components\Select::make('approvato_da')
            ->relationship('approvatoDa', 'nome')
            ->searchable()
            ->label('Approvato da'),
        'note' => Forms\Components\Textarea::make('note')
            ->rows(3),
    ])
    ->columns(2),
```

### 2. **Modello ContractAmendment (Modifiche Contratto)**

#### Passo 1: Creare il Modello
**Dove:** `laravel/Modules/Employee/app/Models/ContractAmendment.php`

**Cosa fare:**
- Creare il file se non esiste
- Definire tutti i campi necessari per una modifica contratto
- Aggiungere le relazioni con altri modelli

**Campi da includere:**
```php
// Dati base
- id (chiave primaria)
- contract_id (contratto da modificare)
- tipo_modifica (proroga, variazione, rinnovo, etc.)
- data_modifica (data della modifica)
- data_effetto (data di effetto della modifica)

// Modifiche
- campo_modificato (campo che è stato modificato)
- valore_precedente (valore precedente)
- valore_nuovo (nuovo valore)
- motivazione (motivazione della modifica)

// Approvazione
- approvato (se approvato)
- approvato_da (chi ha approvato)
- approvato_il (quando è stato approvato)

// Stato
- stato (richiesto, approvato, rifiutato)
- note (note aggiuntive)
- created_at
- updated_at
```

#### Passo 2: Creare la Migrazione
**Dove:** `laravel/Modules/Employee/database/migrations/2025_07_30_000012_create_contract_amendments_table.php`

#### Passo 3: Creare il Resource Filament
**Dove:** `laravel/Modules/Employee/app/Filament/Resources/ContractAmendmentResource.php`

### 3. **Sistema di Generazione Automatica Contratti**

#### Passo 1: Creare Service per Generazione
**Dove:** `laravel/Modules/Employee/app/Services/ContractGeneratorService.php`

**Cosa fare:**
- Creare service per generazione contratti automatica
- Gestire template contratti per tipo
- Integrare con librerie PDF
- Gestire firme digitali

**Funzionalità da implementare:**
```php
// Nel service
public function generateContract(Employee $employee, array $data = [])
{
    $template = DocumentTemplate::where('tipo_template', 'contratto')
        ->where('tipo_contratto', $data['tipo_contratto'])
        ->where('is_active', true)
        ->first();
    
    if (!$template) {
        throw new \Exception('Template contratto non trovato');
    }
    
    // Prepara variabili
    $variables = [
        'employee_name' => $employee->nome . ' ' . $employee->cognome,
        'employee_id' => $employee->matricola,
        'contract_number' => $this->generateContractNumber(),
        'contract_type' => $this->getContractTypeLabel($data['tipo_contratto']),
        'start_date' => $data['data_inizio']->format('d/m/Y'),
        'end_date' => $data['data_fine'] ? $data['data_fine']->format('d/m/Y') : '',
        'salary_gross' => number_format($data['retribuzione_lorda'], 2, ',', '.'),
        'salary_net' => number_format($data['retribuzione_netta'], 2, ',', '.'),
        'weekly_hours' => $data['ore_settimanali'],
        'weekly_days' => $data['giorni_settimanali'],
        'work_location' => $data['sede_lavoro'] ?? '',
        'department' => $employee->department->nome ?? '',
        'position' => $employee->role->nome ?? '',
    ];
    
    // Genera contenuto
    $content = $this->processTemplate($template->template_content, $variables);
    
    // Crea PDF
    $pdf = $this->generatePDF($content);
    
    // Salva contratto
    $contract = Contract::create([
        'employee_id' => $employee->id,
        'numero_contratto' => $variables['contract_number'],
        'tipo_contratto' => $data['tipo_contratto'],
        'categoria_contratto' => $data['categoria_contratto'],
        'data_inizio' => $data['data_inizio'],
        'data_fine' => $data['data_fine'],
        'data_prova_inizio' => $data['data_prova_inizio'] ?? null,
        'data_prova_fine' => $data['data_prova_fine'] ?? null,
        'retribuzione_lorda' => $data['retribuzione_lorda'],
        'retribuzione_netta' => $data['retribuzione_netta'],
        'indennità' => $data['indennità'] ?? 0.00,
        'benefit' => $data['benefit'] ?? [],
        'ore_settimanali' => $data['ore_settimanali'],
        'giorni_settimanali' => $data['giorni_settimanali'],
        'turno_lavoro' => $data['turno_lavoro'] ?? '',
        'sede_lavoro' => $data['sede_lavoro'] ?? '',
        'stato' => 'attivo',
        'approvato' => false,
    ]);
    
    // Salva documento
    $document = Document::create([
        'employee_id' => $employee->id,
        'titolo' => 'Contratto di lavoro - ' . $employee->nome . ' ' . $employee->cognome,
        'tipo_documento' => 'contratto',
        'categoria' => 'hr',
        'file_path' => $this->saveFile($pdf, 'contracts'),
        'file_name' => 'contratto_' . $variables['contract_number'] . '.pdf',
        'file_size' => strlen($pdf),
        'mime_type' => 'application/pdf',
        'file_hash' => hash('sha256', $pdf),
        'uploaded_by' => auth()->id(),
        'is_private' => true,
        'access_level' => 'confidenziale',
    ]);
    
    return $contract;
}

private function generateContractNumber()
{
    $year = now()->year;
    $count = Contract::whereYear('created_at', $year)->count() + 1;
    return 'CONTR-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
}
```

#### Passo 2: Creare Comandi Artisan
**Dove:** `laravel/Modules/Employee/app/Console/Commands/GenerateContracts.php`

**Cosa fare:**
- Comando per generare contratti automatici
- Generazione contratti di prova
- Generazione rinnovi automatici
- Backup contratti

### 4. **Workflow Approvazioni Contratti**

#### Passo 1: Creare Sistema Approvazioni
**Dove:** `laravel/Modules/Employee/app/Models/ContractApproval.php`

**Cosa fare:**
- Creare modello per approvazioni contratti
- Gestire workflow multi-livello
- Notifiche automatiche
- Storico approvazioni

#### Passo 2: Creare Workflow Approvazioni
**Cosa fare:**
- Workflow per contratti di prova
- Approvazioni per tipo contratto
- Notifiche per scadenze
- Report approvazioni

### 5. **Miglioramenti Rispetto a dipendentincloud.it**

#### Funzionalità Avanzate da Aggiungere:

**A. Compliance Legale**
- Controllo compliance normativa
- Validazione automatica clausole
- Alert per scadenze legali
- Report compliance

**B. Analytics Contratti**
- Analisi costi contratti
- Predizione turnover
- Ottimizzazione retribuzioni
- Benchmark di mercato

**C. Integrazione Payroll**
- Sincronizzazione con sistema payroll
- Calcolo automatico stipendi
- Gestione benefit automatica
- Report economici

**D. AI e Machine Learning**
- Predizione rinnovi contratti
- Analisi performance contratti
- Suggerimenti ottimizzazione
- Risk assessment automatico

### 6. **Report e Analytics**

#### Passo 1: Creare Widget Analytics
**Dove:** `laravel/Modules/Employee/app/Filament/Widgets/ContractAnalyticsWidget.php`

**Cosa fare:**
- Statistiche contratti per tipo
- Analisi costi retribuzioni
- Trend assunzioni
- Report compliance

#### Passo 2: Creare Report Personalizzati
**Dove:** `laravel/Modules/Employee/app/Filament/Pages/ContractReports.php`

**Cosa fare:**
- Report per dipendente
- Report per dipartimento
- Report scadenze contratti
- Export Excel/PDF

### 7. **Validazioni e Regole**

#### Validazioni da Implementare:
```php
// Nel modello Contract
protected static function boot()
{
    parent::boot();
    
    static::saving(function ($contract) {
        // Validazione date
        if ($contract->data_inizio > $contract->data_fine && $contract->data_fine) {
            throw new \Exception('La data di inizio deve essere precedente alla data di fine');
        }
        
        // Validazione periodo di prova
        if ($contract->data_prova_inizio && $contract->data_prova_fine) {
            if ($contract->data_prova_inizio > $contract->data_prova_fine) {
                throw new \Exception('La data di inizio prova deve essere precedente alla data di fine prova');
            }
            
            if ($contract->data_prova_inizio < $contract->data_inizio) {
                throw new \Exception('Il periodo di prova non può iniziare prima della data di inizio contratto');
            }
        }
        
        // Validazione retribuzione
        if ($contract->retribuzione_lorda <= 0) {
            throw new \Exception('La retribuzione lorda deve essere maggiore di zero');
        }
        
        // Validazione orario
        if ($contract->ore_settimanali <= 0 || $contract->ore_settimanali > 168) {
            throw new \Exception('Le ore settimanali devono essere tra 1 e 168');
        }
        
        if ($contract->giorni_settimanali <= 0 || $contract->giorni_settimanali > 7) {
            throw new \Exception('I giorni settimanali devono essere tra 1 e 7');
        }
    });
    
    static::creating(function ($contract) {
        // Genera numero contratto se non fornito
        if (empty($contract->numero_contratto)) {
            $contract->numero_contratto = $this->generateContractNumber();
        }
    });
}
```

### 8. **Traduzioni da Aggiungere**

#### File di Traduzione:
**Dove:** `laravel/Modules/Employee/lang/it/contract.php`

```php
return [
    'title' => 'Gestione Contratti',
    'fields' => [
        'employee_id' => 'Dipendente',
        'numero_contratto' => 'Numero Contratto',
        'tipo_contratto' => 'Tipo Contratto',
        'categoria_contratto' => 'Categoria Contratto',
        'data_inizio' => 'Data Inizio',
        'data_fine' => 'Data Fine',
        'data_prova_inizio' => 'Inizio Prova',
        'data_prova_fine' => 'Fine Prova',
        'retribuzione_lorda' => 'Retribuzione Lorda',
        'retribuzione_netta' => 'Retribuzione Netta',
        'indennità' => 'Indennità',
        'benefit' => 'Benefit',
        'ore_settimanali' => 'Ore Settimanali',
        'giorni_settimanali' => 'Giorni Settimanali',
        'turno_lavoro' => 'Turno di Lavoro',
        'sede_lavoro' => 'Sede di Lavoro',
        'stato' => 'Stato',
        'approvato' => 'Approvato',
        'approvato_da' => 'Approvato da',
        'note' => 'Note',
    ],
    'types' => [
        'tempo_indeterminato' => 'Tempo Indeterminato',
        'tempo_determinato' => 'Tempo Determinato',
        'apprendistato' => 'Apprendistato',
        'collaborazione' => 'Collaborazione',
        'stage' => 'Stage',
    ],
    'categories' => [
        'operai' => 'Operai',
        'impiegati' => 'Impiegati',
        'dirigenti' => 'Dirigenti',
        'quadri' => 'Quadri',
    ],
    'status' => [
        'attivo' => 'Attivo',
        'sospeso' => 'Sospeso',
        'terminato' => 'Terminato',
        'scaduto' => 'Scaduto',
    ],
    'messages' => [
        'created' => 'Contratto creato con successo',
        'updated' => 'Contratto aggiornato con successo',
        'deleted' => 'Contratto eliminato con successo',
        'approved' => 'Contratto approvato',
        'rejected' => 'Contratto rifiutato',
    ],
];
```

### 9. **Test da Implementare**

#### Test Unitari:
```php
// Test creazione contratto
public function test_can_create_contract()
{
    $employee = Employee::factory()->create();
    
    $contractData = [
        'employee_id' => $employee->id,
        'tipo_contratto' => 'tempo_indeterminato',
        'categoria_contratto' => 'impiegati',
        'data_inizio' => now(),
        'retribuzione_lorda' => 2500.00,
        'ore_settimanali' => 40.0,
        'giorni_settimanali' => 5,
    ];
    
    $contract = Contract::create($contractData);
    
    $this->assertDatabaseHas('contracts', $contractData);
}

// Test validazione date
public function test_date_validation()
{
    $this->expectException(\Exception::class);
    
    Contract::create([
        'employee_id' => 1,
        'tipo_contratto' => 'tempo_determinato',
        'categoria_contratto' => 'impiegati',
        'data_inizio' => now()->addDays(10),
        'data_fine' => now(),
        'retribuzione_lorda' => 2500.00,
        'ore_settimanali' => 40.0,
        'giorni_settimanali' => 5,
    ]);
}
```

## ✅ Checklist Completamento

- [ ] Modelli Contract e ContractAmendment creati
- [ ] Migrazioni database create e testate
- [ ] Resources Filament implementati
- [ ] Sistema generazione automatica creato
- [ ] Workflow approvazioni creato
- [ ] Widget analytics implementati
- [ ] Report personalizzati creati
- [ ] Validazioni avanzate implementate
- [ ] Traduzioni aggiunte
- [ ] Test funzionali completati

## 🎯 Risultato Finale

Alla fine di questo sviluppo avrai:
1. **Sistema completo di gestione contratti** che replica dipendentincloud.it
2. **Generazione automatica contratti** con template
3. **Workflow approvazioni** per contratti sensibili
4. **Compliance legale** automatica
5. **Analytics contratti** con predizioni
6. **Integrazione payroll** per calcoli automatici
7. **AI e ML** per ottimizzazione contratti
8. **Test coverage** completo

Il sistema sarà pronto per gestire centinaia di contratti con compliance automatica e analytics predittive.

---

*File creato il: 2025-07-30*
*Modulo: Employee*
*Funzionalità: Gestione Contratti*
*Priorità: ALTA* 