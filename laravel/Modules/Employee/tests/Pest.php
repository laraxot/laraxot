<?php

declare(strict_types=1);

use Modules\Employee\Database\Factories\EmployeeFactory;
use Modules\Employee\Database\Factories\WorkHourFactory;
use Modules\Employee\Models\Employee;
use Modules\Employee\Models\WorkHour;

// Ogni file test dichiara uses(\Modules\Employee\Tests\TestCase::class, ...) singolarmente.
// Vietato uses()->in() qui (PHPStan method.internalClass / undefined $this in Pest extension).

/**
 * @param  array<string, mixed>  $attributes
 */
function createEmployee(array $attributes = []): Employee
{
    return EmployeeFactory::new()->createOne($attributes);
}

/**
 * @param  array<string, mixed>  $attributes
 */
function makeEmployee(array $attributes = []): Employee
{
    return EmployeeFactory::new()->makeOne($attributes);
}

/**
 * @param  array<string, mixed>  $attributes
 */
function createWorkHour(array $attributes = []): WorkHour
{
    return WorkHourFactory::new()->createOne($attributes);
}

/**
 * @param  array<string, mixed>  $attributes
 */
function makeWorkHour(array $attributes = []): WorkHour
{
    return WorkHourFactory::new()->makeOne($attributes);
}
