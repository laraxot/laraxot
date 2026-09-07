<?php

declare(strict_types=1);

namespace Modules\User\Tests\Feature;

use Illuminate\Database\QueryException;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\User\Database\Factories\PermissionFactory;
use Modules\User\Database\Factories\ProfileFactory;
use Modules\User\Database\Factories\RoleFactory;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\Profile;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('User Management Business Logic', function (): void {
    test('can create user with profile', function (): void {
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\Permission;
use Modules\User\Models\Profile;
use Modules\User\Models\Role;
use Modules\User\Models\User;
use Tests\TestCase;

class UserManagementBusinessLogicTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_user_with_profile(): void
    {
        // Arrange
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\User\Database\Factories\PermissionFactory;
use Modules\User\Database\Factories\ProfileFactory;
use Modules\User\Database\Factories\RoleFactory;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\Profile;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('User Management Business Logic', function (): void {
    test('can create user with profile', function (): void {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        $userData = [
            'name' => 'Mario Rossi',
            'email' => 'mario.rossi@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ];

        $profileData = [
            'phone' => '+39 123 456 7890',
            'address' => 'Via Roma 123, Milano',
            'birth_date' => '1990-05-15',
            'gender' => 'M',
        ];

<<<<<<< HEAD
<<<<<<< HEAD
        $user = User::create($userData);
        $createdProfile = $user->profile()->create($profileData);
        Assert::assertInstanceOf(Profile::class, $createdProfile);
        $profile = $createdProfile;

        Assert::assertTrue(DB::table('users')->where([
            'id' => $user->id,
            'name' => 'Mario Rossi',
            'email' => 'mario.rossi@example.com',
        ])->exists());
        Assert::assertTrue(DB::table('profiles')->where([
=======
=======
>>>>>>> 87273113 (.)
        // Act
        $user = User::create($userData);
        $profile = $user->profile()->create($profileData);

        // Assert
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Mario Rossi',
            'email' => 'mario.rossi@example.com',
        ]);

        $this->assertDatabaseHas('profiles', [
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        $user = User::create($userData);
        $createdProfile = $user->profile()->create($profileData);
        Assert::assertInstanceOf(Profile::class, $createdProfile);
        $profile = $createdProfile;

        Assert::assertTrue(DB::table('users')->where([
            'id' => $user->id,
            'name' => 'Mario Rossi',
            'email' => 'mario.rossi@example.com',
        ])->exists());
        Assert::assertTrue(DB::table('profiles')->where([
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'id' => $profile->id,
            'user_id' => $user->id,
            'phone' => '+39 123 456 7890',
            'address' => 'Via Roma 123, Milano',
<<<<<<< HEAD
<<<<<<< HEAD
        ])->exists());
        Assert::assertInstanceOf(Profile::class, $user->profile);
        Assert::assertSame($user->id, $profile->user_id);
    });

    test('can assign role to user', function (): void {
        $user = UserFactory::new()->createOne();
        $role = RoleFactory::new()->createOne(['name' => 'doctor']);

        $user->assignRole($role);

        Assert::assertTrue($user->hasRole('doctor'));
        Assert::assertTrue($user->hasRole($role));
        Assert::assertContains($role->name, $user->getRoleNames()->toArray());
    });

    test('can assign multiple roles to user', function (): void {
        $user = UserFactory::new()->createOne();
        $role1 = RoleFactory::new()->createOne(['name' => 'doctor']);
        $role2 = RoleFactory::new()->createOne(['name' => 'admin']);

        $user->assignRole([$role1, $role2]);

        Assert::assertTrue($user->hasRole('doctor'));
        Assert::assertTrue($user->hasRole('admin'));
        Assert::assertTrue($user->hasRole($role1));
        Assert::assertTrue($user->hasRole($role2));
        Assert::assertCount(2, $user->getRoleNames());
    });

    test('can remove role from user', function (): void {
        $user = UserFactory::new()->createOne();
        $role = RoleFactory::new()->createOne(['name' => 'doctor']);
        $user->assignRole($role);

        $user->removeRole($role);

        Assert::assertFalse($user->hasRole('doctor'));
        Assert::assertFalse($user->hasRole($role));
        Assert::assertCount(0, $user->getRoleNames());
    });

    test('can sync user roles', function (): void {
        $user = UserFactory::new()->createOne();
        $role1 = RoleFactory::new()->createOne(['name' => 'doctor']);
        $role2 = RoleFactory::new()->createOne(['name' => 'admin']);
        $role3 = RoleFactory::new()->createOne(['name' => 'nurse']);

        $user->assignRole([$role1, $role2]);

        $user->syncRoles([$role2, $role3]);

        Assert::assertFalse($user->hasRole('doctor'));
        Assert::assertTrue($user->hasRole('admin'));
        Assert::assertTrue($user->hasRole('nurse'));
        Assert::assertCount(2, $user->getRoleNames());
    });

    test('can check user permissions', function (): void {
        $user = UserFactory::new()->createOne();
        $role = RoleFactory::new()->createOne(['name' => 'doctor']);
        $permission = PermissionFactory::new()->createOne(['name' => 'patients.read']);
=======
=======
>>>>>>> 87273113 (.)
        ]);

        $this->assertInstanceOf(Profile::class, $user->profile);
        $this->assertEquals($user->id, $profile->user_id);
    }

    /** @test */
    public function it_can_assign_role_to_user(): void
    {
        // Arrange
        $user = User::factory()->create();
        $role = Role::factory()->create(['name' => 'doctor']);

        // Act
        $user->assignRole($role);

        // Assert
        $this->assertTrue($user->hasRole('doctor'));
        $this->assertTrue($user->hasRole($role));
        $this->assertContains($role->name, $user->getRoleNames()->toArray());
    }

    /** @test */
    public function it_can_assign_multiple_roles_to_user(): void
    {
        // Arrange
        $user = User::factory()->create();
        $role1 = Role::factory()->create(['name' => 'doctor']);
        $role2 = Role::factory()->create(['name' => 'admin']);

        // Act
        $user->assignRole([$role1, $role2]);

        // Assert
        $this->assertTrue($user->hasRole('doctor'));
        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasRole($role1));
        $this->assertTrue($user->hasRole($role2));
        $this->assertCount(2, $user->getRoleNames());
    }

    /** @test */
    public function it_can_remove_role_from_user(): void
    {
        // Arrange
        $user = User::factory()->create();
        $role = Role::factory()->create(['name' => 'doctor']);
        $user->assignRole($role);

        // Act
        $user->removeRole($role);

        // Assert
        $this->assertFalse($user->hasRole('doctor'));
        $this->assertFalse($user->hasRole($role));
        $this->assertCount(0, $user->getRoleNames());
    }

    /** @test */
    public function it_can_sync_user_roles(): void
    {
        // Arrange
        $user = User::factory()->create();
        $role1 = Role::factory()->create(['name' => 'doctor']);
        $role2 = Role::factory()->create(['name' => 'admin']);
        $role3 = Role::factory()->create(['name' => 'nurse']);

        $user->assignRole([$role1, $role2]);

        // Act
        $user->syncRoles([$role2, $role3]);

        // Assert
        $this->assertFalse($user->hasRole('doctor'));
        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasRole('nurse'));
        $this->assertCount(2, $user->getRoleNames());
    }

    /** @test */
    public function it_can_check_user_permissions(): void
    {
        // Arrange
        $user = User::factory()->create();
        $role = Role::factory()->create(['name' => 'doctor']);
        $permission = Permission::factory()->create(['name' => 'patients.read']);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        ])->exists());
        Assert::assertInstanceOf(Profile::class, $user->profile);
        Assert::assertSame($user->id, $profile->user_id);
    });

    test('can assign role to user', function (): void {
        $user = UserFactory::new()->createOne();
        $role = RoleFactory::new()->createOne(['name' => 'doctor']);

        $user->assignRole($role);

        Assert::assertTrue($user->hasRole('doctor'));
        Assert::assertTrue($user->hasRole($role));
        Assert::assertContains($role->name, $user->getRoleNames()->toArray());
    });

    test('can assign multiple roles to user', function (): void {
        $user = UserFactory::new()->createOne();
        $role1 = RoleFactory::new()->createOne(['name' => 'doctor']);
        $role2 = RoleFactory::new()->createOne(['name' => 'admin']);

        $user->assignRole([$role1, $role2]);

        Assert::assertTrue($user->hasRole('doctor'));
        Assert::assertTrue($user->hasRole('admin'));
        Assert::assertTrue($user->hasRole($role1));
        Assert::assertTrue($user->hasRole($role2));
        Assert::assertCount(2, $user->getRoleNames());
    });

    test('can remove role from user', function (): void {
        $user = UserFactory::new()->createOne();
        $role = RoleFactory::new()->createOne(['name' => 'doctor']);
        $user->assignRole($role);

        $user->removeRole($role);

        Assert::assertFalse($user->hasRole('doctor'));
        Assert::assertFalse($user->hasRole($role));
        Assert::assertCount(0, $user->getRoleNames());
    });

    test('can sync user roles', function (): void {
        $user = UserFactory::new()->createOne();
        $role1 = RoleFactory::new()->createOne(['name' => 'doctor']);
        $role2 = RoleFactory::new()->createOne(['name' => 'admin']);
        $role3 = RoleFactory::new()->createOne(['name' => 'nurse']);

        $user->assignRole([$role1, $role2]);

        $user->syncRoles([$role2, $role3]);

        Assert::assertFalse($user->hasRole('doctor'));
        Assert::assertTrue($user->hasRole('admin'));
        Assert::assertTrue($user->hasRole('nurse'));
        Assert::assertCount(2, $user->getRoleNames());
    });

    test('can check user permissions', function (): void {
        $user = UserFactory::new()->createOne();
        $role = RoleFactory::new()->createOne(['name' => 'doctor']);
        $permission = PermissionFactory::new()->createOne(['name' => 'patients.read']);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        $role->givePermissionTo($permission);
        $user->assignRole($role);

<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertTrue($user->hasPermissionTo('patients.read'));
        Assert::assertTrue($user->hasPermissionTo($permission));
        Assert::assertTrue($user->can('patients.read'));
    });

    test('can assign direct permission to user', function (): void {
        $user = UserFactory::new()->createOne();
        $permission = PermissionFactory::new()->createOne(['name' => 'special.permission']);

        $user->givePermissionTo($permission);

        Assert::assertTrue($user->hasPermissionTo('special.permission'));
        Assert::assertTrue($user->hasPermissionTo($permission));
        Assert::assertTrue($user->can('special.permission'));
    });

    test('can revoke direct permission from user', function (): void {
        $user = UserFactory::new()->createOne();
        $permission = PermissionFactory::new()->createOne(['name' => 'special.permission']);
        $user->givePermissionTo($permission);

        $user->revokePermissionTo($permission);

        Assert::assertFalse($user->hasPermissionTo('special.permission'));
        Assert::assertFalse($user->hasPermissionTo($permission));
        Assert::assertFalse($user->can('special.permission'));
    });

    test('can check user has any role', function (): void {
        $user = UserFactory::new()->createOne();
        $role1 = RoleFactory::new()->createOne(['name' => 'doctor']);
        $role2 = RoleFactory::new()->createOne(['name' => 'nurse']);

        $user->assignRole($role1);

        Assert::assertTrue($user->hasAnyRole(['doctor', 'nurse']));
        Assert::assertFalse($user->hasAnyRole(['nurse', 'admin']));
        Assert::assertFalse($user->hasAnyRole(['admin', 'super-admin']));
    });

    test('can check user has all roles', function (): void {
        $user = UserFactory::new()->createOne();
        $role1 = RoleFactory::new()->createOne(['name' => 'doctor']);
        $role2 = RoleFactory::new()->createOne(['name' => 'admin']);

        $user->assignRole([$role1, $role2]);

        Assert::assertTrue($user->hasAllRoles(['doctor', 'admin']));
        Assert::assertFalse($user->hasAllRoles(['doctor', 'nurse']));
    });

    test('can get user permissions', function (): void {
        $user = UserFactory::new()->createOne();
        $role = RoleFactory::new()->createOne(['name' => 'doctor']);
        $permission1 = PermissionFactory::new()->createOne(['name' => 'patients.read']);
        $permission2 = PermissionFactory::new()->createOne(['name' => 'patients.write']);
=======
=======
>>>>>>> 87273113 (.)
        // Act & Assert
        $this->assertTrue($user->hasPermissionTo('patients.read'));
        $this->assertTrue($user->hasPermissionTo($permission));
        $this->assertTrue($user->can('patients.read'));
    }

    /** @test */
    public function it_can_assign_direct_permission_to_user(): void
    {
        // Arrange
        $user = User::factory()->create();
        $permission = Permission::factory()->create(['name' => 'special.permission']);

        // Act
        $user->givePermissionTo($permission);

        // Assert
        $this->assertTrue($user->hasPermissionTo('special.permission'));
        $this->assertTrue($user->hasPermissionTo($permission));
        $this->assertTrue($user->can('special.permission'));
    }

    /** @test */
    public function it_can_revoke_direct_permission_from_user(): void
    {
        // Arrange
        $user = User::factory()->create();
        $permission = Permission::factory()->create(['name' => 'special.permission']);
        $user->givePermissionTo($permission);

        // Act
        $user->revokePermissionTo($permission);

        // Assert
        $this->assertFalse($user->hasPermissionTo('special.permission'));
        $this->assertFalse($user->hasPermissionTo($permission));
        $this->assertFalse($user->can('special.permission'));
    }

    /** @test */
    public function it_can_check_user_has_any_role(): void
    {
        // Arrange
        $user = User::factory()->create();
        $role1 = Role::factory()->create(['name' => 'doctor']);
        $role2 = Role::factory()->create(['name' => 'nurse']);

        $user->assignRole($role1);

        // Act & Assert
        $this->assertTrue($user->hasAnyRole(['doctor', 'nurse']));
        $this->assertTrue($user->hasAnyRole(['nurse', 'admin']));
        $this->assertFalse($user->hasAnyRole(['nurse', 'admin']));
    }

    /** @test */
    public function it_can_check_user_has_all_roles(): void
    {
        // Arrange
        $user = User::factory()->create();
        $role1 = Role::factory()->create(['name' => 'doctor']);
        $role2 = Role::factory()->create(['name' => 'admin']);

        $user->assignRole([$role1, $role2]);

        // Act & Assert
        $this->assertTrue($user->hasAllRoles(['doctor', 'admin']));
        $this->assertFalse($user->hasAllRoles(['doctor', 'nurse']));
    }

    /** @test */
    public function it_can_get_user_permissions(): void
    {
        // Arrange
        $user = User::factory()->create();
        $role = Role::factory()->create(['name' => 'doctor']);
        $permission1 = Permission::factory()->create(['name' => 'patients.read']);
        $permission2 = Permission::factory()->create(['name' => 'patients.write']);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        Assert::assertTrue($user->hasPermissionTo('patients.read'));
        Assert::assertTrue($user->hasPermissionTo($permission));
        Assert::assertTrue($user->can('patients.read'));
    });

    test('can assign direct permission to user', function (): void {
        $user = UserFactory::new()->createOne();
        $permission = PermissionFactory::new()->createOne(['name' => 'special.permission']);

        $user->givePermissionTo($permission);

        Assert::assertTrue($user->hasPermissionTo('special.permission'));
        Assert::assertTrue($user->hasPermissionTo($permission));
        Assert::assertTrue($user->can('special.permission'));
    });

    test('can revoke direct permission from user', function (): void {
        $user = UserFactory::new()->createOne();
        $permission = PermissionFactory::new()->createOne(['name' => 'special.permission']);
        $user->givePermissionTo($permission);

        $user->revokePermissionTo($permission);

        Assert::assertFalse($user->hasPermissionTo('special.permission'));
        Assert::assertFalse($user->hasPermissionTo($permission));
        Assert::assertFalse($user->can('special.permission'));
    });

    test('can check user has any role', function (): void {
        $user = UserFactory::new()->createOne();
        $role1 = RoleFactory::new()->createOne(['name' => 'doctor']);
        $role2 = RoleFactory::new()->createOne(['name' => 'nurse']);

        $user->assignRole($role1);

        Assert::assertTrue($user->hasAnyRole(['doctor', 'nurse']));
        Assert::assertFalse($user->hasAnyRole(['nurse', 'admin']));
        Assert::assertFalse($user->hasAnyRole(['admin', 'super-admin']));
    });

    test('can check user has all roles', function (): void {
        $user = UserFactory::new()->createOne();
        $role1 = RoleFactory::new()->createOne(['name' => 'doctor']);
        $role2 = RoleFactory::new()->createOne(['name' => 'admin']);

        $user->assignRole([$role1, $role2]);

        Assert::assertTrue($user->hasAllRoles(['doctor', 'admin']));
        Assert::assertFalse($user->hasAllRoles(['doctor', 'nurse']));
    });

    test('can get user permissions', function (): void {
        $user = UserFactory::new()->createOne();
        $role = RoleFactory::new()->createOne(['name' => 'doctor']);
        $permission1 = PermissionFactory::new()->createOne(['name' => 'patients.read']);
        $permission2 = PermissionFactory::new()->createOne(['name' => 'patients.write']);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        $role->givePermissionTo([$permission1, $permission2]);
        $user->assignRole($role);

<<<<<<< HEAD
<<<<<<< HEAD
        $permissions = $user->getAllPermissions();

        Assert::assertCount(2, $permissions);
        Assert::assertTrue($permissions->contains($permission1));
        Assert::assertTrue($permissions->contains($permission2));
    });

    test('can get user roles', function (): void {
        $user = UserFactory::new()->createOne();
        $role1 = RoleFactory::new()->createOne(['name' => 'doctor']);
        $role2 = RoleFactory::new()->createOne(['name' => 'admin']);

        $user->assignRole([$role1, $role2]);

        $roles = $user->getRoleNames();

        Assert::assertCount(2, $roles);
        Assert::assertStringContainsString((string) 'doctor', (string) $roles);
        Assert::assertStringContainsString((string) 'admin', (string) $roles);
    });

    test('can check user is super admin', function (): void {
        $user = UserFactory::new()->createOne();
        $superAdminRole = RoleFactory::new()->createOne(['name' => 'super-admin']);

        $user->assignRole($superAdminRole);

        Assert::assertTrue($user->hasRole('super-admin'));
        Assert::assertTrue($user->isSuperAdmin());
    });

    test('can update user profile', function (): void {
        $user = UserFactory::new()->createOne();
        $createdProfile = ProfileFactory::new()->createOne([
            'user_id' => $user->id,
            'phone' => '+39 123 456 7890',
            'address' => 'Via Roma 123, Milano',
        ]);
        Assert::assertInstanceOf(Profile::class, $createdProfile);
        $profile = $createdProfile;
=======
=======
>>>>>>> 87273113 (.)
        // Act
        $permissions = $user->getAllPermissions();

        // Assert
        $this->assertCount(2, $permissions);
        $this->assertTrue($permissions->contains($permission1));
        $this->assertTrue($permissions->contains($permission2));
    }

    /** @test */
    public function it_can_get_user_roles(): void
    {
        // Arrange
        $user = User::factory()->create();
        $role1 = Role::factory()->create(['name' => 'doctor']);
        $role2 = Role::factory()->create(['name' => 'admin']);

        $user->assignRole([$role1, $role2]);

        // Act
        $roles = $user->getRoleNames();

        // Assert
        $this->assertCount(2, $roles);
        $this->assertContains('doctor', $roles);
        $this->assertContains('admin', $roles);
    }

    /** @test */
    public function it_can_check_user_is_super_admin(): void
    {
        // Arrange
        $user = User::factory()->create();
        $superAdminRole = Role::factory()->create(['name' => 'super-admin']);

        $user->assignRole($superAdminRole);

        // Act & Assert
        $this->assertTrue($user->hasRole('super-admin'));
        $this->assertTrue($user->isSuperAdmin());
    }

    /** @test */
    public function it_can_check_user_is_admin(): void
    {
        // Arrange
        $user = User::factory()->create();
        $adminRole = Role::factory()->create(['name' => 'admin']);

        $user->assignRole($adminRole);

        // Act & Assert
        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->isAdmin());
    }

    /** @test */
    public function it_can_check_user_is_doctor(): void
    {
        // Arrange
        $user = User::factory()->create();
        $doctorRole = Role::factory()->create(['name' => 'doctor']);

        $user->assignRole($doctorRole);

        // Act & Assert
        $this->assertTrue($user->hasRole('doctor'));
        $this->assertTrue($user->isDoctor());
    }

    /** @test */
    public function it_can_check_user_is_patient(): void
    {
        // Arrange
        $user = User::factory()->create();
        $patientRole = Role::factory()->create(['name' => 'patient']);

        $user->assignRole($patientRole);

        // Act & Assert
        $this->assertTrue($user->hasRole('patient'));
        $this->assertTrue($user->isPatient());
    }

    /** @test */
    public function it_can_update_user_profile(): void
    {
        // Arrange
        $user = User::factory()->create();
        $profile = $user->profile()->create([
            'phone' => '+39 123 456 7890',
            'address' => 'Via Roma 123, Milano',
        ]);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        $permissions = $user->getAllPermissions();

        Assert::assertCount(2, $permissions);
        Assert::assertTrue($permissions->contains($permission1));
        Assert::assertTrue($permissions->contains($permission2));
    });

    test('can get user roles', function (): void {
        $user = UserFactory::new()->createOne();
        $role1 = RoleFactory::new()->createOne(['name' => 'doctor']);
        $role2 = RoleFactory::new()->createOne(['name' => 'admin']);

        $user->assignRole([$role1, $role2]);

        $roles = $user->getRoleNames();

        Assert::assertCount(2, $roles);
        Assert::assertStringContainsString((string) 'doctor', (string) $roles);
        Assert::assertStringContainsString((string) 'admin', (string) $roles);
    });

    test('can check user is super admin', function (): void {
        $user = UserFactory::new()->createOne();
        $superAdminRole = RoleFactory::new()->createOne(['name' => 'super-admin']);

        $user->assignRole($superAdminRole);

        Assert::assertTrue($user->hasRole('super-admin'));
        Assert::assertTrue($user->isSuperAdmin());
    });

    test('can update user profile', function (): void {
        $user = UserFactory::new()->createOne();
        $createdProfile = ProfileFactory::new()->createOne([
            'user_id' => $user->id,
            'phone' => '+39 123 456 7890',
            'address' => 'Via Roma 123, Milano',
        ]);
        Assert::assertInstanceOf(Profile::class, $createdProfile);
        $profile = $createdProfile;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        $updatedData = [
            'phone' => '+39 987 654 3210',
            'address' => 'Via Milano 456, Roma',
            'birth_date' => '1985-10-20',
        ];

<<<<<<< HEAD
<<<<<<< HEAD
        $profile->update($updatedData);

        Assert::assertTrue(DB::table('profiles')->where([
=======
=======
>>>>>>> 87273113 (.)
        // Act
        $profile->update($updatedData);

        // Assert
        $this->assertDatabaseHas('profiles', [
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        $profile->update($updatedData);

        Assert::assertTrue(DB::table('profiles')->where([
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'id' => $profile->id,
            'phone' => '+39 987 654 3210',
            'address' => 'Via Milano 456, Roma',
            'birth_date' => '1985-10-20',
<<<<<<< HEAD
<<<<<<< HEAD
        ])->exists());
    });

    test('can delete user with profile', function (): void {
        $user = UserFactory::new()->createOne();
        $createdProfile = ProfileFactory::new()->createOne([
            'user_id' => $user->id,
            'phone' => '+39 123 456 7890',
        ]);
        Assert::assertInstanceOf(Profile::class, $createdProfile);
        $profile = $createdProfile;

        $profile->forceDelete();
        $user->forceDelete();

        Assert::assertFalse(DB::table('users')->where(['id' => $user->id])->exists());
        Assert::assertFalse(DB::table('profiles')->where(['id' => $profile->id])->exists());
    });

    test('can soft delete user', function (): void {
        /* @var TestCase $this */
        $this->skipTest('User model does not use SoftDeletes.');
    });

    test('can restore soft deleted user', function (): void {
        /* @var TestCase $this */
        $this->skipTest('User model does not use SoftDeletes.');
    });

    test('can force delete user', function (): void {
        $user = UserFactory::new()->createOne();
        $createdProfile = ProfileFactory::new()->createOne([
            'user_id' => $user->id,
            'phone' => '+39 123 456 7890',
        ]);
        Assert::assertInstanceOf(Profile::class, $createdProfile);
        $profile = $createdProfile;

        $profile->forceDelete();
        $user->forceDelete();

        Assert::assertFalse(DB::table('users')->where(['id' => $user->id])->exists());
        Assert::assertFalse(DB::table('profiles')->where(['id' => $profile->id])->exists());
    });

    test('can search users by name', function (): void {
        $user1 = UserFactory::new()->createOne(['name' => 'Mario Rossi']);
        $user2 = UserFactory::new()->createOne(['name' => 'Giulia Bianchi']);
        $user3 = UserFactory::new()->createOne(['name' => 'Marco Rossi']);

        $results = User::where('name', 'like', '%Rossi%')->get();

        Assert::assertCount(2, $results);
        Assert::assertTrue($results->contains($user1));
        Assert::assertTrue($results->contains($user3));
        Assert::assertFalse($results->contains($user2));
    });

    test('can search users by email', function (): void {
        $user1 = UserFactory::new()->createOne(['email' => 'mario@example.com']);
        $user2 = UserFactory::new()->createOne(['email' => 'giulia@test.com']);
        $user3 = UserFactory::new()->createOne(['email' => 'marco@example.org']);

        $results = User::where('email', 'like', '%@example%')->get();

        Assert::assertCount(2, $results);
        Assert::assertTrue($results->contains($user1));
        Assert::assertTrue($results->contains($user3));
        Assert::assertFalse($results->contains($user2));
    });

    test('can filter users by role', function (): void {
        $doctorRole = RoleFactory::new()->createOne(['name' => 'doctor']);
        $nurseRole = RoleFactory::new()->createOne(['name' => 'nurse']);

        $user1 = UserFactory::new()->createOne();
        $user2 = UserFactory::new()->createOne();
        $user3 = UserFactory::new()->createOne();
=======
=======
>>>>>>> 87273113 (.)
        ]);
    }

    /** @test */
    public function it_can_delete_user_with_profile(): void
    {
        // Arrange
        $user = User::factory()->create();
        $profile = $user->profile()->create([
            'phone' => '+39 123 456 7890',
        ]);

        // Act
        $user->delete();

        // Assert
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('profiles', ['id' => $profile->id]);
    }

    /** @test */
    public function it_can_soft_delete_user(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $user->delete();

        // Assert
        $this->assertSoftDeleted('users', ['id' => $user->id]);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    /** @test */
    public function it_can_restore_soft_deleted_user(): void
    {
        // Arrange
        $user = User::factory()->create();
        $user->delete();

        // Act
        $user->restore();

        // Assert
        $this->assertNotSoftDeleted('users', ['id' => $user->id]);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    /** @test */
    public function it_can_force_delete_user(): void
    {
        // Arrange
        $user = User::factory()->create();
        $profile = $user->profile()->create([
            'phone' => '+39 123 456 7890',
        ]);

        // Act
        $user->forceDelete();

        // Assert
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('profiles', ['id' => $profile->id]);
    }

    /** @test */
    public function it_can_search_users_by_name(): void
    {
        // Arrange
        $user1 = User::factory()->create(['name' => 'Mario Rossi']);
        $user2 = User::factory()->create(['name' => 'Giulia Bianchi']);
        $user3 = User::factory()->create(['name' => 'Marco Rossi']);

        // Act
        $results = User::where('name', 'like', '%Rossi%')->get();

        // Assert
        $this->assertCount(2, $results);
        $this->assertTrue($results->contains($user1));
        $this->assertTrue($results->contains($user3));
        $this->assertFalse($results->contains($user2));
    }

    /** @test */
    public function it_can_search_users_by_email(): void
    {
        // Arrange
        $user1 = User::factory()->create(['email' => 'mario@example.com']);
        $user2 = User::factory()->create(['email' => 'giulia@test.com']);
        $user3 = User::factory()->create(['email' => 'marco@example.org']);

        // Act
        $results = User::where('email', 'like', '%@example%')->get();

        // Assert
        $this->assertCount(2, $results);
        $this->assertTrue($results->contains($user1));
        $this->assertTrue($results->contains($user3));
        $this->assertFalse($results->contains($user2));
    }

    /** @test */
    public function it_can_filter_users_by_role(): void
    {
        // Arrange
        $doctorRole = Role::factory()->create(['name' => 'doctor']);
        $nurseRole = Role::factory()->create(['name' => 'nurse']);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        ])->exists());
    });

    test('can delete user with profile', function (): void {
        $user = UserFactory::new()->createOne();
        $createdProfile = ProfileFactory::new()->createOne([
            'user_id' => $user->id,
            'phone' => '+39 123 456 7890',
        ]);
        Assert::assertInstanceOf(Profile::class, $createdProfile);
        $profile = $createdProfile;

        $profile->forceDelete();
        $user->forceDelete();

        Assert::assertFalse(DB::table('users')->where(['id' => $user->id])->exists());
        Assert::assertFalse(DB::table('profiles')->where(['id' => $profile->id])->exists());
    });

    test('can soft delete user', function (): void {
        /* @var TestCase $this */
        $this->skipTest('User model does not use SoftDeletes.');
    });

    test('can restore soft deleted user', function (): void {
        /* @var TestCase $this */
        $this->skipTest('User model does not use SoftDeletes.');
    });

    test('can force delete user', function (): void {
        $user = UserFactory::new()->createOne();
        $createdProfile = ProfileFactory::new()->createOne([
            'user_id' => $user->id,
            'phone' => '+39 123 456 7890',
        ]);
        Assert::assertInstanceOf(Profile::class, $createdProfile);
        $profile = $createdProfile;

        $profile->forceDelete();
        $user->forceDelete();

        Assert::assertFalse(DB::table('users')->where(['id' => $user->id])->exists());
        Assert::assertFalse(DB::table('profiles')->where(['id' => $profile->id])->exists());
    });

    test('can search users by name', function (): void {
        $user1 = UserFactory::new()->createOne(['name' => 'Mario Rossi']);
        $user2 = UserFactory::new()->createOne(['name' => 'Giulia Bianchi']);
        $user3 = UserFactory::new()->createOne(['name' => 'Marco Rossi']);

        $results = User::where('name', 'like', '%Rossi%')->get();

        Assert::assertCount(2, $results);
        Assert::assertTrue($results->contains($user1));
        Assert::assertTrue($results->contains($user3));
        Assert::assertFalse($results->contains($user2));
    });

    test('can search users by email', function (): void {
        $user1 = UserFactory::new()->createOne(['email' => 'mario@example.com']);
        $user2 = UserFactory::new()->createOne(['email' => 'giulia@test.com']);
        $user3 = UserFactory::new()->createOne(['email' => 'marco@example.org']);

        $results = User::where('email', 'like', '%@example%')->get();

        Assert::assertCount(2, $results);
        Assert::assertTrue($results->contains($user1));
        Assert::assertTrue($results->contains($user3));
        Assert::assertFalse($results->contains($user2));
    });

    test('can filter users by role', function (): void {
        $doctorRole = RoleFactory::new()->createOne(['name' => 'doctor']);
        $nurseRole = RoleFactory::new()->createOne(['name' => 'nurse']);

        $user1 = UserFactory::new()->createOne();
        $user2 = UserFactory::new()->createOne();
        $user3 = UserFactory::new()->createOne();
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        $user1->assignRole($doctorRole);
        $user2->assignRole($nurseRole);
        $user3->assignRole($doctorRole);

<<<<<<< HEAD
<<<<<<< HEAD
        $doctors = User::role('doctor')->get();

        Assert::assertCount(2, $doctors);
        Assert::assertTrue($doctors->contains($user1));
        Assert::assertTrue($doctors->contains($user3));
        Assert::assertFalse($doctors->contains($user2));
    });

    test('can filter users by permission', function (): void {
        $role = RoleFactory::new()->createOne(['name' => 'doctor']);
        $permission = PermissionFactory::new()->createOne(['name' => 'patients.read']);

        $role->givePermissionTo($permission);

        $user1 = UserFactory::new()->createOne();
        $user2 = UserFactory::new()->createOne();

        $user1->assignRole($role);

        $usersWithPermission = User::permission('patients.read')->get();

        Assert::assertCount(1, $usersWithPermission);
        Assert::assertTrue($usersWithPermission->contains($user1));
        Assert::assertFalse($usersWithPermission->contains($user2));
    });

    test('can get users with roles and permissions', function (): void {
        $role = RoleFactory::new()->createOne(['name' => 'doctor']);
        $permission = PermissionFactory::new()->createOne(['name' => 'patients.read']);

        $role->givePermissionTo($permission);

        $user = UserFactory::new()->createOne();
        $user->assignRole($role);

        $userWithRelations = User::with(['roles', 'permissions'])->find($user->id);

        Assert::assertNotNull($userWithRelations);
        Assert::assertTrue($userWithRelations->relationLoaded('roles'));
        Assert::assertTrue($userWithRelations->relationLoaded('permissions'));
        Assert::assertCount(1, $userWithRelations->roles);
        Assert::assertCount(1, $userWithRelations->getAllPermissions());
    });

    test('can validate user email uniqueness', function (): void {
        /* @var TestCase $this */
        UserFactory::new()->createOne(['email' => 'test@example.com']);

        try {
            User::create([
                'name' => 'Another User',
                'email' => 'test@example.com',
                'password' => Hash::make('password123'),
            ]);
            $this->fail('Expected QueryException was not thrown');
        } catch (QueryException $exception) {
            Assert::assertInstanceOf(QueryException::class, $exception);
        }
    });

    test('can handle user email verification', function (): void {
        $user = UserFactory::new()->createOne(['email_verified_at' => null]);

        $user->markEmailAsVerified();

        Assert::assertNotNull($user->email_verified_at);
        Assert::assertTrue($user->hasVerifiedEmail());
    });

    test('can handle user status changes', function (): void {
        $user = UserFactory::new()->createOne(['is_active' => true]);

        $user->update(['is_active' => false]);

        $freshModel2 = $user->fresh();
        Assert::assertNotNull($freshModel2);
        Assert::assertFalse($freshModel2->is_active);

        $user->update(['is_active' => true]);

        $freshModel3 = $user->fresh();
        Assert::assertNotNull($freshModel3);
        Assert::assertTrue($freshModel3->is_active);
    });

    test('can handle user info', function (): void {
        $user = UserFactory::new()->createOne();

        $user->update(['lang' => 'it']);

        Assert::assertTrue(DB::table('users')->where([
            'id' => $user->id,
            'lang' => 'it',
        ])->exists());
    });
});
=======
=======
>>>>>>> 87273113 (.)
        // Act
        $doctors = User::role('doctor')->get();

        // Assert
        $this->assertCount(2, $doctors);
        $this->assertTrue($doctors->contains($user1));
        $this->assertTrue($doctors->contains($user3));
        $this->assertFalse($doctors->contains($user2));
    }

    /** @test */
    public function it_can_filter_users_by_permission(): void
    {
        // Arrange
        $role = Role::factory()->create(['name' => 'doctor']);
        $permission = Permission::factory()->create(['name' => 'patients.read']);

        $role->givePermissionTo($permission);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $user1->assignRole($role);

        // Act
        $usersWithPermission = User::permission('patients.read')->get();

        // Assert
        $this->assertCount(1, $usersWithPermission);
        $this->assertTrue($usersWithPermission->contains($user1));
        $this->assertFalse($usersWithPermission->contains($user2));
    }

    /** @test */
    public function it_can_get_users_with_roles_and_permissions(): void
    {
        // Arrange
        $role = Role::factory()->create(['name' => 'doctor']);
        $permission = Permission::factory()->create(['name' => 'patients.read']);

        $role->givePermissionTo($permission);

        $user = User::factory()->create();
        $user->assignRole($role);

        // Act
        $userWithRelations = User::with(['roles', 'permissions'])->find($user->id);

        // Assert
        $this->assertNotNull($userWithRelations);
        $this->assertTrue($userWithRelations->relationLoaded('roles'));
        $this->assertTrue($userWithRelations->relationLoaded('permissions'));
        $this->assertCount(1, $userWithRelations->roles);
        $this->assertCount(1, $userWithRelations->permissions);
    }

    /** @test */
    public function it_can_validate_user_email_uniqueness(): void
    {
        // Arrange
        User::factory()->create(['email' => 'test@example.com']);

        // Act & Assert
        $this->expectException(QueryException::class);

        User::create([
            'name' => 'Another User',
            'email' => 'test@example.com', // Same email
            'password' => Hash::make('password123'),
        ]);
    }

    /** @test */
    public function it_can_validate_user_password_strength(): void
    {
        // Arrange
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'weak', // Weak password
        ];

        // Act & Assert
        $this->expectException(ValidationException::class);

        $this->post('/register', $userData);
    }

    /** @test */
    public function it_can_handle_user_password_reset(): void
    {
        // Arrange
        $user = User::factory()->create();
        $token = 'reset-token-123';

        // Act
        $user->update(['password_reset_token' => $token]);

        // Assert
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'password_reset_token' => $token,
        ]);
    }

    /** @test */
    public function it_can_handle_user_email_verification(): void
    {
        // Arrange
        $user = User::factory()->create(['email_verified_at' => null]);

        // Act
        $user->markEmailAsVerified();

        // Assert
        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue($user->hasVerifiedEmail());
    }

    /** @test */
    public function it_can_handle_user_last_login(): void
    {
        // Arrange
        $user = User::factory()->create();
        $lastLogin = now();

        // Act
        $user->update(['last_login_at' => $lastLogin]);

        // Assert
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'last_login_at' => $lastLogin,
        ]);
    }

    /** @test */
    public function it_can_handle_user_status_changes(): void
    {
        // Arrange
        $user = User::factory()->create(['status' => 'active']);

        // Act - Deactivate user
        $user->update(['status' => 'inactive']);

        // Assert
        $this->assertEquals('inactive', $user->fresh()->status);

        // Act - Activate user
        $user->update(['status' => 'active']);

        // Assert
        $this->assertEquals('active', $user->fresh()->status);
    }

    /** @test */
    public function it_can_handle_user_preferences(): void
    {
        // Arrange
        $user = User::factory()->create();
        $preferences = [
            'language' => 'it',
            'timezone' => 'Europe/Rome',
            'notifications' => true,
            'theme' => 'dark',
        ];

        // Act
        $user->update(['preferences' => $preferences]);

        // Assert
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'preferences' => json_encode($preferences),
        ]);

        $this->assertEquals('it', $user->fresh()->preferences['language']);
        $this->assertEquals('Europe/Rome', $user->fresh()->preferences['timezone']);
        $this->assertTrue($user->fresh()->preferences['notifications']);
        $this->assertEquals('dark', $user->fresh()->preferences['theme']);
    }
}
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        $doctors = User::role('doctor')->get();

        Assert::assertCount(2, $doctors);
        Assert::assertTrue($doctors->contains($user1));
        Assert::assertTrue($doctors->contains($user3));
        Assert::assertFalse($doctors->contains($user2));
    });

    test('can filter users by permission', function (): void {
        $role = RoleFactory::new()->createOne(['name' => 'doctor']);
        $permission = PermissionFactory::new()->createOne(['name' => 'patients.read']);

        $role->givePermissionTo($permission);

        $user1 = UserFactory::new()->createOne();
        $user2 = UserFactory::new()->createOne();

        $user1->assignRole($role);

        $usersWithPermission = User::permission('patients.read')->get();

        Assert::assertCount(1, $usersWithPermission);
        Assert::assertTrue($usersWithPermission->contains($user1));
        Assert::assertFalse($usersWithPermission->contains($user2));
    });

    test('can get users with roles and permissions', function (): void {
        $role = RoleFactory::new()->createOne(['name' => 'doctor']);
        $permission = PermissionFactory::new()->createOne(['name' => 'patients.read']);

        $role->givePermissionTo($permission);

        $user = UserFactory::new()->createOne();
        $user->assignRole($role);

        $userWithRelations = User::with(['roles', 'permissions'])->find($user->id);

        Assert::assertNotNull($userWithRelations);
        Assert::assertTrue($userWithRelations->relationLoaded('roles'));
        Assert::assertTrue($userWithRelations->relationLoaded('permissions'));
        Assert::assertCount(1, $userWithRelations->roles);
        Assert::assertCount(1, $userWithRelations->getAllPermissions());
    });

    test('can validate user email uniqueness', function (): void {
        /* @var TestCase $this */
        UserFactory::new()->createOne(['email' => 'test@example.com']);

        try {
            User::create([
                'name' => 'Another User',
                'email' => 'test@example.com',
                'password' => Hash::make('password123'),
            ]);
            $this->fail('Expected QueryException was not thrown');
        } catch (QueryException $exception) {
            Assert::assertInstanceOf(QueryException::class, $exception);
        }
    });

    test('can handle user email verification', function (): void {
        $user = UserFactory::new()->createOne(['email_verified_at' => null]);

        $user->markEmailAsVerified();

        Assert::assertNotNull($user->email_verified_at);
        Assert::assertTrue($user->hasVerifiedEmail());
    });

    test('can handle user status changes', function (): void {
        $user = UserFactory::new()->createOne(['is_active' => true]);

        $user->update(['is_active' => false]);

        $freshModel2 = $user->fresh();
        Assert::assertNotNull($freshModel2);
        Assert::assertFalse($freshModel2->is_active);

        $user->update(['is_active' => true]);

        $freshModel3 = $user->fresh();
        Assert::assertNotNull($freshModel3);
        Assert::assertTrue($freshModel3->is_active);
    });

    test('can handle user info', function (): void {
        $user = UserFactory::new()->createOne();

        $user->update(['lang' => 'it']);

        Assert::assertTrue(DB::table('users')->where([
            'id' => $user->id,
            'lang' => 'it',
        ])->exists());
    });
});
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
