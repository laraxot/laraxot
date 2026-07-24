<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Compliance\Database\Factories\CustomerSystemFactory;
use Modules\Compliance\Models\CustomerSystem;

class CustomerSystemSeeder extends Seeder
{
    public function run(): void
    {
        if (CustomerSystem::query()->exists()) {
            return;
        }

        CustomerSystemFactory::new()->count(3)->create();
    }
}
