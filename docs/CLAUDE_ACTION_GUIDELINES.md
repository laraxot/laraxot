# CLAUDE ACTION GUIDELINES - Spatie Queueable Actions

## 🚫 ABSOLUTELY NO SERVICES - USE QUEUEABLE ACTIONS ONLY

### CRITICAL RULE
**NEVER create Service classes. ALWAYS use Spatie Queueable Actions for ALL business logic operations.**

## ✅ CORRECT PATTERN (MANDATORY)

### Action Class Structure
```php
<?php

namespace App\Actions\Employee;

use Spatie\QueueableAction\QueueableAction;
use Modules\Employee\Models\Employee;
use Modules\Employee\Models\WorkHour;
use Modules\Employee\Enums\WorkHourTypeEnum;
use Modules\Employee\Enums\WorkHourStatusEnum;
use Modules\Employee\Data\EmployeeData;

class CreateEmployeeAction
{
    use QueueableAction;

    public function execute(EmployeeData $employeeData): Employee
    {
        // Business logic here
        return Employee::create($employeeData->toArray());
    }
}

class CreateWorkHourAction
{
    use QueueableAction;

    public function execute(int $employeeId, WorkHourTypeEnum $type): WorkHour
    {
        return WorkHour::create([
            'employee_id' => $employeeId,
            'type' => $type,
            'timestamp' => now(),
            'status' => WorkHourStatusEnum::PENDING,
        ]);
    }
}
```

### Usage Pattern
```php
// CORRECT: Use Queueable Action
$action = new CreateEmployeeAction();
$employee = $action->execute($employeeData);

// CORRECT: Dispatch to queue
$action->onQueue()->execute($employeeData);
```

## ❌ FORBIDDEN PATTERNS

### NO Service Classes
```php
// ❌ FORBIDDEN - Service class
class EmployeeService {
    public function createEmployee($data) { /* ... */ }
}

// ❌ FORBIDDEN - Service with static methods
class EmployeeService {
    public static function create($data) { /* ... */ }
}
```

### NO Helper Classes with Business Logic
```php
// ❌ FORBIDDEN - Helper class with business logic
class EmployeeHelper {
    public function processEmployee($data) { /* ... */ }
}
```

### NO Direct Logic in Controllers
```php
// ❌ FORBIDDEN - Business logic in controller
public function store(Request $request)
{
    // Business logic should be in Action, not here
    $employee = Employee::create($request->validated());
    // ... more business logic
}
```

## 🏗️ Action Design Patterns

### 1. CRUD Actions
```php
class CreateEmployeeAction { use QueueableAction; }
class UpdateEmployeeAction { use QueueableAction; }
class DeleteEmployeeAction { use QueueableAction; }
class GetEmployeeAction { use QueueableAction; }
```

### 2. Business Process Actions
```php
class CalculateEmployeeSalaryAction { use QueueableAction; }
class ProcessTimeSheetAction { use QueueableAction; }
class GenerateEmployeeReportAction { use QueueableAction; }
```

### 3. Integration Actions
```php
class SyncEmployeeToHRSystemAction { use QueueableAction; }
class ExportEmployeeDataAction { use QueueableAction; }
class ImportEmployeeWorkHoursAction { use QueueableAction; }
class CalculateWorkHoursAction { use QueueableAction; }
```

## 📁 Directory Structure

### Required Structure
```
app/
├── Actions/
│   ├── Employee/
│   │   ├── CreateEmployeeAction.php
│   │   ├── UpdateEmployeeAction.php
│   │   ├── DeleteEmployeeAction.php
│   │   └── CalculateSalaryAction.php
│   ├── WorkHour/
│   │   ├── CreateWorkHourAction.php
│   │   ├── ClockInAction.php
│   │   ├── ClockOutAction.php
│   │   └── CalculateWorkedHoursAction.php
│   └── Reporting/
│       ├── GenerateAttendanceReportAction.php
│       └── ExportWorkHourDataAction.php
```

