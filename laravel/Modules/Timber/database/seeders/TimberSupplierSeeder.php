<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Timber\Models\TimberSupplier;

/**
 * Seeder idempotente per TimberSupplier: popola record realistici tramite la factory dedicata.
 */
final class TimberSupplierSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberSupplier::query()->exists()) {
            return;
        }

        TimberSupplier::factory()->count(3)->create();
    }
}
