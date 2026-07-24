<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\EnergyBroker\Database\Factories\EnergyBrokerSupplyHistoryFactory;
use Modules\EnergyBroker\Models\EnergyBrokerSupplyHistory;

class EnergyBrokerSupplyHistorySeeder extends Seeder
{
    public function run(): void
    {
        if (EnergyBrokerSupplyHistory::query()->exists()) {
            return;
        }

        EnergyBrokerSupplyHistoryFactory::new()->count(2)->create();
    }
}
