<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Catalog\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        if (Product::query()->exists()) {
            return;
        }

        Product::factory()->count(10)->create();
    }
}
