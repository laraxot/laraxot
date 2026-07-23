# Business Logic - Employee Management System

## Panoramica

Il sistema di Employee Management gestisce il ciclo di vita completo dei dipendenti, dalle assunzioni ai trasferimenti, promozioni e cessazioni. Implementa una gerarchia organizzativa flessibile con controlli di integrità.

## Employee Lifecycle Management

### 1.1 Employee Status State Machine

```php
enum EmployeeStatusEnum: string 
{
    case ACTIVE = 'active';           // Dipendente attivo
    case INACTIVE = 'inactive';       // Temporaneamente inattivo
    case SUSPENDED = 'suspended';     // Sospeso (disciplinare)
    case ON_LEAVE = 'on_leave';       // In congedo
    case TERMINATED = 'terminated';   // Licenziato/Dimesso
}
```

### 1.2 Status Transition Rules

```php
class EmployeeStatusManager
{
    private const ALLOWED_TRANSITIONS = [
        EmployeeStatusEnum::ACTIVE->value => [
            EmployeeStatusEnum::INACTIVE->value,
            EmployeeStatusEnum::SUSPENDED->value,
            EmployeeStatusEnum::ON_LEAVE->value,
            EmployeeStatusEnum::TERMINATED->value
        ],
        EmployeeStatusEnum::INACTIVE->value => [
            EmployeeStatusEnum::ACTIVE->value,
            EmployeeStatusEnum::TERMINATED->value
        ],
        EmployeeStatusEnum::SUSPENDED->value => [
            EmployeeStatusEnum::ACTIVE->value,
            EmployeeStatusEnum::TERMINATED->value
        ],
        EmployeeStatusEnum::ON_LEAVE->value => [
            EmployeeStatusEnum::ACTIVE->value,
            EmployeeStatusEnum::TERMINATED->value
        ],
        EmployeeStatusEnum::TERMINATED->value => [] // Stato finale
    ];
    
    public function canTransition(EmployeeStatusEnum $from, EmployeeStatusEnum $to): bool
    {
        return in_array($to->value, self::ALLOWED_TRANSITIONS[$from->value] ?? []);
    }
    
    public function transition(Employee $employee, EmployeeStatusEnum $newStatus, User $actor, ?string $reason = null): void
    {
        if (!$this->canTransition($employee->status, $newStatus)) {
            throw new InvalidStatusTransitionException(
                "Cannot transition from {$employee->status->value} to {$newStatus->value}"
            );
        }
        
        // Audit trail
        EmployeeStatusHistory::create([
            'employee_id' => $employee->id,
            'from_status' => $employee->status,
            'to_status' => $newStatus,
            'changed_by' => $actor->id,
            'reason' => $reason,
            'changed_at' => now()
        ]);
        
        $employee->update(['status' => $newStatus]);
        
        // Trigger business events
        event(new EmployeeStatusChanged($employee, $newStatus, $actor));
    }
}
```

## Organizational Hierarchy Management

### 2.1 Manager-Subordinate Relationships

```php
class Employee extends BaseModel
{
    /**
     * Manager diretto del dipendente.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }
    
    /**
     * Subordinati diretti.
     */
    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }
    
    /**
     * Tutti i subordinati nella gerarchia (ricorsivo).
     */
    public function allSubordinates(): Collection
    {
        $subordinates = collect();
        
        foreach ($this->subordinates as $subordinate) {
            $subordinates->push($subordinate);
            $subordinates = $subordinates->merge($subordinate->allSubordinates());
        }
        
        return $subordinates;
    }
    
    /**
     * Catena di comando verso l'alto.
     */
    public function getReportingChain(): Collection
    {
        $chain = collect();
        $current = $this->manager;
        
        while ($current) {
            $chain->push($current);
            $current = $current->manager;
        }
        
        return $chain;
    }
    
    /**
     * Verifica se può essere manager di un altro dipendente.
     */
    public function canManage(Employee $employee): bool
    {
        // Non può gestire se stesso
        if ($this->id === $employee->id) {
            return false;
        }
        
        // Non può gestire il proprio manager o superiori
        if ($employee->getReportingChain()->contains('id', $this->id)) {
            return false;
        }
        
        return true;
    }
}
```

### 2.2 Hierarchy Validation Logic

