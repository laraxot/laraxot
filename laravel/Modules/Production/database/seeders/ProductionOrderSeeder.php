<?php

declare(strict_types=1);

namespace Modules\Production\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Production\Database\Factories\ProductionOrderFactory;
use Modules\Production\Models\ProductionOrder;

class ProductionOrderSeeder extends Seeder
{
    public function run(): void
    {
        if (ProductionOrder::query()->exists()) {
            return;
        }

        ProductionOrderFactory::new()->count(2)->create();
    }
}
