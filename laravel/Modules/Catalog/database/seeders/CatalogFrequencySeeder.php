<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Catalog\Models\CatalogFrequency;

class CatalogFrequencySeeder extends Seeder
{
    public function run(): void
    {
        if (CatalogFrequency::query()->exists()) {
            return;
        }

        $factory = CatalogFrequency::factory();
        if ($factory !== null) {
            $factory->count(5)->create();
        }
    }
}
