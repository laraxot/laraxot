<?php

declare(strict_types=1);

namespace Modules\HR\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\HR\Database\Factories\EmployeeTrainingFactory;
use Modules\HR\Models\EmployeeTraining;

class EmployeeTrainingSeeder extends Seeder
{
    public function run(): void
    {
        if (EmployeeTraining::query()->exists()) {
            return;
        }

        EmployeeTrainingFactory::new()->count(3)->create();
    }
}
