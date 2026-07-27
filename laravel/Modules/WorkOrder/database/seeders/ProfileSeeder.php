<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\WorkOrder\Models\Profile;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        if (Profile::query()->exists()) {
            return;
        }
    }
}
