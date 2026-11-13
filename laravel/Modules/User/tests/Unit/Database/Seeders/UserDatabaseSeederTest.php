<?php

declare(strict_types=1);
<<<<<<< .merge_file_5C1yJr

=======
>>>>>>> .merge_file_jctTe7
use Modules\User\Database\Seeders\UserDatabaseSeeder;
use Modules\User\Models\Permission;
use Modules\User\Models\Role;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/*
 * @covers \Modules\User\Database\Seeders\UserDatabaseSeeder
 */
it('runs UserDatabaseSeeder successfully', function (): void {
<<<<<<< .merge_file_5C1yJr
    $seeder = new UserDatabaseSeeder();
=======
    $seeder = new UserDatabaseSeeder;
>>>>>>> .merge_file_jctTe7
    $seeder->setContainer(app());

    $seeder->run();

    Assert::assertTrue(Role::where('name', 'super-admin')->where('guard_name', 'web')->exists());

    $permissionCount = Permission::where('guard_name', 'web')->count();
    Assert::assertGreaterThan(0, $permissionCount);
});

it('gives super-admin role all permissions after seeding', function (): void {
<<<<<<< .merge_file_5C1yJr
    $seeder = new UserDatabaseSeeder();
=======
    $seeder = new UserDatabaseSeeder;
>>>>>>> .merge_file_jctTe7
    $seeder->setContainer(app());

    $seeder->run();

    $superAdmin = Role::where('name', 'super-admin')
        ->where('guard_name', 'web')
        ->firstOrFail();

    $allPermissions = Permission::all();
    $superAdminPermissions = $superAdmin->permissions;

    Assert::assertGreaterThan(0, $allPermissions->count());
    Assert::assertGreaterThan(0, $superAdminPermissions->count());
});
