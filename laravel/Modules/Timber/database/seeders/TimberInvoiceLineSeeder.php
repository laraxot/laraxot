<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Timber\Models\TimberInvoiceLine;

/**
 * Seeder idempotente per TimberInvoiceLine: popola record realistici tramite la factory dedicata.
 */
final class TimberInvoiceLineSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberInvoiceLine::query()->exists()) {
            return;
        }

        TimberInvoiceLine::factory()->count(3)->create();
    }
}
