<?php

declare(strict_types=1);

namespace Modules\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Employee\Database\Factories\TimeEntryFactory;
use Modules\Employee\Models\TimeEntry;

class TimeEntrySeeder extends Seeder
{
    public function run(): void
    {
        if (TimeEntry::query()->exists()) {
            return;
        }

        TimeEntryFactory::new()->count(30)->create();
    }
}
