# 05 - Gestione Documenti (Document Management)

## 🎯 Obiettivo
Creare un sistema completo per gestire tutti i documenti dei dipendenti, replicando e migliorando le funzionalità di dipendentincloud.it.

## 📋 Cosa Dobbiamo Fare

### 1. **Modello Document (Documento)**

#### Passo 1: Creare il Modello
**Dove:** `laravel/Modules/Employee/app/Models/Document.php`

**Cosa fare:**
- Creare il file se non esiste
- Definire tutti i campi necessari per un documento
- Aggiungere le relazioni con altri modelli

**Campi da includere:**
```php
// Dati base
- id (chiave primaria)
- employee_id (dipendente proprietario)
- titolo (titolo del documento)
- descrizione (descrizione del documento)
- tipo_documento (contratto, busta_paga, certificato, etc.)
- categoria (hr, amministrativo, tecnico, etc.)

// File
- file_path (percorso del file)
- file_name (nome originale del file)
- file_size (dimensione del file in bytes)
- mime_type (tipo MIME del file)
- file_hash (hash per sicurezza)

// Metadati
- data_upload (data di caricamento)
- data_scadenza (data di scadenza se applicabile)
- versione (versione del documento)
- stato (attivo, scaduto, archiviato)

// Sicurezza
- is_private (se il documento è privato)
- is_confidential (se il documento è confidenziale)
- access_level (livello di accesso richiesto)

// Relazioni
- uploaded_by (chi ha caricato il documento)
- approved_by (chi ha approvato se necessario)
- created_at
- updated_at
```

#### Passo 2: Creare la Migrazione
**Dove:** `laravel/Modules/Employee/database/migrations/2025_07_30_000008_create_documents_table.php`

**Cosa fare:**
- Creare la migrazione per la tabella documents
- Definire tutti i campi con i tipi corretti
- Aggiungere indici per performance
- Aggiungere foreign keys per relazioni

**Campi della tabella:**
```sql
- id (bigint, primary key, auto increment)
- employee_id (bigint, foreign key, not null)
- titolo (varchar(255), not null)
- descrizione (text, nullable)
- tipo_documento (enum('contratto','busta_paga','certificato','visita_medica','altro'), not null)
- categoria (enum('hr','amministrativo','tecnico','legale','altro'), not null)
- file_path (varchar(500), not null)
- file_name (varchar(255), not null)
- file_size (bigint, not null)
- mime_type (varchar(100), not null)
- file_hash (varchar(64), not null)
- data_upload (timestamp, default now())
- data_scadenza (date, nullable)
- versione (varchar(20), default '1.0')
- stato (enum('attivo','scaduto','archiviato'), default 'attivo')
- is_private (boolean, default false)
- is_confidential (boolean, default false)
- access_level (enum('pubblico','privato','confidenziale'), default 'privato')
- uploaded_by (bigint, foreign key, not null)
- approved_by (bigint, foreign key, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

#### Passo 3: Creare il Resource Filament
**Dove:** `laravel/Modules/Employee/app/Filament/Resources/DocumentResource.php`

**Cosa fare:**
- Creare il resource che estende XotBaseResource
- Definire il form per inserimento/modifica
- Definire la tabella per visualizzazione
- Aggiungere filtri e azioni

**Form Schema:**
```php
// Sezione Dati Base
'sezione_base' => Forms\Components\Section::make('Dati Documento')
    ->schema([
        'employee_id' => Forms\Components\Select::make('employee_id')
            ->relationship('employee', 'nome')
            ->searchable()
            ->required(),
        'titolo' => Forms\Components\TextInput::make('titolo')
            ->required()
            ->maxLength(255),
        'descrizione' => Forms\Components\Textarea::make('descrizione')
            ->rows(3),
        'tipo_documento' => Forms\Components\Select::make('tipo_documento')
            ->options([
                'contratto' => 'Contratto di lavoro',
                'busta_paga' => 'Busta paga',
                'certificato' => 'Certificato',
                'visita_medica' => 'Visita medica',
                'altro' => 'Altro'
            ])
            ->required(),
        'categoria' => Forms\Components\Select::make('categoria')
            ->options([
                'hr' => 'Risorse Umane',
                'amministrativo' => 'Amministrativo',
                'tecnico' => 'Tecnico',
                'legale' => 'Legale',
                'altro' => 'Altro'
            ])
            ->required(),
    ])
    ->columns(2),

