<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TimberBilling\Models\TimberMovement;

/**
 * Seeder idempotente per TimberMovement: popola record realistici tramite la factory dedicata.
 */
final class TimberMovementSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberMovement::query()->exists()) {
            return;
        }

        TimberMovement::factory()->count(3)->create();
    }
}
