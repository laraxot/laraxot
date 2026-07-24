<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TimberBilling\Models\TimberLabelPrint;

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
