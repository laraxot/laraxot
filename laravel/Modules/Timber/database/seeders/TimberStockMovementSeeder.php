<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Timber\Models\TimberStockMovement;

/**
 * Seeder idempotente per TimberStockMovement: popola record realistici tramite la factory dedicata.
 */
final class TimberStockMovementSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberStockMovement::query()->exists()) {
            return;
        }

        TimberStockMovement::factory()->count(3)->create();
    }
}
