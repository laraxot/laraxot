<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Catalog\Models\CatalogMeasureUnit;

class CatalogMeasureUnitSeeder extends Seeder
{
    public function run(): void
    {
        if (CatalogMeasureUnit::query()->exists()) {
            return;
        }

        CatalogMeasureUnit::factory()->count(5)->create();
    }
}
