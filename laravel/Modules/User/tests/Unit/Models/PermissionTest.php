<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\Facades\DB;
use Modules\User\Database\Factories\PermissionFactory;
use Modules\User\Models\Permission;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/**
 * @param  array<string, mixed>  $attributes
 */
function modelsPermissionCreate(array $attributes = []): Permission
{
    return PermissionFactory::new()->createOne(array_merge([
        'name' => 'models-permission-'.uniqid(),
        'guard_name' => 'web',
    ], $attributes));
}

test('can create permission with minimal data', function (): void {
    $name = 'test.permission-'.uniqid();
    $permission = modelsPermissionCreate(['name' => $name]);

    Assert::assertInstanceOf(Permission::class, $permission);
    Assert::assertNotNull($permission->id);
    Assert::assertSame($name, $permission->name);
    Assert::assertSame('web', $permission->guard_name);
});

test('can create permission with all fields', function (): void {
    $name = 'full.permission-'.uniqid();
    $permission = modelsPermissionCreate(['name' => $name, 'guard_name' => 'web']);

    Permission::withoutEvents(static function () use ($permission): void {
        $permission->forceFill([
            'created_by' => 'user123',
            'updated_by' => 'user456',
        ])->save();
    });
    $permission->refresh();

    Assert::assertSame($name, $permission->name);
    Assert::assertSame('web', $permission->guard_name);
    Assert::assertSame('user123', $permission->created_by);
    Assert::assertSame('user456', $permission->updated_by);
});

test('permission has connection attribute', function (): void {
    Assert::assertSame('user', (new Permission)->getConnectionName());
});

test('permission has fillable attributes', function (): void {
    $fillable = (new Permission)->getFillable();

    Assert::assertContains('name', $fillable);
    Assert::assertContains('guard_name', $fillable);
});

test('permission has casts', function (): void {
    $casts = (new Permission)->getCasts();

    Assert::assertArrayHasKey('id', $casts);
    Assert::assertSame('int', $casts['id']);
});

test('can find permission by name', function (): void {
    $name = 'unique.permission-'.uniqid();
    $permission = modelsPermissionCreate(['name' => $name]);
    $foundPermission = Permission::where('name', $name)->first();

    Assert::assertInstanceOf(Permission::class, $foundPermission);
    Assert::assertSame($permission->id, $foundPermission->id);
});

test('can find permission by guard name', function (): void {
    $marker = uniqid();
    modelsPermissionCreate(['guard_name' => 'web', 'name' => "web-{$marker}-1"]);
    modelsPermissionCreate(['guard_name' => 'api', 'name' => "api-{$marker}-1"]);
    modelsPermissionCreate(['guard_name' => 'web', 'name' => "web-{$marker}-2"]);

    $webPermissions = Permission::where('guard_name', 'web')
        ->where('name', 'like', "%{$marker}%")
        ->get();

    Assert::assertGreaterThanOrEqual(2, $webPermissions->count());
    foreach ($webPermissions as $webPermission) {
        Assert::assertInstanceOf(Permission::class, $webPermission);
        Assert::assertSame('web', $webPermission->guard_name);
    }
});

test('can find permission by created by', function (): void {
    $createdBy = 'user123-'.uniqid();
    $permission = modelsPermissionCreate();

    Permission::withoutEvents(static function () use ($permission, $createdBy): void {
        $permission->forceFill(['created_by' => $createdBy])->save();
    });

    $foundPermission = Permission::where('created_by', $createdBy)->first();

    Assert::assertInstanceOf(Permission::class, $foundPermission);
    Assert::assertSame($permission->id, $foundPermission->id);
});

test('can find permission by updated by', function (): void {
    $updatedBy = 'user456-'.uniqid();
    $permission = modelsPermissionCreate();

    Permission::withoutEvents(static function () use ($permission, $updatedBy): void {
        $permission->forceFill(['updated_by' => $updatedBy])->save();
    });

    $foundPermission = Permission::where('updated_by', $updatedBy)->first();

    Assert::assertInstanceOf(Permission::class, $foundPermission);
    Assert::assertSame($permission->id, $foundPermission->id);
});

