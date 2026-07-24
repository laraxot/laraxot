<?php

declare(strict_types=1);

namespace Modules\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Employee\Database\Factories\TimeRecordFactory;
use Modules\Employee\Models\TimeRecord;

class TimeRecordSeeder extends Seeder
{
    public function run(): void
    {
        if (TimeRecord::query()->exists()) {
            return;
        }

        TimeRecordFactory::new()->count(30)->create();
    }
}
