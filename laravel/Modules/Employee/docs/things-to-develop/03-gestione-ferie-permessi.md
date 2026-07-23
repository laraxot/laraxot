# 03 - Gestione Ferie e Permessi

## Panoramica
Sistema completo per la gestione delle richieste di ferie, permessi e assenze, con workflow di approvazione automatizzato e calcolo automatico dei giorni residui.

## Obiettivi
- Automatizzare completamente il processo di richiesta ferie/permessi
- Eliminare la gestione cartacea
- Fornire visibilità real-time sui giorni disponibili
- Implementare workflow di approvazione gerarchico
- Calcolare automaticamente banche ore e permessi ROL
- Integrare con sistema presenze per validazione automatica

## Funzionalità da Implementare

### 1. Richiesta Ferie Self-Service

#### 1.1 Form di Richiesta Interattivo
**Obiettivo**: Permettere ai dipendenti di richiedere ferie in autonomia

**Implementazione Step-by-Step**:

1. **Creare il Model LeaveRequest**
```php
// app/Models/LeaveRequest.php
class LeaveRequest extends Model
{
    protected $fillable = [
        'employee_id',
        'leave_type_id', 
        'start_date',
        'end_date',
        'days_requested',
        'reason',
        'status', // pending, approved, rejected
        'approved_by',
        'approved_at',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime'
    ];
}
```

2. **Creare il Model LeaveType**
```php
// app/Models/LeaveType.php
class LeaveType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'max_days_per_year',
        'requires_approval',
        'can_be_split',
        'advance_notice_days'
    ];
}
```

3. **Creare Migration per leave_requests**
```php
Schema::create('leave_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('employee_id')->constrained()->onDelete('cascade');
    $table->foreignId('leave_type_id')->constrained()->onDelete('cascade');
    $table->date('start_date');
    $table->date('end_date');
    $table->decimal('days_requested', 5, 2);
    $table->text('reason')->nullable();
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->foreignId('approved_by')->nullable()->constrained('employees');
    $table->timestamp('approved_at')->nullable();
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

4. **Creare Filament Resource LeaveRequestResource**
```php
// app/Filament/Resources/LeaveRequestResource.php
class LeaveRequestResource extends XotBaseResource
{
    protected static ?string $model = LeaveRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Gestione Presenze';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('leave_type_id')
                ->relationship('leaveType', 'name')
                ->required()
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set) => 
                    $set('max_days', LeaveType::find($state)?->max_days_per_year)
                ),
            
            DatePicker::make('start_date')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    if ($state && $get('end_date')) {
                        $days = Carbon::parse($state)->diffInDays($get('end_date')) + 1;
                        $set('days_requested', $days);
                    }
                }),
                
            DatePicker::make('end_date')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    if ($state && $get('start_date')) {
                        $days = Carbon::parse($get('start_date'))->diffInDays($state) + 1;
                        $set('days_requested', $days);
                    }
                }),
                
            TextInput::make('days_requested')
                ->numeric()
                ->disabled()
                ->dehydrated(),
                
            Textarea::make('reason')
                ->maxLength(500),
                
            // Mostra giorni disponibili
            Placeholder::make('available_days')
                ->content(function (callable $get) {
                    $employeeId = auth()->user()->employee_id ?? $get('employee_id');
                    $leaveTypeId = $get('leave_type_id');
                    
                    if ($employeeId && $leaveTypeId) {
                        $available = app(LeaveBalanceService::class)
                            ->getAvailableDays($employeeId, $leaveTypeId);
                        return "Giorni disponibili: {$available}";
                    }
                    return 'Seleziona tipo ferie per vedere i giorni disponibili';
                })
        ]);
    }
}
```

#### 1.2 Validazione Automatica Disponibilità
**Obiettivo**: Verificare automaticamente che il dipendente abbia giorni sufficienti

**Implementazione**:

1. **Creare Service LeaveBalanceService**
```php
// app/Services/LeaveBalanceService.php
class LeaveBalanceService
{
    public function getAvailableDays(int $employeeId, int $leaveTypeId): float
    {
        $employee = Employee::find($employeeId);
        $leaveType = LeaveType::find($leaveTypeId);
        
        // Calcola giorni spettanti nell'anno
        $entitledDays = $this->calculateEntitledDays($employee, $leaveType);
        
        // Sottrai giorni già utilizzati
        $usedDays = LeaveRequest::where('employee_id', $employeeId)
            ->where('leave_type_id', $leaveTypeId)
            ->where('status', 'approved')
            ->whereYear('start_date', now()->year)
            ->sum('days_requested');
            
        return max(0, $entitledDays - $usedDays);
    }
    
