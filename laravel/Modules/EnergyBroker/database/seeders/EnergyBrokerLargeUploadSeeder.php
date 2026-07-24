<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\EnergyBroker\Database\Factories\EnergyBrokerLargeUploadFactory;
use Modules\EnergyBroker\Models\EnergyBrokerLargeUpload;

class EnergyBrokerLargeUploadSeeder extends Seeder
{
    public function run(): void
    {
        if (EnergyBrokerLargeUpload::query()->exists()) {
            return;
        }

        EnergyBrokerLargeUploadFactory::new()->count(2)->create();
    }
}
