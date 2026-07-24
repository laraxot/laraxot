<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TimberBilling\Models\TimberDryingCycle;

/**
 * Seeder idempotente per TimberDryingCycle: popola record realistici tramite la factory dedicata.
 */
final class TimberDryingCycleSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberDryingCycle::query()->exists()) {
            return;
        }

        TimberDryingCycle::factory()->count(3)->create();
    }
}
