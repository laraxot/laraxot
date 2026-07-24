<?php

declare(strict_types=1);

namespace Modules\Employee\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Employee\Models\AbsenceRequest;

/**
 * @extends Factory<AbsenceRequest>
 */
class AbsenceRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<AbsenceRequest>
     */
    protected $model = AbsenceRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startsAt = $this->faker->dateTimeBetween('now', '+1 month');

        return [
            'user_id' => $this->faker->numberBetween(1, 1000),
            'type' => $this->faker->randomElement([
                AbsenceRequest::TYPE_VACATION,
                AbsenceRequest::TYPE_LEAVE,
                AbsenceRequest::TYPE_SICK,
                AbsenceRequest::TYPE_INJURY,
            ]),
            'starts_at' => $startsAt,
            'ends_at' => (clone $startsAt)->modify('+1 day'),
            'notes' => $this->faker->optional()->sentence(),
            'status' => AbsenceRequest::STATUS_PENDING,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $_attributes) => [
            'status' => AbsenceRequest::STATUS_APPROVED,
            'decided_by_user_id' => $this->faker->numberBetween(1, 1000),
            'decided_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $_attributes) => [
            'status' => AbsenceRequest::STATUS_REJECTED,
            'decided_by_user_id' => $this->faker->numberBetween(1, 1000),
            'decided_at' => now(),
        ]);
    }
}
