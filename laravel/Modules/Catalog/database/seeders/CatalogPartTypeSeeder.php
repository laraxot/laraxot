<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Catalog\Models\CatalogPartType;

class CatalogPartTypeSeeder extends Seeder
{
    public function run(): void
    {
        if (CatalogPartType::query()->exists()) {
            return;
        }

        CatalogPartType::factory()->count(5)->create();
    }
}
