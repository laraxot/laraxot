<?php

declare(strict_types=1);

namespace Modules\HR\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\HR\Database\Factories\EmployeeFactory;
use Modules\HR\Models\Employee;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        if (Employee::query()->exists()) {
            return;
        }

        EmployeeFactory::new()->count(5)->create();
    }
}
