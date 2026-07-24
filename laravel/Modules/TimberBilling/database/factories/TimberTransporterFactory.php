<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TimberBilling\Models\TimberTransporter;

/**
 * @extends Factory<TimberTransporter>
 */
final class TimberTransporterFactory extends Factory
{
    protected $model = TimberTransporter::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'legal_name' => $this->faker->company().' Trasporti',
            'vat_number' => 'IT'.$this->faker->unique()->numerify('###########'),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'vehicle_plate' => $this->faker->bothify('??###??'),
            'notes' => null,
            'is_active' => true,
        ];
    }
}
