<?php

declare(strict_types=1);

namespace Modules\Quotation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Quotation\Models\Quotation;

class QuotationSeeder extends Seeder
{
    public function run(): void
    {
        if (Quotation::query()->exists()) {
            return;
        }

        Quotation::factory()->count(2)->create();
    }
}
