# 11 - Integrazione Consulenti del Lavoro

## Panoramica
Sistema completo di integrazione con consulenti del lavoro esterni per export automatico dati, condivisione sicura informazioni payroll, gestione accessi temporanei e compliance normativa.

## Obiettivi
- Automatizzare export dati per consulenti esterni
- Fornire accesso sicuro e limitato ai dati necessari
- Garantire compliance normativa e privacy
- Implementare audit trail completo per accessi esterni
- Gestire workflow approvazione per condivisioni dati
- Integrare con sistemi contabili esterni

## Funzionalità da Implementare

### 1. Sistema Gestione Consulenti

#### 1.1 Anagrafica Consulenti Esterni
**Obiettivo**: Gestire anagrafica e autorizzazioni consulenti del lavoro

**Implementazione Step-by-Step**:

1. **Creare il Model ExternalConsultant**
```php
// app/Models/ExternalConsultant.php
class ExternalConsultant extends Model
{
    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'vat_number',
        'tax_code',
        'address',
        'city',
        'postal_code',
        'province',
        'specialization', // payroll, hr, legal, tax
        'certification_number',
        'certification_expiry',
        'is_active',
        'contract_start_date',
        'contract_end_date',
        'access_level', // read_only, limited_write, full_access
        'allowed_data_types',
        'ip_whitelist',
        'notes'
    ];

    protected $casts = [
        'certification_expiry' => 'date',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'is_active' => 'boolean',
        'allowed_data_types' => 'array',
        'ip_whitelist' => 'array'
    ];

    public function dataExports()
    {
        return $this->hasMany(ConsultantDataExport::class);
    }

    public function accessLogs()
    {
        return $this->hasMany(ConsultantAccessLog::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'consultant_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('contract_end_date')
                          ->orWhere('contract_end_date', '>=', now());
                    });
    }
}
```

2. **Creare il Model ConsultantDataExport**
```php
// app/Models/ConsultantDataExport.php
class ConsultantDataExport extends Model
{
    protected $fillable = [
        'external_consultant_id',
        'export_type', // payroll, attendance, employees, leaves
        'period_start',
        'period_end',
        'file_path',
        'file_name',
        'file_size',
        'file_hash',
        'encryption_key',
        'requested_by',
        'approved_by',
        'status', // pending, approved, rejected, generated, downloaded
        'download_count',
        'expires_at',
        'notes'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'expires_at' => 'datetime'
    ];

    public function consultant()
    {
        return $this->belongsTo(ExternalConsultant::class, 'external_consultant_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
```

3. **Creare Migration per external_consultants**
```php
Schema::create('external_consultants', function (Blueprint $table) {
    $table->id();
    $table->string('company_name');
    $table->string('contact_person');
    $table->string('email');
    $table->string('phone')->nullable();
    $table->string('vat_number');
    $table->string('tax_code');
    $table->text('address');
    $table->string('city');
    $table->string('postal_code');
    $table->string('province');
    $table->enum('specialization', ['payroll', 'hr', 'legal', 'tax']);
    $table->string('certification_number')->nullable();
    $table->date('certification_expiry')->nullable();
    $table->boolean('is_active')->default(true);
    $table->date('contract_start_date');
    $table->date('contract_end_date')->nullable();
    $table->enum('access_level', ['read_only', 'limited_write', 'full_access'])->default('read_only');
    $table->json('allowed_data_types')->nullable();
    $table->json('ip_whitelist')->nullable();
    $table->text('notes')->nullable();
    $table->timestamps();
    
    $table->index(['is_active', 'specialization']);
    $table->index(['contract_end_date']);
});
```

