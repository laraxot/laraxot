<?php

declare(strict_types=1);

namespace Modules\Employee\Actions\TimeTracking;

use Carbon\Carbon;
use Modules\Employee\Models\Employee;
use Modules\Employee\Models\TimeEntry;
use Spatie\QueueableAction\QueueableAction;

/**
 * Clock out action for employees.
 */
class ClockOutAction
{
    use QueueableAction;

    /**
     * Execute clock out for an employee.
     */
    public function execute(Employee $employee): void
    {
        // Find the most recent clock-in entry for today without clock-out
        $lastEntry = TimeEntry::where('employee_id', $employee->id)
            ->whereDate('clock_in', Carbon::today())
            ->whereNull('clock_out')
            ->latest()
            ->first();

        if (! $lastEntry) {
            // User hasn't clocked in or already clocked out
            return;
        }

        // Update the entry with clock-out time
        $lastEntry->update([
            'clock_out' => Carbon::now(),
        ]);

        // Calculate total hours
        $totalHours = $lastEntry->calculateTotalHours();
        $lastEntry->update([
            'total_hours' => $totalHours,
        ]);
    }
}
