# 🚨 CRITICAL: Laraxot Actions Pattern Implementation

## ⚠️ ABSOLUTE RULE: NO SERVICES - ONLY QUEUEABLE ACTIONS

**This is the most important architectural rule in Laraxot. Violation will cause immediate build failures and require complete rewrites.**

## 🎯 Why Queueable Actions Instead of Services

### Traditional Service Pattern (FORBIDDEN)
```php
// ❌ FORBIDDEN - NEVER CREATE SERVICES
class EmployeeService
{
    public function createEmployee(array $data): Employee
    {
        // Business logic here
    }
    
    public function updateEmployee(Employee $employee, array $data): bool
    {
        // More logic
    }
}

// ❌ FORBIDDEN USAGE
$service = new EmployeeService();
$employee = $service->createEmployee($data);
```

### Laraxot Actions Pattern (MANDATORY)
```php
// ✅ MANDATORY - ALWAYS USE QUEUEABLE ACTIONS
class CreateEmployeeAction extends QueueableAction
{
    public function handle(array $data): Employee
    {
        // Business logic here
        return $this->execute($data);
    }
    
    private function execute(array $data): Employee
    {
        // Implementation details
    }
}

// ✅ CORRECT USAGE
$employee = CreateEmployeeAction::dispatch($data);
```

## 📋 Actions Pattern Benefits

### 1. **Automatic Queueing**
- Actions can be queued automatically
- Background processing without extra code
- Built-in retry mechanisms

### 2. **Better Testing**
- Isolated business logic
- Mockable dependencies
- Clear input/output contracts

### 3. **Monitoring & Tracing**
- Built-in logging
- Performance tracking
- Error handling

### 4. **Scalability**
- Horizontal scaling
- Load distribution
- Priority management

## 🏗️ Actions Implementation Template

### Basic Action Structure
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Actions;

use Modules\Employee\Models\Employee;
use Spatie\QueueableAction\QueueableAction;

class CreateEmployeeAction extends QueueableAction
{
    public function handle(array $data): Employee
    {
        // Input validation
        $this->validateData($data);
        
        // Business logic execution
        return $this->executeCreation($data);
    }
    
    private function validateData(array $data): void
    {
        // Validation rules
        validator($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            // ... more rules
        ])->validate();
    }
    
    private function executeCreation(array $data): Employee
    {
        // Core business logic
        $employee = Employee::create([
            'employee_code' => $this->generateEmployeeCode(),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            // ... other fields
        ]);
        
        // Post-creation logic
        $this->sendWelcomeNotification($employee);
        $this->assignDefaultPermissions($employee);
        
        return $employee;
    }
    
    private function generateEmployeeCode(): string
    {
        return 'EMP' . now()->format('YmdHis') . rand(100, 999);
    }
}
```

### Action with Dependencies
```php
class ProcessEmployeeSalaryAction extends QueueableAction
{
    public function __construct(
        private TaxCalculator $taxCalculator,
        private BenefitsProcessor $benefitsProcessor,
        private NotificationService $notificationService
    ) {}
    
    public function handle(Employee $employee, array $periodData): SalaryResult
    {
        $grossSalary = $this->calculateGrossSalary($employee, $periodData);
        $deductions = $this->calculateDeductions($employee, $grossSalary);
        $netSalary = $grossSalary - $deductions;
        
        $result = new SalaryResult($grossSalary, $deductions, $netSalary);
        
        $this->notificationService->sendPayslip($employee, $result);
        
        return $result;
    }
}
```

## 🔄 Action Execution Methods

### 1. **Immediate Execution**
```php
// Execute immediately (synchronous)
$result = CreateEmployeeAction::execute($data);

// Or using helper
$result = action(CreateEmployeeAction::class, $data);
```

### 2. **Async Queue Execution**
```php
// Dispatch to queue (asynchronous)
CreateEmployeeAction::dispatch($data);

// With specific queue
CreateEmployeeAction::dispatch($data)->onQueue('high');

