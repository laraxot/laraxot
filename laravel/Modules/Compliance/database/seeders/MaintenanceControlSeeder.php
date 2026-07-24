<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Compliance\Database\Factories\MaintenanceControlFactory;
use Modules\Compliance\Models\MaintenanceControl;

class MaintenanceControlSeeder extends Seeder
{
    public function run(): void
    {
        if (MaintenanceControl::query()->exists()) {
            return;
        }

        MaintenanceControlFactory::new()->count(3)->create();
    }
}
