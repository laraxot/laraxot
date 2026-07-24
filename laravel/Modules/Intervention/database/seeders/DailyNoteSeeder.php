<?php

declare(strict_types=1);

namespace Modules\Intervention\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Intervention\Database\Factories\DailyNoteFactory;
use Modules\Intervention\Models\DailyNote;

class DailyNoteSeeder extends Seeder
{
    public function run(): void
    {
        if (DailyNote::query()->exists()) {
            return;
        }

        DailyNoteFactory::new()->count(2)->create();
    }
}
