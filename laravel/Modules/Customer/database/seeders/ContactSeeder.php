<?php

declare(strict_types=1);

namespace Modules\Customer\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Customer\Database\Factories\ContactFactory;
use Modules\Customer\Models\Contact;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        if (Contact::query()->exists()) {
            return;
        }

        ContactFactory::new()->count(3)->create();
    }
}
