<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TimberBilling\Models\TimberPurchaseRecord;

/**
 * Seeder idempotente per TimberPurchaseRecord: popola record realistici tramite la factory dedicata.
 */
final class TimberPurchaseRecordSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberPurchaseRecord::query()->exists()) {
            return;
        }

        TimberPurchaseRecord::factory()->count(3)->create();
    }
}
