<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TimberBilling\Models\TimberLot;

/**
 * Seeder idempotente per TimberLot: popola record realistici tramite la factory dedicata.
 */
final class TimberLotSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberLot::query()->exists()) {
            return;
        }

        TimberLot::factory()->count(3)->create();
    }
}
