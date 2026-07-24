<?php

declare(strict_types=1);

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Inventory\Database\Factories\WarehouseFactory;
use Modules\Inventory\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        if (Warehouse::query()->exists()) {
            return;
        }

        WarehouseFactory::new()->count(3)->create();
    }
}
