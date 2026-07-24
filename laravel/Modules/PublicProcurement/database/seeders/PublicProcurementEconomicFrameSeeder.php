<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PublicProcurement\Models\PublicProcurementEconomicFrame;

class PublicProcurementEconomicFrameSeeder extends Seeder
{
    public function run(): void
    {
        if (PublicProcurementEconomicFrame::query()->exists()) {
            return;
        }

        PublicProcurementEconomicFrame::factory()->count(3)->create();
    }
}