// With delay
CreateEmployeeAction::dispatch($data)->delay(now()->addMinutes(5));
```

### 3. **Chain Execution**
```php
// Chain multiple actions
CreateEmployeeAction::withChain([
    new AssignDepartmentAction($departmentId),
    new SendWelcomeEmailAction(),
    new SetupSystemAccessAction()
])->dispatch($data);
```

## 📁 Actions Organization Structure

### Directory Structure
```
Modules/Employee/
├── app/
│   ├── Actions/                    # Business logic actions
│   │   ├── Employee/              # Employee-related actions
│   │   │   ├── CreateEmployeeAction.php
│   │   │   ├── UpdateEmployeeAction.php
│   │   │   ├── DeleteEmployeeAction.php
│   │   │   └── ArchiveEmployeeAction.php
│   │   ├── TimeTracking/          # Time tracking actions
│   │   │   ├── ClockInAction.php
│   │   │   ├── ClockOutAction.php
│   │   │   ├── CalculateHoursAction.php
│   │   │   └── ApproveTimeAction.php
│   │   ├── Department/            # Department actions
│   │   │   ├── CreateDepartmentAction.php
│   │   │   ├── AssignEmployeeAction.php
│   │   │   └── ReorganizeAction.php
│   │   └── Notification/          # Notification actions
│   │       ├── SendWelcomeEmailAction.php
│   │       ├── NotifyManagerAction.php
│   │       └── BroadcastAnnouncementAction.php
│   ├── Models/
│   ├── Filament/
│   └── Providers/
```

## 🧪 Testing Actions

### Action Test Example
```php
class CreateEmployeeActionTest extends TestCase
{
    public function test_creates_employee_with_valid_data(): void
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
        ];
        
        $employee = CreateEmployeeAction::execute($data);
        
        $this->assertInstanceOf(Employee::class, $employee);
        $this->assertEquals('John', $employee->first_name);
        $this->assertEquals('Doe', $employee->last_name);
        $this->assertStringStartsWith('EMP', $employee->employee_code);
    }
    
    public function test_throws_validation_error_for_invalid_email(): void
    {
        $this->expectException(ValidationException::class);
        
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'invalid-email',
        ];
        
        CreateEmployeeAction::execute($data);
    }
}
```

## 🔧 Configuration & Optimization

### Queue Configuration
```php
// config/queue.php
'employee_actions' => [
    'driver' => 'database',
    'table' => 'jobs',
    'queue' => 'employee',
    'retry_after' => 90,
    'after_commit' => false,
],

// Action-specific queue
class ProcessLargeReportAction extends QueueableAction
{
    public $queue = 'reports';
    public $maxExceptions = 3;
    public $timeout = 300;
    public $tries = 5;
    
    public function handle(Report $report): void
    {
        // Long-running processing
    }
}
```

### Performance Optimization
```php
class OptimizedAction extends QueueableAction
{
    // Disable queue for high-frequency actions
    public $shouldQueue = false;
    
    // Cache results for identical inputs
    public function handle(string $input): string
    {
        return Cache::remember(
            "action_result_{$input}",
            3600,
            fn() => $this->computeResult($input)
        );
    }
}
```

## 🚨 Common Mistakes & Solutions

### Mistake 1: Creating Service Classes
```php
// ❌ WRONG - Service class
class EmployeeService {}

// ✅ CORRECT - Action class  
class ManageEmployeeAction extends QueueableAction {}
```

### Mistake 2: Putting Logic in Controllers
```php
// ❌ WRONG - Logic in controller
class EmployeeController
{
    public function store(Request $request)
    {
        // Business logic here ❌
        $employee = Employee::create($request->all());
        // More logic ❌
    }
}

// ✅ CORRECT - Controller calls action
class EmployeeController
{
    public function store(CreateEmployeeRequest $request)
    {
        $employee = CreateEmployeeAction::execute($request->validated());
        return response()->json($employee);
    }
}
```

### Mistake 3: Not Using Dependency Injection
```php
// ❌ WRONG - Direct instantiation
$action = new ProcessDataAction();

// ✅ CORRECT - Laravel container
$action = app(ProcessDataAction::class);
// Or better: use constructor injection
class MyController
{
    public function __construct(
        private ProcessDataAction $processAction
    ) {}
}
```

## 📊 Migration Guide: Services → Actions

### Step 1: Identify Service Methods
```php
// OLD SERVICE
class EmployeeService
{
    public function createEmployee($data) { /* logic */ }
    public function updateEmployee($id, $data) { /* logic */ }
    public function deleteEmployee($id) { /* logic */ }
}
```

### Step 2: Create Equivalent Actions
```php
// NEW ACTIONS
class CreateEmployeeAction extends QueueableAction
{
    public function handle(array $data) { /* logic */ }
}

