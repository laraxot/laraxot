# 05 - Gestione Buste Paga e Documenti

## Panoramica
Sistema completo per la distribuzione automatica di buste paga, gestione documenti aziendali, archiviazione sicura e notifiche di consegna con tracciamento delle letture.

## Obiettivi
- Automatizzare completamente la distribuzione delle buste paga
- Eliminare la gestione cartacea dei documenti
- Garantire sicurezza e privacy dei dati sensibili
- Fornire accesso immediato ai documenti storici
- Tracciare consegna e lettura dei documenti
- Integrare con sistemi di payroll esterni

## Funzionalità da Implementare

### 1. Sistema Distribuzione Buste Paga

#### 1.1 Upload e Smistamento Automatico
**Obiettivo**: Permettere upload massivo di buste paga con smistamento automatico per dipendente

**Implementazione Step-by-Step**:

1. **Creare il Model PayrollDocument**
```php
// app/Models/PayrollDocument.php
class PayrollDocument extends Model
{
    protected $fillable = [
        'employee_id',
        'document_type', // payslip, tax_certificate, contract, etc.
        'period_month',
        'period_year',
        'file_name',
        'file_path',
        'file_size',
        'file_hash',
        'is_encrypted',
        'uploaded_by',
        'delivered_at',
        'read_at',
        'download_count',
        'expires_at'
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_encrypted' => 'boolean'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
```

2. **Creare Migration per payroll_documents**
```php
Schema::create('payroll_documents', function (Blueprint $table) {
    $table->id();
    $table->foreignId('employee_id')->constrained()->onDelete('cascade');
    $table->enum('document_type', ['payslip', 'tax_certificate', 'contract', 'bonus', 'other']);
    $table->integer('period_month')->nullable();
    $table->integer('period_year')->nullable();
    $table->string('file_name');
    $table->string('file_path');
    $table->bigInteger('file_size');
    $table->string('file_hash', 64);
    $table->boolean('is_encrypted')->default(true);
    $table->foreignId('uploaded_by')->constrained('users');
    $table->timestamp('delivered_at')->nullable();
    $table->timestamp('read_at')->nullable();
    $table->integer('download_count')->default(0);
    $table->timestamp('expires_at')->nullable();
    $table->timestamps();
    
    $table->index(['employee_id', 'document_type']);
    $table->index(['period_year', 'period_month']);
});
```

3. **Creare Service PayrollDistributionService**
```php
// app/Services/PayrollDistributionService.php
class PayrollDistributionService
{
    public function uploadBulkPayslips(UploadedFile $zipFile, int $month, int $year): array
    {
        $results = ['success' => 0, 'errors' => []];
        
        // Estrai il file ZIP
        $extractPath = storage_path('app/temp/payroll_' . uniqid());
        $zip = new ZipArchive();
        
        if ($zip->open($zipFile->path()) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();
            
            // Processa ogni file
            $files = File::files($extractPath);
            foreach ($files as $file) {
                try {
                    $employee = $this->identifyEmployeeFromFilename($file->getFilename());
                    if ($employee) {
                        $this->processPayslipFile($file, $employee, $month, $year);
                        $results['success']++;
                    } else {
                        $results['errors'][] = "Dipendente non identificato per: {$file->getFilename()}";
                    }
                } catch (Exception $e) {
                    $results['errors'][] = "Errore elaborando {$file->getFilename()}: {$e->getMessage()}";
                }
            }
            
            // Pulisci file temporanei
            File::deleteDirectory($extractPath);
        }
        
        return $results;
    }
    
    private function identifyEmployeeFromFilename(string $filename): ?Employee
    {
        // Logica per identificare dipendente dal nome file
        // Supporta diversi pattern: "123456_Rossi_Mario.pdf", "Rossi_Mario_2024_01.pdf", etc.
        
        // Pattern 1: Codice dipendente all'inizio
        if (preg_match('/^(\d+)_/', $filename, $matches)) {
            return Employee::where('employee_code', $matches[1])->first();
        }
        
        // Pattern 2: Nome e cognome
        if (preg_match('/^([A-Za-z]+)_([A-Za-z]+)/', $filename, $matches)) {
            $surname = $matches[1];
            $name = $matches[2];
            return Employee::where('surname', 'LIKE', "%{$surname}%")
                          ->where('name', 'LIKE', "%{$name}%")
                          ->first();
        }
        
        return null;
    }
    
    private function processPayslipFile(SplFileInfo $file, Employee $employee, int $month, int $year): PayrollDocument
    {
        // Crittografa il file
        $encryptedContent = $this->encryptFile($file->getContents(), $employee);
        
        // Salva il file crittografato
        $fileName = "payslip_{$employee->employee_code}_{$year}_{$month}.pdf.enc";
        $filePath = "payroll/{$year}/{$month}/{$fileName}";
        Storage::disk('private')->put($filePath, $encryptedContent);
        
        // Crea record nel database
        $document = PayrollDocument::create([
            'employee_id' => $employee->id,
            'document_type' => 'payslip',
            'period_month' => $month,
            'period_year' => $year,
            'file_name' => $file->getFilename(),
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'file_hash' => hash_file('sha256', $file->getPathname()),
            'is_encrypted' => true,
            'uploaded_by' => auth()->id()
        ]);
        
        // Notifica il dipendente
        $employee->notify(new PayslipAvailableNotification($document));
        
        return $document;
    }
    
    private function encryptFile(string $content, Employee $employee): string
    {
        // Usa la chiave basata su dati dipendente per crittografia
        $key = hash('sha256', $employee->employee_code . $employee->tax_code . config('app.key'));
        return encrypt($content, $key);
    }
}
```

