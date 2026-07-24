<?php

declare(strict_types=1);

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Inventory\Database\Factories\WarehouseLocationFactory;
use Modules\Inventory\Models\WarehouseLocation;

class WarehouseLocationSeeder extends Seeder
{
    public function run(): void
    {
        if (WarehouseLocation::query()->exists()) {
            return;
        }

        WarehouseLocationFactory::new()->count(5)->create();
    }
}
