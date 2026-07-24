<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PublicProcurement\Models\PublicProcurementProgressReport;

class PublicProcurementProgressReportSeeder extends Seeder
{
    public function run(): void
    {
        if (PublicProcurementProgressReport::query()->exists()) {
            return;
        }

        PublicProcurementProgressReport::factory()->count(3)->create();
    }
}