### Namespace Convention
```php
namespace App\Actions\{Module}\{ActionName}Action;
// Examples:
namespace App\Actions\Employee\CreateEmployeeAction;
namespace App\Actions\TimeTracking\ClockInAction;
namespace App\Actions\Reporting\GenerateReportAction;
```

## 🔧 Configuration & Setup

### composer.json Requirement
```json
{
    "require": {
        "spatie/laravel-queueable-action": "^3.0"
    }
}
```

### Action Service Provider
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Actions\Employee\CreateEmployeeAction;
use App\Actions\Employee\UpdateEmployeeAction;

class ActionServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CreateEmployeeAction::class);
        $this->app->singleton(UpdateEmployeeAction::class);
    }
}
```

## 🎯 Usage Examples

### Basic Action Usage
```php
// Instantiate and execute
$action = new CreateEmployeeAction();
$employee = $action->execute($employeeData);

// With dependency injection
public function __construct(
    private CreateEmployeeAction $createAction
) {}

public function store(EmployeeRequest $request)
{
    $employee = $this->createAction->execute(
        EmployeeData::fromRequest($request)
    );
}
```

### Queueable Action Usage
```php
// Execute on specific queue
$action->onQueue('high')->execute($data);

// Execute with delay
$action->onQueue('default')->delay(60)->execute($data);

// Execute with specific connection
$action->onConnection('redis')->execute($data);
```

### Action with Dependencies
```php
<?php

namespace App\Actions\Employee;

use Spatie\QueueableAction\QueueableAction;
use App\Services\NotificationService;
use App\Services\AuditService;

class CreateEmployeeAction
{
    use QueueableAction;

    public function __construct(
        private NotificationService $notification,
        private AuditService $audit
    ) {}

    public function execute(EmployeeData $data)
    {
        $employee = Employee::create($data->toArray());
        
        $this->notification->sendWelcomeEmail($employee);
        $this->audit->logEmployeeCreation($employee);
        
        return $employee;
    }
}
```

## 🚀 Advanced Patterns

### Action Chaining
```php
// Chain multiple actions
$employee = (new CreateEmployeeAction())->execute($data);
(new AssignDepartmentAction())->execute($employee, $departmentId);
(new SendWelcomeEmailAction())->execute($employee);
```

### Conditional Queueing
```php
public function execute(EmployeeData $data, bool $async = true)
{
    if ($async) {
        return $this->onQueue()->execute($data);
    }
    
    // Synchronous execution
    return Employee::create($data->toArray());
}
```

### Action with Validation
```php
public function execute(EmployeeData $data): Employee
{
    // Validate business rules
    $this->validateEmployeeData($data);
    
    // Execute business logic
    return Employee::create($data->toArray());
}

private function validateEmployeeData(EmployeeData $data): void
{
    // Business rule validation
    if ($data->salary < config('employee.min_salary')) {
        throw new BusinessRuleException('Salary below minimum');
    }
}
```

## 📊 Monitoring & Debugging

### Action Logging
```php
public function execute(EmployeeData $data)
{
    logger()->info('Creating employee', ['email' => $data->email]);
    
    try {
        $employee = Employee::create($data->toArray());
        logger()->info('Employee created', ['id' => $employee->id]);
        
        return $employee;
    } catch (\Exception $e) {
        logger()->error('Employee creation failed', [
            'error' => $e->getMessage(),
            'data' => $data->toArray()
        ]);
        
        throw $e;
    }
}
```

### Performance Tracking
```php
public function execute(EmployeeData $data)
{
    $start = microtime(true);
    
    $employee = Employee::create($data->toArray());
    
    $duration = microtime(true) - $start;
    logger()->debug('Action execution time', [
        'action' => static::class,
        'duration' => $duration
    ]);
    
    return $employee;
}
```

## 🔒 Security Considerations

### Input Validation
```php
public function execute(EmployeeData $data)
{
    // Always validate input in actions
    $validator = Validator::make($data->toArray(), [
        'email' => 'required|email|unique:employees',
        'salary' => 'required|numeric|min:0',
    ]);
    
    if ($validator->fails()) {
        throw new ValidationException($validator);
    }
    
    return Employee::create($data->toArray());
}
```

### Authorization Checks
```php
public function execute(EmployeeData $data)
{
    // Check permissions in actions
    if (!auth()->user()->can('create', Employee::class)) {
        throw new AuthorizationException('Not allowed to create employees');
    }
    
    return Employee::create($data->toArray());
}
```

## 🧪 Testing Guidelines

### Action Testing
```php
// Test action execution
public function test_create_employee_action()
{
    $action = new CreateEmployeeAction();
    $employeeData = EmployeeData::factory()->make();
    
    $employee = $action->execute($employeeData);
    
    $this->assertInstanceOf(Employee::class, $employee);
    $this->assertDatabaseHas('employees', ['email' => $employeeData->email]);
}

