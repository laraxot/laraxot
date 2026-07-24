<?php

declare(strict_types=1);

namespace Modules\Quotation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Quotation\Models\QuotationTemplate;

class QuotationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        if (QuotationTemplate::query()->exists()) {
            return;
        }

        QuotationTemplate::factory()->count(2)->create();
    }
}
