<?php

declare(strict_types=1);

namespace Modules\Customer\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Customer\Database\Factories\CustomerSiteFactory;
use Modules\Customer\Models\CustomerSite;

class CustomerSiteSeeder extends Seeder
{
    public function run(): void
    {
        if (CustomerSite::query()->exists()) {
            return;
        }

        CustomerSiteFactory::new()->count(3)->create();
    }
}
