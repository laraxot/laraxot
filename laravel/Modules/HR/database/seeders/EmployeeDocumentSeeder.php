<?php

declare(strict_types=1);

namespace Modules\HR\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\HR\Database\Factories\EmployeeDocumentFactory;
use Modules\HR\Models\EmployeeDocument;

class EmployeeDocumentSeeder extends Seeder
{
    public function run(): void
    {
        if (EmployeeDocument::query()->exists()) {
            return;
        }

        EmployeeDocumentFactory::new()->count(3)->create();
    }
}