class UpdateEmployeeAction extends QueueableAction  
{
    public function handle(Employee $employee, array $data) { /* logic */ }
}

class DeleteEmployeeAction extends QueueableAction
{
    public function handle(Employee $employee) { /* logic */ }
}
```

### Step 3: Update Usage
```php
// OLD: Service usage
$service = new EmployeeService();
$employee = $service->createEmployee($data);

// NEW: Action usage
$employee = CreateEmployeeAction::execute($data);
// Or async:
CreateEmployeeAction::dispatch($data);
```

## 🔍 Code Review Checklist

### Actions Pattern Compliance
- [ ] **NO** Service classes exist
- [ ] **ALL** business logic in QueueableAction classes
- [ ] **NO** logic in controllers (only action calls)
- [ ] **PROPER** dependency injection
- [ ] **CLEAR** input/output contracts
- [ ] **APPROPRIATE** queue configuration
- [ ] **COMPLETE** test coverage
- [ ] **PROPER** error handling
- [ ] **LOGICAL** action organization
- [ ] **CONSISTENT** naming conventions

### Performance Considerations
- [ ] Should this action be queued? (yes/no)
- [ ] Appropriate queue priority configured
- [ ] Timeout and retry settings appropriate
- [ ] Memory usage optimized
- [ ] Database queries optimized
- [ ] Caching strategy implemented

### Security Considerations
- [ ] Input validation complete
- [ ] Authorization checks implemented
- [ ] Sensitive data handling proper
- [ ] Audit logging included
- [ ] GDPR compliance verified

---

**🚨 VIOLATION CONSEQUENCES:**
- Build failures in CI/CD pipeline
- Code rejection during review
- Performance degradation
- Maintenance difficulties
- Security vulnerabilities

**✅ COMPLIANCE REWARDS:**
- Clean, maintainable code
- Better performance
- Easier testing
- Improved scalability
- Career advancement

*This pattern is non-negotiable. All new code must follow Actions pattern. Existing services must be migrated to Actions.*

### ✅ CORRETTO - Spatie QueueableAction

```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Actions;

use Spatie\QueueableAction\QueueableAction;
use Modules\Employee\Models\Employee;
use Modules\Employee\Data\EmployeeData;

class CreateEmployeeAction
{
    use QueueableAction;

    public function execute(EmployeeData $data): Employee
    {
        return Employee::create([
            'name' => $data->name,
            'email' => $data->email,
            'department_id' => $data->departmentId,
            'hire_date' => $data->hireDate,
        ]);
    }
}
```

### ❌ ERRATO - Service Tradizionale

```php
<?php

// ❌ MAI CREARE QUESTO
namespace Modules\Employee\Services;

class EmployeeService
{
    public function createEmployee(array $data): Employee
    {
        // Logica di business
    }
}
```

## Utilizzo delle Actions

### In Controller

```php
class EmployeeController extends Controller
{
    public function store(CreateEmployeeRequest $request, CreateEmployeeAction $action): JsonResponse
    {
        $employeeData = EmployeeData::from($request->validated());
        
        // Esecuzione sincrona
        $employee = $action->execute($employeeData);
        
        return response()->json([
            'success' => true,
            'data' => $employee,
        ]);
    }
    
    public function storeAsync(CreateEmployeeRequest $request, CreateEmployeeAction $action): JsonResponse
    {
        $employeeData = EmployeeData::from($request->validated());
        
        // Esecuzione asincrona (queue)
        $action->onQueue('employees')->execute($employeeData);
        
        return response()->json([
            'success' => true,
            'message' => 'Employee creation queued',
        ]);
    }
}
```

### In Filament Resources

```php
class EmployeeResource extends XotBaseResource
{
    protected function handleRecordCreation(array $data)
    {
        $employeeData = EmployeeData::from($data);
        
        return app(CreateEmployeeAction::class)->execute($employeeData);
    }
}
```

### In Livewire Components

```php
class EmployeeForm extends Component
{
    public function save(): void
    {
        $this->validate();
        
        $employeeData = new EmployeeData(
            name: $this->name,
            email: $this->email,
            departmentId: $this->departmentId
        );
        
        app(CreateEmployeeAction::class)->execute($employeeData);
        
        $this->notify('Employee created successfully');
    }
}
```

## Actions per Employee Module

### Time Tracking Actions

```php
namespace Modules\Employee\Actions;

