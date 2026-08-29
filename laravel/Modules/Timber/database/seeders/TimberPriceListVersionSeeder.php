<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Timber\Models\TimberPriceListVersion;

/**
 * Seeder idempotente per TimberPriceListVersion: popola record realistici tramite la factory dedicata.
 */
final class TimberPriceListVersionSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberPriceListVersion::query()->exists()) {
            return;
        }

        TimberPriceListVersion::factory()->count(3)->create();
    }
}
