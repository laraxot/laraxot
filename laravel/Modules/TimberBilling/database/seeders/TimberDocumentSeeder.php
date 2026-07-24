<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TimberBilling\Models\TimberDocument;

/**
 * Seeder idempotente per TimberDocument: popola record realistici tramite la factory dedicata.
 */
final class TimberDocumentSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberDocument::query()->exists()) {
            return;
        }

        TimberDocument::factory()->count(3)->create();
    }
}
