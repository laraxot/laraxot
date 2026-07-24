<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\EnergyBroker\Models\EnergyBrokerCustomerMatch;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;

/**
 * @extends Factory<EnergyBrokerCustomerMatch>
 */
class EnergyBrokerCustomerMatchFactory extends Factory
{
    /** @var class-string<EnergyBrokerCustomerMatch> */
    protected $model = EnergyBrokerCustomerMatch::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'energy_broker_document_id' => EnergyBrokerDocument::factory(),
            'customer_id' => null,
            'score' => $this->faker->randomFloat(2, 0, 100),
            'status' => $this->faker->randomElement(['needs_review', 'matched', 'rejected']),
            'matched_on' => $this->faker->randomElement([
                ['vat_number'],
                ['pod'],
                ['name', 'address'],
                null,
            ]),
            'reason' => $this->faker->optional()->sentence(),
        ];
    }
}
