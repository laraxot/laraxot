<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Timber\Models\TimberDerivedMaterial;

/**
 * Seeder idempotente per TimberDerivedMaterial: popola record realistici tramite la factory dedicata.
 */
final class TimberDerivedMaterialSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberDerivedMaterial::query()->exists()) {
            return;
        }

        TimberDerivedMaterial::factory()->count(3)->create();
    }
}
