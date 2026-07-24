<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TimberBilling\Models\TimberEInvoice;

/**
 * Seeder idempotente per TimberEInvoice: popola record realistici tramite la factory dedicata.
 */
final class TimberEInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberEInvoice::query()->exists()) {
            return;
        }

        TimberEInvoice::factory()->count(3)->create();
    }
}
