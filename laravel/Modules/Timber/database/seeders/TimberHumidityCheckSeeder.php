<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Timber\Models\TimberHumidityCheck;

/**
 * Seeder idempotente per TimberHumidityCheck: popola record realistici tramite la factory dedicata.
 */
final class TimberHumidityCheckSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberHumidityCheck::query()->exists()) {
            return;
        }

        TimberHumidityCheck::factory()->count(3)->create();
    }
}
