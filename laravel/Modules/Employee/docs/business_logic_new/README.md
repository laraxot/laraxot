# Business Logic Documentation

## 📚 Overview

This directory contains comprehensive documentation of the business logic governing the Employee module. All business logic is implemented using **Spatie QueueableActions** following Laraxot conventions.

## 🚨 CRITICAL LARAXOT RULE

**NEVER use Services - ALWAYS use Spatie QueueableActions**

Repository: https://github.com/spatie/laravel-queueable-action

## 📋 Documentation Structure

### Core Business Logic
- **[overview.md](overview.md)** - Complete business logic overview with Actions pattern
- **[time_tracking.md](time_tracking.md)** - Time tracking Actions and workflows
- **[employee_management.md](employee_management.md)** - Employee lifecycle Actions
- **[security_authorization.md](security_authorization.md)** - Security Actions and policies
- **[analytics_reporting.md](analytics_reporting.md)** - Analytics and reporting Actions

### Action Categories

#### Time Tracking Actions
```php
namespace Modules\Employee\Actions\TimeTracking;

- ClockInAction
- ClockOutAction  
- StartBreakAction
- EndBreakAction
- CalculateDailyHoursAction
- ValidateTimeSequenceAction
```

#### Employee Management Actions
```php
namespace Modules\Employee\Actions\EmployeeManagement;

- CreateEmployeeAction
- UpdateEmployeeAction
- TransferEmployeeAction
- ChangeEmployeeStatusAction
- InitiateOnboardingAction
- CompleteOnboardingAction
```

#### Analytics Actions
```php
namespace Modules\Employee\Actions\Analytics;

- CalculateAttendanceRateAction
- CalculatePunctualityScoreAction
- GenerateAttendanceReportAction
- GetDepartmentMetricsAction
- ExportTimeDataAction
```

#### Security Actions
```php
namespace Modules\Employee\Actions\Security;

- LogSecurityAccessAction
- ValidatePermissionsAction
- ExportEmployeeDataAction
- AnonymizeEmployeeDataAction
- AuditDataAccessAction
```

## 🎯 Action Implementation Patterns

### Basic Action Structure

```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Actions;

use Spatie\QueueableAction\QueueableAction;

class ExampleAction
{
    use QueueableAction;

    public function execute(InputData $input): OutputData
    {
        // Business logic implementation
        return new OutputData(/* ... */);
    }
}
```

### Usage Patterns

```php
// Synchronous execution
$result = app(ExampleAction::class)->execute($input);

// Asynchronous execution (queued)
app(ExampleAction::class)
    ->onQueue('employees')
    ->execute($input);

// With dependency injection
class EmployeeController extends Controller
{
    public function store(Request $request, CreateEmployeeAction $action): JsonResponse
    {
        $data = EmployeeData::from($request->validated());
        $employee = $action->execute($data);
        
        return response()->json($employee);
    }
}
```

## 🔄 Migration from Services

If any Services exist in the module, they must be converted to Actions:

1. **Identify Services**: Find all existing Service classes
2. **Decompose**: Split each Service method into separate Actions
3. **Convert**: Implement each Action with QueueableAction trait
4. **Update Usage**: Update Controllers, Resources, Components
5. **Test**: Verify functionality with new Actions
6. **Remove**: Delete obsolete Service classes

## 📊 Business Rule Categories

### Validation Actions
- Data integrity and format validation
- Sequence and timing validation  
- Business process validation
- Compliance validation

### Calculation Actions
- Time and attendance calculations
- Compensation and benefits calculations
- Performance metrics calculations
- Statistical analysis

### Workflow Actions
- Approval processes and hierarchies
- Notification and escalation
- State transition logic
- Exception handling

### Integration Actions
- External API interactions
- Data import/export
- System synchronization
- Third-party service integration

## 🛠️ Development Guidelines

### Creating New Actions

1. **Single Responsibility**: One Action = One business operation
2. **Descriptive Naming**: Use verb + entity pattern (CreateEmployeeAction)
3. **Type Safety**: Full type hints with PHPStan Level 10 compliance
4. **Data Objects**: Use Spatie Laravel Data for input/output
5. **Error Handling**: Proper exception handling and logging

### Testing Actions

```php
class CreateEmployeeActionTest extends TestCase
{
    /** @test */
    public function it_creates_employee_successfully(): void
    {
        $data = new EmployeeData(
            name: 'John Doe',
            email: 'john@example.com'
        );
        
        $employee = app(CreateEmployeeAction::class)->execute($data);
        
        $this->assertInstanceOf(Employee::class, $employee);
        $this->assertEquals('John Doe', $employee->name);
    }
    
    /** @test */
    public function it_can_be_queued(): void
    {
        Queue::fake();
        
        app(CreateEmployeeAction::class)
            ->onQueue('employees')
            ->execute($data);
            
        Queue::assertPushed(CreateEmployeeAction::class);
    }
}
```

## 🔗 Related Documentation

- **[Spatie QueueableAction Documentation](https://github.com/spatie/laravel-queueable-action)** - Official package documentation
- **[Laraxot Actions Pattern](../laraxot_actions_pattern.md)** - Laraxot-specific implementation rules
- **[Technical Architecture](../architecture/technical_architecture.md)** - System design
- **[Implementation Guide](../implementation/setup_guide.md)** - Setup and configuration

---

*This documentation represents the authoritative source for business logic implementation using Spatie QueueableActions. All business logic must be implemented as Actions, never as Services.*
