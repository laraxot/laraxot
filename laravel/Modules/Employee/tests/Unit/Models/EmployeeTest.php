<?php

declare(strict_types=1);

namespace Modules\Employee\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Modules\Employee\Models\Department;
use Modules\Employee\Models\Employee;
use Modules\Employee\Models\Position;

beforeEach(function () {
    DB::beginTransaction();
});

afterEach(function () {
    DB::rollBack();
});

beforeEach(function () {
    $this->employee = Employee::factory()->create([
        'personal_data' => [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
        ],
        'contact_data' => [
            'email' => 'mario.rossi@example.com',
            'phone' => '+39 123 456 7890',
        ],
        'work_data' => [
            'employee_id' => 'EMP001',
            'hire_date' => '2024-01-01',
        ],
        'status' => 'attivo',
    ]);
});

test('employee can be created', function () {
    expect($this->employee)->toBeInstanceOf(Employee::class);
});

test('employee has fillable attributes', function () {
    $fillable = $this->employee->getFillable();

    expect($fillable)->toContain('user_id');
    expect($fillable)->toContain('employee_code');
    expect($fillable)->toContain('personal_data');
    expect($fillable)->toContain('contact_data');
    expect($fillable)->toContain('work_data');
    expect($fillable)->toContain('status');
});

test('employee has casts defined', function () {
    $casts = $this->employee->getCasts();

    expect($casts)->toHaveKey('personal_data');
    expect($casts)->toHaveKey('contact_data');
    expect($casts)->toHaveKey('work_data');
    expect($casts)->toHaveKey('documents');
    expect($casts)->toHaveKey('salary_data');
    expect($casts)->toHaveKey('created_at');
    expect($casts)->toHaveKey('updated_at');
});

test('employee has proper table name', function () {
    expect($this->employee->getTable())->toBe('employees');
});

test('employee belongs to user', function () {
    expect($this->employee->user())->toBeInstanceOf(BelongsTo::class);
});

test('employee belongs to department', function () {
    $department = Department::factory()->create();
    $this->employee->update(['department_id' => $department->id]);

    expect($this->employee->department)->toBeInstanceOf(Department::class);
    expect($this->employee->department->id)->toBe($department->id);
});

test('employee belongs to position', function () {
    $position = Position::factory()->create();
    $this->employee->update(['position_id' => $position->id]);

    expect($this->employee->position)->toBeInstanceOf(Position::class);
    expect($this->employee->position->id)->toBe($position->id);
});

test('employee can have manager', function () {
    $manager = Employee::factory()->create();
    $this->employee->update(['manager_id' => $manager->id]);

    expect($this->employee->manager)->toBeInstanceOf(Employee::class);
    expect($this->employee->manager->id)->toBe($manager->id);
});

test('employee can have subordinates', function () {
    $subordinate = Employee::factory()->create(['manager_id' => $this->employee->id]);

    expect($this->employee->subordinates)->toBeInstanceOf(Collection::class);
    expect($this->employee->subordinates)->toHaveCount(1);
    expect($this->employee->subordinates->first()->id)->toBe($subordinate->id);
});

test('employee can get full name', function () {
    expect($this->employee->full_name)->toBe('Mario Rossi');
});

test('employee can get email', function () {
    expect($this->employee->email)->toBe('mario.rossi@example.com');
});

test('employee can get phone', function () {
    expect($this->employee->phone)->toBe('+39 123 456 7890');
});

test('employee can check if active', function () {
    expect($this->employee->isActive())->toBeTrue();

    $this->employee->update(['status' => 'inattivo']);
    expect($this->employee->isActive())->toBeFalse();
});

test('employee can check if has manager', function () {
    expect($this->employee->hasManager())->toBeFalse();

    $manager = Employee::factory()->create();
    $this->employee->update(['manager_id' => $manager->id]);

    $manager = Employee::factory()->create();
    $this->employee->update(['manager_id' => $manager->id]);

    $manager = Employee::factory()->create();
    $this->employee->update(['manager_id' => $manager->id]);

    expect($this->employee->hasManager())->toBeTrue();
});

test('employee can check if has subordinates', function () {
    expect($this->employee->hasSubordinates())->toBeFalse();

    Employee::factory()->create(['manager_id' => $this->employee->id]);

    Employee::factory()->create(['manager_id' => $this->employee->id]);

    Employee::factory()->create(['manager_id' => $this->employee->id]);

    expect($this->employee->hasSubordinates())->toBeTrue();
});

test('employee can be filtered by status', function () {
    $activeEmployee = Employee::factory()->create(['status' => 'attivo']);
    $inactiveEmployee = Employee::factory()->create(['status' => 'inattivo']);

    $activeEmployees = Employee::active()->get();
    $inactiveEmployees = Employee::inactive()->get();

    $activeEmployees = Employee::active()->get();
    $inactiveEmployees = Employee::inactive()->get();

    expect($activeEmployees)->toHaveCount(2); // Including the one from beforeEach
    expect($inactiveEmployees)->toHaveCount(1);

    $activeEmployees = Employee::active()->get();
    $inactiveEmployees = Employee::inactive()->get();

    expect($activeEmployees)->toHaveCount(2); // Including the one from beforeEach
    expect($inactiveEmployees)->toHaveCount(1);

    expect($activeEmployees->pluck('id'))->toContain($this->employee->id);
    expect($activeEmployees->pluck('id'))->toContain($activeEmployee->id);
    expect($inactiveEmployees->pluck('id'))->toContain($inactiveEmployee->id);
});

test('employee has work hours relationship', function () {
    expect($this->employee->workHours())->toBeInstanceOf(HasMany::class);
});

test('employee has leaves relationship', function () {
    expect($this->employee->leaves())->toBeInstanceOf(HasMany::class);
});

test('employee has documents relationship', function () {
    expect($this->employee->documents())->toBeInstanceOf(HasMany::class);
});
