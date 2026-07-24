<?php

declare(strict_types=1);

namespace Modules\Billing\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Billing\Models\SupplierInvoiceLine;

class SupplierInvoiceLineSeeder extends Seeder
{
    public function run(): void
    {
        if (SupplierInvoiceLine::query()->exists()) {
            return;
        }

        SupplierInvoiceLine::factory()->count(3)->create();
    }
}