4. **Creare Filament Resource PayrollDocumentResource**
```php
// app/Filament/Resources/PayrollDocumentResource.php
class PayrollDocumentResource extends XotBaseResource
{
    protected static ?string $model = PayrollDocument::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Gestione Documenti';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Upload Buste Paga')->schema([
                FileUpload::make('bulk_upload')
                    ->label('File ZIP Buste Paga')
                    ->acceptedFileTypes(['application/zip'])
                    ->disk('temp')
                    ->visibility('private')
                    ->helperText('Carica un file ZIP contenente le buste paga. I file devono essere nominati con il codice dipendente o nome_cognome.'),
                    
                Select::make('period_month')
                    ->label('Mese')
                    ->options([
                        1 => 'Gennaio', 2 => 'Febbraio', 3 => 'Marzo',
                        4 => 'Aprile', 5 => 'Maggio', 6 => 'Giugno',
                        7 => 'Luglio', 8 => 'Agosto', 9 => 'Settembre',
                        10 => 'Ottobre', 11 => 'Novembre', 12 => 'Dicembre'
                    ])
                    ->required(),
                    
                Select::make('period_year')
                    ->label('Anno')
                    ->options(function () {
                        $years = [];
                        for ($i = now()->year - 5; $i <= now()->year + 1; $i++) {
                            $years[$i] = $i;
                        }
                        return $years;
                    })
                    ->default(now()->year)
                    ->required()
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Dipendente')
                    ->searchable(['employees.name', 'employees.surname']),
                    
                TextColumn::make('document_type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'payslip' => 'success',
                        'tax_certificate' => 'warning',
                        'contract' => 'info',
                        default => 'gray',
                    }),
                    
                TextColumn::make('period')
                    ->label('Periodo')
                    ->getStateUsing(fn ($record) => 
                        $record->period_month && $record->period_year 
                            ? sprintf('%02d/%d', $record->period_month, $record->period_year)
                            : 'N/A'
                    ),
                    
                TextColumn::make('file_name')
                    ->label('File')
                    ->limit(30),
                    
                IconColumn::make('delivered_at')
                    ->label('Consegnato')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !is_null($record->delivered_at)),
                    
                IconColumn::make('read_at')
                    ->label('Letto')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !is_null($record->read_at)),
                    
                TextColumn::make('download_count')
                    ->label('Download')
                    ->alignCenter(),
                    
                TextColumn::make('created_at')
                    ->label('Caricato')
                    ->dateTime('d/m/Y H:i')
            ])
            ->filters([
                SelectFilter::make('document_type')
                    ->options([
                        'payslip' => 'Busta Paga',
                        'tax_certificate' => 'Certificato Fiscale',
                        'contract' => 'Contratto',
                        'bonus' => 'Bonus',
                        'other' => 'Altro'
                    ]),
                    
                Filter::make('period')
                    ->form([
                        Select::make('month')->options([
                            1 => 'Gennaio', 2 => 'Febbraio', 3 => 'Marzo',
                            4 => 'Aprile', 5 => 'Maggio', 6 => 'Giugno',
                            7 => 'Luglio', 8 => 'Agosto', 9 => 'Settembre',
                            10 => 'Ottobre', 11 => 'Novembre', 12 => 'Dicembre'
                        ]),
                        Select::make('year')->options(function () {
                            $years = [];
                            for ($i = now()->year - 5; $i <= now()->year; $i++) {
                                $years[$i] = $i;
                            }
                            return $years;
                        })
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['month'], fn ($q, $month) => $q->where('period_month', $month))
                            ->when($data['year'], fn ($q, $year) => $q->where('period_year', $year));
                    })
            ])
            ->actions([
                Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (PayrollDocument $record) {
                        return response()->streamDownload(function () use ($record) {
                            echo app(PayrollDistributionService::class)->decryptFile($record);
                        }, $record->file_name);
                    }),
                    
                Action::make('resend')
                    ->icon('heroicon-o-paper-airplane')
                    ->action(function (PayrollDocument $record) {
                        $record->employee->notify(new PayslipAvailableNotification($record));
                        Notification::make()
                            ->title('Notifica inviata')
                            ->success()
                            ->send();
                    })
            ])
            ->headerActions([
                Action::make('bulk_upload')
                    ->label('Upload Massivo')
                    ->icon('heroicon-o-cloud-arrow-up')
                    ->form([
                        FileUpload::make('zip_file')
                            ->label('File ZIP')
                            ->acceptedFileTypes(['application/zip'])
                            ->required(),
                            
                        Select::make('month')
                            ->label('Mese')
                            ->options([
                                1 => 'Gennaio', 2 => 'Febbraio', 3 => 'Marzo',
                                4 => 'Aprile', 5 => 'Maggio', 6 => 'Giugno',
                                7 => 'Luglio', 8 => 'Agosto', 9 => 'Settembre',
                                10 => 'Ottobre', 11 => 'Novembre', 12 => 'Dicembre'
                            ])
                            ->required(),
                            
                        Select::make('year')
                            ->label('Anno')
                            ->options(function () {
                                $years = [];
                                for ($i = now()->year - 1; $i <= now()->year + 1; $i++) {
                                    $years[$i] = $i;
                                }
                                return $years;
                            })
                            ->default(now()->year)
                            ->required()
                    ])
                    ->action(function (array $data) {
                        $zipFile = $data['zip_file'];
                        $results = app(PayrollDistributionService::class)
                            ->uploadBulkPayslips($zipFile, $data['month'], $data['year']);
                            
                        $message = "Elaborate {$results['success']} buste paga.";
                        if (!empty($results['errors'])) {
                            $message .= " Errori: " . implode(', ', array_slice($results['errors'], 0, 3));
                        }
                        
                        Notification::make()
                            ->title($message)
                            ->success()
                            ->send();
                    })
            ]);
    }
}
```

