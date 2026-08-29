<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Timber\Models\TimberContactNote;

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
