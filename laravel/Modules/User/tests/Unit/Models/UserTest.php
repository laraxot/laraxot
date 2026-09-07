<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Enums\UserType;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/**
 * @param  array<string, mixed>  $attributes
 */
function modelsUserCreate(array $attributes = []): User
{
    return UserFactory::new()->createOne(array_merge([
        'email' => 'test-'.uniqid('', true).'@example.com',
    ], $attributes));
}

function modelsUserCreateDefault(): User
{
    return modelsUserCreate([
        'type' => UserType::MasterAdmin,
        'password' => Hash::make('password123'),
    ]);
}

function modelsUserTypeValue(User $user): string
{
    $type = $user->type;

    return $type instanceof UserType ? $type->value : (string) $type;
}

test('user can be created', function (): void {
    $user = modelsUserCreateDefault();

    Assert::assertInstanceOf(User::class, $user);
    Assert::assertIsString($user->email);
    Assert::assertNotSame('', $user->email);
    Assert::assertSame(UserType::MasterAdmin->value, modelsUserTypeValue($user));
});

test('user has correct type casting', function (): void {
    Assert::assertSame('master_admin', modelsUserTypeValue(modelsUserCreateDefault()));
});

test('user password is hashed', function (): void {
    $user = modelsUserCreateDefault();

    Assert::assertTrue(Hash::check('password123', $user->password));
    Assert::assertFalse(Hash::check('wrongpassword', $user->password));
});

test('user can change password', function (): void {
    $user = modelsUserCreateDefault();
    $user->update(['password' => Hash::make('newpassword123')]);

    $refreshed = $user->fresh();
    Assert::assertInstanceOf(User::class, $refreshed);
    Assert::assertTrue(Hash::check('newpassword123', $refreshed->password));
    Assert::assertFalse(Hash::check('password123', $refreshed->password));
});

test('user can be updated', function (): void {
    $user = modelsUserCreateDefault();

    $updatedEmail = 'updated-'.uniqid('', true).'@example.com';
    $user->update([
        'email' => $updatedEmail,
        'type' => UserType::BoUser,
    ]);
    $user->refresh();

    Assert::assertSame($updatedEmail, $user->email);
    Assert::assertSame(UserType::BoUser->value, modelsUserTypeValue($user));
});

test('user can be deleted', function (): void {
    $user = modelsUserCreateDefault();
    $userId = $user->id;

    if (! Schema::connection('user')->hasTable('model_has_permission')) {
        DB::connection('user')->table('users')->where('id', $userId)->delete();
    } else {
        $user->delete();
    }

    Assert::assertNull(User::find($userId));
});

test('user has fillable attributes', function (): void {
    $fillable = modelsUserCreateDefault()->getFillable();

    Assert::assertContains('email', $fillable);
    Assert::assertContains('password', $fillable);
    Assert::assertContains('type', $fillable);
});

test('user has hidden attributes', function (): void {
    $hidden = modelsUserCreateDefault()->getHidden();

    Assert::assertContains('password', $hidden);
    Assert::assertContains('remember_token', $hidden);
});

test('user can be found by email', function (): void {
    $user = modelsUserCreateDefault();
    $foundUser = User::where('email', $user->email)->first();

    Assert::assertInstanceOf(User::class, $foundUser);
    Assert::assertSame($user->id, $foundUser->id);
});

test('user can be found by type', function (): void {
    $user = modelsUserCreateDefault();
    $admins = User::where('type', UserType::MasterAdmin)->get();

    Assert::assertGreaterThanOrEqual(1, $admins->count());
    Assert::assertTrue($admins->contains(static fn (User $admin): bool => $admin->id === $user->id));
});

test('user can be created with different types', function (): void {
    $boUser = modelsUserCreate(['type' => UserType::BoUser]);
    $customerUser = modelsUserCreate(['type' => UserType::CustomerUser]);

    Assert::assertSame(UserType::BoUser->value, modelsUserTypeValue($boUser));
    Assert::assertSame(UserType::CustomerUser->value, modelsUserTypeValue($customerUser));
});

