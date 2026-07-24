<?php

declare(strict_types=1);

namespace Modules\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Employee\Database\Factories\DepartmentFactory;
use Modules\Employee\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        if (Department::query()->exists()) {
            return;
        }

        DepartmentFactory::new()->count(8)->create();
    }
}
