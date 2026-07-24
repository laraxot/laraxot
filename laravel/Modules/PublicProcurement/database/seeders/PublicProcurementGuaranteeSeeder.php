<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PublicProcurement\Models\PublicProcurementGuarantee;

class PublicProcurementGuaranteeSeeder extends Seeder
{
    public function run(): void
    {
        if (PublicProcurementGuarantee::query()->exists()) {
            return;
        }

        PublicProcurementGuarantee::factory()->count(3)->create();
    }
}
