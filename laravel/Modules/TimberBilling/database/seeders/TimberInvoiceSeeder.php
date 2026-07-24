<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TimberBilling\Models\TimberInvoice;

/**
 * Seeder idempotente per TimberInvoice: popola record realistici tramite la factory dedicata.
 */
final class TimberInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberInvoice::query()->exists()) {
            return;
        }

        TimberInvoice::factory()->count(3)->create();
    }
}
