<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PublicProcurement\Models\PublicProcurementContact;
use Modules\PublicProcurement\Models\PublicProcurementContract;

/**
 * @extends Factory<PublicProcurementContact>
 */
class PublicProcurementContactFactory extends Factory
{
    /** @var class-string<PublicProcurementContact> */
    protected $model = PublicProcurementContact::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'public_procurement_contract_id' => PublicProcurementContract::factory(),
            'role' => $this->faker->randomElement(['rup', 'works_director', 'safety_coordinator', 'referent']),
            'name' => $this->faker->name(),
            'email' => $this->faker->optional()->companyEmail(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
