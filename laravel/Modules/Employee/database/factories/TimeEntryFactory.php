<?php

declare(strict_types=1);

namespace Modules\Employee\Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Employee\Models\TimeEntry;
use Modules\Xot\Actions\Cast\SafeIntCastAction;

/** @extends Factory<TimeEntry> */
class TimeEntryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = TimeEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $clockInMinute = app(SafeIntCastAction::class)->execute($this->faker->randomElement([0, 15, 30, 45]));
        $clockIn = Carbon::instance($this->faker->dateTimeBetween('-30 days', 'now'))
            ->setTime($this->faker->numberBetween(7, 9), $clockInMinute, 0);
        $clockOut = $clockIn->copy()->addHours($this->faker->numberBetween(6, 9));
        $breakStart = $clockIn->copy()->addHours($this->faker->numberBetween(3, 5));
        $breakEnd = $breakStart->copy()->addMinutes($this->faker->numberBetween(30, 60));
        $breakDuration = (int) $breakStart->diffInMinutes($breakEnd);
        $totalHours = round((float) $clockIn->diffInMinutes($clockOut) / 60 - $breakDuration / 60, 2);

        return [
            'employee_id' => $this->faker->numberBetween(1, 1000),
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
            'break_start' => $breakStart,
            'break_end' => $breakEnd,
            'break_duration' => $breakDuration,
            'total_hours' => $totalHours,
            'regular_hours' => min($totalHours, 8.0),
            'overtime_hours' => max($totalHours - 8.0, 0.0),
            'location_in' => null,
            'location_out' => null,
            'device_info' => null,
            'notes' => $this->faker->optional(0.3)->sentence(),
            'employee_notes' => $this->faker->optional(0.2)->sentence(),
            'supervisor_notes' => null,
            'status' => $this->faker->randomElement([
                TimeEntry::STATUS_PENDING,
                TimeEntry::STATUS_APPROVED,
                TimeEntry::STATUS_AUTO_APPROVED,
            ]),
            'approved_by' => $this->faker->optional(0.4)->numberBetween(1, 10),
            'approved_at' => $this->faker->optional(0.4)->dateTimeBetween('-7 days', 'now'),
            'rejection_reason' => null,
            'anomalies' => null,
        ];
    }
}
