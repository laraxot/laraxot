<?php

declare(strict_types=1);

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Models\Role;

final class TimberRolesSeeder extends Seeder
{
    public function run(): void
    {
        Role::findOrCreate('timber');
        Role::findOrCreate('timber_operator');
        Role::findOrCreate('timber_admin');
    }
}