// Sezione File
'sezione_file' => Forms\Components\Section::make('File')
    ->schema([
        'file' => Forms\Components\FileUpload::make('file')
            ->directory('employee-documents')
            ->visibility('private')
            ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
            ->maxSize(10240) // 10MB
            ->required(),
        'data_scadenza' => Forms\Components\DatePicker::make('data_scadenza')
            ->label('Data scadenza (se applicabile)'),
        'versione' => Forms\Components\TextInput::make('versione')
            ->default('1.0')
            ->maxLength(20),
    ])
    ->columns(3),

// Sezione Sicurezza
'sezione_sicurezza' => Forms\Components\Section::make('Sicurezza e Accesso')
    ->schema([
        'is_private' => Forms\Components\Toggle::make('is_private')
            ->label('Documento privato'),
        'is_confidential' => Forms\Components\Toggle::make('is_confidential')
            ->label('Documento confidenziale'),
        'access_level' => Forms\Components\Select::make('access_level')
            ->options([
                'pubblico' => 'Pubblico',
                'privato' => 'Privato',
                'confidenziale' => 'Confidenziale'
            ])
            ->default('privato')
            ->required(),
        'approved_by' => Forms\Components\Select::make('approved_by')
            ->relationship('approvedBy', 'nome')
            ->searchable()
            ->label('Approvato da'),
    ])
    ->columns(2),
```

### 2. **Modello DocumentTemplate (Template Documenti)**

#### Passo 1: Creare il Modello
**Dove:** `laravel/Modules/Employee/app/Models/DocumentTemplate.php`

**Cosa fare:**
- Creare il file se non esiste
- Definire tutti i campi necessari per un template
- Aggiungere le relazioni con altri modelli

**Campi da includere:**
```php
// Dati base
- id (chiave primaria)
- nome (nome del template)
- descrizione (descrizione del template)
- tipo_template (contratto, lettera, certificato, etc.)
- categoria (hr, amministrativo, tecnico, etc.)

// Template
- template_content (contenuto del template)
- template_variables (variabili disponibili nel template)
- file_template (file template se presente)

// Configurazione
- is_active (se il template è attivo)
- requires_approval (se richiede approvazione)
- auto_generate (se si genera automaticamente)

// Stato
- created_at
- updated_at
```

#### Passo 2: Creare la Migrazione
**Dove:** `laravel/Modules/Employee/database/migrations/2025_07_30_000009_create_document_templates_table.php`

#### Passo 3: Creare il Resource Filament
**Dove:** `laravel/Modules/Employee/app/Filament/Resources/DocumentTemplateResource.php`

### 3. **Sistema di Generazione Automatica**

#### Passo 1: Creare Service per Generazione
**Dove:** `laravel/Modules/Employee/app/Services/DocumentGeneratorService.php`

**Cosa fare:**
- Creare service per generazione documenti automatica
- Gestire template con variabili
- Integrare con librerie PDF
- Gestire firme digitali

**Funzionalità da implementare:**
```php
// Nel service
public function generateContract(Employee $employee, array $data = [])
{
    $template = DocumentTemplate::where('tipo_template', 'contratto')
        ->where('is_active', true)
        ->first();
    
    if (!$template) {
        throw new \Exception('Template contratto non trovato');
    }
    
    // Prepara variabili
    $variables = [
        'employee_name' => $employee->nome . ' ' . $employee->cognome,
        'employee_id' => $employee->matricola,
        'hire_date' => $employee->data_assunzione->format('d/m/Y'),
        'department' => $employee->department->nome ?? '',
        'position' => $employee->role->nome ?? '',
        'salary' => $data['salary'] ?? '',
        'contract_type' => $employee->tipo_contratto ?? '',
    ];
    
    // Genera contenuto
    $content = $this->processTemplate($template->template_content, $variables);
    
    // Crea PDF
    $pdf = $this->generatePDF($content);
    
    // Salva documento
    $document = Document::create([
        'employee_id' => $employee->id,
        'titolo' => 'Contratto di lavoro - ' . $employee->nome . ' ' . $employee->cognome,
        'tipo_documento' => 'contratto',
        'categoria' => 'hr',
        'file_path' => $this->saveFile($pdf, 'contracts'),
        'file_name' => 'contratto_' . $employee->matricola . '.pdf',
        'file_size' => strlen($pdf),
        'mime_type' => 'application/pdf',
        'file_hash' => hash('sha256', $pdf),
        'uploaded_by' => auth()->id(),
        'is_private' => true,
        'access_level' => 'confidenziale',
    ]);
    
    return $document;
}

