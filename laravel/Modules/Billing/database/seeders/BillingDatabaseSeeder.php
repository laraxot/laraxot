<?php

declare(strict_types=1);

namespace Modules\Billing\Database\Seeders;

use Illuminate\Database\Seeder;

class BillingDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SupplierSeeder::class,
            DeliveryNoteSeeder::class,
            DeliveryNoteLineSeeder::class,
            InvoiceSeeder::class,
            SupplierInvoiceSeeder::class,
            SupplierInvoiceLineSeeder::class,
        ]);
    }
}