// Test queued action
public function test_queued_employee_creation()
{
    Queue::fake();
    
    $action = new CreateEmployeeAction();
    $employeeData = EmployeeData::factory()->make();
    
    $action->onQueue()->execute($employeeData);
    
    Queue::assertPushed(CreateEmployeeAction::class);
}
```

## 📈 Performance Optimization

### Heavy Operations in Queue
```php
// Move heavy operations to queue
public function execute(ReportRequest $request)
{
    // Quick validation
    $this->validateRequest($request);
    
    // Heavy processing in queue
    return $this->onQueue('reports')->execute($request);
}

// The queued method handles heavy work
public function __invoke(ReportRequest $request)
{
    // CPU-intensive report generation
    $report = $this->generateLargeReport($request);
    
    // Store and notify
    $this->storeReport($report);
    $this->notifyUser($request->user(), $report);
}
```

## 🚨 COMMON MISTAKES TO AVOID

### 1. Mixing Patterns
```php
// ❌ DON'T mix Action and Service patterns
class CreateEmployeeAction {
    public function execute($data) {
        // Don't call services from actions
        $service = new EmployeeService(); // ❌ WRONG
        return $service->create($data);
    }
}
```

### 2. Skipping QueueableAction Trait
```php
// ❌ DON'T forget the trait
class CreateEmployeeAction {
    // Missing: use QueueableAction; ❌ WRONG
    public function execute($data) { /* ... */ }
}
```

### 3. Business Logic in Wrong Layer
```php
// ❌ DON'T put business logic in controllers
class EmployeeController {
    public function store(Request $request)
    {
        // Business logic should be in Action ❌ WRONG
        if ($request->salary < 30000) {
            throw new Exception('Salary too low');
        }
        // ...
    }
}
```

## 🔄 Migration Strategy

### From Services to Actions
```php
// BEFORE: Service pattern ❌
class EmployeeService {
    public function create(array $data) {
        return Employee::create($data);
    }
}

// AFTER: Action pattern ✅
class CreateEmployeeAction {
    use QueueableAction;
    
    public function execute(EmployeeData $data) {
        return Employee::create($data->toArray());
    }
}
```

### From Controller Logic to Actions
```php
// BEFORE: Logic in controller ❌
public function store(Request $request)
{
    $validated = $request->validate([...]);
    
    // Business logic in controller
    if ($validated['salary'] < config('min_salary')) {
        abort(422, 'Salary too low');
    }
    
    $employee = Employee::create($validated);
    
    // More business logic
    event(new EmployeeCreated($employee));
    
    return $employee;
}