test('user has timestamps', function (): void {
    $user = modelsUserCreateDefault();

    Assert::assertNotNull($user->created_at);
    Assert::assertNotNull($user->updated_at);
});

test('user can access socialite', function (): void {
    Assert::assertTrue(modelsUserCreateDefault()->canAccessSocialite());
});

test('user has connection attribute', function (): void {
    Assert::assertSame('user', modelsUserCreateDefault()->getConnectionName());
});

test('user can be found by name pattern', function (): void {
    modelsUserCreate(['name' => 'John Doe']);
    modelsUserCreate(['name' => 'Jane Doe']);
    modelsUserCreate(['name' => 'Bob Smith']);

    $doeUsers = User::where('name', 'like', '%Doe%')->get();

    Assert::assertGreaterThanOrEqual(2, $doeUsers->count());
    foreach ($doeUsers as $doeUser) {
        Assert::assertStringContainsString('Doe', (string) $doeUser->name);
    }
});

test('user can be found by language', function (): void {
    modelsUserCreate(['lang' => 'en']);
    modelsUserCreate(['lang' => 'it']);
    modelsUserCreate(['lang' => 'de']);

    $englishUsers = User::where('lang', 'en')->get();

    Assert::assertGreaterThanOrEqual(1, $englishUsers->count());
    $first = $englishUsers->first();
    Assert::assertInstanceOf(User::class, $first);
    Assert::assertSame('en', $first->lang);
});

test('user can be found by active status', function (): void {
    modelsUserCreate(['is_active' => true]);
    modelsUserCreate(['is_active' => false]);
    modelsUserCreate(['is_active' => true]);

    $activeUsers = User::where('is_active', true)->get();

    Assert::assertGreaterThanOrEqual(2, $activeUsers->count());
    foreach ($activeUsers as $activeUser) {
        Assert::assertTrue((bool) $activeUser->is_active);
    }
});

test('user can be found by otp status', function (): void {
    modelsUserCreate(['is_otp' => true]);
    modelsUserCreate(['is_otp' => false]);
    modelsUserCreate(['is_otp' => true]);

    $otpUsers = User::where('is_otp', true)->get();

    Assert::assertGreaterThanOrEqual(2, $otpUsers->count());
    foreach ($otpUsers as $otpUser) {
        Assert::assertTrue((bool) $otpUser->is_otp);
    }
});

