<?php

declare(strict_types=1);

namespace Modules\Intervention\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Intervention\Database\Factories\InterventionReportShareFactory;
use Modules\Intervention\Models\InterventionReportShare;

class InterventionReportShareSeeder extends Seeder
{
    public function run(): void
    {
        if (InterventionReportShare::query()->exists()) {
            return;
        }

        InterventionReportShareFactory::new()->count(2)->create();
    }
}
