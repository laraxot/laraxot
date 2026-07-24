<?php

declare(strict_types=1);

namespace Modules\Intervention\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Intervention\Database\Factories\TimePunchFactory;
use Modules\Intervention\Models\TimePunch;

class TimePunchSeeder extends Seeder
{
    public function run(): void
    {
        if (TimePunch::query()->exists()) {
            return;
        }

        TimePunchFactory::new()->count(2)->create();
    }
}