// Timbrature
class ClockInAction
{
    use QueueableAction;
    
    public function execute(int $employeeId, Carbon $timestamp, ?string $location = null): WorkHour
    {
        // Validazione business logic
        $this->validateClockIn($employeeId, $timestamp);
        
        return WorkHour::create([
            'employee_id' => $employeeId,
            'type' => WorkHourTypeEnum::CLOCK_IN,
            'timestamp' => $timestamp,
            'location_lat' => $location ? $this->extractLat($location) : null,
            'location_lng' => $location ? $this->extractLng($location) : null,
            'status' => WorkHourStatusEnum::PENDING,
        ]);
    }
}

class ClockOutAction
{
    use QueueableAction;
    
    public function execute(int $employeeId, Carbon $timestamp): WorkHour
    {
        $this->validateClockOut($employeeId, $timestamp);
        
        return WorkHour::create([
            'employee_id' => $employeeId,
            'type' => WorkHourTypeEnum::CLOCK_OUT,
            'timestamp' => $timestamp,
            'status' => WorkHourStatusEnum::PENDING,
        ]);
    }
}

class CalculateDailyHoursAction
{
    use QueueableAction;
    
    public function execute(int $employeeId, Carbon $date): float
    {
        $entries = WorkHour::where('employee_id', $employeeId)
            ->whereDate('timestamp', $date)
            ->orderBy('timestamp')
            ->get();
            
        return $this->calculateFromEntries($entries);
    }
}
```

### Employee Management Actions

```php
class CreateEmployeeAction
{
    use QueueableAction;
    
    public function execute(EmployeeData $data): Employee
    {
        return DB::transaction(function() use ($data) {
            $employee = Employee::create($data->toArray());
            
            // Trigger onboarding
            app(InitiateOnboardingAction::class)->execute($employee);
            
            return $employee;
        });
    }
}

class TransferEmployeeAction
{
    use QueueableAction;
    
    public function execute(Employee $employee, Department $newDepartment, User $actor, ?string $reason = null): void
    {
        $this->validateTransfer($employee, $newDepartment);
        
        DB::transaction(function() use ($employee, $newDepartment, $actor, $reason) {
            // Audit trail
            $this->createTransferRecord($employee, $newDepartment, $actor, $reason);
            
            // Update assignment
            $employee->update(['department_id' => $newDepartment->id]);
            
            // Handle manager reassignment
            app(ReassignManagerAction::class)->execute($employee, $newDepartment);
        });
    }
}

class ChangeEmployeeStatusAction
{
    use QueueableAction;
    
    public function execute(Employee $employee, EmployeeStatusEnum $newStatus, User $actor, ?string $reason = null): void
    {
        app(ValidateStatusTransitionAction::class)->execute($employee->status, $newStatus);
        
        // Create status history
        EmployeeStatusHistory::create([
            'employee_id' => $employee->id,
            'from_status' => $employee->status,
            'to_status' => $newStatus,
            'changed_by' => $actor->id,
            'reason' => $reason,
            'changed_at' => now()
        ]);
        
        $employee->update(['status' => $newStatus]);
        
        event(new EmployeeStatusChanged($employee, $newStatus, $actor));
    }
}
```

### Analytics & Reporting Actions

```php
class GenerateAttendanceReportAction
{
    use QueueableAction;
    
    public function execute(Department $department, Carbon $startDate, Carbon $endDate): AttendanceReport
    {
        $employees = $department->allEmployees();
        $reportData = [];
        
        foreach ($employees as $employee) {
            $reportData[] = app(CalculateEmployeeMetricsAction::class)
                ->execute($employee, $startDate, $endDate);
        }
        
        return new AttendanceReport($reportData, $department, $startDate, $endDate);
    }
}

class CalculateEmployeeMetricsAction
{
    use QueueableAction;
    
