<?php

declare(strict_types=1);

namespace Modules\Billing\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Billing\Models\Invoice;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        if (Invoice::query()->exists()) {
            return;
        }

        Invoice::factory()->count(3)->create();
    }
}
