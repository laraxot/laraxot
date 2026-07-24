<?php

declare(strict_types=1);

namespace Modules\HR\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\HR\Database\Factories\EmployeeMedicalVisitFactory;
use Modules\HR\Models\EmployeeMedicalVisit;

class EmployeeMedicalVisitSeeder extends Seeder
{
    public function run(): void
    {
        if (EmployeeMedicalVisit::query()->exists()) {
            return;
        }

        EmployeeMedicalVisitFactory::new()->count(3)->create();
    }
}