    private function calculateEntitledDays(Employee $employee, LeaveType $leaveType): float
    {
        // Logica per calcolare giorni spettanti basata su:
        // - Anzianità di servizio
        // - Tipo di contratto
        // - Mesi lavorati nell'anno corrente
        
        $monthsWorked = $this->getMonthsWorkedThisYear($employee);
        $yearlyEntitlement = $leaveType->max_days_per_year;
        
        return ($yearlyEntitlement / 12) * $monthsWorked;
    }
}
```

2. **Aggiungere Validazione Custom al Form**
```php
// Nel LeaveRequestResource, aggiungere validazione
TextInput::make('days_requested')
    ->numeric()
    ->rules([
        function (callable $get) {
            return function (string $attribute, $value, callable $fail) use ($get) {
                $available = app(LeaveBalanceService::class)
                    ->getAvailableDays($get('employee_id'), $get('leave_type_id'));
                    
                if ($value > $available) {
                    $fail("Hai solo {$available} giorni disponibili per questo tipo di ferie.");
                }
            };
        }
    ])
```

### 2. Workflow di Approvazione Gerarchico

#### 2.1 Sistema di Approvazione Multi-Livello
**Obiettivo**: Implementare approvazione automatica basata su gerarchia aziendale

**Implementazione**:

1. **Creare Model ApprovalWorkflow**
```php
// app/Models/ApprovalWorkflow.php
class ApprovalWorkflow extends Model
{
    protected $fillable = [
        'leave_request_id',
        'approver_id',
        'level',
        'status', // pending, approved, rejected
        'approved_at',
        'notes'
    ];
}
```

2. **Creare Service ApprovalService**
```php
// app/Services/ApprovalService.php
class ApprovalService
{
    public function createApprovalWorkflow(LeaveRequest $leaveRequest): void
    {
        $employee = $leaveRequest->employee;
        $approvers = $this->getApprovers($employee, $leaveRequest);
        
        foreach ($approvers as $level => $approverId) {
            ApprovalWorkflow::create([
                'leave_request_id' => $leaveRequest->id,
                'approver_id' => $approverId,
                'level' => $level,
                'status' => 'pending'
            ]);
        }
        
        // Notifica primo approvatore
        $this->notifyNextApprover($leaveRequest);
    }
    
    private function getApprovers(Employee $employee, LeaveRequest $leaveRequest): array
    {
        $approvers = [];
        
        // Livello 1: Manager diretto
        if ($employee->manager_id) {
            $approvers[1] = $employee->manager_id;
        }
        
        // Livello 2: HR (per ferie > 5 giorni)
        if ($leaveRequest->days_requested > 5) {
            $hrManager = Employee::where('department', 'HR')
                ->where('role', 'manager')
                ->first();
            if ($hrManager) {
                $approvers[2] = $hrManager->id;
            }
        }
        
        return $approvers;
    }
}
```

#### 2.2 Notifiche Automatiche
**Obiettivo**: Notificare automaticamente gli approvatori

**Implementazione**:

1. **Creare Notification LeaveRequestPending**
```php
// app/Notifications/LeaveRequestPending.php
class LeaveRequestPending extends Notification
{
    public function __construct(
        private LeaveRequest $leaveRequest
    ) {}
    
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }
    
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Richiesta Ferie in Attesa di Approvazione')
            ->line("Il dipendente {$this->leaveRequest->employee->full_name} ha richiesto ferie.")
            ->line("Periodo: {$this->leaveRequest->start_date->format('d/m/Y')} - {$this->leaveRequest->end_date->format('d/m/Y')}")
            ->line("Giorni richiesti: {$this->leaveRequest->days_requested}")
            ->action('Visualizza Richiesta', url("/admin/leave-requests/{$this->leaveRequest->id}"));
    }
}
```

2. **Aggiungere Observer per LeaveRequest**
```php
// app/Observers/LeaveRequestObserver.php
class LeaveRequestObserver
{
    public function created(LeaveRequest $leaveRequest): void
    {
        app(ApprovalService::class)->createApprovalWorkflow($leaveRequest);
    }
    