test('user can handle null values', function (): void {
    $user = modelsUserCreate([
        'first_name' => null,
        'last_name' => null,
        'lang' => null,
    ]);

    Assert::assertNull($user->first_name);
    Assert::assertNull($user->last_name);
    Assert::assertNull($user->lang);
});
=======
=======
>>>>>>> 87273113 (.)
namespace Modules\User\Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_user_with_minimal_data(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'test@example.com',
        ]);

        static::assertTrue(Hash::check('password', $user->password));
    }

    public function test_can_create_user_with_all_fields(): void
    {
        $userData = [
            'name' => 'John Doe',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567890',
            'address' => '123 Main St',
            'city' => 'New York',
            'state' => 'NY',
            'registration_number' => 'REG123',
            'status' => 'active',
            'type' => 'individual',
            'lang' => 'en',
            'is_active' => true,
            'is_otp' => false,
        ];

        $user = User::factory()->create($userData);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'john@example.com',
            'name' => 'John Doe',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '+1234567890',
            'address' => '123 Main St',
            'city' => 'New York',
            'state' => 'NY',
            'registration_number' => 'REG123',
            'status' => 'active',
            'type' => 'individual',
            'lang' => 'en',
            'is_active' => true,
            'is_otp' => false,
        ]);
    }

    public function test_user_has_soft_deletes(): void
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $user->delete();

        $this->assertSoftDeleted('users', ['id' => $userId]);
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    public function test_can_restore_soft_deleted_user(): void
    {
        if (!method_exists(User::class, 'withTrashed')) {
            $this->markTestSkipped('SoftDeletes trait not present on User model');
            return;
        }

        $user = User::factory()->create();
        $userId = $user->id;

        $user->delete();
        $this->assertSoftDeleted('users', ['id' => $userId]);

        /** @var User $restoredUser */
        $restoredUser = User::withTrashed()->find($userId);
        $restoredUser->restore();

        $this->assertDatabaseHas('users', ['id' => $userId]);
        static::assertNull($restoredUser->deleted_at);
    }

    public function test_can_find_user_by_email(): void
    {
        $user = User::factory()->create(['email' => 'unique@example.com']);

        $foundUser = User::where('email', 'unique@example.com')->first();

        static::assertNotNull($foundUser);
        static::assertSame($user->id, $foundUser->id);
    }

    public function test_can_find_user_by_name_pattern(): void
    {
        User::factory()->create(['name' => 'John Doe']);
        User::factory()->create(['name' => 'Jane Doe']);
        User::factory()->create(['name' => 'Bob Smith']);

        $doeUsers = User::where('name', 'like', '%Doe%')->get();

        static::assertCount(2, $doeUsers);
        static::assertTrue($doeUsers->every(fn($user) => str_contains($user->name, 'Doe')));
    }

    public function test_can_find_user_by_status(): void
    {
        User::factory()->create(['status' => 'active']);
        User::factory()->create(['status' => 'inactive']);
        User::factory()->create(['status' => 'pending']);

        $activeUsers = User::where('status', 'active')->get();

        static::assertCount(1, $activeUsers);
        static::assertSame('active', $activeUsers->first()->status);
    }

    public function test_can_find_user_by_type(): void
    {
        User::factory()->create(['type' => 'individual']);
        User::factory()->create(['type' => 'company']);
        User::factory()->create(['type' => 'organization']);

        $individualUsers = User::where('type', 'individual')->get();

        static::assertCount(1, $individualUsers);
        static::assertSame('individual', $individualUsers->first()->type);
    }

    public function test_can_find_user_by_city(): void
    {
        User::factory()->create(['city' => 'New York']);
        User::factory()->create(['city' => 'Los Angeles']);
        User::factory()->create(['city' => 'Chicago']);

        $nyUsers = User::where('city', 'New York')->get();

        static::assertCount(1, $nyUsers);
        static::assertSame('New York', $nyUsers->first()->city);
    }

    public function test_can_find_user_by_registration_number(): void
    {
        $user = User::factory()->create(['registration_number' => 'REG123456']);

        $foundUser = User::where('registration_number', 'REG123456')->first();

        static::assertNotNull($foundUser);
        static::assertSame($user->id, $foundUser->id);
    }

    public function test_can_find_user_by_phone(): void
    {
        $user = User::factory()->create(['phone' => '+1234567890']);

        $foundUser = User::where('phone', '+1234567890')->first();

        static::assertNotNull($foundUser);
        static::assertSame($user->id, $foundUser->id);
    }

    public function test_can_find_user_by_language(): void
    {
        User::factory()->create(['lang' => 'en']);
        User::factory()->create(['lang' => 'it']);
        User::factory()->create(['lang' => 'de']);

        $englishUsers = User::where('lang', 'en')->get();

        static::assertCount(1, $englishUsers);
        static::assertSame('en', $englishUsers->first()->lang);
    }

    public function test_can_find_active_users(): void
    {
        User::factory()->create(['is_active' => true]);
        User::factory()->create(['is_active' => false]);
        User::factory()->create(['is_active' => true]);

        $activeUsers = User::where('is_active', true)->get();

        static::assertCount(2, $activeUsers);
        static::assertTrue($activeUsers->every(fn($user) => $user->is_active));
    }

    public function test_can_find_otp_users(): void
    {
        User::factory()->create(['is_otp' => true]);
        User::factory()->create(['is_otp' => false]);
        User::factory()->create(['is_otp' => true]);

        $otpUsers = User::where('is_otp', true)->get();

        static::assertCount(2, $otpUsers);
        static::assertTrue($otpUsers->every(fn($user) => $user->is_otp));
    }

    public function test_can_update_user(): void
    {
        $user = User::factory()->create(['name' => 'Old Name']);

        $user->update(['name' => 'New Name']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
        ]);
    }

    public function test_can_access_socialite(): void
    {
        $user = User::factory()->create();

        static::assertTrue($user->canAccessSocialite());
    }

    public function test_user_has_connection_attribute(): void
    {
        $user = new User();

        static::assertSame('user', $user->connection);
    }

    public function test_can_find_users_by_multiple_criteria(): void
    {
        User::factory()->create([
            'status' => 'active',
            'type' => 'individual',
            'city' => 'New York',
        ]);

        User::factory()->create([
            'status' => 'active',
            'type' => 'company',
            'city' => 'New York',
        ]);

        User::factory()->create([
            'status' => 'inactive',
            'type' => 'individual',
            'city' => 'Los Angeles',
        ]);

        $users = User::where('status', 'active')->where('city', 'New York')->get();

        static::assertCount(2, $users);
        static::assertTrue($users->every(fn($user) => $user->status === 'active' && $user->city === 'New York'));
    }

    public function test_can_handle_null_values(): void
    {
        $user = User::factory()->create([
            'phone' => null,
            'address' => null,
            'city' => null,
            'state' => null,
            'registration_number' => null,
            'status' => null,
            'type' => null,
            'lang' => null,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'phone' => null,
            'address' => null,
            'city' => null,
            'state' => null,
            'registration_number' => null,
            'status' => null,
            'type' => null,
            'lang' => null,
        ]);
    }
}
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Enums\UserType;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/**
 * @param  array<string, mixed>  $attributes
 */
