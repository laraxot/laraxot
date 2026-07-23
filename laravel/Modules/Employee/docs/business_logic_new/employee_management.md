# Employee Management Business Logic - Actions Implementation

## 📚 Overview

Il sistema di Employee Management gestisce il ciclo di vita completo dei dipendenti utilizzando **esclusivamente Spatie QueueableActions**, implementando gerarchia organizzativa, onboarding, performance management e compliance.

## 🚨 CRITICAL: Actions-Only Architecture

Tutta la business logic è implementata tramite Actions, mai Services.

## 🏗️ Employee Management Actions

### 1. Employee Lifecycle Actions

#### CreateEmployeeAction
```php
namespace Modules\Employee\Actions\EmployeeManagement;

use Spatie\QueueableAction\QueueableAction;

class CreateEmployeeAction
{
    use QueueableAction;

    public function execute(EmployeeData $data, User $creator): Employee
    {
        return DB::transaction(function() use ($data, $creator) {
            // 1. Crea user base
            $user = app(CreateUserAccountAction::class)->execute($data->getUserData());
            
            // 2. Genera employee number
            $employeeNumber = app(GenerateEmployeeNumberAction::class)->execute();
            
            // 3. Crea employee record
            $employee = Employee::create([
                'id' => $user->id,
                'employee_number' => $employeeNumber,
                'department_id' => $data->departmentId,
                'manager_id' => $data->managerId,
                'position_id' => $data->positionId,
                'hire_date' => $data->hireDate ?? today(),
                'status' => EmployeeStatusEnum::ACTIVE,
                'salary' => $data->salary,
                'created_by' => $creator->id,
            ]);
            
            // 4. Assegna ruoli di default
            app(AssignDefaultRolesAction::class)->execute($employee);
            
            // 5. Inizia onboarding
            app(InitiateOnboardingAction::class)->execute($employee);
            
            // 6. Trigger eventi
            event(new EmployeeCreated($employee, $creator));
            
            return $employee;
        });
    }
}

class UpdateEmployeeAction
{
    use QueueableAction;

    public function execute(Employee $employee, EmployeeData $data, User $updater): Employee
    {
        // Validazione autorizzazioni
        app(ValidateEmployeeUpdatePermissionAction::class)->execute($updater, $employee);
        
        // Audit trail prima della modifica
        app(CreateEmployeeChangeLogAction::class)->execute($employee, $data, $updater);
        
        $employee->update($data->toArray());
        
        event(new EmployeeUpdated($employee, $updater));
        
        return $employee->fresh();
    }
}

class DeleteEmployeeAction
{
    use QueueableAction;

    public function execute(Employee $employee, User $deleter, string $reason): void
    {
        // Solo super admin può eliminare
        if (!$deleter->hasRole('super_admin')) {
            throw new UnauthorizedException('Only super admin can delete employees');
        }
        
        DB::transaction(function() use ($employee, $deleter, $reason) {
            // Anonimizza invece di eliminare (GDPR)
            app(AnonymizeEmployeeDataAction::class)->execute($employee, $deleter, $reason);
            
            // Riassegna subordinati
            app(ReassignSubordinatesAction::class)->execute($employee);
            
            // Log eliminazione
            app(LogEmployeeDeletionAction::class)->execute($employee, $deleter, $reason);
            
            event(new EmployeeDeleted($employee, $deleter, $reason));
        });
    }
}
```

### 2. Status Management Actions

