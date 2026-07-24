<?php

declare(strict_types=1);

namespace Modules\Quotation\Database\Seeders;

use Illuminate\Database\Seeder;

class QuotationDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            QuotationTemplateSeeder::class,
            QuotationSeeder::class,
            QuotationItemSeeder::class,
            QuotationTemplateItemSeeder::class,
            SiteSurveySeeder::class,
        ]);
    }
}