test('can find permissions by name pattern', function (): void {
    $marker = uniqid();
    modelsPermissionCreate(['name' => "user.create-{$marker}"]);
    modelsPermissionCreate(['name' => "user.update-{$marker}"]);
    modelsPermissionCreate(['name' => "user.delete-{$marker}"]);
    modelsPermissionCreate(['name' => "post.read-{$marker}"]);

    $userPermissions = Permission::where('name', 'like', "user.%-{$marker}")->get();

    Assert::assertGreaterThanOrEqual(3, $userPermissions->count());
    foreach ($userPermissions as $userPermission) {
        Assert::assertInstanceOf(Permission::class, $userPermission);
        Assert::assertStringStartsWith('user.', $userPermission->name);
    }
});

test('can update permission', function (): void {
    $oldName = 'old.permission-'.uniqid();
    $newName = 'new.permission-'.uniqid();
    $permission = modelsPermissionCreate(['name' => $oldName]);

    $permission->update(['name' => $newName]);
    $fresh = $permission->fresh();

    Assert::assertInstanceOf(Permission::class, $fresh);
    Assert::assertSame($newName, $fresh->name);
});

test('can handle null values', function (): void {
    $permission = modelsPermissionCreate();

    Permission::withoutEvents(static function () use ($permission): void {
        $permission->forceFill([
            'created_by' => null,
            'updated_by' => null,
        ])->save();
    });
    $permission->refresh();

    Assert::assertNull($permission->created_by);
    Assert::assertNull($permission->updated_by);
});

test('can find permissions by multiple criteria', function (): void {
    $marker = uniqid();
    $first = modelsPermissionCreate([
        'name' => "admin.user.create-{$marker}",
        'guard_name' => 'web',
    ]);
    $second = modelsPermissionCreate([
        'name' => "admin.user.update-{$marker}",
        'guard_name' => 'api',
    ]);

    Permission::withoutEvents(static function () use ($first, $second): void {
        $first->forceFill(['created_by' => 'admin'])->save();
        $second->forceFill(['created_by' => 'admin'])->save();
    });

    $permissions = Permission::where('name', 'like', "admin.user.%-{$marker}")
        ->where('created_by', 'admin')
        ->get();

    Assert::assertGreaterThanOrEqual(2, $permissions->count());
    foreach ($permissions as $permission) {
        Assert::assertInstanceOf(Permission::class, $permission);
        Assert::assertStringStartsWith('admin.user.', $permission->name);
        Assert::assertSame('admin', $permission->created_by);
    }
});

test('permission has table name', function (): void {
    Assert::assertNotSame('', (new Permission)->getTable());
});