#### ChangeEmployeeStatusAction
```php
class ChangeEmployeeStatusAction
{
    use QueueableAction;

    public function execute(Employee $employee, EmployeeStatusEnum $newStatus, User $actor, ?string $reason = null): void
    {
        // Validazione transizione
        app(ValidateStatusTransitionAction::class)->execute($employee->status, $newStatus);
        
        // Validazione autorizzazione
        app(ValidateStatusChangePermissionAction::class)->execute($actor, $employee, $newStatus);
        
        $oldStatus = $employee->status;
        
        DB::transaction(function() use ($employee, $newStatus, $actor, $reason, $oldStatus) {
            // Storia cambiamenti
            EmployeeStatusHistory::create([
                'employee_id' => $employee->id,
                'from_status' => $oldStatus,
                'to_status' => $newStatus,
                'changed_by' => $actor->id,
                'reason' => $reason,
                'changed_at' => now()
            ]);
            
            $employee->update(['status' => $newStatus]);
            
            // Azioni conseguenti al cambio stato
            app(HandleStatusChangeConsequencesAction::class)->execute($employee, $oldStatus, $newStatus);
        });
        
        event(new EmployeeStatusChanged($employee, $newStatus, $actor));
    }
}

class ValidateStatusTransitionAction
{
    use QueueableAction;

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

    public function execute(EmployeeStatusEnum $from, EmployeeStatusEnum $to): bool
    {
        $allowedTransitions = self::ALLOWED_TRANSITIONS[$from->value] ?? [];
        
        if (!in_array($to->value, $allowedTransitions)) {
            throw new InvalidStatusTransitionException(
                "Cannot transition from {$from->value} to {$to->value}"
            );
        }
        
        return true;
    }
}
```

### 3. Department Management Actions

#### TransferEmployeeAction
```php
class TransferEmployeeAction
{
    use QueueableAction;

    public function execute(Employee $employee, Department $newDepartment, User $actor, ?string $reason = null): void
    {
        $oldDepartment = $employee->department;
        
        // Validazioni business
        app(ValidateDepartmentTransferAction::class)->execute($employee, $newDepartment);
        
        DB::transaction(function() use ($employee, $newDepartment, $actor, $reason, $oldDepartment) {
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
            app(HandleManagerReassignmentAction::class)->execute($employee, $newDepartment);
            
            // Aggiorna permessi se necessario
            app(UpdateDepartmentPermissionsAction::class)->execute($employee, $newDepartment);
        });
        
        event(new EmployeeTransferred($employee, $oldDepartment, $newDepartment, $actor));
    }
}

class ValidateDepartmentTransferAction
{
    use QueueableAction;

    public function execute(Employee $employee, Department $newDepartment): bool
    {
        // Verifica che il dipartimento sia attivo
        if (!$newDepartment->is_active) {
            throw new InactiveDepartmentException("Cannot transfer to inactive department");
        }
        
        // Verifica capacità del dipartimento
        if ($newDepartment->employees()->count() >= $newDepartment->max_employees) {
            throw new DepartmentCapacityException("Department has reached maximum capacity");
        }
        
        // Verifica compatibilità ruolo
        app(ValidateRoleDepartmentCompatibilityAction::class)->execute($employee->position, $newDepartment);
        
        return true;
    }
}
```

### 4. Onboarding Actions

