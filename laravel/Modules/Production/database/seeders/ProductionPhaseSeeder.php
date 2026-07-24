<?php

declare(strict_types=1);

namespace Modules\Production\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Production\Database\Factories\ProductionPhaseFactory;
use Modules\Production\Models\ProductionPhase;

class ProductionPhaseSeeder extends Seeder
{
    public function run(): void
    {
        if (ProductionPhase::query()->exists()) {
            return;
        }

        ProductionPhaseFactory::new()->count(2)->create();
    }
}