4. **Creare Filament Resource ExternalConsultantResource**
```php
// app/Filament/Resources/ExternalConsultantResource.php
class ExternalConsultantResource extends XotBaseResource
{
    protected static ?string $model = ExternalConsultant::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Consulenti Esterni';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informazioni Azienda')->schema([
                TextInput::make('company_name')
                    ->label('Ragione Sociale')
                    ->required()
                    ->maxLength(255),
                    
                TextInput::make('contact_person')
                    ->label('Persona di Contatto')
                    ->required()
                    ->maxLength(255),
                    
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
                    
                TextInput::make('phone')
                    ->label('Telefono')
                    ->tel(),
                    
                TextInput::make('vat_number')
                    ->label('Partita IVA')
                    ->required()
                    ->maxLength(20),
                    
                TextInput::make('tax_code')
                    ->label('Codice Fiscale')
                    ->required()
                    ->maxLength(20)
            ]),
            
            Section::make('Indirizzo')->schema([
                Textarea::make('address')
                    ->label('Indirizzo')
                    ->required()
                    ->maxLength(500),
                    
                Grid::make(3)->schema([
                    TextInput::make('city')
                        ->label('Città')
                        ->required(),
                        
                    TextInput::make('postal_code')
                        ->label('CAP')
                        ->required()
                        ->maxLength(10),
                        
                    TextInput::make('province')
                        ->label('Provincia')
                        ->required()
                        ->maxLength(5)
                ])
            ]),
            
            Section::make('Specializzazione')->schema([
                Select::make('specialization')
                    ->label('Specializzazione')
                    ->options([
                        'payroll' => 'Gestione Paghe',
                        'hr' => 'Risorse Umane',
                        'legal' => 'Legale del Lavoro',
                        'tax' => 'Fiscale'
                    ])
                    ->required(),
                    
                TextInput::make('certification_number')
                    ->label('Numero Abilitazione')
                    ->maxLength(50),
                    
                DatePicker::make('certification_expiry')
                    ->label('Scadenza Abilitazione')
            ]),
            
            Section::make('Contratto')->schema([
                DatePicker::make('contract_start_date')
                    ->label('Inizio Contratto')
                    ->required(),
                    
                DatePicker::make('contract_end_date')
                    ->label('Fine Contratto')
                    ->nullable(),
                    
                Toggle::make('is_active')
                    ->label('Attivo')
                    ->default(true)
            ]),
            
            Section::make('Autorizzazioni')->schema([
                Select::make('access_level')
                    ->label('Livello Accesso')
                    ->options([
                        'read_only' => 'Solo Lettura',
                        'limited_write' => 'Scrittura Limitata',
                        'full_access' => 'Accesso Completo'
                    ])
                    ->default('read_only')
                    ->required(),
                    
                CheckboxList::make('allowed_data_types')
                    ->label('Tipi Dati Autorizzati')
                    ->options([
                        'employees' => 'Anagrafica Dipendenti',
                        'attendance' => 'Presenze',
                        'payroll' => 'Buste Paga',
                        'leaves' => 'Ferie e Permessi',
                        'expenses' => 'Note Spese',
                        'contracts' => 'Contratti'
                    ])
                    ->columns(2),
                    
                TagsInput::make('ip_whitelist')
                    ->label('IP Autorizzati')
                    ->helperText('Lista IP da cui il consulente può accedere'),
                    
                Textarea::make('notes')
                    ->label('Note')
                    ->maxLength(1000)
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company_name')
                    ->label('Azienda')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('contact_person')
                    ->label('Contatto')
                    ->searchable(),
                    
                TextColumn::make('specialization')
                    ->label('Specializzazione')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'payroll' => 'success',
                        'hr' => 'info',
                        'legal' => 'warning',
                        'tax' => 'danger',
                    }),
                    
                TextColumn::make('access_level')
                    ->label('Accesso')
                    ->badge(),
                    
                TextColumn::make('contract_end_date')
                    ->label('Scadenza Contratto')
                    ->date('d/m/Y')
                    ->color(fn ($state) => $state && $state->isPast() ? 'danger' : null),
                    
                IconColumn::make('is_active')
                    ->label('Attivo')
                    ->boolean(),
                    
                TextColumn::make('dataExports_count')
                    ->label('Export')
                    ->counts('dataExports')
                    ->alignCenter()
            ])
            ->filters([
                SelectFilter::make('specialization')
                    ->options([
                        'payroll' => 'Gestione Paghe',
                        'hr' => 'Risorse Umane',
                        'legal' => 'Legale del Lavoro',
                        'tax' => 'Fiscale'
                    ]),
                    
                Filter::make('is_active')
                    ->toggle(),
                    
                Filter::make('contract_expiring')
                    ->label('Contratto in Scadenza')
                    ->query(fn ($query) => $query->where('contract_end_date', '<=', now()->addMonth()))
            ])
            ->actions([
                Action::make('create_export')
                    ->label('Crea Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => route('filament.admin.resources.consultant-data-exports.create', ['consultant' => $record->id]))
            ]);
    }
}
```

