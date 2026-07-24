<?php

declare(strict_types=1);

namespace Modules\Customer\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Customer\Database\Factories\CustomerFactory;
use Modules\Customer\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        if (Customer::query()->exists()) {
            return;
        }

        CustomerFactory::new()->count(3)->create();
    }
}