### 2. Portale Self-Service Dipendenti

#### 2.1 Dashboard Documenti Personali
**Obiettivo**: Permettere ai dipendenti di accedere ai propri documenti

**Implementazione**:

1. **Creare Filament Widget EmployeeDocumentsWidget**
```php
// app/Filament/Widgets/EmployeeDocumentsWidget.php
class EmployeeDocumentsWidget extends XotBaseWidget
{
    protected static string $view = 'filament.widgets.employee-documents';
    protected int | string | array $columnSpan = 'full';
    
    public function getViewData(): array
    {
        $employee = auth()->user()->employee;
        
        if (!$employee) {
            return ['documents' => collect()];
        }
        
        $documents = PayrollDocument::where('employee_id', $employee->id)
            ->orderBy('period_year', 'desc')
            ->orderBy('period_month', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($doc) {
                return $doc->period_year ?? 'Altri';
            });
            
        return ['documents' => $documents];
    }
    
    public function downloadDocument($documentId): StreamedResponse
    {
        $document = PayrollDocument::where('id', $documentId)
            ->where('employee_id', auth()->user()->employee->id)
            ->firstOrFail();
            
        // Aggiorna statistiche
        $document->increment('download_count');
        if (!$document->read_at) {
            $document->update(['read_at' => now()]);
        }
        
        return response()->streamDownload(function () use ($document) {
            echo app(PayrollDistributionService::class)->decryptFile($document);
        }, $document->file_name);
    }
}
```

