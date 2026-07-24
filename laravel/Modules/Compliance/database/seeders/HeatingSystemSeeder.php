<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Compliance\Database\Factories\HeatingSystemFactory;
use Modules\Compliance\Models\HeatingSystem;

class HeatingSystemSeeder extends Seeder
{
    public function run(): void
    {
        if (HeatingSystem::query()->exists()) {
            return;
        }

        HeatingSystemFactory::new()->count(3)->create();
    }
}
