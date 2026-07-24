<?php

declare(strict_types=1);

namespace Modules\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Employee\Database\Factories\AbsenceRequestFactory;
use Modules\Employee\Models\AbsenceRequest;

class AbsenceRequestSeeder extends Seeder
{
    public function run(): void
    {
        if (AbsenceRequest::query()->exists()) {
            return;
        }

        AbsenceRequestFactory::new()->count(20)->create();
    }
}
