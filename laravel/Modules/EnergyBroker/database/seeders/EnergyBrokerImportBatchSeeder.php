<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\EnergyBroker\Database\Factories\EnergyBrokerImportBatchFactory;
use Modules\EnergyBroker\Models\EnergyBrokerImportBatch;

class EnergyBrokerImportBatchSeeder extends Seeder
{
    public function run(): void
    {
        if (EnergyBrokerImportBatch::query()->exists()) {
            return;
        }

        EnergyBrokerImportBatchFactory::new()->count(2)->create();
    }
}
