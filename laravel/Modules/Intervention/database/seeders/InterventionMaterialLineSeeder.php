<?php

declare(strict_types=1);

namespace Modules\Intervention\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Intervention\Database\Factories\InterventionMaterialLineFactory;
use Modules\Intervention\Models\InterventionMaterialLine;

class InterventionMaterialLineSeeder extends Seeder
{
    public function run(): void
    {
        if (InterventionMaterialLine::query()->exists()) {
            return;
        }

        InterventionMaterialLineFactory::new()->count(2)->create();
    }
}
