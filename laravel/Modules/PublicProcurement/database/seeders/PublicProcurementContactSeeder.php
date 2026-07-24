<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PublicProcurement\Models\PublicProcurementContact;

class PublicProcurementContactSeeder extends Seeder
{
    public function run(): void
    {
        if (PublicProcurementContact::query()->exists()) {
            return;
        }

        PublicProcurementContact::factory()->count(3)->create();
    }
}
