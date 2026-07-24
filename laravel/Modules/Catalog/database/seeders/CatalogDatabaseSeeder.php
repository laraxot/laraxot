<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Seeders;

use Illuminate\Database\Seeder;

class CatalogDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CatalogFrequencySeeder::class,
            CatalogMeasureUnitSeeder::class,
            CatalogPartTypeSeeder::class,
            CatalogModelSeeder::class,
            ProductSeeder::class,
            MetelDemoSeeder::class,
        ]);
    }
}
