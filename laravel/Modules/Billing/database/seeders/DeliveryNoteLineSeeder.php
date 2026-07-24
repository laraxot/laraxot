<?php

declare(strict_types=1);

namespace Modules\Billing\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Billing\Models\DeliveryNoteLine;

class DeliveryNoteLineSeeder extends Seeder
{
    public function run(): void
    {
        if (DeliveryNoteLine::query()->exists()) {
            return;
        }

        DeliveryNoteLine::factory()->count(3)->create();
    }
}
