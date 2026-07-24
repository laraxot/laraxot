<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Compliance\Database\Factories\EquipmentFactory;
use Modules\Compliance\Models\Equipment;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        if (Equipment::query()->exists()) {
            return;
        }

        EquipmentFactory::new()->count(3)->create();
    }
}
