<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\EnergyBroker\Database\Factories\EnergyBrokerSupplyPointFactory;
use Modules\EnergyBroker\Models\EnergyBrokerSupplyPoint;

class EnergyBrokerSupplyPointSeeder extends Seeder
{
    public function run(): void
    {
        if (EnergyBrokerSupplyPoint::query()->exists()) {
            return;
        }

        EnergyBrokerSupplyPointFactory::new()->count(2)->create();
    }
}
