<?php

declare(strict_types=1);

namespace Modules\HR\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\HR\Models\EmployeeMedicalVisit;

/**
 * @extends Factory<EmployeeMedicalVisit>
 */
class EmployeeMedicalVisitFactory extends Factory
{
    /** @var class-string<EmployeeMedicalVisit> */
    protected $model = EmployeeMedicalVisit::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => EmployeeFactory::new(),
            'visited_at' => $this->faker->date(),
            'expires_at' => $this->faker->optional()->date(),
        ];
    }
}
