<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PublicProcurement\Models\PublicProcurementMeasurement;

class PublicProcurementMeasurementSeeder extends Seeder
{
    public function run(): void
    {
        if (PublicProcurementMeasurement::query()->exists()) {
            return;
        }

        PublicProcurementMeasurement::factory()->count(3)->create();
    }
}
