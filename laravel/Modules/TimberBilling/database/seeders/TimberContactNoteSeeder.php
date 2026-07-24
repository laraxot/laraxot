<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TimberBilling\Models\TimberContactNote;

/**
 * Seeder idempotente per TimberContactNote: popola record realistici tramite la factory dedicata.
 */
final class TimberContactNoteSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberContactNote::query()->exists()) {
            return;
        }

        TimberContactNote::factory()->count(3)->create();
    }
}
