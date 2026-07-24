<?php

declare(strict_types=1);

namespace Modules\Quotation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Quotation\Models\QuotationTemplateItem;

class QuotationTemplateItemSeeder extends Seeder
{
    public function run(): void
    {
        if (QuotationTemplateItem::query()->exists()) {
            return;
        }

        QuotationTemplateItem::factory()->count(2)->create();
    }
}