private function processTemplate($template, $variables)
{
    foreach ($variables as $key => $value) {
        $template = str_replace('{{' . $key . '}}', $value, $template);
    }
    
    return $template;
}
```

#### Passo 2: Creare Comandi Artisan
**Dove:** `laravel/Modules/Employee/app/Console/Commands/GenerateDocuments.php`

**Cosa fare:**
- Comando per generare documenti automatici
- Generazione buste paga mensili
- Generazione certificati automatici
- Backup documenti

### 4. **Sistema di Versioning**

#### Passo 1: Creare Modello DocumentVersion
**Dove:** `laravel/Modules/Employee/app/Models/DocumentVersion.php`

**Cosa fare:**
- Creare modello per versioni documenti
- Gestire storico modifiche
- Mantenere tracciabilità
- Gestire rollback

**Campi del modello:**
```php
- id (chiave primaria)
- document_id (documento padre)
- version_number (numero versione)
- file_path (percorso file versione)
- file_hash (hash file)
- changes_description (descrizione modifiche)
- created_by (chi ha creato la versione)
- created_at
- updated_at
```

#### Passo 2: Creare Migrazione
**Dove:** `laravel/Modules/Employee/database/migrations/2025_07_30_000010_create_document_versions_table.php`

### 5. **Sistema di Approvazioni**

#### Passo 1: Creare Modello DocumentApproval
**Dove:** `laravel/Modules/Employee/app/Models/DocumentApproval.php`

**Cosa fare:**
- Creare modello per approvazioni documenti
- Gestire workflow approvazioni
- Notifiche automatiche
- Storico approvazioni

#### Passo 2: Creare Workflow Approvazioni
**Cosa fare:**
- Workflow multi-livello per documenti sensibili
- Approvazioni per tipo documento
- Notifiche per scadenze
- Report approvazioni

### 6. **Miglioramenti Rispetto a dipendentincloud.it**

#### Funzionalità Avanzate da Aggiungere:

**A. OCR e Estrazione Dati**
- OCR automatico per documenti scansionati
- Estrazione automatica dati da PDF
- Validazione automatica contenuti
- Indexing automatico

**B. Firma Digitale**
- Integrazione firma digitale
- Certificati digitali
- Validazione firme
- Storico firme

**C. AI e Machine Learning**
- Classificazione automatica documenti
- Riconoscimento pattern
- Predizione scadenze
- Suggerimenti automatici

**D. Integrazione Cloud**
- Sincronizzazione con Google Drive
- OneDrive integration
- Dropbox sync
- Backup automatico cloud

### 7. **Report e Analytics**

#### Passo 1: Creare Widget Analytics
**Dove:** `laravel/Modules/Employee/app/Filament/Widgets/DocumentAnalyticsWidget.php`

**Cosa fare:**
- Statistiche documenti per tipo
- Analisi scadenze
- Report storage utilizzato
- Trend caricamenti

#### Passo 2: Creare Report Personalizzati
**Dove:** `laravel/Modules/Employee/app/Filament/Pages/DocumentReports.php`

**Cosa fare:**
- Report per dipendente
- Report per categoria
- Report scadenze
- Export Excel/PDF

### 8. **Validazioni e Regole**

#### Validazioni da Implementare:
```php
// Nel modello Document
protected static function boot()
{
    parent::boot();
    
    static::saving(function ($document) {
        // Validazione dimensione file
        if ($document->file_size > 10 * 1024 * 1024) { // 10MB
            throw new \Exception('Il file è troppo grande. Dimensione massima: 10MB');
        }
        
        // Validazione tipo file
        $allowedTypes = ['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($document->mime_type, $allowedTypes)) {
            throw new \Exception('Tipo di file non supportato');
        }
        
        // Genera hash se non presente
        if (empty($document->file_hash)) {
            $document->file_hash = hash_file('sha256', storage_path('app/' . $document->file_path));
        }
    });
    
    static::created(function ($document) {
        // Crea prima versione
        DocumentVersion::create([
            'document_id' => $document->id,
            'version_number' => '1.0',
            'file_path' => $document->file_path,
            'file_hash' => $document->file_hash,
            'changes_description' => 'Versione iniziale',
            'created_by' => $document->uploaded_by,
        ]);
    });
}
```

### 9. **Traduzioni da Aggiungere**

#### File di Traduzione:
**Dove:** `laravel/Modules/Employee/lang/it/document.php`

```php
return [
    'title' => 'Gestione Documenti',
    'fields' => [
        'employee_id' => 'Dipendente',
        'titolo' => 'Titolo',
        'descrizione' => 'Descrizione',
        'tipo_documento' => 'Tipo Documento',
        'categoria' => 'Categoria',
        'file' => 'File',
        'data_scadenza' => 'Data Scadenza',
        'versione' => 'Versione',
        'is_private' => 'Privato',
        'is_confidential' => 'Confidenziale',
        'access_level' => 'Livello Accesso',
        'approved_by' => 'Approvato da',
    ],
    'types' => [
        'contratto' => 'Contratto di lavoro',
        'busta_paga' => 'Busta paga',
        'certificato' => 'Certificato',
        'visita_medica' => 'Visita medica',
        'altro' => 'Altro',
    ],
    'categories' => [
        'hr' => 'Risorse Umane',
        'amministrativo' => 'Amministrativo',
        'tecnico' => 'Tecnico',
        'legale' => 'Legale',
        'altro' => 'Altro',
    ],
    'access_levels' => [
        'pubblico' => 'Pubblico',
        'privato' => 'Privato',
        'confidenziale' => 'Confidenziale',
    ],
    'messages' => [
        'uploaded' => 'Documento caricato con successo',
        'updated' => 'Documento aggiornato con successo',
        'deleted' => 'Documento eliminato con successo',
        'file_too_large' => 'Il file è troppo grande',
        'invalid_type' => 'Tipo di file non supportato',
    ],
];
```

### 10. **Test da Implementare**

#### Test Unitari:
```php
// Test caricamento documento
public function test_can_upload_document()
{
    $employee = Employee::factory()->create();
    
    $documentData = [
        'employee_id' => $employee->id,
        'titolo' => 'Contratto di lavoro',
        'tipo_documento' => 'contratto',
        'categoria' => 'hr',
        'file_name' => 'contratto.pdf',
        'file_size' => 1024,
        'mime_type' => 'application/pdf',
    ];
    
    $document = Document::create($documentData);
    
    $this->assertDatabaseHas('documents', $documentData);
}

