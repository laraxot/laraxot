<?php

declare(strict_types=1);

namespace Modules\Bom\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Bom\Database\Factories\BomFactory;
use Modules\Bom\Models\Bom;

class BomSeeder extends Seeder
{
    public function run(): void
    {
        if (Bom::query()->exists()) {
            return;
        }

        BomFactory::new()->count(2)->create();
    }
}
