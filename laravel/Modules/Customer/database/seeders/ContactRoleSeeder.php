<?php

declare(strict_types=1);

namespace Modules\Customer\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Customer\Models\ContactRole;

class ContactRoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Titolare', 'Tecnico', 'Amministrazione', 'Referente cantiere'] as $name) {
            ContactRole::query()->firstOrCreate(['name' => $name]);
        }
    }
}