test('permission can be deleted from database', function (): void {
    $permission = modelsPermissionCreate();
    $permissionId = $permission->id;

    DB::connection('user')->table($permission->getTable())->where('id', $permissionId)->delete();

    Assert::assertNull(Permission::query()->find($permissionId));
});
=======
=======
>>>>>>> 87273113 (.)
namespace Modules\User\Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\Permission;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_permission_with_minimal_data(): void
    {
        $permission = Permission::factory()->create([
            'name' => 'test.permission',
            'guard_name' => 'web',
        ]);

        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id,
            'name' => 'test.permission',
            'guard_name' => 'web',
        ]);
    }

    public function test_can_create_permission_with_all_fields(): void
    {
        $permissionData = [
            'name' => 'full.permission',
            'guard_name' => 'web',
            'created_by' => 'user123',
            'updated_by' => 'user456',
        ];

        $permission = Permission::factory()->create($permissionData);

        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id,
            'name' => 'full.permission',
            'guard_name' => 'web',
            'created_by' => 'user123',
            'updated_by' => 'user456',
        ]);
    }

    public function test_permission_has_connection_attribute(): void
    {
        $permission = new Permission();

        static::assertSame('user', $permission->connection);
    }

    public function test_permission_has_key_type_attribute(): void
    {
        $permission = new Permission();

        static::assertSame('string', $permission->keyType);
    }

    public function test_permission_has_fillable_attributes(): void
    {
        $permission = new Permission();

        $expectedFillable = [
            'id',
            'name',
            'guard_name',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
        ];

        static::assertSame($expectedFillable, $permission->getFillable());
    }

    public function test_permission_has_casts(): void
    {
        $permission = new Permission();

        $expectedCasts = [
            'id' => 'string',
            'uuid' => 'string',
            'name' => 'string',
            'guard_name' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];

        static::assertSame($expectedCasts, $permission->getCasts());
    }

    public function test_can_find_permission_by_name(): void
    {
        $permission = Permission::factory()->create(['name' => 'unique.permission']);

        $foundPermission = Permission::where('name', 'unique.permission')->first();

        static::assertNotNull($foundPermission);
        static::assertSame($permission->id, $foundPermission->id);
    }

    public function test_can_find_permission_by_guard_name(): void
    {
        Permission::factory()->create(['guard_name' => 'web']);
        Permission::factory()->create(['guard_name' => 'api']);
        Permission::factory()->create(['guard_name' => 'web']);

        $webPermissions = Permission::where('guard_name', 'web')->get();

        static::assertCount(2, $webPermissions);
        static::assertTrue($webPermissions->every(fn($permission) => $permission->guard_name === 'web'));
    }

    public function test_can_find_permission_by_created_by(): void
    {
        $permission = Permission::factory()->create(['created_by' => 'user123']);

        $foundPermission = Permission::where('created_by', 'user123')->first();

        static::assertNotNull($foundPermission);
        static::assertSame($permission->id, $foundPermission->id);
    }

    public function test_can_find_permission_by_updated_by(): void
    {
        $permission = Permission::factory()->create(['updated_by' => 'user456']);

        $foundPermission = Permission::where('updated_by', 'user456')->first();

        static::assertNotNull($foundPermission);
        static::assertSame($permission->id, $foundPermission->id);
    }

    public function test_can_find_permissions_by_name_pattern(): void
    {
        Permission::factory()->create(['name' => 'user.create']);
        Permission::factory()->create(['name' => 'user.update']);
        Permission::factory()->create(['name' => 'user.delete']);
        Permission::factory()->create(['name' => 'post.read']);

        $userPermissions = Permission::where('name', 'like', 'user.%')->get();

        static::assertCount(3, $userPermissions);
        static::assertTrue($userPermissions->every(fn($permission) => str_starts_with($permission->name, 'user.')));
    }

    public function test_can_update_permission(): void
    {
        $permission = Permission::factory()->create(['name' => 'old.permission']);

        $permission->update(['name' => 'new.permission']);

        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id,
            'name' => 'new.permission',
        ]);
    }

    public function test_can_handle_null_values(): void
    {
        $permission = Permission::factory()->create([
            'name' => 'test.permission',
            'guard_name' => 'web',
            'created_by' => null,
            'updated_by' => null,
        ]);

        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id,
            'created_by' => null,
            'updated_by' => null,
        ]);
    }

    public function test_can_find_permissions_by_multiple_criteria(): void
    {
        Permission::factory()->create([
            'name' => 'admin.user.create',
            'guard_name' => 'web',
            'created_by' => 'admin',
        ]);

        Permission::factory()->create([
            'name' => 'admin.user.update',
            'guard_name' => 'api',
            'created_by' => 'admin',
        ]);

        $permissions = Permission::where('name', 'like', 'admin.user.%')->where('created_by', 'admin')->get();

        static::assertCount(2, $permissions);
        static::assertTrue($permissions->every(
            fn($permission) => str_starts_with($permission->name, 'admin.user.') && $permission->created_by === 'admin',
        ));
    }

    public function test_permission_has_roles_relationship(): void
    {
        $permission = Permission::factory()->create();

        static::assertTrue(method_exists($permission, 'roles'));
    }

    public function test_permission_has_users_relationship(): void
    {
        $permission = Permission::factory()->create();

        static::assertTrue(method_exists($permission, 'users'));
    }

    public function test_permission_can_use_role_scopes(): void
    {
        $permission = Permission::factory()->create();

        static::assertTrue(method_exists($permission, 'role'));
    }

    public function test_permission_can_use_permission_scopes(): void
    {
        $permission = Permission::factory()->create();

        static::assertTrue(method_exists($permission, 'permission'));
        static::assertTrue(method_exists($permission, 'withoutPermission'));
    }

    public function test_permission_can_use_without_role_scopes(): void
    {
        $permission = Permission::factory()->create();

        static::assertTrue(method_exists($permission, 'withoutRole'));
    }

    public function test_permission_has_factory_method(): void
    {
        $permission = new Permission();

        static::assertTrue(method_exists($permission, 'newFactory'));
    }

    public function test_permission_has_get_table_method(): void
    {
        $permission = new Permission();

        static::assertTrue(method_exists($permission, 'getTable'));
    }
}
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Support\Facades\DB;
use Modules\User\Database\Factories\PermissionFactory;
use Modules\User\Models\Permission;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/**
 * @param  array<string, mixed>  $attributes
 */
