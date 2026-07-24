<?php

declare(strict_types=1);

namespace Modules\Billing\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Billing\Models\DeliveryNote;

class DeliveryNoteSeeder extends Seeder
{
    public function run(): void
    {
        if (DeliveryNote::query()->exists()) {
            return;
        }

        DeliveryNote::factory()->count(3)->create();
    }
}
