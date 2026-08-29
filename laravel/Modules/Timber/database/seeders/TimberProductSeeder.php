<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Timber\Models\TimberProduct;

/**
 * Seeder idempotente per TimberProduct: popola record realistici tramite la factory dedicata.
 */
final class TimberProductSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberProduct::query()->exists()) {
            return;
        }

        TimberProduct::factory()->count(3)->create();
    }
}