#### InitiateOnboardingAction
```php
class InitiateOnboardingAction
{
    use QueueableAction;

    public function execute(Employee $employee): Collection
    {
        $tasks = $this->getOnboardingTasks();
        $createdTasks = collect();
        
        foreach ($tasks as $taskKey => $taskData) {
            $task = OnboardingTask::create([
                'employee_id' => $employee->id,
                'task_key' => $taskKey,
                'description' => $taskData['description'],
                'status' => 'pending',
                'due_date' => now()->addDays($taskData['due_days']),
                'priority' => $taskData['priority'],
            ]);
            
            $createdTasks->push($task);
        }
        
        // Notifica manager
        app(NotifyManagerOfNewEmployeeAction::class)->execute($employee);
        
        // Invia email di benvenuto
        app(SendWelcomeEmailAction::class)->execute($employee);
        
        event(new EmployeeOnboardingInitiated($employee, $createdTasks));
        
        return $createdTasks;
    }
    
    private function getOnboardingTasks(): array
    {
        return [
            'email_verification' => [
                'description' => 'Verify email address',
                'due_days' => 1,
                'priority' => 'high'
            ],
            'profile_completion' => [
                'description' => 'Complete profile information',
                'due_days' => 3,
                'priority' => 'high'
            ],
            'document_upload' => [
                'description' => 'Upload required documents',
                'due_days' => 7,
                'priority' => 'medium'
            ],
            'system_training' => [
                'description' => 'Complete system training',
                'due_days' => 14,
                'priority' => 'medium'
            ],
            'manager_meeting' => [
                'description' => 'Meet with direct manager',
                'due_days' => 5,
                'priority' => 'high'
            ],
        ];
    }
}

class CompleteOnboardingTaskAction
{
    use QueueableAction;

    public function execute(Employee $employee, string $taskKey, User $completedBy): OnboardingTask
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
        
        // Verifica se onboarding è completo
        $isComplete = app(CheckOnboardingCompletionAction::class)->execute($employee);
        
        if ($isComplete) {
            app(FinalizeOnboardingAction::class)->execute($employee);
        }
        
        event(new OnboardingTaskCompleted($employee, $task, $completedBy));
        
        return $task;
    }
}
```

## 🔄 Action Composition Patterns

### Composite Actions

```php
class ProcessEmployeeHireAction
{
    use QueueableAction;

    public function execute(EmployeeData $data, User $hirer): Employee
    {
        // Composizione di multiple Actions
        return DB::transaction(function() use ($data, $hirer) {
            // 1. Crea employee
            $employee = app(CreateEmployeeAction::class)->execute($data, $hirer);
            
            // 2. Setup workspace
            app(SetupEmployeeWorkspaceAction::class)->execute($employee);
            
            // 3. Assign equipment
            app(AssignEmployeeEquipmentAction::class)->execute($employee, $data->equipmentNeeds ?? []);
            
            // 4. Setup system access
            app(SetupSystemAccessAction::class)->execute($employee);
            
            // 5. Schedule orientation
            app(ScheduleOrientationAction::class)->execute($employee);
            
            return $employee;
        });
    }
}
```

### Chain of Actions

```php
class ProcessDailyAttendanceAction
{
    use QueueableAction;

    public function execute(Department $department, Carbon $date): DepartmentAttendanceReport
    {
        $employees = $department->allEmployees();
        $attendanceData = collect();
        
        foreach ($employees as $employee) {
            // Chain di Actions per ogni dipendente
            $dailyData = app(GetEmployeeDailyDataAction::class)->execute($employee, $date);
            $metrics = app(CalculateEmployeeMetricsAction::class)->execute($employee, $date, $date);
            $compliance = app(CheckAttendanceComplianceAction::class)->execute($employee, $date);
            
            $attendanceData->push([
                'employee' => $employee,
                'daily_data' => $dailyData,
                'metrics' => $metrics,
                'compliance' => $compliance,
            ]);
        }
        
        return app(GenerateDepartmentReportAction::class)->execute($department, $date, $attendanceData);
    }
}
```

## 🎯 Integration with Filament

### Resource Actions Integration

```php
class EmployeeResource extends XotBaseResource
{
    protected function handleRecordCreation(array $data)
    {
        $employeeData = EmployeeData::from($data);
        
        return app(CreateEmployeeAction::class)->execute($employeeData, auth()->user());
    }
    
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $employeeData = EmployeeData::from($data);
        
        return app(UpdateEmployeeAction::class)->execute($record, $employeeData, auth()->user());
    }
}
```

### Custom Page Actions

```php
class EmployeeBulkImportPage extends XotBasePage
{
    public function importEmployees(): void
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);
        
        // Processa import tramite Action
        app(ImportEmployeesFromFileAction::class)
            ->onQueue('imports')
            ->execute($this->file, auth()->user());
            
        $this->notify('Import started. You will be notified when complete.');
    }
}
```

## 🔒 Security Actions Integration