2. **Creare Vista Blade per Widget Documenti**
```blade
{{-- resources/views/filament/widgets/employee-documents.blade.php --}}
<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            I Miei Documenti
        </x-slot>
        
        @if($this->getViewData()['documents']->isEmpty())
            <div class="text-center py-8">
                <x-heroicon-o-document-text class="w-12 h-12 mx-auto text-gray-400 mb-4"/>
                <p class="text-gray-500">Nessun documento disponibile</p>
            </div>
        @else
            @foreach($this->getViewData()['documents'] as $year => $yearDocuments)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3 text-gray-900">{{ $year }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($yearDocuments as $document)
                            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center space-x-2">
                                        @switch($document->document_type)
                                            @case('payslip')
                                                <x-heroicon-o-currency-euro class="w-5 h-5 text-green-500"/>
                                                @break
                                            @case('tax_certificate')
                                                <x-heroicon-o-document-check class="w-5 h-5 text-blue-500"/>
                                                @break
                                            @case('contract')
                                                <x-heroicon-o-document-text class="w-5 h-5 text-purple-500"/>
                                                @break
                                            @default
                                                <x-heroicon-o-document class="w-5 h-5 text-gray-500"/>
                                        @endswitch
                                        
                                        <span class="text-sm font-medium">
                                            @switch($document->document_type)
                                                @case('payslip')
                                                    Busta Paga
                                                    @break
                                                @case('tax_certificate')
                                                    Certificato Fiscale
                                                    @break
                                                @case('contract')
                                                    Contratto
                                                    @break
                                                @default
                                                    Documento
                                            @endswitch
                                        </span>
                                    </div>
                                    
                                    @if(!$document->read_at)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Nuovo
                                        </span>
                                    @endif
                                </div>
                                
                                @if($document->period_month && $document->period_year)
                                    <p class="text-sm text-gray-600 mb-2">
                                        {{ DateTime::createFromFormat('!m', $document->period_month)->format('F') }} {{ $document->period_year }}
                                    </p>
                                @endif
                                
                                <p class="text-xs text-gray-500 mb-3">
                                    Caricato il {{ $document->created_at->format('d/m/Y') }}
                                </p>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">
                                        {{ $document->download_count }} download
                                    </span>
                                    
                                    <x-filament::button
                                        wire:click="downloadDocument({{ $document->id }})"
                                        size="sm"
                                        icon="heroicon-o-arrow-down-tray"
                                    >
                                        Scarica
                                    </x-filament::button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
```

### 3. Sistema Notifiche e Tracciamento

#### 3.1 Notifiche Automatiche
**Obiettivo**: Notificare automaticamente i dipendenti quando nuovi documenti sono disponibili

**Implementazione**:

1. **Creare Notification PayslipAvailableNotification**
```php
// app/Notifications/PayslipAvailableNotification.php
class PayslipAvailableNotification extends Notification
{
    public function __construct(
        private PayrollDocument $document
    ) {}
    
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }
    
    public function toMail($notifiable): MailMessage
    {
        $period = $this->document->period_month && $this->document->period_year
            ? sprintf('%02d/%d', $this->document->period_month, $this->document->period_year)
            : 'N/A';
            
        return (new MailMessage)
            ->subject('Nuovo Documento Disponibile')
            ->greeting("Ciao {$notifiable->name},")
            ->line("È disponibile un nuovo documento nel tuo portale dipendente.")
            ->line("Tipo: " . $this->getDocumentTypeName())
            ->line("Periodo: {$period}")
            ->action('Visualizza Documento', url('/admin/employee-documents'))
            ->line('Accedi al portale per scaricare il documento.')
            ->salutation('Il Team HR');
    }
    
    public function toDatabase($notifiable): array
    {
        return [
            'document_id' => $this->document->id,
            'document_type' => $this->document->document_type,
            'period' => sprintf('%02d/%d', $this->document->period_month, $this->document->period_year),
            'message' => 'Nuovo documento disponibile: ' . $this->getDocumentTypeName()
        ];
    }
    
    private function getDocumentTypeName(): string
    {
        return match($this->document->document_type) {
            'payslip' => 'Busta Paga',
            'tax_certificate' => 'Certificato Fiscale',
            'contract' => 'Contratto',
            'bonus' => 'Bonus',
            default => 'Documento'
        };
    }
}
```

### 4. Sicurezza e Crittografia

#### 4.1 Sistema Crittografia Avanzata
**Obiettivo**: Garantire massima sicurezza dei documenti sensibili

**Implementazione**:

