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

        /** @var \Modules\Catalog\Database\Factories\CatalogFrequencyFactory $factory */
        $factory = CatalogFrequency::factory();
        $factory->count(5)->create();
    }
}
