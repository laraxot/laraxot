<?php

declare(strict_types=1);

namespace Modules\Vehicle\Database\Seeders;

use Illuminate\Database\Seeder;

class VehicleDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            VehicleSeeder::class,
            VehicleDocumentSeeder::class,
        ]);
    }
}
