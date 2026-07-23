<?php

namespace Modules\Employee\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Employee\Models\TimeRecord;

/** @extends Factory<TimeRecord> */
class TimeRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = TimeRecord::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}
