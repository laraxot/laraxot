<?php

declare(strict_types=1);

namespace Modules\Employee\Actions\TimeTracking;

use Carbon\Carbon;
use Modules\Employee\Models\Employee;
use Modules\Employee\Models\TimeEntry;
use Spatie\QueueableAction\QueueableAction;

/**
 * Clock in action for employees.
 */
class ClockInAction
{
    use QueueableAction;

    /**
     * Execute clock in for an employee.
     */
    public function execute(Employee $employee): void
    {
        // Check if already clocked in today without a clock out
        $lastEntry = TimeEntry::where('employee_id', $employee->id)
            ->whereDate('clock_in', Carbon::today())
            ->whereNull('clock_out')
            ->latest()
            ->first();

        if ($lastEntry) {
            // User is already clocked in
            return;
        }

        TimeEntry::create([
            'employee_id' => $employee->id,
            'clock_in' => Carbon::now(),
            'status' => 'pending',
        ]);
    }
}
