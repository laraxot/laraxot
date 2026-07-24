<?php

declare(strict_types=1);

namespace Modules\Intervention\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Intervention\Database\Factories\InterventionFactory;
use Modules\Intervention\Models\Intervention;

class InterventionSeeder extends Seeder
{
    public function run(): void
    {
        if (Intervention::query()->exists()) {
            return;
        }

        InterventionFactory::new()->count(2)->create();
    }
}
