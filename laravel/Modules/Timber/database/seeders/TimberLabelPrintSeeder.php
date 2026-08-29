<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Timber\Models\TimberLabelPrint;

/**
 * Seeder idempotente per TimberLabelPrint: popola record realistici tramite la factory dedicata.
 */
final class TimberLabelPrintSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberLabelPrint::query()->exists()) {
            return;
        }

        TimberLabelPrint::factory()->count(3)->create();
    }
}