    public function updated(LeaveRequest $leaveRequest): void
    {
        if ($leaveRequest->wasChanged('status') && $leaveRequest->status === 'approved') {
            $leaveRequest->employee->notify(new LeaveRequestApproved($leaveRequest));
        }
    }
}
```

### 3. Calendario Ferie Interattivo

#### 3.1 Vista Calendario Team
**Obiettivo**: Permettere ai manager di visualizzare le ferie del team

**Implementazione**:

1. **Creare Filament Widget CalendarWidget**
```php
// app/Filament/Widgets/LeaveCalendarWidget.php
class LeaveCalendarWidget extends XotBaseWidget
{
    protected static string $view = 'filament.widgets.leave-calendar';
    
    public function getViewData(): array
    {
        $user = auth()->user();
        $employeeIds = $this->getTeamEmployeeIds($user);
        
        $leaveRequests = LeaveRequest::with(['employee', 'leaveType'])
            ->whereIn('employee_id', $employeeIds)
            ->where('status', 'approved')
            ->whereBetween('start_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->get();
            
        return [
            'events' => $this->formatEventsForCalendar($leaveRequests)
        ];
    }
    
    private function formatEventsForCalendar($leaveRequests): array
    {
        return $leaveRequests->map(function ($request) {
            return [
                'title' => $request->employee->full_name . ' - ' . $request->leaveType->name,
                'start' => $request->start_date->format('Y-m-d'),
                'end' => $request->end_date->addDay()->format('Y-m-d'), // FullCalendar usa end esclusivo
                'color' => $this->getColorByLeaveType($request->leaveType->code)
            ];
        })->toArray();
    }
}
```

2. **Creare Vista Blade per il Calendario**
```blade
{{-- resources/views/filament/widgets/leave-calendar.blade.php --}}
<x-filament-widgets::widget>
    <x-filament::section>
        <div id="leave-calendar"></div>
    </x-filament::section>
    
    @push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('leave-calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'it',
                events: @json($this->getViewData()['events']),
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                }
            });
            calendar.render();
        });
    </script>
    @endpush
</x-filament-widgets::widget>
```

### 4. Calcolo Automatico Banche Ore

#### 4.1 Sistema Accumulo Ore
**Obiettivo**: Calcolare automaticamente le banche ore e i permessi ROL

**Implementazione**:

1. **Creare Model HourBank**
```php
// app/Models/HourBank.php
class HourBank extends Model
{
    protected $fillable = [
        'employee_id',
        'year',
        'hours_accumulated',
        'hours_used',
        'hours_available'
    ];
    
    protected $casts = [
        'hours_accumulated' => 'decimal:2',
        'hours_used' => 'decimal:2',
        'hours_available' => 'decimal:2'
    ];
}
```

2. **Creare Service HourBankService**
```php
// app/Services/HourBankService.php
class HourBankService
{
    public function calculateMonthlyAccumulation(Employee $employee, int $month, int $year): float
    {
        // Calcola ore accumulate nel mese basate su:
        // - Ore straordinarie lavorate
        // - Ore di recupero per festività
        // - Ore bonus per anzianità
        
        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();
            
        $overtimeHours = 0;
        foreach ($attendances as $attendance) {
            $standardHours = 8; // Ore standard giornaliere
            $workedHours = $attendance->hours_worked;
            
            if ($workedHours > $standardHours) {
                $overtimeHours += ($workedHours - $standardHours);
            }
        }
        
        return $overtimeHours;
    }
    
