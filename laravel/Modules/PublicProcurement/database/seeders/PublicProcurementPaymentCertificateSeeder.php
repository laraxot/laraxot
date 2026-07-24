<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PublicProcurement\Models\PublicProcurementPaymentCertificate;

class PublicProcurementPaymentCertificateSeeder extends Seeder
{
    public function run(): void
    {
        if (PublicProcurementPaymentCertificate::query()->exists()) {
            return;
        }

        PublicProcurementPaymentCertificate::factory()->count(3)->create();
    }
}