### 2. Sistema Export Dati Automatico

#### 2.1 Generazione Export Personalizzati
**Obiettivo**: Creare export dati personalizzati per ogni consulente

**Implementazione**:

1. **Creare Service ConsultantExportService**
```php
// app/Services/ConsultantExportService.php
class ConsultantExportService
{
    public function generateExport(ConsultantDataExport $export): string
    {
        $consultant = $export->consultant;
        
        // Verifica autorizzazioni
        if (!$this->canExportDataType($consultant, $export->export_type)) {
            throw new UnauthorizedException('Consulente non autorizzato per questo tipo di dati');
        }
        
        $data = $this->collectData($export);
        $filePath = $this->createExportFile($export, $data);
        
        // Crittografa il file
        $encryptedPath = $this->encryptFile($filePath, $export);
        
        // Aggiorna record export
        $export->update([
            'file_path' => $encryptedPath,
            'file_size' => filesize($encryptedPath),
            'file_hash' => hash_file('sha256', $encryptedPath),
            'status' => 'generated'
        ]);
        
        return $encryptedPath;
    }
    
    private function collectData(ConsultantDataExport $export): array
    {
        return match($export->export_type) {
            'payroll' => $this->collectPayrollData($export),
            'attendance' => $this->collectAttendanceData($export),
            'employees' => $this->collectEmployeeData($export),
            'leaves' => $this->collectLeaveData($export),
            'expenses' => $this->collectExpenseData($export),
            default => throw new InvalidArgumentException('Tipo export non supportato')
        };
    }
    
    private function collectPayrollData(ConsultantDataExport $export): array
    {
        return Employee::with(['payrollRecords' => function ($query) use ($export) {
                $query->whereBetween('period_date', [$export->period_start, $export->period_end]);
            }])
            ->where('is_active', true)
            ->get()
            ->map(function ($employee) {
                return [
                    'employee_code' => $employee->employee_code,
                    'tax_code' => $employee->tax_code,
                    'name' => $employee->name,
                    'surname' => $employee->surname,
                    'hire_date' => $employee->hire_date,
                    'contract_type' => $employee->contract_type,
                    'department' => $employee->department->name,
                    'payroll_records' => $employee->payrollRecords->map(function ($record) {
                        return [
                            'period' => $record->period_date,
                            'gross_salary' => $record->gross_salary,
                            'net_salary' => $record->net_salary,
                            'tax_deductions' => $record->tax_deductions,
                            'social_contributions' => $record->social_contributions,
                            'overtime_hours' => $record->overtime_hours,
                            'overtime_amount' => $record->overtime_amount
                        ];
                    })
                ];
            })
            ->toArray();
    }
    
    private function collectAttendanceData(ConsultantDataExport $export): array
    {
        return Attendance::with('employee')
            ->whereBetween('date', [$export->period_start, $export->period_end])
            ->get()
            ->map(function ($attendance) {
                return [
                    'employee_code' => $attendance->employee->employee_code,
                    'date' => $attendance->date,
                    'check_in' => $attendance->check_in,
                    'check_out' => $attendance->check_out,
                    'hours_worked' => $attendance->hours_worked,
                    'overtime_hours' => $attendance->overtime_hours,
                    'break_duration' => $attendance->break_duration,
                    'status' => $attendance->status
                ];
            })
            ->toArray();
    }
    
    private function createExportFile(ConsultantDataExport $export, array $data): string
    {
        $fileName = sprintf(
            '%s_%s_%s_%s.xlsx',
            $export->export_type,
            $export->consultant->company_name,
            $export->period_start->format('Y-m'),
            now()->format('YmdHis')
        );
        
        $filePath = storage_path('app/consultant-exports/' . $fileName);
        
        // Crea directory se non esiste
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }
        
        // Genera file Excel
        Excel::store(new ConsultantExport($data, $export->export_type), 'consultant-exports/' . $fileName);
        
        return $filePath;
    }
    
    private function encryptFile(string $filePath, ConsultantDataExport $export): string
    {
        $content = file_get_contents($filePath);
        $encryptionKey = Str::random(32);
        
        // Crittografa contenuto
        $encryptedContent = encrypt($content, $encryptionKey);
        
        // Salva file crittografato
        $encryptedPath = $filePath . '.enc';
        file_put_contents($encryptedPath, $encryptedContent);
        
        // Salva chiave crittografia (separatamente e sicura)
        $export->update(['encryption_key' => encrypt($encryptionKey)]);
        
        // Rimuovi file originale
        unlink($filePath);
        
        return $encryptedPath;
    }
}
```

