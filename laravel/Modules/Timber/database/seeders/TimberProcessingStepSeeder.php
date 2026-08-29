<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Timber\Models\TimberProcessingStep;

/**
 * Seeder idempotente per TimberProcessingStep: popola record realistici tramite la factory dedicata.
 */
final class TimberProcessingStepSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberProcessingStep::query()->exists()) {
            return;
        }

        TimberProcessingStep::factory()->count(3)->create();
    }
}
