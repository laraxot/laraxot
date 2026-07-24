<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PublicProcurement\Models\PublicProcurementPriceItem;

class PublicProcurementPriceItemSeeder extends Seeder
{
    public function run(): void
    {
        if (PublicProcurementPriceItem::query()->exists()) {
            return;
        }

        PublicProcurementPriceItem::factory()->count(3)->create();
    }
}
