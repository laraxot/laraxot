<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Compliance\Database\Factories\ComplianceDocumentFactory;
use Modules\Compliance\Models\ComplianceDocument;

class ComplianceDocumentSeeder extends Seeder
{
    public function run(): void
    {
        if (ComplianceDocument::query()->exists()) {
            return;
        }

        ComplianceDocumentFactory::new()->count(3)->create();
    }
}