### Authorization Actions

```php
class ValidateEmployeeAccessAction
{
    use QueueableAction;

    public function execute(User $accessor, Employee $target, string $operation): bool
    {
        // Log accesso
        app(LogSecurityAccessAction::class)->execute($accessor, 'employee', $operation, $target);
        
        // Validazione gerarchica
        $hasHierarchicalAccess = app(CheckHierarchicalAccessAction::class)->execute($accessor, $target);
        
        // Validazione basata su ruoli
        $hasRoleAccess = app(CheckRoleBasedAccessAction::class)->execute($accessor, $operation);
        
        return $hasHierarchicalAccess || $hasRoleAccess;
    }
}

class CheckHierarchicalAccessAction
{
    use QueueableAction;

    public function execute(User $accessor, Employee $target): bool
    {
        // Self access
        if ($accessor->id === $target->id) {
            return true;
        }
        
        // Manager access to subordinates
        return app(IsInReportingChainAction::class)->execute($accessor, $target);
    }
}
```

## 📊 Performance Actions

### Caching Actions

```php
class GetCachedEmployeeDataAction
{
    use QueueableAction;

    public function execute(int $employeeId, array $includes = []): EmployeeData
    {
        $cacheKey = "employee_data:{$employeeId}:" . md5(serialize($includes));
        
        return Cache::remember($cacheKey, now()->addHours(1), function() use ($employeeId, $includes) {
            return app(GetEmployeeDataAction::class)->execute($employeeId, $includes);
        });
    }
}

class InvalidateEmployeeCacheAction
{
    use QueueableAction;

    public function execute(int $employeeId): void
    {
        $tags = ["employee:{$employeeId}", "department_stats", "employee_metrics"];
        
        Cache::tags($tags)->flush();
    }
}
```

### Batch Processing Actions

```php
class ProcessBulkEmployeeUpdatesAction
{
    use QueueableAction;

    public function execute(Collection $updates, User $actor): array
    {
        $results = [];
        
        foreach ($updates as $update) {
            try {
                $employee = Employee::findOrFail($update['employee_id']);
                $data = EmployeeData::from($update['data']);
                
                $results[] = app(UpdateEmployeeAction::class)->execute($employee, $data, $actor);
                
            } catch (Exception $e) {
                Log::error('Bulk employee update failed', [
                    'update' => $update,
                    'error' => $e->getMessage()
                ]);
                
                $results[] = ['error' => $e->getMessage(), 'data' => $update];
            }
        }
        
        return $results;
    }
}
```

## 🎯 Action Testing Patterns

### Unit Testing Actions

```php
class CreateEmployeeActionTest extends TestCase
{
    /** @test */
    public function it_creates_employee_with_complete_onboarding(): void
    {
        // Arrange
        $data = new EmployeeData(
            name: 'John Doe',
            email: 'john@company.com',
            departmentId: 1
        );
        $creator = User::factory()->create();
        
        // Act
        $employee = app(CreateEmployeeAction::class)->execute($data, $creator);
        
        // Assert
        $this->assertInstanceOf(Employee::class, $employee);
        $this->assertEquals('John Doe', $employee->name);
        $this->assertEquals(EmployeeStatusEnum::ACTIVE, $employee->status);
        
        // Verifica onboarding tasks create
        $this->assertGreaterThan(0, $employee->onboardingTasks()->count());
    }
    
    /** @test */
    public function it_can_be_executed_asynchronously(): void
    {
        Queue::fake();
        
        $data = new EmployeeData(/* ... */);
        $creator = User::factory()->create();
        
        app(CreateEmployeeAction::class)
            ->onQueue('employee_management')
            ->execute($data, $creator);
            
        Queue::assertPushed(CreateEmployeeAction::class);
    }
}
```

---

*Documento creato: 2025-01-06*  
*Pattern: Spatie QueueableActions*  
*Compliance: Laraxot conventions*
