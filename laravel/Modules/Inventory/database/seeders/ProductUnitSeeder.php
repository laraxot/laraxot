<?php

declare(strict_types=1);

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Inventory\Database\Factories\ProductUnitFactory;
use Modules\Inventory\Models\ProductUnit;

class ProductUnitSeeder extends Seeder
{
    public function run(): void
    {
        if (ProductUnit::query()->exists()) {
            return;
        }

        ProductUnitFactory::new()->count(5)->create();
    }
}
