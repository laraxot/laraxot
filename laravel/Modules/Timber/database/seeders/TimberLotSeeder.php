<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Timber\Models\TimberLot;

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
