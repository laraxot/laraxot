<?php

declare(strict_types=1);

namespace Modules\Billing\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Billing\Models\SupplierInvoice;

class SupplierInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        if (SupplierInvoice::query()->exists()) {
            return;
        }

        SupplierInvoice::factory()->count(3)->create();
    }
}
