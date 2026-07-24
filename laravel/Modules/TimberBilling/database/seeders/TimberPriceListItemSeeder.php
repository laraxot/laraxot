<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TimberBilling\Models\TimberPriceListItem;

/**
 * Seeder idempotente per TimberPriceListItem: popola record realistici tramite la factory dedicata.
 */
final class TimberPriceListItemSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberPriceListItem::query()->exists()) {
            return;
        }

        TimberPriceListItem::factory()->count(3)->create();
    }
}
