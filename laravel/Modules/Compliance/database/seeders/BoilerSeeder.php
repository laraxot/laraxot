<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Compliance\Database\Factories\BoilerFactory;
use Modules\Compliance\Models\Boiler;

class BoilerSeeder extends Seeder
{
    public function run(): void
    {
        if (Boiler::query()->exists()) {
            return;
        }

        BoilerFactory::new()->count(3)->create();
    }
}
