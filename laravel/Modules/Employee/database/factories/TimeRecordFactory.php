<?php

declare(strict_types=1);

namespace Modules\Employee\Database\Factories;

use Carbon\Carbon;
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
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $timestamp = Carbon::instance($this->faker->dateTimeBetween('-30 days', 'now'));

        return [
            'user_id' => $this->faker->numberBetween(1, 1000),
            'timestamp' => $timestamp,
            'type' => $this->faker->randomElement(['entry', 'exit']),
            'method' => $this->faker->randomElement(['manual', 'badge', 'app', 'kiosk']),
            'latitude' => $this->faker->optional(0.3)->latitude(),
            'longitude' => $this->faker->optional(0.3)->longitude(),
            'address' => $this->faker->optional(0.3)->address(),
            'notes' => $this->faker->optional(0.2)->sentence(),
            'status' => $this->faker->randomElement(['valid', 'invalid', 'pending']),
            'is_manual' => $this->faker->boolean(20),
            'created_by' => $this->faker->optional(0.5)->numberBetween(1, 10),
            'updated_by' => $this->faker->optional(0.3)->numberBetween(1, 10),
        ];
    }
}
