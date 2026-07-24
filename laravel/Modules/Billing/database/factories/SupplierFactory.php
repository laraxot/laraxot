<?php

declare(strict_types=1);

namespace Modules\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Billing\Models\Supplier;

/**
 * @extends Factory<Supplier>
 */
class SupplierFactory extends Factory
{
    /** @var class-string<Supplier> */
    protected $model = Supplier::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'legal_name' => $this->faker->company().' S.r.l.',
            'vat_number' => 'IT'.$this->faker->unique()->numerify('###########'),
            'email' => $this->faker->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'is_active' => true,
        ];
    }
}
