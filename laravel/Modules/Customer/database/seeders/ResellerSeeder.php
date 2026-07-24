<?php

declare(strict_types=1);

namespace Modules\Customer\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Customer\Database\Factories\ResellerFactory;
use Modules\Customer\Models\Reseller;

class ResellerSeeder extends Seeder
{
    public function run(): void
    {
        if (Reseller::query()->exists()) {
            return;
        }

        ResellerFactory::new()->count(3)->create();
    }
}