function modelsUserCreate(array $attributes = []): User
{
    return UserFactory::new()->createOne(array_merge([
        'email' => 'test-'.uniqid('', true).'@example.com',
    ], $attributes));
}

function modelsUserCreateDefault(): User
{
    return modelsUserCreate([
        'type' => UserType::MasterAdmin,
        'password' => Hash::make('password123'),
    ]);
}

function modelsUserTypeValue(User $user): string
{
    $type = $user->type;

    return $type instanceof UserType ? $type->value : (string) $type;
}

test('user can be created', function (): void {
    $user = modelsUserCreateDefault();

    Assert::assertInstanceOf(User::class, $user);
    Assert::assertIsString($user->email);
    Assert::assertNotSame('', $user->email);
    Assert::assertSame(UserType::MasterAdmin->value, modelsUserTypeValue($user));
});

test('user has correct type casting', function (): void {
    Assert::assertSame('master_admin', modelsUserTypeValue(modelsUserCreateDefault()));
});

test('user password is hashed', function (): void {
    $user = modelsUserCreateDefault();

    Assert::assertTrue(Hash::check('password123', $user->password));
    Assert::assertFalse(Hash::check('wrongpassword', $user->password));
});

test('user can change password', function (): void {
    $user = modelsUserCreateDefault();
    $user->update(['password' => Hash::make('newpassword123')]);

    $refreshed = $user->fresh();
    Assert::assertInstanceOf(User::class, $refreshed);
    Assert::assertTrue(Hash::check('newpassword123', $refreshed->password));
    Assert::assertFalse(Hash::check('password123', $refreshed->password));
});

test('user can be updated', function (): void {
    $user = modelsUserCreateDefault();

    $updatedEmail = 'updated-'.uniqid('', true).'@example.com';
    $user->update([
        'email' => $updatedEmail,
        'type' => UserType::BoUser,
    ]);
    $user->refresh();

    Assert::assertSame($updatedEmail, $user->email);
    Assert::assertSame(UserType::BoUser->value, modelsUserTypeValue($user));
});

test('user can be deleted', function (): void {
    $user = modelsUserCreateDefault();
    $userId = $user->id;

    if (! Schema::connection('user')->hasTable('model_has_permission')) {
        DB::connection('user')->table('users')->where('id', $userId)->delete();
    } else {
        $user->delete();
    }

    Assert::assertNull(User::find($userId));
});

