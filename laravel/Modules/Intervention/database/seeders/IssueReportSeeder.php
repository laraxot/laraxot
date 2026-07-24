<?php

declare(strict_types=1);

namespace Modules\Intervention\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Intervention\Database\Factories\IssueReportFactory;
use Modules\Intervention\Models\IssueReport;

class IssueReportSeeder extends Seeder
{
    public function run(): void
    {
        if (IssueReport::query()->exists()) {
            return;
        }

        IssueReportFactory::new()->count(2)->create();
    }
}
