<?php

declare(strict_types=1);

namespace Modules\Email\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Email\Database\Factories\EmailContactIdentityFactory;
use Modules\Email\Models\EmailContactIdentity;

class EmailContactIdentitySeeder extends Seeder
{
    public function run(): void
    {
        if (EmailContactIdentity::query()->exists()) {
            return;
        }

        EmailContactIdentityFactory::new()->count(5)->create();
    }
}
