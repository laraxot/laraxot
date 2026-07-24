<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PublicProcurement\Models\PublicProcurementTenderSource;

class PublicProcurementTenderSourceSeeder extends Seeder
{
    public function run(): void
    {
        if (PublicProcurementTenderSource::query()->exists()) {
            return;
        }

        PublicProcurementTenderSource::factory()->count(3)->create();
    }
}
