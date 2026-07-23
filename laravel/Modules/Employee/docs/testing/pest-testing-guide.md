# Pest Testing Guide - Employee Module

## 🎯 Overview

Comprehensive guide for implementing and maintaining Pest tests in the Employee module, following Laraxot conventions and best practices established in the CMS module.

## ✅ Laraxot Testing Standards

### **Header Standard**
```php
<?php

declare(strict_types=1);

use Modules\Employee\Models\Employee;
use Modules\Employee\Models\WorkHour;

uses(\Modules\Employee\Tests\TestCase::class);
```

### **Critical Rules** 🔧
1. ✅ **Only** `declare(strict_types=1);` in header
2. ✅ **NEVER** declare namespace in test files
3. ✅ **ALWAYS** use module-specific TestCase
4. ✅ **Direct imports** of tested classes

## 🎯 Employee Module Patterns

### **Custom Expectations**
```php
expect()->extend('toBeEmployee', function () {
    return $this->toBeInstanceOf(\Modules\Employee\Models\Employee::class);
});

expect()->extend('toBeWorkHour', function () {
    return $this->toBeInstanceOf(\Modules\Employee\Models\WorkHour::class);
});
```

### **Helper Functions**
```php
function createEmployee(array $attributes = []): \Modules\Employee\Models\Employee
{
    return \Modules\Employee\Models\Employee::factory()->create($attributes);
}

function makeEmployee(array $attributes = []): \Modules\Employee\Models\Employee
{
    return \Modules\Employee\Models\Employee::factory()->make($attributes);
}

function createWorkHour(array $attributes = []): \Modules\Employee\Models\WorkHour
{
    return \Modules\Employee\Models\WorkHour::factory()->create($attributes);
}

function makeWorkHour(array $attributes = []): \Modules\Employee\Models\WorkHour
{
    return \Modules\Employee\Models\WorkHour::factory()->make($attributes);
}
```

## 🏗️ Test Organization

### **Business Logic Tests**
```php
describe('Work Hour Management Business Logic', function () {
    beforeEach(function () {
        $this->employee = createEmployee();
        $this->today = Carbon::today();
    });

    test('employee can clock in at start of day', function () {
        $clockIn = createWorkHour([
            'employee_id' => $this->employee->id,
            'type' => WorkHour::TYPE_CLOCK_IN,
            'timestamp' => $this->today->copy()->setTime(9, 0),
        ]);

        expect($clockIn->isClockIn())->toBeTrue();
        expect(WorkHour::getCurrentStatus($this->employee->id))->toBe('clocked_in');
        expect(WorkHour::getNextAction($this->employee->id))->toBe(WorkHour::TYPE_BREAK_START);
    });
});
```

### **Feature Tests Structure**
```php
describe('Time Tracking Features', function () {
    test('calculates correct worked hours with break', function () {
        // Setup complete work day with break
        createWorkHour([
            'employee_id' => $this->employee->id,
            'type' => WorkHour::TYPE_CLOCK_IN,
            'timestamp' => $this->today->copy()->setTime(9, 0),
        ]);

        createWorkHour([
            'employee_id' => $this->employee->id,
            'type' => WorkHour::TYPE_BREAK_START,
            'timestamp' => $this->today->copy()->setTime(12, 0),
        ]);

        createWorkHour([
            'employee_id' => $this->employee->id,
            'type' => WorkHour::TYPE_BREAK_END,
            'timestamp' => $this->today->copy()->setTime(13, 0),
        ]);

        createWorkHour([
            'employee_id' => $this->employee->id,
            'type' => WorkHour::TYPE_CLOCK_OUT,
            'timestamp' => $this->today->copy()->setTime(17, 0),
        ]);

        $workedHours = WorkHour::calculateWorkedHours($this->employee->id, $this->today);

        expect($workedHours)->toBe(7.0);
    });
});
```

## 🛡️ Error Handling Patterns

### **Robust Testing with Fallbacks**
```php
test('validates correct entry sequence', function () {
    expect(WorkHour::isValidNextEntry($this->employee->id, WorkHour::TYPE_CLOCK_IN))->toBeTrue();
    
    createWorkHour([
        'employee_id' => $this->employee->id,
        'type' => WorkHour::TYPE_CLOCK_IN,
        'timestamp' => $this->today->copy()->setTime(9, 0),
    ]);

    expect(WorkHour::isValidNextEntry($this->employee->id, WorkHour::TYPE_BREAK_START))->toBeTrue();
    expect(WorkHour::isValidNextEntry($this->employee->id, WorkHour::TYPE_CLOCK_OUT))->toBeFalse();
});
```

### **Edge Cases Testing**
```php
test('handles empty work hour records', function () {
    expect(WorkHour::calculateWorkedHours($this->employee->id, $this->today))->toBe(0.0);
    expect(WorkHour::getCurrentStatus($this->employee->id))->toBe('not_clocked_in');
    expect(WorkHour::getNextAction($this->employee->id))->toBe(WorkHour::TYPE_CLOCK_IN);
});
```

## 🚀 Performance Best Practices

### **Fast Test Execution**
- Use `beforeEach()` for common setup
- Focus on isolated unit tests
- Avoid heavy database operations when possible
- Use factories for consistent test data

### **Test Data Management**
```php
beforeEach(function () {
    $this->employee = createEmployee();
    $this->today = Carbon::today();
});
```

## 📊 Test Coverage Areas

### **Core Business Logic**
- ✅ Work hour entry sequences (clock in/out, breaks)
- ✅ Time calculations with breaks
- ✅ Status management and validation
- ✅ Multi-employee independence

### **Edge Cases**
- ✅ Empty records handling
- ✅ Invalid entry sequences
- ✅ Date filtering accuracy
- ✅ Cross-day scenarios

### **Integration Points**
- ✅ Factory integration
- ✅ Model relationships
- ✅ Business rule enforcement
- ✅ Data consistency

## 🎉 Current Performance Metrics

Based on existing Employee module tests:
- **17 tests** in WorkHourManagementTest
- **Comprehensive coverage** of business logic
- **Fast execution** with proper setup/teardown
- **100% pass rate** for core functionality

## 🔗 Related Documentation

### **Module Documentation**
- [Employee Module README](../README.md)
- [WorkHour Model Documentation](../work_hour.md)
- [Time Tracking Architecture](../technical_architecture.md)

### **Testing Resources**
- [CMS Testing Best Practices](../../Cms/docs/tests/pestphp-best-practices.md)
- [Laraxot Testing Standards](../../../../docs/testing-standards.md)

### **Implementation Files**
- [WorkHourManagementTest.php](../../tests/Feature/WorkHourManagementTest.php)
- [TimeTrackingBusinessLogicTest.php](../../tests/Feature/TimeTrackingBusinessLogicTest.php)
- [Pest.php](../../tests/Pest.php)

---
**Last Updated**: September 2025  
**Status**: ✅ PRODUCTION READY  
**Coverage**: Business Logic + Edge Cases + Performance Optimized
