<?php

declare(strict_types=1);

namespace Modules\Billing\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Billing\Database\Factories\SupplierInvoiceFactory;
use Modules\Billing\Models\SupplierInvoice;

class SupplierInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        if (SupplierInvoice::query()->exists()) {
            return;
        }

        SupplierInvoiceFactory::new()->count(3)->create();
    }
}