```php
class HierarchyValidator
{
    public function validateManagerAssignment(Employee $employee, ?Employee $newManager): void
    {
        if (!$newManager) {
            return; // CEO/Founder case
        }
        
        // Prevenzione cicli
        if (!$newManager->canManage($employee)) {
            throw new CircularHierarchyException(
                "Assigning {$newManager->name} as manager would create a circular hierarchy"
            );
        }
        
        // Validazione livelli massimi
        if ($this->getHierarchyDepth($newManager) >= config('employee.max_hierarchy_levels', 10)) {
            throw new MaxHierarchyDepthException(
                "Maximum hierarchy depth exceeded"
            );
        }
        
        // Validazione span of control
        if ($newManager->subordinates()->count() >= config('employee.max_subordinates', 20)) {
            throw new MaxSubordinatesException(
                "Manager already has maximum number of subordinates"
            );
        }
    }
    
    private function getHierarchyDepth(Employee $employee): int
    {
        return $employee->getReportingChain()->count();
    }
}
```

## Department Management Logic

### 3.1 Department Structure

```php
class Department extends BaseModel
{
    /**
     * Dipartimento parent (struttura gerarchica).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }
    
    /**
     * Sotto-dipartimenti.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Department::class, 'parent_id');
    }
    
    /**
     * Dipendenti assegnati direttamente.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
    
    /**
     * Tutti i dipendenti inclusi sotto-dipartimenti.
     */
    public function allEmployees(): Collection
    {
        $employees = $this->employees;
        
        foreach ($this->children as $child) {
            $employees = $employees->merge($child->allEmployees());
        }
        
        return $employees;
    }
    
    /**
     * Manager del dipartimento.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }
}
```

### 3.2 Department Transfer Logic

```php
class TransferEmployeeToDepartmentAction
{
    use QueueableAction;

    public function execute(Employee $employee, Department $newDepartment, User $actor, ?string $reason = null): void
    {
        $oldDepartment = $employee->department;
        
        // Validazioni business
        $this->validateTransfer($employee, $newDepartment);
        
        // Audit trail
        DepartmentTransferHistory::create([
            'employee_id' => $employee->id,
            'from_department_id' => $oldDepartment?->id,
            'to_department_id' => $newDepartment->id,
            'transferred_by' => $actor->id,
            'reason' => $reason,
            'transferred_at' => now()
        ]);
        
        // Aggiorna assegnazione
        $employee->update(['department_id' => $newDepartment->id]);
        
        // Gestione manager se necessario
        $this->handleManagerReassignment($employee, $newDepartment);
        
        // Trigger eventi
        event(new EmployeeTransferred($employee, $oldDepartment, $newDepartment, $actor));
    }
    
    private function validateTransfer(Employee $employee, Department $newDepartment): void
    {
        // Verifica che il dipartimento sia attivo
        if (!$newDepartment->is_active) {
            throw new InactiveDepartmentException("Cannot transfer to inactive department");
        }
        
        // Verifica capacità del dipartimento
        if ($newDepartment->employees()->count() >= $newDepartment->max_employees) {
            throw new DepartmentCapacityException("Department has reached maximum capacity");
        }
        
        // Verifica autorizzazioni
        if (!$employee->canBeTransferredTo($newDepartment)) {
            throw new UnauthorizedTransferException("Employee cannot be transferred to this department");
        }
    }
    
    private function handleManagerReassignment(Employee $employee, Department $newDepartment): void
    {
        // Se il dipendente ha subordinati, potrebbe essere necessario riassegnare
        if ($employee->subordinates()->count() > 0) {
            $departmentManager = $newDepartment->manager;
            
            if ($departmentManager && $departmentManager->canManage($employee)) {
                $employee->update(['manager_id' => $departmentManager->id]);
            }
        }
    }
}
```

## Employee Onboarding Logic

### 4.1 Onboarding Workflow

```php
class CreateEmployeeWithOnboardingAction
{
    use QueueableAction;

    public function execute(EmployeeData $data, User $creator): Employee
    {
        DB::transaction(function() use ($data, $creator) {
            // Crea user base
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['temporary_password'] ?? Str::random(12)),
                'email_verified_at' => null, // Deve verificare email
            ]);
            
            // Crea record employee
            $employee = Employee::create([
                'id' => $user->id,
                'employee_number' => $this->generateEmployeeNumber(),
                'department_id' => $data['department_id'],
                'manager_id' => $data['manager_id'] ?? null,
                'position_id' => $data['position_id'],
                'hire_date' => $data['hire_date'] ?? today(),
                'status' => EmployeeStatusEnum::ACTIVE,
                'salary' => $data['salary'] ?? null,
                'created_by' => $creator->id,
            ]);
            
            // Assegna ruoli di default
            $employee->assignRole('employee');
            
            // Crea task onboarding
            $this->createOnboardingTasks($employee);
            
            // Trigger eventi
            event(new EmployeeCreated($employee, $creator));
            
            return $employee;
        });
    }
    
    private function generateEmployeeNumber(): string
    {
        $year = date('Y');
        $lastNumber = Employee::whereYear('created_at', $year)
            ->max(DB::raw('CAST(SUBSTRING(employee_number, -4) AS UNSIGNED)')) ?? 0;
            
        return sprintf('EMP%s%04d', $year, $lastNumber + 1);
    }
    
    private function createOnboardingTasks(Employee $employee): void
    {
        $tasks = [
            'email_verification' => 'Verify email address',
            'profile_completion' => 'Complete profile information',
            'document_upload' => 'Upload required documents',
            'system_training' => 'Complete system training',
            'manager_meeting' => 'Meet with direct manager',
        ];
        
        foreach ($tasks as $key => $description) {
            OnboardingTask::create([
                'employee_id' => $employee->id,
                'task_key' => $key,
                'description' => $description,
                'status' => 'pending',
                'due_date' => now()->addDays($this->getTaskDueDays($key)),
            ]);
        }
    }
}
```

