<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PublicProcurement\Models\PublicProcurementAiExtraction;

class PublicProcurementAiExtractionSeeder extends Seeder
{
    public function run(): void
    {
        if (PublicProcurementAiExtraction::query()->exists()) {
            return;
        }

        PublicProcurementAiExtraction::factory()->count(3)->create();
    }
}
