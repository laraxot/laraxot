<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PublicProcurement\Models\PublicProcurementTenderEligibilityCheck;

class PublicProcurementTenderEligibilityCheckSeeder extends Seeder
{
    public function run(): void
    {
        if (PublicProcurementTenderEligibilityCheck::query()->exists()) {
            return;
        }

        PublicProcurementTenderEligibilityCheck::factory()->count(3)->create();
    }
}
