<?php

declare(strict_types=1);

namespace Modules\Billing\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Billing\Database\Factories\SupplierInvoiceLineFactory;
use Modules\Billing\Models\SupplierInvoiceLine;

class SupplierInvoiceLineSeeder extends Seeder
{
    public function run(): void
    {
        if (SupplierInvoiceLine::query()->exists()) {
            return;
        }

        SupplierInvoiceLineFactory::new()->count(3)->create();
    }
}