2. **Creare Export Excel Personalizzato**
```php
// app/Exports/ConsultantExport.php
class ConsultantExport implements FromArray, WithHeadings, WithStyles
{
    public function __construct(
        private array $data,
        private string $exportType
    ) {}
    
    public function array(): array
    {
        return $this->data;
    }
    
    public function headings(): array
    {
        return match($this->exportType) {
            'payroll' => [
                'Codice Dipendente', 'Codice Fiscale', 'Nome', 'Cognome',
                'Data Assunzione', 'Tipo Contratto', 'Dipartimento',
                'Periodo', 'Lordo', 'Netto', 'Ritenute', 'Contributi',
                'Ore Straordinario', 'Importo Straordinario'
            ],
            'attendance' => [
                'Codice Dipendente', 'Data', 'Entrata', 'Uscita',
                'Ore Lavorate', 'Ore Straordinario', 'Pausa', 'Stato'
            ],
            default => ['Dati']
        };
    }
    
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A:Z' => ['alignment' => ['wrapText' => true]]
        ];
    }
}
```

### 3. Portale Consulenti Sicuro

#### 3.1 Dashboard Consulenti
**Obiettivo**: Fornire interfaccia dedicata per consulenti esterni

**Implementazione**:

1. **Creare Filament Panel per Consulenti**
```php
// app/Providers/Filament/ConsultantPanelProvider.php
class ConsultantPanelProvider extends XotBasePanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('consultant')
            ->path('/consultant')
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Consultant/Resources'), for: 'App\\Filament\\Consultant\\Resources')
            ->discoverPages(in: app_path('Filament/Consultant/Pages'), for: 'App\\Filament\\Consultant\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Consultant/Widgets'), for: 'App\\Filament\\Consultant\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                'consultant.access'
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
```

2. **Creare Middleware ConsultantAccess**
```php
// app/Http/Middleware/ConsultantAccessMiddleware.php
class ConsultantAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if (!$user || !$user->consultant_id) {
            abort(403, 'Accesso negato: utente non autorizzato');
        }
        
        $consultant = ExternalConsultant::find($user->consultant_id);
        
        if (!$consultant || !$consultant->is_active) {
            abort(403, 'Accesso negato: consulente non attivo');
        }
        
        // Verifica IP whitelist
        if (!empty($consultant->ip_whitelist) && !in_array($request->ip(), $consultant->ip_whitelist)) {
            abort(403, 'Accesso negato: IP non autorizzato');
        }
        
        // Verifica scadenza contratto
        if ($consultant->contract_end_date && $consultant->contract_end_date->isPast()) {
            abort(403, 'Accesso negato: contratto scaduto');
        }
        
        return $next($request);
    }
}
```

## Checklist Implementazione

### Phase 1: Base Models e Database
- [ ] Creare migrations per external_consultants, consultant_data_exports
- [ ] Implementare Models con relazioni
- [ ] Configurare sistema autorizzazioni

### Phase 2: Export System
- [ ] Implementare ConsultantExportService
- [ ] Creare export personalizzati per tipo dati
- [ ] Sistema crittografia file

### Phase 3: Portale Consulenti
- [ ] Configurare Filament Panel dedicato
- [ ] Implementare dashboard consulenti
- [ ] Sistema download sicuro

### Phase 4: Security & Compliance
- [ ] Audit trail completo
- [ ] IP whitelisting
- [ ] Data retention policies
- [ ] GDPR compliance

### Phase 5: Advanced Features
- [ ] API per integrazione sistemi esterni
- [ ] Notifiche automatiche scadenze
- [ ] Dashboard analytics accessi

## Note Tecniche

### Sicurezza
- Crittografia end-to-end per tutti i file
- Audit trail completo per accessi
- IP whitelisting obbligatorio
- Scadenza automatica file export

### Compliance
- GDPR compliance per trasferimento dati
- Retention policy automatica
- Consenso esplicito per ogni export
- Tracciabilità completa accessi

### Performance
- Export asincroni per dataset grandi
- Cache per dati frequenti
- Compressione file export
- CDN per download veloci

Questo sistema garantirà integrazione sicura e compliant con consulenti esterni.
