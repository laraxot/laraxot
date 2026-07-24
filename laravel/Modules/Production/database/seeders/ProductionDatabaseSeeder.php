<?php

declare(strict_types=1);

namespace Modules\Production\Database\Seeders;

use Illuminate\Database\Seeder;

class ProductionDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProductionOrderSeeder::class,
            ProductionPhaseSeeder::class,
        ]);
    }
}
