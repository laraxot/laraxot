<?php

declare(strict_types=1);

namespace Modules\HR\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\HR\Database\Factories\AbsenceRequestFactory;
use Modules\HR\Models\AbsenceRequest;

class AbsenceRequestSeeder extends Seeder
{
    public function run(): void
    {
        if (AbsenceRequest::query()->exists()) {
            return;
        }

        AbsenceRequestFactory::new()->count(3)->create();
    }
}
