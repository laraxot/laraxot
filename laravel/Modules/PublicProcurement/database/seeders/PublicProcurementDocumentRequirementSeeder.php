<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PublicProcurement\Models\PublicProcurementDocumentRequirement;

class PublicProcurementDocumentRequirementSeeder extends Seeder
{
    public function run(): void
    {
        if (PublicProcurementDocumentRequirement::query()->exists()) {
            return;
        }

        PublicProcurementDocumentRequirement::factory()->count(3)->create();
    }
}
