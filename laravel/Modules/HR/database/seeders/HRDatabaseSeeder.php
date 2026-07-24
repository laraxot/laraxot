<?php

declare(strict_types=1);

namespace Modules\HR\Database\Seeders;

use Illuminate\Database\Seeder;

class HRDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EmployeeSeeder::class,
            EmployeeMedicalVisitSeeder::class,
            EmployeeTrainingSeeder::class,
            EmployeeDocumentSeeder::class,
            AbsenceRequestSeeder::class,
        ]);
    }
}
