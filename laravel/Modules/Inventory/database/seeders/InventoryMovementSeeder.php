<?php

declare(strict_types=1);

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Inventory\Database\Factories\InventoryMovementFactory;
use Modules\Inventory\Models\InventoryMovement;

class InventoryMovementSeeder extends Seeder
{
    public function run(): void
    {
        if (InventoryMovement::query()->exists()) {
            return;
        }

        InventoryMovementFactory::new()->count(5)->create();
    }
}
