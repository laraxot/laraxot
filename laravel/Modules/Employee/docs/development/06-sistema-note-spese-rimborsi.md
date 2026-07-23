# 06 - Sistema Note Spese e Rimborsi

## Panoramica
Sistema completo per la gestione delle note spese aziendali, con workflow di approvazione automatizzato, calcolo automatico rimborsi chilometrici e integrazione con sistemi contabili.

## Obiettivi
- Digitalizzare completamente il processo di note spese
- Automatizzare calcoli rimborsi chilometrici
- Implementare workflow approvazione gerarchico
- Fornire controlli automatici per policy aziendali
- Integrare con sistemi contabili per elaborazione pagamenti
- Tracciare stato rimborsi in tempo reale

## Funzionalità da Implementare

### 1. Creazione Note Spese

#### 1.1 Form Interattivo per Dipendenti
**Obiettivo**: Permettere ai dipendenti di creare note spese in modo semplice e intuitivo

**Implementazione Step-by-Step**:

1. **Creare il Model ExpenseReport**
```php
// app/Models/ExpenseReport.php
class ExpenseReport extends Model
{
    protected $fillable = [
        'employee_id',
        'title',
        'description',
        'submission_date',
        'period_start',
        'period_end',
        'total_amount',
        'status', // draft, submitted, approved, rejected, paid
        'submitted_at',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'payment_date',
        'payment_reference'
    ];

    protected $casts = [
        'submission_date' => 'date',
        'period_start' => 'date',
        'period_end' => 'date',
        'total_amount' => 'decimal:2',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'payment_date' => 'datetime'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }
}
```

2. **Creare il Model Expense**
```php
// app/Models/Expense.php
class Expense extends Model
{
    protected $fillable = [
        'expense_report_id',
        'expense_category_id',
        'date',
        'description',
        'amount',
        'currency',
        'exchange_rate',
        'amount_eur',
        'vat_amount',
        'is_vat_deductible',
        'supplier_name',
        'supplier_vat',
        'receipt_number',
        'payment_method', // cash, card, bank_transfer
        'is_mileage',
        'mileage_km',
        'mileage_rate',
        'start_location',
        'end_location',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'amount_eur' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'is_vat_deductible' => 'boolean',
        'is_mileage' => 'boolean',
        'mileage_km' => 'decimal:2',
        'mileage_rate' => 'decimal:3'
    ];

    public function expenseReport()
    {
        return $this->belongsTo(ExpenseReport::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function attachments()
    {
        return $this->hasMany(ExpenseAttachment::class);
    }
}
```

3. **Creare il Model ExpenseCategory**
```php
// app/Models/ExpenseCategory.php
class ExpenseCategory extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'max_amount_per_expense',
        'max_amount_per_month',
        'requires_receipt',
        'requires_approval',
        'is_mileage_category',
        'default_mileage_rate',
        'is_active'
    ];

    protected $casts = [
        'max_amount_per_expense' => 'decimal:2',
        'max_amount_per_month' => 'decimal:2',
        'requires_receipt' => 'boolean',
        'requires_approval' => 'boolean',
        'is_mileage_category' => 'boolean',
        'default_mileage_rate' => 'decimal:3',
        'is_active' => 'boolean'
    ];
}
```

4. **Creare Migration per expense_reports**
```php
Schema::create('expense_reports', function (Blueprint $table) {
    $table->id();
    $table->foreignId('employee_id')->constrained()->onDelete('cascade');
    $table->string('title');
    $table->text('description')->nullable();
    $table->date('submission_date');
    $table->date('period_start');
    $table->date('period_end');
    $table->decimal('total_amount', 10, 2)->default(0);
    $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'paid'])->default('draft');
    $table->timestamp('submitted_at')->nullable();
    $table->foreignId('approved_by')->nullable()->constrained('employees');
    $table->timestamp('approved_at')->nullable();
    $table->text('rejection_reason')->nullable();
    $table->date('payment_date')->nullable();
    $table->string('payment_reference')->nullable();
    $table->timestamps();
    
    $table->index(['employee_id', 'status']);
    $table->index(['submission_date']);
});
```

