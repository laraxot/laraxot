<?php

declare(strict_types=1);

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Modules\User\Models\Role;

final class RolesAndUsersSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::findOrCreate('admin');
        $operatorRole = Role::findOrCreate('operator');
        $customerRole = Role::findOrCreate('customer');
        $supplierRole = Role::findOrCreate('supplier');

        $admin = User::firstOrCreate(
            ['email' => 'admin@local.test'],
            ['name' => 'Admin', 'password' => Hash::make('ChangeMe123!'), 'approved_at' => now()],
        );
        $admin->forceFill(['approved_at' => $admin->getAttribute('approved_at') ?? now()])->save();
        $admin->syncRoles([$adminRole]);

        $operator = User::firstOrCreate(
            ['email' => 'operator@local.test'],
            ['name' => 'Operator', 'password' => Hash::make('ChangeMe123!'), 'approved_at' => now()],
        );
        $operator->forceFill(['approved_at' => $operator->getAttribute('approved_at') ?? now()])->save();
        $operator->syncRoles([$operatorRole]);

        $customer = User::firstOrCreate(
            ['email' => 'customer@local.test'],
            ['name' => 'Cliente Demo', 'password' => Hash::make('Demo123!'), 'approved_at' => now()],
        );
        $customer->forceFill(['approved_at' => $customer->getAttribute('approved_at') ?? now()])->save();
        $customer->syncRoles([$customerRole]);

        $supplier = User::firstOrCreate(
            ['email' => 'supplier@local.test'],
            ['name' => 'Fornitore Demo', 'password' => Hash::make('Demo123!'), 'approved_at' => now()],
        );
        $supplier->forceFill(['approved_at' => $supplier->getAttribute('approved_at') ?? now()])->save();
        $supplier->syncRoles([$supplierRole]);
    }
}