    public function updateHourBank(Employee $employee, int $year): void
    {
        $hourBank = HourBank::firstOrCreate([
            'employee_id' => $employee->id,
            'year' => $year
        ]);
        
        $totalAccumulated = 0;
        for ($month = 1; $month <= 12; $month++) {
            $totalAccumulated += $this->calculateMonthlyAccumulation($employee, $month, $year);
        }
        
        $hourBank->update([
            'hours_accumulated' => $totalAccumulated,
            'hours_available' => $totalAccumulated - $hourBank->hours_used
        ]);
    }
}
```

### 5. Dashboard Ferie per Manager

#### 5.1 Statistiche Team
**Obiettivo**: Fornire ai manager una vista completa delle ferie del team

**Implementazione**:

1. **Creare Widget TeamLeaveStatsWidget**
```php
// app/Filament/Widgets/TeamLeaveStatsWidget.php
class TeamLeaveStatsWidget extends XotBaseWidget
{
    protected static string $view = 'filament.widgets.team-leave-stats';
    protected int | string | array $columnSpan = 'full';
    
    public function getViewData(): array
    {
        $user = auth()->user();
        $teamEmployees = $this->getTeamEmployees($user);
        
        $stats = [];
        foreach ($teamEmployees as $employee) {
            $stats[] = [
                'employee' => $employee,
                'vacation_used' => $this->getUsedDays($employee, 'vacation'),
                'vacation_available' => $this->getAvailableDays($employee, 'vacation'),
                'sick_days' => $this->getUsedDays($employee, 'sick'),
                'pending_requests' => $this->getPendingRequests($employee)
            ];
        }
        
        return ['team_stats' => $stats];
    }
}
```

### 6. Export e Report

#### 6.1 Report Ferie per Consulenti
**Obiettivo**: Generare report per consulenti del lavoro

**Implementazione**:

1. **Creare Action ExportLeaveReport**
```php
// app/Filament/Actions/ExportLeaveReportAction.php
class ExportLeaveReportAction extends Action
{
    public static function make(): static
    {
        return parent::make('export_leave_report')
            ->label('Esporta Report Ferie')
            ->icon('heroicon-o-document-arrow-down')
            ->form([
                DatePicker::make('start_date')->required(),
                DatePicker::make('end_date')->required(),
                Select::make('department_id')
                    ->relationship('department', 'name')
                    ->multiple(),
            ])
            ->action(function (array $data) {
                return Excel::download(
                    new LeaveReportExport($data),
                    'report-ferie-' . now()->format('Y-m-d') . '.xlsx'
                );
            });
    }
}
```

## Checklist Implementazione

### Phase 1: Base Models e Database
- [ ] Creare migrations per leave_requests, leave_types, approval_workflows, hour_banks
- [ ] Implementare Models con relazioni
- [ ] Creare Seeders per dati di base (tipi ferie standard)

### Phase 2: Core Functionality
- [ ] Implementare LeaveBalanceService per calcolo giorni disponibili
- [ ] Creare ApprovalService per workflow approvazioni
- [ ] Implementare HourBankService per gestione banche ore

### Phase 3: UI Filament
- [ ] Creare LeaveRequestResource con form avanzato
- [ ] Implementare Widget calendario ferie
- [ ] Creare Dashboard statistiche per manager

### Phase 4: Automazioni
- [ ] Implementare Notifications per approvazioni
- [ ] Creare Jobs per calcoli automatici mensili
- [ ] Aggiungere Observer per eventi automatici

### Phase 5: Advanced Features
- [ ] Sistema export report per consulenti
- [ ] Integrazione con sistema presenze
- [ ] API per app mobile

## Note Tecniche

### Sicurezza
- Validare sempre autorizzazioni (dipendente può vedere solo le sue ferie)
- Crittografare dati sensibili nei report
- Audit trail per tutte le modifiche

### Performance
- Usare cache per calcoli giorni disponibili
- Indicizzare tabelle per query frequenti
- Ottimizzare query per dashboard manager

### Integrazione
- Sincronizzare con sistema presenze per validazione automatica
- Integrare con sistema payroll per calcolo retribuzioni
- API REST per integrazione sistemi esterni

Questo sistema fornirà una gestione completa e automatizzata delle ferie, eliminando completamente la gestione manuale e fornendo trasparenza totale a dipendenti e manager.
