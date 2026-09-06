<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Catalog\Models\CatalogModel;

class CatalogModelSeeder extends Seeder
{
    public function run(): void
    {
        if (CatalogModel::query()->exists()) {
            return;
        }

        /** @var \Modules\Catalog\Database\Factories\CatalogModelFactory $factory */
        $factory = CatalogModel::factory();
        $factory->count(5)->create();
    }
}
