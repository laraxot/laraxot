<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Timber\Models\TimberAgent;

/**
 * Seeder idempotente per TimberAgent: popola record realistici tramite la factory dedicata.
 */
final class TimberAgentSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberAgent::query()->exists()) {
            return;
        }

        TimberAgent::factory()->count(3)->create();
    }
}