5. **Creare Filament Resource ExpenseReportResource**
```php
// app/Filament/Resources/ExpenseReportResource.php
class ExpenseReportResource extends XotBaseResource
{
    protected static ?string $model = ExpenseReport::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $navigationGroup = 'Gestione Spese';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informazioni Generali')->schema([
                TextInput::make('title')
                    ->label('Titolo Nota Spese')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('es. Trasferta Milano - Gennaio 2024'),
                    
                Textarea::make('description')
                    ->label('Descrizione')
                    ->maxLength(1000)
                    ->placeholder('Descrizione dettagliata della trasferta o delle spese'),
                    
                DatePicker::make('period_start')
                    ->label('Periodo Dal')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => 
                        $set('submission_date', $state)
                    ),
                    
                DatePicker::make('period_end')
                    ->label('Periodo Al')
                    ->required()
                    ->afterOrEqual('period_start'),
                    
                DatePicker::make('submission_date')
                    ->label('Data Presentazione')
                    ->required()
                    ->default(now())
            ]),
            
            Section::make('Spese')->schema([
                Repeater::make('expenses')
                    ->label('Elenco Spese')
                    ->relationship()
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('expense_category_id')
                                ->label('Categoria')
                                ->relationship('category', 'name')
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $category = ExpenseCategory::find($state);
                                    if ($category?->is_mileage_category) {
                                        $set('is_mileage', true);
                                        $set('mileage_rate', $category->default_mileage_rate);
                                    }
                                }),
                                
                            DatePicker::make('date')
                                ->label('Data')
                                ->required()
                                ->default(now()),
                                
                            TextInput::make('description')
                                ->label('Descrizione')
                                ->required()
                                ->placeholder('Descrizione della spesa')
                        ]),
                        
                        Grid::make(4)->schema([
                            TextInput::make('amount')
                                ->label('Importo')
                                ->numeric()
                                ->required()
                                ->prefix('€')
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    if ($get('is_mileage') && $get('mileage_km') && $get('mileage_rate')) {
                                        $amount = $get('mileage_km') * $get('mileage_rate');
                                        $set('amount', $amount);
                                    }
                                }),
                                
                            Select::make('payment_method')
                                ->label('Metodo Pagamento')
                                ->options([
                                    'cash' => 'Contanti',
                                    'card' => 'Carta',
                                    'bank_transfer' => 'Bonifico'
                                ])
                                ->required(),
                                
                            TextInput::make('supplier_name')
                                ->label('Fornitore')
                                ->placeholder('Nome fornitore/esercente'),
                                
                            TextInput::make('receipt_number')
                                ->label('Numero Scontrino')
                                ->placeholder('Numero documento fiscale')
                        ]),
                        
                        // Sezione Rimborso Chilometrico
                        Group::make([
                            Toggle::make('is_mileage')
                                ->label('Rimborso Chilometrico')
                                ->reactive(),
                                
                            Grid::make(4)->schema([
                                TextInput::make('mileage_km')
                                    ->label('Chilometri')
                                    ->numeric()
                                    ->suffix('km')
                                    ->visible(fn (callable $get) => $get('is_mileage'))
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state && $get('mileage_rate')) {
                                            $amount = $state * $get('mileage_rate');
                                            $set('amount', $amount);
                                        }
                                    }),
                                    
                                TextInput::make('mileage_rate')
                                    ->label('Tariffa/km')
                                    ->numeric()
                                    ->prefix('€')
                                    ->step(0.001)
                                    ->visible(fn (callable $get) => $get('is_mileage'))
                                    ->reactive(),
                                    
                                TextInput::make('start_location')
                                    ->label('Partenza')
                                    ->visible(fn (callable $get) => $get('is_mileage')),
                                    
                                TextInput::make('end_location')
                                    ->label('Destinazione')
                                    ->visible(fn (callable $get) => $get('is_mileage'))
                            ])
                        ]),
                        
                        Textarea::make('notes')
                            ->label('Note')
                            ->maxLength(500)
                    ])
                    ->collapsible()
                    ->cloneable()
                    ->deleteAction(fn (Action $action) => $action->requiresConfirmation())
                    ->addActionLabel('Aggiungi Spesa')
            ]),
            
            Section::make('Riepilogo')->schema([
                Placeholder::make('total_calculated')
                    ->label('Totale Calcolato')
                    ->content(function (callable $get) {
                        $expenses = $get('expenses') ?? [];
                        $total = collect($expenses)->sum('amount');
                        return '€ ' . number_format($total, 2, ',', '.');
                    })
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Titolo')
                    ->searchable()
                    ->limit(50),
                    
                TextColumn::make('employee.full_name')
                    ->label('Dipendente')
                    ->searchable(['employees.name', 'employees.surname']),
                    
                TextColumn::make('period_start')
                    ->label('Periodo')
                    ->formatStateUsing(fn ($record) => 
                        $record->period_start->format('d/m/Y') . ' - ' . $record->period_end->format('d/m/Y')
                    ),
                    
                TextColumn::make('total_amount')
                    ->label('Totale')
                    ->money('EUR')
                    ->alignEnd(),
                    
                TextColumn::make('status')
                    ->label('Stato')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'submitted' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'paid' => 'info',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Bozza',
                        'submitted' => 'Inviata',
                        'approved' => 'Approvata',
                        'rejected' => 'Rifiutata',
                        'paid' => 'Pagata',
                    }),
                    
                TextColumn::make('submission_date')
                    ->label('Data Invio')
                    ->date('d/m/Y')
                    ->sortable()
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Bozza',
                        'submitted' => 'Inviata',
                        'approved' => 'Approvata',
                        'rejected' => 'Rifiutata',
                        'paid' => 'Pagata'
                    ]),
                    
                Filter::make('period')
                    ->form([
                        DatePicker::make('from')->label('Dal'),
                        DatePicker::make('until')->label('Al')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->where('period_start', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->where('period_end', '<=', $date));
                    })
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approva')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'submitted')
                    ->action(function (ExpenseReport $record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_by' => auth()->user()->employee->id,
                            'approved_at' => now()
                        ]);
                    }),
                    
                Action::make('reject')
                    ->label('Rifiuta')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'submitted')
                    ->form([
                        Textarea::make('rejection_reason')
                            ->label('Motivo Rifiuto')
                            ->required()
                    ])
                    ->action(function (ExpenseReport $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'rejection_reason' => $data['rejection_reason']
                        ]);
                    })
            ]);
    }
}
```