test('user has fillable attributes', function (): void {
    $fillable = modelsUserCreateDefault()->getFillable();

    Assert::assertContains('email', $fillable);
    Assert::assertContains('password', $fillable);
    Assert::assertContains('type', $fillable);
});

test('user has hidden attributes', function (): void {
    $hidden = modelsUserCreateDefault()->getHidden();

    Assert::assertContains('password', $hidden);
    Assert::assertContains('remember_token', $hidden);
});

test('user can be found by email', function (): void {
    $user = modelsUserCreateDefault();
    $foundUser = User::where('email', $user->email)->first();

    Assert::assertInstanceOf(User::class, $foundUser);
    Assert::assertSame($user->id, $foundUser->id);
});

test('user can be found by type', function (): void {
    $user = modelsUserCreateDefault();
    $admins = User::where('type', UserType::MasterAdmin)->get();

    Assert::assertGreaterThanOrEqual(1, $admins->count());
    Assert::assertTrue($admins->contains(static fn (User $admin): bool => $admin->id === $user->id));
});

test('user can be created with different types', function (): void {
    $boUser = modelsUserCreate(['type' => UserType::BoUser]);
    $customerUser = modelsUserCreate(['type' => UserType::CustomerUser]);

    Assert::assertSame(UserType::BoUser->value, modelsUserTypeValue($boUser));
    Assert::assertSame(UserType::CustomerUser->value, modelsUserTypeValue($customerUser));
});

test('user has timestamps', function (): void {
    $user = modelsUserCreateDefault();

    Assert::assertNotNull($user->created_at);
    Assert::assertNotNull($user->updated_at);
});

test('user can access socialite', function (): void {
    Assert::assertTrue(modelsUserCreateDefault()->canAccessSocialite());
});

test('user has connection attribute', function (): void {
    Assert::assertSame('user', modelsUserCreateDefault()->getConnectionName());
});

test('user can be found by name pattern', function (): void {
    modelsUserCreate(['name' => 'John Doe']);
    modelsUserCreate(['name' => 'Jane Doe']);
    modelsUserCreate(['name' => 'Bob Smith']);

    $doeUsers = User::where('name', 'like', '%Doe%')->get();

    Assert::assertGreaterThanOrEqual(2, $doeUsers->count());
    foreach ($doeUsers as $doeUser) {
        Assert::assertStringContainsString('Doe', (string) $doeUser->name);
    }
});

test('user can be found by language', function (): void {
    modelsUserCreate(['lang' => 'en']);
    modelsUserCreate(['lang' => 'it']);
    modelsUserCreate(['lang' => 'de']);

    $englishUsers = User::where('lang', 'en')->get();

    Assert::assertGreaterThanOrEqual(1, $englishUsers->count());
    $first = $englishUsers->first();
    Assert::assertInstanceOf(User::class, $first);
    Assert::assertSame('en', $first->lang);
});

test('user can be found by active status', function (): void {
    modelsUserCreate(['is_active' => true]);
    modelsUserCreate(['is_active' => false]);
    modelsUserCreate(['is_active' => true]);

    $activeUsers = User::where('is_active', true)->get();

    Assert::assertGreaterThanOrEqual(2, $activeUsers->count());
    foreach ($activeUsers as $activeUser) {
        Assert::assertTrue((bool) $activeUser->is_active);
    }
});

test('user can be found by otp status', function (): void {
    modelsUserCreate(['is_otp' => true]);
    modelsUserCreate(['is_otp' => false]);
    modelsUserCreate(['is_otp' => true]);

    $otpUsers = User::where('is_otp', true)->get();

    Assert::assertGreaterThanOrEqual(2, $otpUsers->count());
    foreach ($otpUsers as $otpUser) {
        Assert::assertTrue((bool) $otpUser->is_otp);
    }
});

test('user can handle null values', function (): void {
    $user = modelsUserCreate([
        'first_name' => null,
        'last_name' => null,
        'lang' => null,
    ]);

    Assert::assertNull($user->first_name);
    Assert::assertNull($user->last_name);
    Assert::assertNull($user->lang);
});
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