function modelsPermissionCreate(array $attributes = []): Permission
{
    return PermissionFactory::new()->createOne(array_merge([
        'name' => 'models-permission-'.uniqid(),
        'guard_name' => 'web',
    ], $attributes));
}

test('can create permission with minimal data', function (): void {
    $name = 'test.permission-'.uniqid();
    $permission = modelsPermissionCreate(['name' => $name]);

    Assert::assertInstanceOf(Permission::class, $permission);
    Assert::assertNotNull($permission->id);
    Assert::assertSame($name, $permission->name);
    Assert::assertSame('web', $permission->guard_name);
});

test('can create permission with all fields', function (): void {
    $name = 'full.permission-'.uniqid();
    $permission = modelsPermissionCreate(['name' => $name, 'guard_name' => 'web']);

    Permission::withoutEvents(static function () use ($permission): void {
        $permission->forceFill([
            'created_by' => 'user123',
            'updated_by' => 'user456',
        ])->save();
    });
    $permission->refresh();

    Assert::assertSame($name, $permission->name);
    Assert::assertSame('web', $permission->guard_name);
    Assert::assertSame('user123', $permission->created_by);
    Assert::assertSame('user456', $permission->updated_by);
});

test('permission has connection attribute', function (): void {
    Assert::assertSame('user', (new Permission)->getConnectionName());
});

test('permission has fillable attributes', function (): void {
    $fillable = (new Permission)->getFillable();

    Assert::assertContains('name', $fillable);
    Assert::assertContains('guard_name', $fillable);
});

test('permission has casts', function (): void {
    $casts = (new Permission)->getCasts();

    Assert::assertArrayHasKey('id', $casts);
    Assert::assertSame('int', $casts['id']);
});

test('can find permission by name', function (): void {
    $name = 'unique.permission-'.uniqid();
    $permission = modelsPermissionCreate(['name' => $name]);
    $foundPermission = Permission::where('name', $name)->first();

    Assert::assertInstanceOf(Permission::class, $foundPermission);
    Assert::assertSame($permission->id, $foundPermission->id);
});

test('can find permission by guard name', function (): void {
    $marker = uniqid();
    modelsPermissionCreate(['guard_name' => 'web', 'name' => "web-{$marker}-1"]);
    modelsPermissionCreate(['guard_name' => 'api', 'name' => "api-{$marker}-1"]);
    modelsPermissionCreate(['guard_name' => 'web', 'name' => "web-{$marker}-2"]);

    $webPermissions = Permission::where('guard_name', 'web')
        ->where('name', 'like', "%{$marker}%")
        ->get();

    Assert::assertGreaterThanOrEqual(2, $webPermissions->count());
    foreach ($webPermissions as $webPermission) {
        Assert::assertInstanceOf(Permission::class, $webPermission);
        Assert::assertSame('web', $webPermission->guard_name);
    }
});

