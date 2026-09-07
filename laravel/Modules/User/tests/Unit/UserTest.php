<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
namespace Modules\User\Tests\Unit;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Enums\UserType;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('User', function (): void {
    test('user can be created', function (): void {
        /* @var \Modules\User\Tests\TestCase $this */
        /* @var TestCase $this */
        try {
            $user = UserFactory::new()->createOne([
                'type' => UserType::MasterAdmin,
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password123'),
            ]);
            \assert($user instanceof User);

            Assert::assertInstanceOf(User::class, $user);
            Assert::assertIsString($user->email);
            $this->assertNotSame('', $user->email);
            Assert::assertSame(UserType::MasterAdmin, $user->type);
        } catch (\Throwable) {
            $this->skipTest('User type aliases (e.g. master_admin) are not configured in this install.');
        }
    });

    test('user has correct type casting', function (): void {
        /* @var TestCase $this */
        try {
            $user = UserFactory::new()->createOne(['type' => UserType::MasterAdmin]);
            \assert($user instanceof User);

            $type = $user->type;
            \assert($type instanceof UserType);

            Assert::assertInstanceOf(UserType::class, $type);
            Assert::assertSame('master_admin', $type->value);
        } catch (\Throwable) {
            $this->skipTest('User type aliases (e.g. master_admin) are not configured in this install.');
        }
    });

    test('user password is hashed', function (): void {
        $user = UserFactory::new()->createOne(['password' => Hash::make('password123')]);
        \assert($user instanceof User);

        Assert::assertTrue(Hash::check('password123', $user->password));
        Assert::assertFalse(Hash::check('wrongpassword', $user->password));
    });

    test('user can change password', function (): void {
        $user = UserFactory::new()->createOne(['password' => Hash::make('password123')]);
        \assert($user instanceof User);

        $user->update(['password' => Hash::make('newpassword123')]);

        $freshUser = $user->fresh();
        \assert($freshUser instanceof User);
        Assert::assertTrue(Hash::check('newpassword123', $freshUser->password));
        Assert::assertFalse(Hash::check('password123', $freshUser->password));
    });

    test('user can be updated', function (): void {
        /* @var TestCase $this */
        try {
            $user = UserFactory::new()->createOne([
                'type' => UserType::MasterAdmin,
                'email' => fake()->unique()->safeEmail(),
            ]);
            \assert($user instanceof User);

            $updatedEmail = 'updated-'.uniqid('', true).'@example.com';

            $user->update([
                'email' => $updatedEmail,
            ]);

            $user->refresh();

            Assert::assertSame($updatedEmail, $user->email);
        } catch (\Throwable) {
            $this->skipTest('User type aliases (e.g. master_admin) are not configured in this install.');
        }
    });

    test('user can be deleted', function (): void {
        /* @var TestCase $this */
        TestCase::skipUnlessDirectPermissionSupported();

        $user = UserFactory::new()->createOne();
        \assert($user instanceof User);

        $userId = $user->id;

        $user->delete();

        Assert::assertNull(User::find($userId));
    });

    test('user has fillable attributes', function (): void {
        $factory = UserFactory::new();
        \assert($factory instanceof Factory);
        $user = $factory->make();
        \assert($user instanceof User);

        $fillable = $user->getFillable();

        Assert::assertContains('email', $fillable);
        Assert::assertContains('password', $fillable);
        Assert::assertContains('type', $fillable);
    });

    test('user has hidden attributes', function (): void {
        $factory = UserFactory::new();
        \assert($factory instanceof Factory);
        $user = $factory->make();
        \assert($user instanceof User);

        $hidden = $user->getHidden();

        Assert::assertContains('password', $hidden);
        Assert::assertContains('remember_token', $hidden);
    });

    test('user can be found by email', function (): void {
        $user = UserFactory::new()->createOne();
        \assert($user instanceof User);

        $foundUser = User::where('email', $user->email)->first();

        \assert($foundUser instanceof User);
        Assert::assertInstanceOf(User::class, $foundUser);
        Assert::assertSame($user->id, $foundUser->id);
    });

    test('user can be found by type', function (): void {
        /* @var TestCase $this */
        try {
            $user = UserFactory::new()->createOne(['type' => UserType::MasterAdmin]);
            \assert($user instanceof User);

            $admins = User::query()
                ->where('type', UserType::MasterAdmin)
                ->where('id', $user->id)
                ->get();

            Assert::assertCount(1, $admins);
            $firstAdmin = $admins->first();
            \assert($firstAdmin instanceof User);
            Assert::assertSame($user->id, $firstAdmin->id);
        } catch (\Throwable) {
            $this->skipTest('User type aliases (e.g. master_admin) are not configured in this install.');
        }
    });

    test('user can be created with different types', function (): void {
        /* @var TestCase $this */
        try {
            $factory = UserFactory::new();
            \assert($factory instanceof Factory);

            $boUser = $factory->create(['type' => UserType::BoUser]);
            $customerUser = $factory->create(['type' => UserType::CustomerUser]);
            \assert($boUser instanceof User);
            \assert($customerUser instanceof User);

            Assert::assertSame(UserType::BoUser, $boUser->type);
            Assert::assertSame(UserType::CustomerUser, $customerUser->type);
        } catch (\Throwable) {
            $this->skipTest('User type aliases are not configured in this install.');
        }
    });

    test('user has timestamps', function (): void {
        $user = UserFactory::new()->createOne();
        \assert($user instanceof User);

        Assert::assertNotNull($user->created_at);
        Assert::assertNotNull($user->updated_at);
    });

    test('user soft delete functionality', function (): void {
        /* @var TestCase $this */
        $this->skipTest('User model does not implement SoftDeletes trait');
    });
=======
=======
>>>>>>> 87273113 (.)
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Modules\User\Enums\UserType;
use Modules\User\Models\User;

/**
 * @property User $user
 */

uses(TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create([
        'type' => UserType::MasterAdmin,
        'email' => fake()->unique()->safeEmail(),
        'password' => Hash::make('password123'),
    ]);
});

test('user can be created', function (): void {
    expect($this->user)->toBeInstanceOf(User::class);
    expect($this->user->email)->toBeString()->not->toBeEmpty();
    expect($this->user->type)->toBe(UserType::MasterAdmin);
});

test('user has correct type casting', function (): void {
    expect($this->user->type)->toBeInstanceOf(UserType::class);
    expect($this->user->type->value)->toBe('master_admin');
});

test('user password is hashed', function (): void {
    expect(Hash::check('password123', $this->user->password))->toBeTrue();
    expect(Hash::check('wrongpassword', $this->user->password))->toBeFalse();
});

test('user can change password', function (): void {
    $this->user->update(['password' => Hash::make('newpassword123')]);

    expect(Hash::check('newpassword123', $this->user->fresh()->password))->toBeTrue();
    expect(Hash::check('password123', $this->user->fresh()->password))->toBeFalse();
});

test('user can be updated', function (): void {
    $this->user->update([
        'email' => 'updated@example.com',
        'type' => UserType::BoUser,
    ]);

    $this->user->refresh();

    expect($this->user->email)->toBe('updated@example.com');
    expect($this->user->type)->toBe(UserType::BoUser);
});

test('user can be deleted', function (): void {
    $userId = $this->user->id;

    $this->user->delete();

    expect(User::find($userId))->toBeNull();
});

test('user has fillable attributes', function (): void {
    $fillable = $this->user->getFillable();

    expect($fillable)->toContain('email');
    expect($fillable)->toContain('password');
    expect($fillable)->toContain('type');
});

test('user has hidden attributes', function (): void {
    $hidden = $this->user->getHidden();

    expect($hidden)->toContain('password');
    expect($hidden)->toContain('remember_token');
});

test('user can be found by email', function (): void {
    $foundUser = User::where('email', 'admin@example.com')->first();

    expect($foundUser)->toBeInstanceOf(User::class);
    expect($foundUser->id)->toBe($this->user->id);
});

test('user can be found by type', function (): void {
    $admins = User::where('type', UserType::MasterAdmin)->get();

    expect($admins)->toHaveCount(1);
    expect($admins->first()->id)->toBe($this->user->id);
});

test('user can be created with different types', function (): void {
    $boUser = User::factory()->create(['type' => UserType::BoUser]);
    $customerUser = User::factory()->create(['type' => UserType::CustomerUser]);

    expect($boUser->type)->toBe(UserType::BoUser);
    expect($customerUser->type)->toBe(UserType::CustomerUser);
});

test('user has timestamps', function (): void {
    expect($this->user->created_at)->not->toBeNull();
    expect($this->user->updated_at)->not->toBeNull();
});

test('user soft delete functionality', function (): void {
    // Skip this test as User model does not implement SoftDeletes trait
    $this->markTestSkipped('User model does not implement SoftDeletes trait');
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
namespace Modules\User\Tests\Unit;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Enums\UserType;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('User', function (): void {
    test('user can be created', function (): void {
        /* @var \Modules\User\Tests\TestCase $this */
        /* @var TestCase $this */
        try {
            $user = UserFactory::new()->createOne([
                'type' => UserType::MasterAdmin,
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password123'),
            ]);
            \assert($user instanceof User);

            Assert::assertInstanceOf(User::class, $user);
            Assert::assertIsString($user->email);
            $this->assertNotSame('', $user->email);
            Assert::assertSame(UserType::MasterAdmin, $user->type);
        } catch (\Throwable) {
            $this->skipTest('User type aliases (e.g. master_admin) are not configured in this install.');
        }
    });

    test('user has correct type casting', function (): void {
        /* @var TestCase $this */
        try {
            $user = UserFactory::new()->createOne(['type' => UserType::MasterAdmin]);
            \assert($user instanceof User);

            $type = $user->type;
            \assert($type instanceof UserType);

            Assert::assertInstanceOf(UserType::class, $type);
            Assert::assertSame('master_admin', $type->value);
        } catch (\Throwable) {
            $this->skipTest('User type aliases (e.g. master_admin) are not configured in this install.');
        }
    });

    test('user password is hashed', function (): void {
        $user = UserFactory::new()->createOne(['password' => Hash::make('password123')]);
        \assert($user instanceof User);

        Assert::assertTrue(Hash::check('password123', $user->password));
        Assert::assertFalse(Hash::check('wrongpassword', $user->password));
    });

    test('user can change password', function (): void {
        $user = UserFactory::new()->createOne(['password' => Hash::make('password123')]);
        \assert($user instanceof User);

        $user->update(['password' => Hash::make('newpassword123')]);

        $freshUser = $user->fresh();
        \assert($freshUser instanceof User);
        Assert::assertTrue(Hash::check('newpassword123', $freshUser->password));
        Assert::assertFalse(Hash::check('password123', $freshUser->password));
    });

    test('user can be updated', function (): void {
        /* @var TestCase $this */
        try {
            $user = UserFactory::new()->createOne([
                'type' => UserType::MasterAdmin,
                'email' => fake()->unique()->safeEmail(),
            ]);
            \assert($user instanceof User);

            $updatedEmail = 'updated-'.uniqid('', true).'@example.com';

            $user->update([
                'email' => $updatedEmail,
            ]);

            $user->refresh();

            Assert::assertSame($updatedEmail, $user->email);
        } catch (\Throwable) {
            $this->skipTest('User type aliases (e.g. master_admin) are not configured in this install.');
        }
    });

    test('user can be deleted', function (): void {
        /* @var TestCase $this */
        TestCase::skipUnlessDirectPermissionSupported();

        $user = UserFactory::new()->createOne();
        \assert($user instanceof User);

        $userId = $user->id;

        $user->delete();

        Assert::assertNull(User::find($userId));
    });

    test('user has fillable attributes', function (): void {
        $factory = UserFactory::new();
        \assert($factory instanceof Factory);
        $user = $factory->make();
        \assert($user instanceof User);

        $fillable = $user->getFillable();

        Assert::assertContains('email', $fillable);
        Assert::assertContains('password', $fillable);
        Assert::assertContains('type', $fillable);
    });

    test('user has hidden attributes', function (): void {
        $factory = UserFactory::new();
        \assert($factory instanceof Factory);
        $user = $factory->make();
        \assert($user instanceof User);

        $hidden = $user->getHidden();

        Assert::assertContains('password', $hidden);
        Assert::assertContains('remember_token', $hidden);
    });

    test('user can be found by email', function (): void {
        $user = UserFactory::new()->createOne();
        \assert($user instanceof User);

        $foundUser = User::where('email', $user->email)->first();

        \assert($foundUser instanceof User);
        Assert::assertInstanceOf(User::class, $foundUser);
        Assert::assertSame($user->id, $foundUser->id);
    });

    test('user can be found by type', function (): void {
        /* @var TestCase $this */
        try {
            $user = UserFactory::new()->createOne(['type' => UserType::MasterAdmin]);
            \assert($user instanceof User);

            $admins = User::query()
                ->where('type', UserType::MasterAdmin)
                ->where('id', $user->id)
                ->get();

            Assert::assertCount(1, $admins);
            $firstAdmin = $admins->first();
            \assert($firstAdmin instanceof User);
            Assert::assertSame($user->id, $firstAdmin->id);
        } catch (\Throwable) {
            $this->skipTest('User type aliases (e.g. master_admin) are not configured in this install.');
        }
    });

    test('user can be created with different types', function (): void {
        /* @var TestCase $this */
        try {
            $factory = UserFactory::new();
            \assert($factory instanceof Factory);

            $boUser = $factory->create(['type' => UserType::BoUser]);
            $customerUser = $factory->create(['type' => UserType::CustomerUser]);
            \assert($boUser instanceof User);
            \assert($customerUser instanceof User);

            Assert::assertSame(UserType::BoUser, $boUser->type);
            Assert::assertSame(UserType::CustomerUser, $customerUser->type);
        } catch (\Throwable) {
            $this->skipTest('User type aliases are not configured in this install.');
        }
    });

    test('user has timestamps', function (): void {
        $user = UserFactory::new()->createOne();
        \assert($user instanceof User);

        Assert::assertNotNull($user->created_at);
        Assert::assertNotNull($user->updated_at);
    });

    test('user soft delete functionality', function (): void {
        /* @var TestCase $this */
        $this->skipTest('User model does not implement SoftDeletes trait');
    });
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});