// AFTER: Clean controller with Action ✅
public function store(EmployeeRequest $request, CreateEmployeeAction $action)
{
    $employee = $action->execute(
        EmployeeData::fromRequest($request)
    );
    
    return response()->json($employee, 201);
}
```

## 🏗️ LARAXOT XOTBASE EXTENSION RULES

### 🚨 CRITICAL ARCHITECTURE RULE
**NEVER extend Filament classes directly. ALWAYS extend XotBase abstract classes.**

### ✅ CORRECT EXTENSION PATTERNS (MANDATORY)

#### Pages
```php
// ❌ FORBIDDEN - Direct Filament extension
use Filament\Resources\Pages\CreateRecord;
class CreateEmployee extends CreateRecord {}

// ✅ CORRECT - XotBase extension  
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;
class CreateEmployee extends XotBaseCreateRecord {}
```

#### Complete Extension Mapping
```php
// Resources Pages
Filament\Resources\Pages\CreateRecord → Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord
Filament\Resources\Pages\EditRecord → Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord  
Filament\Resources\Pages\ListRecords → Modules\Xot\Filament\Resources\Pages\XotBaseListRecords
Filament\Resources\Pages\Page → Modules\Xot\Filament\Resources\Pages\XotBasePage

// Regular Pages
Filament\Pages\Page → Modules\Xot\Filament\Pages\XotBasePage

// Widgets
Filament\Widgets\Widget → Modules\Xot\Filament\Widgets\XotBaseWidget
Filament\Widgets\StatsOverviewWidget → Modules\Xot\Filament\Widgets\XotBaseStatsOverviewWidget

// Resources
Filament\Resources\Resource → Modules\Xot\Filament\Resources\XotBaseResource
```

### 🔧 XotBase Widget Implementation Requirements

**All XotBase widgets MUST implement the abstract `getFormSchema()` method:**

```php
abstract class XotBaseWidget extends Widget
{
    /**
     * MANDATORY: Define form schema for widget
     * @return array<int|string, Component>
     */
    abstract public function getFormSchema(): array;
}
```

#### ✅ Correct Widget Implementation
```php
<?php

namespace Modules\Employee\Filament\Widgets;

use Filament\Forms\Components\Section;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class MyWidget extends XotBaseWidget
{
    protected static string $view = 'employee::widgets.my-widget';
    
    /**
     * MANDATORY method - must be implemented
     * @return array<int|string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            Section::make('Widget Content')
                ->schema([
                    // Form components here
                ]),
        ];
    }
}
```

#### ❌ Wrong Widget Implementation (Missing getFormSchema)
```php
class MyWidget extends XotBaseWidget  // ❌ Fatal error
{
    protected static string $view = 'employee::widgets.my-widget';
    
    // ❌ MISSING getFormSchema() causes:
    // "Class contains 1 abstract method and must therefore be declared abstract"
}
```

#### ✅ Display-Only Widget (Correct Empty Implementation)
```php
class DisplayOnlyWidget extends XotBaseWidget
{
    protected static string $view = 'employee::widgets.display-only';
    
    /**
     * MANDATORY: Even for display-only widgets
     * @return array<int|string, Component>
     */
    public function getFormSchema(): array
    {
        return []; // ✅ Empty array is valid
    }
    
    public function getViewData(): array
    {
        return ['stats' => $this->calculateStats()];
    }
}
```

### ⚠️ PHPStan Type Safety for Widgets

**Translation Method Return Types:**
```php
// ❌ Wrong - PHPStan cast errors
public static function getNavigationLabel(): string
{
    return (string) __('employee::navigation.label'); // ❌ Cast error
}

// ✅ Correct - Type-safe translation handling
public static function getNavigationLabel(): string
{
    $label = __('employee::navigation.label');
    return is_string($label) ? $label : 'Default Label';
}

public static function getNavigationGroup(): ?string
{
    $group = __('employee::navigation.group');
    return is_string($group) ? $group : null;
}
```

### 🚫 FORBIDDEN PROPERTIES FOR XOTBASE CLASSES

#### XotBasePage Extensions MUST NOT Have:
```php
class WorkHoursPage extends XotBasePage
{
    // ❌ FORBIDDEN - These properties are handled by XotBase
    // protected static ?string $navigationIcon = 'heroicon-o-clock';
    // protected static ?string $title = 'Timbrature';
    // protected static ?string $navigationLabel = 'Timbrature';
    