    public function execute(Employee $employee, Carbon $startDate, Carbon $endDate): array
    {
        return [
            'attendance_rate' => app(CalculateAttendanceRateAction::class)->execute($employee, $startDate, $endDate),
            'punctuality_score' => app(CalculatePunctualityScoreAction::class)->execute($employee, $startDate, $endDate),
            'overtime_hours' => app(CalculateOvertimeHoursAction::class)->execute($employee, $startDate, $endDate),
            'total_work_hours' => app(CalculateTotalWorkHoursAction::class)->execute($employee, $startDate, $endDate),
        ];
    }
}
```

## Migrazione da Services a Actions

### Identificazione Services Esistenti

Se esistono Services nel modulo, devono essere convertiti:

```php
// ❌ DA CONVERTIRE
class EmployeeService
{
    public function createEmployee(array $data): Employee { }
    public function updateEmployee(Employee $employee, array $data): Employee { }
    public function deleteEmployee(Employee $employee): bool { }
}

// ✅ CONVERTITO IN
class CreateEmployeeAction { use QueueableAction; }
class UpdateEmployeeAction { use QueueableAction; }
class DeleteEmployeeAction { use QueueableAction; }
```

### Processo di Migrazione

1. **Identificazione**: Trova tutti i Services esistenti
2. **Decomposizione**: Spezza ogni Service in Actions singole
3. **Conversione**: Implementa ogni Action con QueueableAction trait
4. **Testing**: Verifica funzionalità con nuove Actions
5. **Cleanup**: Rimuovi Services obsoleti

## Testing Actions

```php
class CreateEmployeeActionTest extends TestCase
{
    /** @test */
    public function it_creates_employee_with_valid_data(): void
    {
        $data = new EmployeeData(
            name: 'John Doe',
            email: 'john@example.com',
            departmentId: 1
        );
        
        $employee = app(CreateEmployeeAction::class)->execute($data);
        
        $this->assertInstanceOf(Employee::class, $employee);
        $this->assertEquals('John Doe', $employee->name);
        $this->assertEquals('john@example.com', $employee->email);
    }
    
    /** @test */
    public function it_can_be_queued(): void
    {
        Queue::fake();
        
        $data = new EmployeeData(/* ... */);
        
        app(CreateEmployeeAction::class)
            ->onQueue('employees')
            ->execute($data);
            
        Queue::assertPushed(CreateEmployeeAction::class);
    }
}
```

## Best Practices

### 1. Naming Convention
```php
// Pattern: {Verb}{Entity}Action
CreateEmployeeAction
UpdateEmployeeAction
DeleteEmployeeAction
CalculateHoursAction
GenerateReportAction
```

### 2. Single Responsibility
```php
// ✅ CORRETTO - Una responsabilità
class SendWelcomeEmailAction
{
    use QueueableAction;
    
    public function execute(Employee $employee): void
    {
        Mail::to($employee->email)->send(new WelcomeEmail($employee));
    }
}

// ❌ ERRATO - Troppe responsabilità
class EmployeeOnboardingAction
{
    public function execute(Employee $employee): void
    {
        $this->sendWelcomeEmail($employee);
        $this->createSystemAccounts($employee);
        $this->assignEquipment($employee);
        $this->scheduleTraining($employee);
    }
}
```

### 3. Data Objects Integration
```php
class UpdateEmployeeAction
{
    use QueueableAction;
    
    // ✅ CORRETTO - Usa Data Objects
    public function execute(Employee $employee, EmployeeData $data): Employee
    {
        $employee->update($data->toArray());
        return $employee->fresh();
    }
    
    // ❌ ERRATO - Array non tipizzati
    public function execute(Employee $employee, array $data): Employee
    {
        $employee->update($data);
        return $employee;
    }
}
```

## Checklist Conversione

- [ ] Identificare tutti i Services esistenti nel modulo
- [ ] Convertire ogni metodo Service in Action separata
- [ ] Implementare QueueableAction trait in tutte le Actions
- [ ] Aggiornare Controller per usare Actions
- [ ] Aggiornare Filament Resources per usare Actions
- [ ] Aggiornare test per testare Actions
- [ ] Rimuovere Services obsoleti
- [ ] Documentare tutte le Actions create

---

*Regola aggiornata: 2025-01-06*  
*Priorità: CRITICA - Non violabile*  
*Riferimento: Spatie Laravel QueueableAction*
