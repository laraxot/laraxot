<?php

declare(strict_types=1);

namespace Modules\HR\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\HR\Models\EmployeeTraining;

/**
 * @extends Factory<EmployeeTraining>
 */
class EmployeeTrainingFactory extends Factory
{
    /** @var class-string<EmployeeTraining> */
    protected $model = EmployeeTraining::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => EmployeeFactory::new(),
            'name' => $this->faker->words(3, true),
            'completed_at' => $this->faker->optional()->date(),
            'expires_at' => $this->faker->optional()->date(),
        ];
    }
}
