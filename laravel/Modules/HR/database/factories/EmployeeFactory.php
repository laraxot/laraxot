<?php

declare(strict_types=1);

namespace Modules\HR\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\HR\Models\Employee;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /** @var class-string<Employee> */
    protected $model = Employee::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'hired_at' => $this->faker->date(),
            'is_active' => true,
        ];
    }
}
