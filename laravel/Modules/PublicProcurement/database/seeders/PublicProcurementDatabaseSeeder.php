<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Seeders;

use Illuminate\Database\Seeder;

class PublicProcurementDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PublicProcurementTenderSourceSeeder::class,
            PublicProcurementTenderOpportunitySeeder::class,
            PublicProcurementContractSeeder::class,
            PublicProcurementTenderEligibilityCheckSeeder::class,
            PublicProcurementContactSeeder::class,
            PublicProcurementDeadlineSeeder::class,
            PublicProcurementDocumentRequirementSeeder::class,
            PublicProcurementEconomicFrameSeeder::class,
            PublicProcurementPriceItemSeeder::class,
            PublicProcurementMeasurementSeeder::class,
            PublicProcurementProgressReportSeeder::class,
            PublicProcurementPaymentCertificateSeeder::class,
            PublicProcurementOfficialRecordSeeder::class,
            PublicProcurementGuaranteeSeeder::class,
            PublicProcurementAiExtractionSeeder::class,
        ]);
    }
}
