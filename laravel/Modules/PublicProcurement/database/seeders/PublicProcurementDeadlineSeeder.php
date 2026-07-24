<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PublicProcurement\Models\PublicProcurementDeadline;

class PublicProcurementDeadlineSeeder extends Seeder
{
    public function run(): void
    {
        if (PublicProcurementDeadline::query()->exists()) {
            return;
        }

        PublicProcurementDeadline::factory()->count(3)->create();
    }
}
