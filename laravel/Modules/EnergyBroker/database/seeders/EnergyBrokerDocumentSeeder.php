<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\EnergyBroker\Database\Factories\EnergyBrokerDocumentFactory;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;

class EnergyBrokerDocumentSeeder extends Seeder
{
    public function run(): void
    {
        if (EnergyBrokerDocument::query()->exists()) {
            return;
        }

        EnergyBrokerDocumentFactory::new()->count(2)->create();
    }
}
