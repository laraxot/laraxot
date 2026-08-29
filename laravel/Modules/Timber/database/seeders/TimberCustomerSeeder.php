<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Timber\Models\TimberCustomer;

/**
 * Seeder idempotente per TimberCustomer: popola record realistici tramite la factory dedicata.
 */
final class TimberCustomerSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberCustomer::query()->exists()) {
            return;
        }

        TimberCustomer::factory()->count(3)->create();
    }
}
