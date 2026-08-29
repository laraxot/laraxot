<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Timber\Models\TimberDocumentLine;

/**
 * Seeder idempotente per TimberDocumentLine: popola record realistici tramite la factory dedicata.
 */
final class TimberDocumentLineSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberDocumentLine::query()->exists()) {
            return;
        }

        TimberDocumentLine::factory()->count(3)->create();
    }
}