// Test validazione file
public function test_file_size_validation()
{
    $this->expectException(\Exception::class);
    
    Document::create([
        'employee_id' => 1,
        'titolo' => 'Test',
        'file_size' => 15 * 1024 * 1024, // 15MB
        'mime_type' => 'application/pdf',
    ]);
}
```

## ✅ Checklist Completamento

- [ ] Modelli Document e DocumentTemplate creati
- [ ] Migrazioni database create e testate
- [ ] Resources Filament implementati
- [ ] Sistema generazione automatica creato
- [ ] Sistema versioning implementato
- [ ] Workflow approvazioni creato
- [ ] Widget analytics implementati
- [ ] Report personalizzati creati
- [ ] Validazioni avanzate implementate
- [ ] Traduzioni aggiunte
- [ ] Test funzionali completati

## 🎯 Risultato Finale

Alla fine di questo sviluppo avrai:
1. **Sistema completo di gestione documenti** che replica dipendentincloud.it
2. **Generazione automatica documenti** con template
3. **Sistema versioning avanzato** per tracciabilità
4. **Workflow approvazioni** per documenti sensibili
5. **OCR e estrazione dati** automatica
6. **Integrazione firma digitale** per sicurezza
7. **AI e ML** per classificazione automatica
8. **Test coverage** completo

Il sistema sarà pronto per gestire migliaia di documenti con sicurezza avanzata e automazione intelligente.

---

*File creato il: 2025-07-30*
*Modulo: Employee*
*Funzionalità: Gestione Documenti*
*Priorità: ALTA* 