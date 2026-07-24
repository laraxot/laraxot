<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TimberBilling\Models\TimberPackagedUnit;

/**
 * Seeder idempotente per TimberPackagedUnit: popola record realistici tramite la factory dedicata.
 */
final class TimberPackagedUnitSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberPackagedUnit::query()->exists()) {
            return;
        }

        TimberPackagedUnit::factory()->count(3)->create();
    }
}
