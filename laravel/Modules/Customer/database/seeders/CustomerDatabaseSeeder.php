<?php

declare(strict_types=1);

namespace Modules\Customer\Database\Seeders;

use Illuminate\Database\Seeder;

class CustomerDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ContactRoleSeeder::class,
            CustomerSeeder::class,
            ResellerSeeder::class,
            CustomerSiteSeeder::class,
            ContactSeeder::class,
        ]);
    }
}
