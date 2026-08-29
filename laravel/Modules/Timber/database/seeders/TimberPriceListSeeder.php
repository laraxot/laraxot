<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Timber\Models\TimberPriceList;

/**
 * Seeder idempotente per TimberPriceList: popola record realistici tramite la factory dedicata.
 */
final class TimberPriceListSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberPriceList::query()->exists()) {
            return;
        }

        TimberPriceList::factory()->count(3)->create();
    }
}
