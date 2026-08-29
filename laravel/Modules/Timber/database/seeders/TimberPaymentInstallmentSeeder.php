<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Timber\Models\TimberPaymentInstallment;

/**
 * Seeder idempotente per TimberPaymentInstallment: popola record realistici tramite la factory dedicata.
 */
final class TimberPaymentInstallmentSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberPaymentInstallment::query()->exists()) {
            return;
        }

        TimberPaymentInstallment::factory()->count(3)->create();
    }
}
