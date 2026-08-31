<?php

declare(strict_types=1);

namespace Modules\Employee\Actions;

use Modules\Employee\Models\Employee;
use Modules\Employee\Models\User;
use Spatie\QueueableAction\QueueableAction;

/**
 * Get current employee data for authenticated user.
 *
 * Returns comprehensive employee information for the current session.
 */
class GetCurrentEmployeeDataAction
{
    use QueueableAction;

    /**
     * Execute employee data retrieval.
     *
     * @return array{id: int|string, name: string, email: string, status: string, employeeNumber?: string, hireDate?: string, department?: array{id: int, name: string}, position?: array{id: int, name: string}}
     */
    public function execute(int|string $userId): array
    {
        /** @var User|null $user */
        $user = User::find($userId);

        if (! $user) {
            return [
                'id' => $userId,
                'name' => 'Unknown User',
                'email' => '',
                'status' => 'unknown',
            ];
        }

        // Base user data
        $data = [
            'id' => $user->id,
            'name' => $user->name ?? 'Unknown',
            'email' => $user->email,
            'status' => 'active',
        ];

        // Try to get employee-specific data
        /** @var Employee|null $employee */
        $employee = Employee::find($userId);

        if ($employee) {
            // Employee number from work_data array
            if (isset($employee->work_data['employee_number']) && is_string($employee->work_data['employee_number'])) {
                $data['employeeNumber'] = $employee->work_data['employee_number'];
            }

            // Status - check if it's an enum or string
            if (isset($employee->status)) {
                if (is_object($employee->status) && method_exists($employee->status, 'value')) {
                    $statusValue = $employee->status->value;
                    $data['status'] = is_string($statusValue) ? $statusValue : 'active';
                } elseif (is_string($employee->status)) {
                    $data['status'] = $employee->status;
                } else {
                    $data['status'] = 'active';
                }
            } else {
                $data['status'] = 'active';
            }

            // Hire date from work_data array
            if (isset($employee->work_data['hire_date']) && is_string($employee->work_data['hire_date'])) {
                $data['hireDate'] = $employee->work_data['hire_date'];
            }

            // Department info from work_data
            if (isset($employee->work_data['department']) && is_array($employee->work_data['department'])) {
                $dept = $employee->work_data['department'];
                if (isset($dept['id'], $dept['name']) && is_int($dept['id']) && is_string($dept['name'])) {
                    $data['department'] = [
                        'id' => $dept['id'],
                        'name' => $dept['name'],
                    ];
                }
            }

            // Position info from work_data
            if (isset($employee->work_data['position']) && is_array($employee->work_data['position'])) {
                $pos = $employee->work_data['position'];
                if (isset($pos['id'], $pos['name']) && is_int($pos['id']) && is_string($pos['name'])) {
                    $data['position'] = [
                        'id' => $pos['id'],
                        'name' => $pos['name'],
                    ];
                }
            }
        }

        return $data;
    }
}
