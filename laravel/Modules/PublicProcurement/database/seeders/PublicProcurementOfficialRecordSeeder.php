<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PublicProcurement\Models\PublicProcurementOfficialRecord;

class PublicProcurementOfficialRecordSeeder extends Seeder
{
    public function run(): void
    {
        if (PublicProcurementOfficialRecord::query()->exists()) {
            return;
        }

        PublicProcurementOfficialRecord::factory()->count(3)->create();
    }
}
