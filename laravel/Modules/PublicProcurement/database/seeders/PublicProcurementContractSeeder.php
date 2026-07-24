<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PublicProcurement\Models\PublicProcurementContract;

class PublicProcurementContractSeeder extends Seeder
{
    public function run(): void
    {
        if (PublicProcurementContract::query()->exists()) {
            return;
        }

        PublicProcurementContract::factory()->count(3)->create();
    }
}
