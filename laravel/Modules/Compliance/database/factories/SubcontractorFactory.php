<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Compliance\Models\Subcontractor;

/**
 * @extends Factory<Subcontractor>
 */
class SubcontractorFactory extends Factory
{
    /** @var class-string<Subcontractor> */
    protected $model = Subcontractor::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'business_name' => $this->faker->company(),
            'vat_number' => 'IT'.$this->faker->unique()->numerify('###########'),
            'tax_code' => strtoupper($this->faker->bothify('??????##?##?###?')),
            'email' => $this->faker->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'contact_name' => $this->faker->name(),
            'status' => $this->faker->randomElement(['active', 'suspended', 'archived']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
