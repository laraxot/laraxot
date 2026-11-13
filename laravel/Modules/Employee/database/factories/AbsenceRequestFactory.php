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
                \Modules\Employee\Enums\AbsenceRequestTypeEnum::VACATION->value,
                \Modules\Employee\Enums\AbsenceRequestTypeEnum::LEAVE->value,
                \Modules\Employee\Enums\AbsenceRequestTypeEnum::SICK->value,
                \Modules\Employee\Enums\AbsenceRequestTypeEnum::INJURY->value,
            ]),
            'starts_at' => $startsAt,
            'ends_at' => (clone $startsAt)->modify('+1 day'),
            'notes' => $this->faker->optional()->sentence(),
            'status' => \Modules\Employee\Enums\AbsenceRequestStatusEnum::PENDING->value,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $_attributes) => [
            'status' => \Modules\Employee\Enums\AbsenceRequestStatusEnum::APPROVED->value,
            'decided_by_user_id' => $this->faker->numberBetween(1, 1000),
            'decided_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $_attributes) => [
            'status' => \Modules\Employee\Enums\AbsenceRequestStatusEnum::REJECTED->value,
            'decided_by_user_id' => $this->faker->numberBetween(1, 1000),
            'decided_at' => now(),
        ]);
    }
}
