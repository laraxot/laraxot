<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TimberBilling\Models\TimberPriceList;

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
