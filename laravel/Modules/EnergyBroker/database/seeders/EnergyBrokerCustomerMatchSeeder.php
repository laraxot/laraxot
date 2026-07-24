<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\EnergyBroker\Database\Factories\EnergyBrokerCustomerMatchFactory;
use Modules\EnergyBroker\Models\EnergyBrokerCustomerMatch;

class EnergyBrokerCustomerMatchSeeder extends Seeder
{
    public function run(): void
    {
        if (EnergyBrokerCustomerMatch::query()->exists()) {
            return;
        }

        EnergyBrokerCustomerMatchFactory::new()->count(2)->create();
    }
}
