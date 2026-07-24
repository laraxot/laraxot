<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\EnergyBroker\Models\EnergyBrokerSupplyHistory;
use Modules\EnergyBroker\Models\EnergyBrokerSupplyPoint;

/**
 * @extends Factory<EnergyBrokerSupplyHistory>
 */
class EnergyBrokerSupplyHistoryFactory extends Factory
{
    /** @var class-string<EnergyBrokerSupplyHistory> */
    protected $model = EnergyBrokerSupplyHistory::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startedAt = $this->faker->dateTimeBetween('-3 years', '-1 year');

        return [
            'energy_broker_supply_point_id' => EnergyBrokerSupplyPoint::factory(),
            'energy_broker_document_id' => null,
            'supplier_name' => $this->faker->randomElement(['Enel Energia', 'Eni Plenitude', 'A2A Energia', 'Edison', 'Iren']),
            'offer_name' => $this->faker->optional()->words(2, true),
            'offer_code' => $this->faker->optional()->bothify('OFF-####'),
            'status' => $this->faker->randomElement(['current', 'past', 'pending']),
            'started_at' => $startedAt,
            'ended_at' => $this->faker->optional()->dateTimeBetween($startedAt, 'now'),
            'meta' => null,
        ];
    }
}
