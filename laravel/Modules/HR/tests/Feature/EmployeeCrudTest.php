<?php

declare(strict_types=1);

uses(\Modules\HR\Tests\TestCase::class);

use Modules\HR\Models\Employee;

it('creates and soft-deletes an employee', function (): void {
    /** @var Employee $employee */
    $employee = Employee::query()->create([
        'first_name' => 'Mario',
        'last_name' => 'Rossi',
        'email' => 'mario.rossi@test.local',
        'phone' => '+39 010 000 0000',
        'notes' => null,
        'is_active' => 1,
    ]);

    expect($employee->id)->not->toBeNull();

    $employee->delete();
    expect($employee->refresh()->trashed())->toBeTrue();
});