1. **Estendere PayrollDistributionService con crittografia avanzata**
```php
// Aggiungere al PayrollDistributionService
public function decryptFile(PayrollDocument $document): string
{
    if (!$document->is_encrypted) {
        return Storage::disk('private')->get($document->file_path);
    }
    
    $employee = $document->employee;
    $key = $this->generateDecryptionKey($employee);
    $encryptedContent = Storage::disk('private')->get($document->file_path);
    
    try {
        return decrypt($encryptedContent, $key);
    } catch (DecryptException $e) {
        throw new Exception('Impossibile decrittografare il documento. Contattare l\'amministratore.');
    }
}

private function generateDecryptionKey(Employee $employee): string
{
    // Genera chiave basata su dati immutabili del dipendente
    return hash('sha256', $employee->employee_code . $employee->tax_code . config('app.key'));
}

public function verifyDocumentIntegrity(PayrollDocument $document): bool
{
    $currentHash = hash_file('sha256', Storage::disk('private')->path($document->file_path));
    return hash_equals($document->file_hash, $currentHash);
}
```

### 5. Analytics e Report

#### 5.1 Dashboard Statistiche Distribuzione
**Obiettivo**: Fornire statistiche sulla distribuzione e lettura dei documenti

**Implementazione**:

1. **Creare Widget DocumentDistributionStatsWidget**
```php
// app/Filament/Widgets/DocumentDistributionStatsWidget.php
class DocumentDistributionStatsWidget extends XotBaseWidget
{
    protected static string $view = 'filament.widgets.document-distribution-stats';
    protected int | string | array $columnSpan = 'full';
    
    public function getViewData(): array
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        $stats = [
            'total_documents' => PayrollDocument::count(),
            'this_month' => PayrollDocument::where('period_month', $currentMonth)
                                         ->where('period_year', $currentYear)
                                         ->count(),
            'delivered' => PayrollDocument::whereNotNull('delivered_at')->count(),
            'read' => PayrollDocument::whereNotNull('read_at')->count(),
            'unread' => PayrollDocument::whereNull('read_at')->count(),
        ];
        
        $readingStats = $this->getReadingStatsByMonth();
        $typeDistribution = $this->getDocumentTypeDistribution();
        
        return [
            'stats' => $stats,
            'reading_stats' => $readingStats,
            'type_distribution' => $typeDistribution
        ];
    }
    
    private function getReadingStatsByMonth(): array
    {
        return PayrollDocument::selectRaw('
                period_year,
                period_month,
                COUNT(*) as total,
                COUNT(read_at) as read,
                COUNT(*) - COUNT(read_at) as unread
            ')
            ->whereNotNull('period_month')
            ->whereNotNull('period_year')
            ->groupBy('period_year', 'period_month')
            ->orderBy('period_year', 'desc')
            ->orderBy('period_month', 'desc')
            ->limit(12)
            ->get()
            ->toArray();
    }
    
    private function getDocumentTypeDistribution(): array
    {
        return PayrollDocument::selectRaw('document_type, COUNT(*) as count')
            ->groupBy('document_type')
            ->get()
            ->pluck('count', 'document_type')
            ->toArray();
    }
}
```

## Checklist Implementazione

### Phase 1: Base Models e Database
- [ ] Creare migration per payroll_documents
- [ ] Implementare Model PayrollDocument con relazioni
- [ ] Configurare storage sicuro per documenti

### Phase 2: Core Services
- [ ] Implementare PayrollDistributionService per upload massivo
- [ ] Creare sistema crittografia documenti
- [ ] Implementare identificazione automatica dipendenti

### Phase 3: UI Filament
- [ ] Creare PayrollDocumentResource per gestione admin
- [ ] Implementare Widget documenti per dipendenti
- [ ] Creare interfaccia upload massivo

### Phase 4: Sicurezza e Notifiche
- [ ] Implementare sistema notifiche automatiche
- [ ] Aggiungere audit trail per accessi documenti
- [ ] Creare sistema verifica integrità file

### Phase 5: Advanced Features
- [ ] Dashboard statistiche distribuzione
- [ ] Sistema scadenza documenti automatica
- [ ] API per integrazione sistemi payroll esterni

## Note Tecniche

### Sicurezza
- Crittografia AES-256 per tutti i documenti sensibili
- Chiavi basate su dati immutabili dipendente
- Audit trail completo per tutti gli accessi
- Verifica integrità file con hash SHA-256

### Performance
- Storage ottimizzato per file grandi
- Cache per statistiche frequenti
- Indicizzazione database per query veloci

### Compliance
- Conformità GDPR per trattamento dati
- Retention policy automatica documenti
- Log dettagliati per audit

Questo sistema fornirà una gestione completa e sicura di buste paga e documenti aziendali con massima automazione e sicurezza.
