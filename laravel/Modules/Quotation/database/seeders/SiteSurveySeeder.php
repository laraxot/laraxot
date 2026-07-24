<?php

declare(strict_types=1);

namespace Modules\Quotation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Quotation\Models\SiteSurvey;

class SiteSurveySeeder extends Seeder
{
    public function run(): void
    {
        if (SiteSurvey::query()->exists()) {
            return;
        }

        SiteSurvey::factory()->count(2)->create();
    }
}
