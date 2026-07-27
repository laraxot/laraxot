<?php

declare(strict_types=1);

namespace Modules\Billing\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Billing\Database\Factories\SupplierFactory;
use Modules\Billing\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        if (Supplier::query()->exists()) {
            return;
        }

        SupplierFactory::new()->count(3)->create();
    }
}