### 2. Calcolo Automatico Rimborsi Chilometrici

#### 2.1 Servizio Calcolo Rimborsi
**Obiettivo**: Automatizzare il calcolo dei rimborsi chilometrici con tariffe aggiornate

**Implementazione**:

1. **Creare Service MileageCalculationService**
```php
// app/Services/MileageCalculationService.php
class MileageCalculationService
{
    public function calculateMileageExpense(array $data): array
    {
        $startLocation = $data['start_location'];
        $endLocation = $data['end_location'];
        $categoryId = $data['expense_category_id'];
        
        // Calcola distanza automaticamente se possibile
        $distance = $this->calculateDistance($startLocation, $endLocation);
        
        // Ottieni tariffa dalla categoria
        $category = ExpenseCategory::find($categoryId);
        $rate = $category->default_mileage_rate ?? config('expenses.default_mileage_rate', 0.20);
        
        // Calcola importo
        $amount = $distance * $rate;
        
        return [
            'mileage_km' => $distance,
            'mileage_rate' => $rate,
            'amount' => $amount,
            'calculation_method' => $distance > 0 ? 'automatic' : 'manual'
        ];
    }
    
    private function calculateDistance(string $start, string $end): float
    {
        // Integrazione con Google Maps API o servizio simile
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                'query' => [
                    'origins' => $start,
                    'destinations' => $end,
                    'units' => 'metric',
                    'key' => config('services.google_maps.api_key')
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            if ($data['status'] === 'OK' && isset($data['rows'][0]['elements'][0]['distance'])) {
                $distanceInMeters = $data['rows'][0]['elements'][0]['distance']['value'];
                return round($distanceInMeters / 1000, 2); // Converti in km
            }
        } catch (Exception $e) {
            Log::warning('Errore calcolo distanza automatico: ' . $e->getMessage());
        }
        
        return 0; // Richiederà inserimento manuale
    }
    
    public function getMonthlyMileageReport(Employee $employee, int $month, int $year): array
    {
        $expenses = Expense::whereHas('expenseReport', function ($query) use ($employee) {
                $query->where('employee_id', $employee->id);
            })
            ->where('is_mileage', true)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();
            
        return [
            'total_km' => $expenses->sum('mileage_km'),
            'total_amount' => $expenses->sum('amount'),
            'trip_count' => $expenses->count(),
            'average_rate' => $expenses->avg('mileage_rate'),
            'expenses' => $expenses
        ];
    }
}
```

### 3. Workflow di Approvazione

#### 3.1 Sistema Approvazione Gerarchico
**Obiettivo**: Implementare approvazione automatica basata su importi e gerarchia

**Implementazione**:

1. **Creare Service ExpenseApprovalService**
```php
// app/Services/ExpenseApprovalService.php
class ExpenseApprovalService
{
    public function processSubmission(ExpenseReport $expenseReport): void
    {
        // Valida la nota spese
        $this->validateExpenseReport($expenseReport);
        
        // Determina se richiede approvazione
        if ($this->requiresApproval($expenseReport)) {
            $this->initiateApprovalWorkflow($expenseReport);
        } else {
            // Auto-approva se sotto soglia
            $this->autoApprove($expenseReport);
        }
    }
    
    private function requiresApproval(ExpenseReport $expenseReport): bool
    {
        // Controlla soglie di approvazione
        $autoApprovalLimit = config('expenses.auto_approval_limit', 100);
        
        if ($expenseReport->total_amount > $autoApprovalLimit) {
            return true;
        }
        
        // Controlla se ci sono categorie che richiedono sempre approvazione
        $requiresApproval = $expenseReport->expenses()
            ->whereHas('category', function ($query) {
                $query->where('requires_approval', true);
            })
            ->exists();
            
        return $requiresApproval;
    }
    
    private function initiateApprovalWorkflow(ExpenseReport $expenseReport): void
    {
        $employee = $expenseReport->employee;
        $approver = $this->getApprover($employee, $expenseReport);
        
        if ($approver) {
            $expenseReport->update(['status' => 'submitted', 'submitted_at' => now()]);
            $approver->notify(new ExpenseReportPendingApproval($expenseReport));
        }
    }
    
    private function getApprover(Employee $employee, ExpenseReport $expenseReport): ?Employee
    {
        // Logica per determinare l'approvatore
        // Può essere basata su:
        // - Manager diretto
        // - Importo della spesa
        // - Dipartimento
        
        if ($expenseReport->total_amount > 1000) {
            // Spese elevate vanno al direttore
            return Employee::where('role', 'director')->first();
        }
        
        // Spese normali al manager diretto
        return $employee->manager;
    }
    
    private function validateExpenseReport(ExpenseReport $expenseReport): void
    {
        $violations = [];
        
        foreach ($expenseReport->expenses as $expense) {
            $category = $expense->category;
            
            // Controlla limiti per categoria
            if ($category->max_amount_per_expense && $expense->amount > $category->max_amount_per_expense) {
                $violations[] = "Spesa '{$expense->description}' supera il limite di {$category->max_amount_per_expense}€ per categoria {$category->name}";
            }
            
            // Controlla se richiede scontrino
            if ($category->requires_receipt && $expense->attachments->isEmpty()) {
                $violations[] = "Spesa '{$expense->description}' richiede allegato scontrino";
            }
        }
        
        if (!empty($violations)) {
            throw new ValidationException('Violazioni policy aziendali: ' . implode(', ', $violations));
        }
    }
}
```

### 4. Sistema Allegati e Scontrini

#### 4.1 Gestione Upload Documenti
**Obiettivo**: Permettere upload e gestione scontrini/ricevute

**Implementazione**:

1. **Creare Model ExpenseAttachment**
```php
// app/Models/ExpenseAttachment.php
class ExpenseAttachment extends Model
{
    protected $fillable = [
        'expense_id',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'uploaded_by'
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}
```

2. **Aggiungere al Form ExpenseReportResource**
```php
// Aggiungere al repeater expenses
FileUpload::make('attachments')
    ->label('Scontrini/Ricevute')
    ->relationship('attachments', 'file_name')
    ->multiple()
    ->acceptedFileTypes(['image/*', 'application/pdf'])
    ->disk('private')
    ->directory('expense-attachments')
    ->visibility('private')
    ->downloadable()
    ->previewable()
    ->helperText('Carica scontrini, ricevute o documenti giustificativi')
```

## Checklist Implementazione

### Phase 1: Base Models e Database
- [ ] Creare migrations per expense_reports, expenses, expense_categories, expense_attachments
- [ ] Implementare Models con relazioni complete
- [ ] Creare Seeders per categorie spese standard

### Phase 2: Core Services
- [ ] Implementare MileageCalculationService per rimborsi chilometrici
- [ ] Creare ExpenseApprovalService per workflow approvazioni
- [ ] Implementare validazioni policy aziendali

### Phase 3: UI Filament
- [ ] Creare ExpenseReportResource con form completo
- [ ] Implementare gestione allegati scontrini
- [ ] Creare dashboard statistiche spese

### Phase 4: Automazioni
- [ ] Implementare Notifications per approvazioni
- [ ] Creare Jobs per calcoli automatici
- [ ] Aggiungere integrazione Google Maps per distanze

### Phase 5: Advanced Features
- [ ] Sistema export per contabilità
- [ ] Dashboard analytics spese
- [ ] API per app mobile

## Note Tecniche

### Sicurezza
- Validare sempre autorizzazioni per accesso spese
- Crittografare allegati sensibili
- Audit trail per tutte le approvazioni

### Performance
- Indicizzare tabelle per query frequenti
- Cache per calcoli statistiche
- Ottimizzare upload file grandi

### Integrazione
- API Google Maps per calcolo distanze
- Integrazione sistemi contabili
- Export formati standard (Excel, CSV)

Questo sistema fornirà una gestione completa delle note spese con automazione avanzata e controlli rigorosi.
