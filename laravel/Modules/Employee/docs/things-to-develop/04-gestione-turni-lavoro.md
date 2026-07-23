# 04 - Gestione Turni di Lavoro

## Panoramica
Sistema completo per la pianificazione, assegnazione e gestione dei turni di lavoro, con supporto per rotazioni automatiche, sostituzioni e controllo della copertura minima.

## Obiettivi
- Automatizzare la pianificazione dei turni
- Garantire copertura minima per ogni turno
- Gestire sostituzioni e cambi turno
- Ottimizzare la distribuzione equa dei turni
- Integrare con sistema presenze per controllo automatico
- Fornire notifiche automatiche ai dipendenti

## Funzionalità da Implementare

### 1. Definizione Turni e Orari

#### 1.1 Configurazione Turni Base
**Obiettivo**: Definire i turni standard dell'azienda con orari e requisiti

**Implementazione Step-by-Step**:

1. **Creare il Model Shift**
```php
// app/Models/Shift.php
class Shift extends Model
{
    protected $fillable = [
        'name', 'code', 'start_time', 'end_time', 'duration_hours',
        'break_duration', 'min_employees', 'max_employees',
        'department_id', 'location_id', 'is_active', 'color_code'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_hours' => 'decimal:2',
        'is_active' => 'boolean'
    ];
}
```

2. **Creare Migration per shifts**
```php
Schema::create('shifts', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('code')->unique();
    $table->time('start_time');
    $table->time('end_time');
    $table->decimal('duration_hours', 4, 2);
    $table->integer('break_duration')->default(0);
    $table->integer('min_employees')->default(1);
    $table->integer('max_employees')->default(10);
    $table->foreignId('department_id')->nullable()->constrained();
    $table->foreignId('location_id')->nullable()->constrained();
    $table->boolean('is_active')->default(true);
    $table->string('color_code', 7)->default('#3B82F6');
    $table->timestamps();
});
```

3. **Creare Filament Resource ShiftResource**
```php
// app/Filament/Resources/ShiftResource.php
class ShiftResource extends XotBaseResource
{
    protected static ?string $model = Shift::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Gestione Turni';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->required(),
            TextInput::make('code')->required()->unique(ignoreRecord: true),
            TimePicker::make('start_time')->required(),
            TimePicker::make('end_time')->required(),
            TextInput::make('min_employees')->numeric()->required(),
            TextInput::make('max_employees')->numeric()->required(),
            ColorPicker::make('color_code')->default('#3B82F6'),
            Toggle::make('is_active')->default(true)
        ]);
    }
}
```

### 2. Pianificazione Automatica Turni

#### 2.1 Algoritmo di Assegnazione Intelligente
**Obiettivo**: Assegnare automaticamente i turni ottimizzando distribuzione e preferenze

**Implementazione**:

1. **Creare Model ShiftAssignment**
```php
// app/Models/ShiftAssignment.php
class ShiftAssignment extends Model
{
    protected $fillable = [
        'employee_id', 'shift_id', 'date', 'status',
        'assigned_by', 'confirmed_at', 'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'confirmed_at' => 'datetime'
    ];
}
```

2. **Creare Service ShiftPlanningService**
```php
// app/Services/ShiftPlanningService.php
class ShiftPlanningService
{
    public function generateWeeklySchedule(Carbon $startDate): array
    {
        $schedule = [];
        $employees = Employee::where('is_active', true)->get();
        
        for ($day = 0; $day < 7; $day++) {
            $currentDate = $startDate->copy()->addDays($day);
            $daySchedule = $this->planDayShifts($currentDate, $employees);
            $schedule[$currentDate->format('Y-m-d')] = $daySchedule;
        }
        
        return $schedule;
    }
    
    private function selectOptimalEmployees(Collection $employees, int $required, Shift $shift, Carbon $date): Collection
    {
        return $employees
            ->map(function ($employee) use ($shift, $date) {
                $score = $this->calculateEmployeeScore($employee, $shift, $date);
                return ['employee' => $employee, 'score' => $score];
            })
            ->sortByDesc('score')
            ->take($required)
            ->pluck('employee');
    }
    
    private function calculateEmployeeScore(Employee $employee, Shift $shift, Carbon $date): float
    {
        $score = 100;
        
        // Penalizza se ha già molti turni questo mese
        $monthlyShifts = ShiftAssignment::where('employee_id', $employee->id)
            ->whereMonth('date', $date->month)
            ->count();
        $score -= $monthlyShifts * 2;
        
        // Considera riposo minimo tra turni
        $previousDay = ShiftAssignment::where('employee_id', $employee->id)
            ->where('date', $date->copy()->subDay())
            ->exists();
        if ($previousDay) {
            $score -= 10;
        }
        
        return max(0, $score);
    }
}
```

### 3. Gestione Sostituzioni e Cambi Turno

#### 3.1 Sistema Richieste di Sostituzione
**Obiettivo**: Permettere ai dipendenti di richiedere sostituzioni per i propri turni

**Implementazione**:

1. **Creare Model ShiftSubstitutionRequest**
```php
// app/Models/ShiftSubstitutionRequest.php
class ShiftSubstitutionRequest extends Model
{
    protected $fillable = [
        'original_assignment_id', 'requesting_employee_id',
        'substitute_employee_id', 'reason', 'status',
        'approved_by', 'approved_at', 'notes'
    ];
}
```

