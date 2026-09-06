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

        /** @var \Modules\Catalog\Database\Factories\CatalogMeasureUnitFactory $factory */
        $factory = CatalogMeasureUnit::factory();
        $factory->count(5)->create();
    }
}