### 4.2 Onboarding Progress Tracking

```php
class GetOnboardingProgressAction
{
    use QueueableAction;

    public function execute(Employee $employee): array
    {
        $tasks = $employee->onboardingTasks;
        $completed = $tasks->where('status', 'completed')->count();
        $total = $tasks->count();
        
        return [
            'progress_percentage' => $total > 0 ? round(($completed / $total) * 100, 2) : 0,
            'completed_tasks' => $completed,
            'total_tasks' => $total,
            'overdue_tasks' => $tasks->where('due_date', '<', now())
                                   ->where('status', '!=', 'completed')
                                   ->count(),
            'next_task' => $this->getNextTask($tasks),
            'estimated_completion' => $this->estimateCompletion($tasks),
        ];
    }
    
    public function completeTask(Employee $employee, string $taskKey, User $completedBy): void
    {
        $task = $employee->onboardingTasks()
            ->where('task_key', $taskKey)
            ->where('status', '!=', 'completed')
            ->firstOrFail();
            
        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completed_by' => $completedBy->id,
        ]);
        
        // Check if onboarding is complete
        if ($this->isOnboardingComplete($employee)) {
            event(new EmployeeOnboardingCompleted($employee));
        }
        
        event(new OnboardingTaskCompleted($employee, $task, $completedBy));
    }
    
    private function isOnboardingComplete(Employee $employee): bool
    {
        return $employee->onboardingTasks()
            ->where('status', '!=', 'completed')
            ->doesntExist();
    }
}
```

## Performance Management Integration

### 5.1 Performance Review Cycle

```php
class InitiatePerformanceReviewAction
{
    use QueueableAction;

    public function execute(Employee $employee, string $reviewType, Carbon $dueDate): PerformanceReview
    {
        // Verifica se dipendente è eleggibile per review
        if (!$this->isEligibleForReview($employee, $reviewType)) {
            throw new ReviewEligibilityException("Employee not eligible for {$reviewType} review");
        }
        
        $review = PerformanceReview::create([
            'employee_id' => $employee->id,
            'reviewer_id' => $employee->manager_id,
            'review_type' => $reviewType,
            'review_period_start' => $this->getReviewPeriodStart($reviewType),
            'review_period_end' => $this->getReviewPeriodEnd($reviewType),
            'due_date' => $dueDate,
            'status' => 'pending',
        ]);
        
        // Crea goal se review annuale
        if ($reviewType === 'annual') {
            $this->createAnnualGoals($review);
        }
        
        event(new PerformanceReviewInitiated($review));
        
        return $review;
    }
    
    private function isEligibleForReview(Employee $employee, string $reviewType): bool
    {
        // Deve essere attivo
        if ($employee->status !== EmployeeStatusEnum::ACTIVE) {
            return false;
        }
        
        // Deve aver lavorato per il periodo minimo
        $minWorkDays = match($reviewType) {
            'probationary' => 90,
            'quarterly' => 90,
            'annual' => 365,
            default => 30
        };
        
        return $employee->hire_date->diffInDays(now()) >= $minWorkDays;
    }
}
```

## Employee Analytics & Reporting

### 6.1 Employee Metrics Calculation

