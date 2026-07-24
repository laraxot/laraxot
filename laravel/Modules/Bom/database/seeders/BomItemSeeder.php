<?php

declare(strict_types=1);

namespace Modules\Bom\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Bom\Database\Factories\BomItemFactory;
use Modules\Bom\Models\BomItem;

class BomItemSeeder extends Seeder
{
    public function run(): void
    {
        if (BomItem::query()->exists()) {
            return;
        }

        BomItemFactory::new()->count(2)->create();
    }
}
