<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Compliance\Database\Factories\SubcontractorFactory;
use Modules\Compliance\Models\Subcontractor;

class SubcontractorSeeder extends Seeder
{
    public function run(): void
    {
        if (Subcontractor::query()->exists()) {
            return;
        }

        SubcontractorFactory::new()->count(3)->create();
    }
}
