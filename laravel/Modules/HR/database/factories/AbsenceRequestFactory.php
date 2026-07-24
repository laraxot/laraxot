<?php

declare(strict_types=1);

namespace Modules\HR\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\HR\Models\AbsenceRequest;
use Modules\User\Models\User;

/**
 * @extends Factory<AbsenceRequest>
 */
class AbsenceRequestFactory extends Factory
{
    /** @var class-string<AbsenceRequest> */
    protected $model = AbsenceRequest::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startsAt = $this->faker->dateTimeBetween('now', '+1 month');

        return [
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(['vacation', 'leave', 'sick', 'injury']),
            'starts_at' => $startsAt,
            'ends_at' => (clone $startsAt)->modify('+1 day'),
            'status' => 'pending',
        ];
    }
}
