<?php

declare(strict_types=1);

namespace Modules\Employee\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Modules\Employee\Models\WorkHour;
use Modules\User\Models\User;

class WorkHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, User> $users */
        $users = User::all();
        if ($users->isEmpty()) {
            /** @var Factory<User> $factory */
            $factory = User::factory(5);
            if (is_object($factory) && method_exists($factory, 'create')) {
                /** @var \Illuminate\Database\Eloquent\Collection<int, User> $users */
                $users = $factory->create();
            }
        }

        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        foreach ($users as $user) {
            if (! ($user instanceof User)) {
                continue;
            }

            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                if ($currentDate->isWeekend()) {
                    $currentDate->addDay();

                    continue;
                }

                // 80% chance of having work entries for each day
                if (rand(1, 100) <= 80) {
                    $userId = $user->id;
                    if (is_int($userId) || is_string($userId) && ctype_digit($userId)) {
                        $this->createWorkDayEntries((int) $userId, $currentDate->copy());
                    }
                }

                $currentDate->addDay();
            }
        }

        $this->createIncompleteWorkDays($users);
    }

    private function createWorkDayEntries(int $employeeId, Carbon $date): void
    {
        $clockInTime = $date->copy()->setTime(rand(7, 9), rand(0, 59), 0);
        WorkHour::query()->create([
            'employee_id' => $employeeId,
            'timestamp' => $clockInTime,
            'type' => 'clock_in',
            'notes' => rand(1, 100) <= 10 ? 'Morning shift' : null,
            'status' => 'approved',
        ]);

        // Break start (3-5 hours later)
        $breakStartTime = $clockInTime->copy()->addHours(rand(3, 5))->addMinutes(rand(0, 30));
        WorkHour::query()->create([
            'employee_id' => $employeeId,
            'timestamp' => $breakStartTime,
            'type' => 'break_start',
            'notes' => rand(1, 100) <= 10 ? 'Lunch break' : null,
            'status' => 'approved',
        ]);

        // Break end (30-60 minutes later)
        $breakEndTime = $breakStartTime->copy()->addMinutes(rand(30, 60));
        WorkHour::query()->create([
            'employee_id' => $employeeId,
            'timestamp' => $breakEndTime,
            'type' => 'break_end',
            'notes' => rand(1, 100) <= 10 ? 'Back from lunch' : null,
            'status' => 'approved',
        ]);

        // Clock out (3-5 hours after break end)
        $clockOutTime = $breakEndTime->copy()->addHours(rand(3, 5))->addMinutes(rand(0, 30));
        WorkHour::query()->create([
            'employee_id' => $employeeId,
            'timestamp' => $clockOutTime,
            'type' => 'clock_out',
            'notes' => rand(1, 100) <= 20 ? 'End of work day' : null,
            'status' => 'approved',
        ]);
    }

    /**
     * Create some incomplete work days for testing.
     */
    /**
     * @param  \Illuminate\Database\Eloquent\Collection<int, User>  $users
     */
    private function createIncompleteWorkDays(Collection $users): void
    {
        foreach ($users as $user) {
            if (! ($user instanceof User)) {
                continue;
            }
            $today = Carbon::today();

            $userId = $user->id;
            if (! is_int($userId) && ! is_string($userId)) {
                continue;
            }

            // User who clocked in but didn't clock out
            WorkHour::query()->create([
                'employee_id' => (int) $userId,
                'timestamp' => $today->copy()->setTime(8, 30, 0),
                'type' => 'clock_in',
                'notes' => 'Current work session',
                'status' => 'pending',
            ]);

            // User on break yesterday
            $yesterday = Carbon::yesterday();
            WorkHour::query()->create([
                'employee_id' => (int) $userId,
                'timestamp' => $yesterday->copy()->setTime(8, 0, 0),
                'type' => 'clock_in',
                'status' => 'approved',
            ]);

            WorkHour::query()->create([
                'employee_id' => (int) $userId,
                'timestamp' => $yesterday->copy()->setTime(12, 0, 0),
                'type' => 'break_start',
                'notes' => 'Extended lunch break',
                'status' => 'pending',
            ]);
        }
    }
}
