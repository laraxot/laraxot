<?php

declare(strict_types=1);

namespace Modules\Employee\Tests\Unit\Models;

use Modules\Employee\Models\Employee;
use PHPUnit\Framework\Assert;

test('employee model uses the users table and employee data columns', function (): void {
    $employee = new Employee();
    $fillable = $employee->getFillable();

    Assert::assertSame('users', $employee->getTable());
    foreach ([
        'user_id',
        'employee_code',
        'personal_data',
        'contact_data',
        'work_data',
        'documents',
        'photo_url',
        'status',
        'department_id',
        'manager_id',
        'position_id',
        'salary_data',
    ] as $column) {
        Assert::assertContains($column, $fillable);
    }
});