2. **Creare Service SubstitutionService**
```php
// app/Services/SubstitutionService.php
class SubstitutionService
{
    public function findAvailableSubstitutes(ShiftAssignment $assignment): Collection
    {
        $date = $assignment->date;
        
        return Employee::where('is_active', true)
            ->where('id', '!=', $assignment->employee_id)
            ->whereDoesntHave('shiftAssignments', function ($query) use ($date) {
                $query->where('date', $date);
            })
            ->get();
    }
    
    public function processSubstitution(ShiftSubstitutionRequest $request): bool
    {
        if ($request->status !== 'approved') {
            return false;
        }
        
        DB::transaction(function () use ($request) {
            $request->originalAssignment->update([
                'employee_id' => $request->substitute_employee_id,
                'notes' => 'Sostituito da: ' . $request->requestingEmployee->full_name
            ]);
            
            $request->update(['status' => 'completed']);
        });
        
        return true;
    }
}
```

### 4. Controllo Copertura e Alert

#### 4.1 Sistema Monitoraggio Copertura
**Obiettivo**: Monitorare in tempo reale la copertura dei turni e inviare alert

**Implementazione**:

1. **Creare Service CoverageMonitoringService**
```php
// app/Services/CoverageMonitoringService.php
class CoverageMonitoringService
{
    public function checkDailyCoverage(Carbon $date): array
    {
        $shifts = Shift::where('is_active', true)->get();
        $coverageReport = [];
        
        foreach ($shifts as $shift) {
            $assigned = ShiftAssignment::where('shift_id', $shift->id)
                ->where('date', $date)
                ->where('status', '!=', 'cancelled')
                ->count();
                
            $coverage = [
                'shift' => $shift,
                'required' => $shift->min_employees,
                'assigned' => $assigned,
                'shortage' => max(0, $shift->min_employees - $assigned),
                'status' => $assigned >= $shift->min_employees ? 'covered' : 'understaffed'
            ];
            
            $coverageReport[] = $coverage;
        }
        
        return $coverageReport;
    }
}
```

### 5. Dashboard e Interfaccia Pianificazione

#### 5.1 Interfaccia Drag & Drop
**Obiettivo**: Permettere pianificazione manuale con interfaccia intuitiva

**Implementazione**:

1. **Creare Filament Page ShiftPlanningPage**
```php
// app/Filament/Pages/ShiftPlanningPage.php
class ShiftPlanningPage extends XotBasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static string $view = 'filament.pages.shift-planning';
    
    public $selectedWeek;
    public $shifts;
    public $employees;
    public $assignments;
    
    public function mount(): void
    {
        $this->selectedWeek = now()->startOfWeek();
        $this->loadData();
    }
    
    public function assignEmployee($employeeId, $shiftId, $date): void
    {
        ShiftAssignment::updateOrCreate([
            'employee_id' => $employeeId,
            'shift_id' => $shiftId,
            'date' => $date
        ], [
            'status' => 'assigned',
            'assigned_by' => auth()->id()
        ]);
        
        $this->loadData();
    }
    
    public function generateAutoSchedule(): void
    {
        $schedule = app(ShiftPlanningService::class)
            ->generateWeeklySchedule($this->selectedWeek);
            
        foreach ($schedule as $date => $dayAssignments) {
            foreach ($dayAssignments as $assignment) {
                ShiftAssignment::create($assignment);
            }
        }
        
        $this->loadData();
    }
}
```

## Checklist Implementazione

### Phase 1: Base Models e Database
- [ ] Creare migrations per shifts, shift_assignments, shift_substitution_requests
- [ ] Implementare Models con relazioni
- [ ] Creare Seeders per turni standard

### Phase 2: Core Services
- [ ] Implementare ShiftPlanningService per pianificazione automatica
- [ ] Creare SubstitutionService per gestione sostituzioni
- [ ] Implementare CoverageMonitoringService per controllo copertura

### Phase 3: UI Filament
- [ ] Creare ShiftResource per gestione turni
- [ ] Implementare ShiftPlanningPage con drag & drop
- [ ] Creare Widget per dashboard turni

### Phase 4: Automazioni
- [ ] Implementare Job per controllo copertura automatico
- [ ] Creare Notifications per alert e sostituzioni
- [ ] Aggiungere Observer per eventi automatici

### Phase 5: Advanced Features
- [ ] Sistema marketplace turni per scambi
- [ ] Integrazione con sistema presenze
- [ ] API per app mobile

## Note Tecniche

### Sicurezza
- Validare sempre autorizzazioni per modifiche turni
- Audit trail per tutte le modifiche pianificazione
- Controllo accesso basato su ruoli

### Performance
- Indicizzare tabelle per query pianificazione
- Cache per calcoli copertura frequenti
- Ottimizzare algoritmi di assegnazione

### Integrazione
- Sincronizzare con sistema presenze
- Integrare con calendario aziendale
- API REST per sistemi esterni

Questo sistema fornirà una gestione completa dei turni con pianificazione intelligente e controllo automatico della copertura.
