<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TimberBilling\Models\TimberWoodSpecies;

/**
 * Seeder idempotente per TimberWoodSpecies: popola record realistici tramite la factory dedicata.
 */
final class TimberWoodSpeciesSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberWoodSpecies::query()->exists()) {
            return;
        }

        TimberWoodSpecies::factory()->count(3)->create();
    }
}
