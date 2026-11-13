<?php

declare(strict_types=1);

namespace Modules\Activity\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
<<<<<<< HEAD
use Illuminate\Support\Str;
=======
>>>>>>> 13cf1831 (.)
use Modules\Activity\Models\Activity;

/**
 * @extends Factory<Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * @var class-string<Activity>
     */
    protected $model = Activity::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'log_name' => $this->faker->randomElement(['default', 'auth', 'system']),
            'description' => $this->faker->sentence(),
            'subject_type' => $this->faker->randomElement(['Modules\User\Models\User', 'App\Models\Appointment']),
<<<<<<< HEAD
            'subject_id' => Str::uuid()->toString(),
            'causer_type' => 'Modules\User\Models\User',
            'causer_id' => Str::uuid()->toString(),
=======
            // User model uses UUID; subject_id/causer_id are string(36) in activity_log
            'subject_id' => $this->faker->uuid(),
            'causer_type' => 'Modules\User\Models\User',
            'causer_id' => $this->faker->uuid(),
>>>>>>> 13cf1831 (.)
            'properties' => ['key' => 'value'],
            'batch_uuid' => $this->faker->uuid(),
            'event' => $this->faker->randomElement(['created', 'updated', 'deleted']),
            'created_at' => $this->faker->dateTimeBetween('-1 year'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year'),
        ];
    }
}
