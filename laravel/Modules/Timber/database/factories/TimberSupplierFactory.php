<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Timber\Models\TimberSupplier;

/**
 * @extends Factory<TimberSupplier>
 */
final class TimberSupplierFactory extends Factory
{
    protected $model = TimberSupplier::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'legal_name' => $this->faker->company(),
            'vat_number' => 'IT'.$this->faker->unique()->numerify('###########'),
            'tax_code' => $this->faker->numerify('##################'),
            'email' => $this->faker->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address_line1' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'province' => $this->faker->randomElement(['MI', 'RM', 'TO', 'NA', 'FI', 'BO', 'VE', 'GE']),
            'country_code' => 'IT',
            'certifications' => 'FSC, PEFC',
            'notes' => null,
            'is_active' => true,
        ];
    }
}
