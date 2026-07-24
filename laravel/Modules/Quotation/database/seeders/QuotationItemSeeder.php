<?php

declare(strict_types=1);

namespace Modules\Quotation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Quotation\Models\QuotationItem;

class QuotationItemSeeder extends Seeder
{
    public function run(): void
    {
        if (QuotationItem::query()->exists()) {
            return;
        }

        QuotationItem::factory()->count(2)->create();
    }
}
