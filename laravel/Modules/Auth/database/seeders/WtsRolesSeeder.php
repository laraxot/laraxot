<?php

declare(strict_types=1);

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Models\Role;

final class WtsRolesSeeder extends Seeder
{
    public function run(): void
    {
        Role::findOrCreate('wts');
        Role::findOrCreate('wts_operator');
        Role::findOrCreate('wts_admin');
    }
}