test('can find permission by created by', function (): void {
    $createdBy = 'user123-'.uniqid();
    $permission = modelsPermissionCreate();

    Permission::withoutEvents(static function () use ($permission, $createdBy): void {
        $permission->forceFill(['created_by' => $createdBy])->save();
    });

    $foundPermission = Permission::where('created_by', $createdBy)->first();

    Assert::assertInstanceOf(Permission::class, $foundPermission);
    Assert::assertSame($permission->id, $foundPermission->id);
});

test('can find permission by updated by', function (): void {
    $updatedBy = 'user456-'.uniqid();
    $permission = modelsPermissionCreate();

    Permission::withoutEvents(static function () use ($permission, $updatedBy): void {
        $permission->forceFill(['updated_by' => $updatedBy])->save();
    });

    $foundPermission = Permission::where('updated_by', $updatedBy)->first();

    Assert::assertInstanceOf(Permission::class, $foundPermission);
    Assert::assertSame($permission->id, $foundPermission->id);
});

test('can find permissions by name pattern', function (): void {
    $marker = uniqid();
    modelsPermissionCreate(['name' => "user.create-{$marker}"]);
    modelsPermissionCreate(['name' => "user.update-{$marker}"]);
    modelsPermissionCreate(['name' => "user.delete-{$marker}"]);
    modelsPermissionCreate(['name' => "post.read-{$marker}"]);

    $userPermissions = Permission::where('name', 'like', "user.%-{$marker}")->get();

    Assert::assertGreaterThanOrEqual(3, $userPermissions->count());
    foreach ($userPermissions as $userPermission) {
        Assert::assertInstanceOf(Permission::class, $userPermission);
        Assert::assertStringStartsWith('user.', $userPermission->name);
    }
});

test('can update permission', function (): void {
    $oldName = 'old.permission-'.uniqid();
    $newName = 'new.permission-'.uniqid();
    $permission = modelsPermissionCreate(['name' => $oldName]);

    $permission->update(['name' => $newName]);
    $fresh = $permission->fresh();

    Assert::assertInstanceOf(Permission::class, $fresh);
    Assert::assertSame($newName, $fresh->name);
});

test('can handle null values', function (): void {
    $permission = modelsPermissionCreate();

    Permission::withoutEvents(static function () use ($permission): void {
        $permission->forceFill([
            'created_by' => null,
            'updated_by' => null,
        ])->save();
    });
    $permission->refresh();

    Assert::assertNull($permission->created_by);
    Assert::assertNull($permission->updated_by);
});

test('can find permissions by multiple criteria', function (): void {
    $marker = uniqid();
    $first = modelsPermissionCreate([
        'name' => "admin.user.create-{$marker}",
        'guard_name' => 'web',
    ]);
    $second = modelsPermissionCreate([
        'name' => "admin.user.update-{$marker}",
        'guard_name' => 'api',
    ]);

    Permission::withoutEvents(static function () use ($first, $second): void {
        $first->forceFill(['created_by' => 'admin'])->save();
        $second->forceFill(['created_by' => 'admin'])->save();
    });

    $permissions = Permission::where('name', 'like', "admin.user.%-{$marker}")
        ->where('created_by', 'admin')
        ->get();

    Assert::assertGreaterThanOrEqual(2, $permissions->count());
    foreach ($permissions as $permission) {
        Assert::assertInstanceOf(Permission::class, $permission);
        Assert::assertStringStartsWith('admin.user.', $permission->name);
        Assert::assertSame('admin', $permission->created_by);
    }
});

test('permission has table name', function (): void {
    Assert::assertNotSame('', (new Permission)->getTable());
});

test('permission can be deleted from database', function (): void {
    $permission = modelsPermissionCreate();
    $permissionId = $permission->id;

    DB::connection('user')->table($permission->getTable())->where('id', $permissionId)->delete();

    Assert::assertNull(Permission::query()->find($permissionId));
});
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
