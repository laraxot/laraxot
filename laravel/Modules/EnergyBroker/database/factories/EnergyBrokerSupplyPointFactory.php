<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Customer\Models\Customer;
use Modules\EnergyBroker\Models\EnergyBrokerSupplyPoint;

/**
 * @extends Factory<EnergyBrokerSupplyPoint>
 */
class EnergyBrokerSupplyPointFactory extends Factory
{
    /** @var class-string<EnergyBrokerSupplyPoint> */
    protected $model = EnergyBrokerSupplyPoint::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $serviceType = $this->faker->randomElement(['electricity', 'gas']);

        return [
            'customer_id' => Customer::factory(),
            'customer_site_id' => null,
            'service_type' => $serviceType,
            'pod' => 'electricity' === $serviceType ? 'IT001E'.$this->faker->numerify('########') : null,
            'pdr' => 'gas' === $serviceType ? $this->faker->numerify('##############') : null,
            'supply_code' => $this->faker->optional()->bothify('SUP-####'),
            'supplier_name' => $this->faker->randomElement(['Enel Energia', 'Eni Plenitude', 'A2A Energia', 'Edison', 'Iren']),
            'offer_name' => $this->faker->optional()->words(2, true),
            'annual_consumption' => $this->faker->randomFloat(3, 500, 50000),
            'power_kw' => 'electricity' === $serviceType ? $this->faker->randomFloat(2, 3, 100) : null,
        ];
    }
}
