<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TimberBilling\Models\TimberAgentCommission;

/**
 * Seeder idempotente per TimberAgentCommission: popola record realistici tramite la factory dedicata.
 */
final class TimberAgentCommissionSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberAgentCommission::query()->exists()) {
            return;
        }

        TimberAgentCommission::factory()->count(3)->create();
    }
}