```php
class CalculateEmployeeMetricsAction
{
    use QueueableAction;

    public function execute(Employee $employee, Carbon $startDate, Carbon $endDate): array
    {
        return [
            'attendance_rate' => $this->calculateAttendanceRate($employee, $startDate, $endDate),
            'punctuality_score' => $this->calculatePunctualityScore($employee, $startDate, $endDate),
            'overtime_hours' => $this->calculateOvertimeHours($employee, $startDate, $endDate),
            'productivity_score' => $this->calculateProductivityScore($employee, $startDate, $endDate),
            'goal_completion_rate' => $this->calculateGoalCompletionRate($employee, $startDate, $endDate),
        ];
    }
    
    private function calculateAttendanceRate(Employee $employee, Carbon $startDate, Carbon $endDate): float
    {
        $workDays = $this->getWorkDaysCount($startDate, $endDate);
        $attendedDays = WorkHour::where('employee_id', $employee->id)
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->where('type', WorkHourTypeEnum::CLOCK_IN)
            ->distinct(DB::raw('DATE(timestamp)'))
            ->count();
            
        return $workDays > 0 ? round(($attendedDays / $workDays) * 100, 2) : 0;
    }
    
    private function calculatePunctualityScore(Employee $employee, Carbon $startDate, Carbon $endDate): float
    {
        $clockIns = WorkHour::where('employee_id', $employee->id)
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->where('type', WorkHourTypeEnum::CLOCK_IN)
            ->get();
            
        if ($clockIns->isEmpty()) {
            return 0;
        }
        
        $onTimeCount = $clockIns->filter(function($clockIn) {
            return $clockIn->timestamp->hour <= 9; // Considerato puntuale se entro le 9:00
        })->count();
        
        return round(($onTimeCount / $clockIns->count()) * 100, 2);
    }
    
    private function calculateOvertimeHours(Employee $employee, Carbon $startDate, Carbon $endDate): float
    {
        $totalOvertime = 0;
        $current = $startDate->copy();
        
        while ($current->lte($endDate)) {
            $dailyHours = WorkHourCalculator::calculateDailyHours($employee->id, $current);
            $overtime = max(0, $dailyHours - 8); // Overtime oltre 8 ore
            $totalOvertime += $overtime;
            
            $current->addDay();
        }
        
        return round($totalOvertime, 2);
    }
}
```

### 6.2 Department Analytics

```php
class GetDepartmentMetricsAction
{
    use QueueableAction;

    public function execute(Department $department, Carbon $startDate, Carbon $endDate): array
    {
        $employees = $department->allEmployees();
        
        return [
            'total_employees' => $employees->count(),
            'active_employees' => $employees->where('status', EmployeeStatusEnum::ACTIVE)->count(),
            'average_tenure' => $this->calculateAverageTenure($employees),
            'turnover_rate' => $this->calculateTurnoverRate($department, $startDate, $endDate),
            'department_attendance_rate' => $this->calculateDepartmentAttendanceRate($employees, $startDate, $endDate),
            'total_work_hours' => $this->calculateTotalWorkHours($employees, $startDate, $endDate),
            'productivity_trends' => $this->getProductivityTrends($employees, $startDate, $endDate),
        ];
    }
    
    private function calculateTurnoverRate(Department $department, Carbon $startDate, Carbon $endDate): float
    {
        $avgEmployees = $this->getAverageEmployeeCount($department, $startDate, $endDate);
        $separations = Employee::where('department_id', $department->id)
            ->where('status', EmployeeStatusEnum::TERMINATED)
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->count();
            
        return $avgEmployees > 0 ? round(($separations / $avgEmployees) * 100, 2) : 0;
    }
}
```

## Business Events & Notifications

### 7.1 Employee Lifecycle Events

```php
// Eventi di business per Employee Management
class EmployeeCreated extends Event
{
    public function __construct(
        public Employee $employee,
        public User $creator
    ) {}
}

class EmployeeStatusChanged extends Event
{
    public function __construct(
        public Employee $employee,
        public EmployeeStatusEnum $newStatus,
        public User $actor
    ) {}
}

class EmployeeTransferred extends Event
{
    public function __construct(
        public Employee $employee,
        public ?Department $fromDepartment,
        public Department $toDepartment,
        public User $actor
    ) {}
}

// Listeners per automazioni
class NotifyHROfNewEmployee
{
    public function handle(EmployeeCreated $event): void
    {
        $hrTeam = User::role('hr')->get();
        
        foreach ($hrTeam as $hrUser) {
            $hrUser->notify(new NewEmployeeNotification($event->employee));
        }
    }
}

class CreateSystemAccountsForNewEmployee
{
    public function handle(EmployeeCreated $event): void
    {
        // Crea account nei sistemi integrati
        $this->createEmailAccount($event->employee);
        $this->assignSystemPermissions($event->employee);
        $this->addToDirectoryService($event->employee);
    }
}
```

---

*Documento creato: 2025-01-06*  
*Versione: 1.0*  
*Stato: Completo*
