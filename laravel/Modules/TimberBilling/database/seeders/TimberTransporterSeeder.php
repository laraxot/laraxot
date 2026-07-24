<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TimberBilling\Models\TimberTransporter;

/**
 * Seeder idempotente per TimberTransporter: popola record realistici tramite la factory dedicata.
 */
final class TimberTransporterSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberTransporter::query()->exists()) {
            return;
        }

        TimberTransporter::factory()->count(3)->create();
    }
}