    // ✅ ALLOWED - Only widget definitions and custom logic
    protected function getHeaderWidgets(): array { return [...]; }
    protected function getFooterWidgets(): array { return [...]; }
}
```

#### XotBaseResource Extensions MUST NOT Have:
```php
class EmployeeResource extends XotBaseResource
{
    // ❌ FORBIDDEN - getTableColumns method
    // public static function getTableColumns(): array { return [...]; }
    
    // ✅ ALLOWED - Other resource methods
    public static function form(Form $form): Form { return $form->schema([...]); }
    public static function table(Table $table): Table { return $table->columns([...]); }
}
```

### 📁 File Structure Examples

#### Correct Page Structure
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Pages;

use Modules\Employee\Filament\Widgets\TimeClockWidget;
use Modules\Employee\Filament\Widgets\WorkHoursBoardWidget;
use Modules\Xot\Filament\Pages\XotBasePage;

class WorkHoursPage extends XotBasePage
{
    protected static string $view = 'employee::filament.pages.work-hours';

    protected function getHeaderWidgets(): array
    {
        return [TimeClockWidget::class];
    }

    protected function getFooterWidgets(): array
    {
        return [WorkHoursBoardWidget::class];
    }
}
```

#### Correct Resource Structure
```php
<?php

namespace Modules\Employee\Filament\Resources;

use Modules\Xot\Filament\Resources\XotBaseResource;

class EmployeeResource extends XotBaseResource
{
    protected static ?string $model = Employee::class;

    public static function form(Form $form): Form
    {
        return $form->schema([...]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([...]);
    }
    
    // ❌ NO getTableColumns() method!
}
```

### 🔍 Why XotBase Extensions?

1. **Backward Compatibility**: XotBase classes handle old route patterns
2. **Consistent Behavior**: Standard navigation, titles, icons handled centrally
3. **Module Integration**: Proper integration with Laraxot module system
4. **Future-Proofing**: Updates to base behavior don't break modules

### 🚨 Common Violations to Avoid

#### 1. Direct Filament Extension
```php
// ❌ WRONG
use Filament\Resources\Pages\ListRecords;
class ListEmployees extends ListRecords {}

// ✅ CORRECT
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
class ListEmployees extends XotBaseListRecords {}
```

#### 2. Adding Forbidden Properties
```php
// ❌ WRONG
class MyPage extends XotBasePage {
    protected static ?string $navigationIcon = 'heroicon-o-users'; // ❌ FORBIDDEN
    protected static ?string $title = 'My Page'; // ❌ FORBIDDEN
}

// ✅ CORRECT
class MyPage extends XotBasePage {
    protected static string $view = 'module::pages.my-page';
    // Navigation handled by XotBasePage
}
```

#### 3. Adding getTableColumns in Resources
```php
// ❌ WRONG
class MyResource extends XotBaseResource {
    public static function getTableColumns(): array { // ❌ FORBIDDEN METHOD
        return [...];
    }
}

// ✅ CORRECT  
class MyResource extends XotBaseResource {
    public static function table(Table $table): Table {
        return $table->columns([...]); // ✅ CORRECT METHOD
    }
}
```

---

**REMEMBER: ABSOLUTELY NO SERVICES. ALWAYS USE SPATIE QUEUEABLE ACTIONS FOR ALL BUSINESS LOGIC.**

**REMEMBER: NEVER EXTEND FILAMENT DIRECTLY. ALWAYS USE XOTBASE ABSTRACT CLASSES.**

These guidelines MUST be followed for ALL new development and ALL refactoring of existing code. Violating these rules will result in inconsistent architecture and maintenance problems.