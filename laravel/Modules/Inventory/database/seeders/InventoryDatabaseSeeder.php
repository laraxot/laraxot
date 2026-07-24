<?php

declare(strict_types=1);

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;

class InventoryDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            WarehouseSeeder::class,
            WarehouseLocationSeeder::class,
            ProductUnitSeeder::class,
            InventoryMovementSeeder::class,
        ]);
    }
}
