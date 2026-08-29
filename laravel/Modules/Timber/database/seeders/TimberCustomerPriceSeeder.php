<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Timber\Models\TimberCustomerPrice;

/**
 * Seeder idempotente per TimberCustomerPrice: popola record realistici tramite la factory dedicata.
 */
final class TimberCustomerPriceSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberCustomerPrice::query()->exists()) {
            return;
        }

        TimberCustomerPrice::factory()->count(3)->create();
    }
}
