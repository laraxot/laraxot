<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\PublicProcurement\Models\PublicProcurementMeasurement;

/**
 * @extends Factory<PublicProcurementMeasurement>
 */
class PublicProcurementMeasurementFactory extends Factory
{
    /** @var class-string<PublicProcurementMeasurement> */
    protected $model = PublicProcurementMeasurement::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'public_procurement_contract_id' => PublicProcurementContract::factory(),
            'price_item_id' => null,
            'intervention_id' => null,
            'created_by_user_id' => null,
            'status' => $this->faker->randomElement(['draft', 'approved', 'rejected']),
            'measured_on' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'quantity' => $this->faker->randomFloat(3, 1, 200),
            'amount' => $this->faker->randomFloat(2, 100, 20000),
            'description' => $this->faker->sentence(6),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
