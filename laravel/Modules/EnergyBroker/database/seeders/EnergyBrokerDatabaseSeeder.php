<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Database\Seeders;

use Illuminate\Database\Seeder;

class EnergyBrokerDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EnergyBrokerImportBatchSeeder::class,
            EnergyBrokerDocumentSeeder::class,
            EnergyBrokerCustomerMatchSeeder::class,
            EnergyBrokerSupplyPointSeeder::class,
            EnergyBrokerSupplyHistorySeeder::class,
            EnergyBrokerLargeUploadSeeder::class,
        ]);
    }
}
