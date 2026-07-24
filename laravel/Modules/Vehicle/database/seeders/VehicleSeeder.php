<?php

declare(strict_types=1);

namespace Modules\Vehicle\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Vehicle\Database\Factories\VehicleFactory;
use Modules\Vehicle\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        if (Vehicle::query()->exists()) {
            return;
        }

        VehicleFactory::new()->count(2)->create();
    }
}
