<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\BaseUser;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('User Business Logic', function () {
    test('user extends base user', function () {
        Assert::assertInstanceOf(BaseUser::class, new User);
    });

    test('user has authentication capabilities', function () {
        $user = new User;
        $user->email = 'test@example.com';
        $user->password = 'hashed-password';

        Assert::assertSame('test@example.com', $user->email);
        Assert::assertTrue(Hash::check('hashed-password', $user->password));
    });

    test('user can have name components', function () {
        $user = new User;
=======
=======
>>>>>>> 87273113 (.)
use Modules\User\Models\BaseUser;
use Modules\User\Models\User;

describe('User Business Logic', function () {
    test('user extends base user', function () {
        expect(User::class)->toBeSubclassOf(BaseUser::class);
    });

    test('user has authentication capabilities', function () {
        $user = new User();
        $user->email = 'test@example.com';
        $user->password = 'hashed-password';

        expect($user->email)->toBe('test@example.com');
        expect($user->password)->toBe('hashed-password');
    });

    test('user can have name components', function () {
        $user = new User();
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\BaseUser;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('User Business Logic', function () {
    test('user extends base user', function () {
        Assert::assertInstanceOf(BaseUser::class, new User);
    });

    test('user has authentication capabilities', function () {
        $user = new User;
        $user->email = 'test@example.com';
        $user->password = 'hashed-password';

        Assert::assertSame('test@example.com', $user->email);
        Assert::assertTrue(Hash::check('hashed-password', $user->password));
    });

    test('user can have name components', function () {
        $user = new User;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        $user->first_name = 'Mario';
        $user->last_name = 'Rossi';
        $user->name = 'Mario Rossi';

<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertSame('Mario', $user->first_name);
        Assert::assertSame('Rossi', $user->last_name);
        Assert::assertSame('Mario Rossi', $user->name);
    });

    test('user has activation status', function () {
        $user = new User;
        $user->is_active = true;

        Assert::assertSame(true, $user->is_active);
    });

    test('user has otp capability', function () {
        $user = new User;
        $user->is_otp = true;

        Assert::assertSame(true, $user->is_otp);
    });

    test('user can have language preference', function () {
        $user = new User;
        $user->lang = 'it';

        Assert::assertSame('it', $user->lang);
    });

    test('user has email verification tracking', function () {
        $user = new User;
        $verifiedAt = Carbon::parse('2023-01-01 12:00:00');
        $user->email_verified_at = $verifiedAt;

        Assert::assertNotNull($user->email_verified_at);
        Assert::assertSame('2023-01-01 12:00:00', $user->email_verified_at->format('Y-m-d H:i:s'));
    });

    test('user has password expiry tracking', function () {
        $user = new User;
        $expiresAt = Carbon::parse('2023-12-31 23:59:59');
        $user->password_expires_at = $expiresAt;

        Assert::assertNotNull($user->password_expires_at);
        Assert::assertSame('2023-12-31 23:59:59', $user->password_expires_at->format('Y-m-d H:i:s'));
    });

    test('user can have current team', function () {
        $user = new User;
        $user->current_team_id = 1;

        Assert::assertSame(1, $user->current_team_id);
    });

    test('user can have profile photo', function () {
        $user = new User;
        $user->profile_photo_path = '/storage/profile-photos/user.jpg';

        Assert::assertSame('/storage/profile-photos/user.jpg', $user->profile_photo_path);
    });

    test('user can have remember token', function () {
        $user = new User;
        $user->remember_token = 'abc123def456';

        Assert::assertSame('abc123def456', $user->remember_token);
=======
=======
>>>>>>> 87273113 (.)
        expect($user->first_name)->toBe('Mario');
        expect($user->last_name)->toBe('Rossi');
        expect($user->name)->toBe('Mario Rossi');
    });

    test('user has activation status', function () {
        $user = new User();
        $user->is_active = true;

        expect($user->is_active)->toBe(true);
    });

    test('user has otp capability', function () {
        $user = new User();
        $user->is_otp = true;

        expect($user->is_otp)->toBe(true);
    });

    test('user can have language preference', function () {
        $user = new User();
        $user->lang = 'it';

        expect($user->lang)->toBe('it');
    });

    test('user has email verification tracking', function () {
        $user = new User();
        $user->email_verified_at = '2023-01-01 12:00:00';

        expect($user->email_verified_at)->toBe('2023-01-01 12:00:00');
    });

    test('user has password expiry tracking', function () {
        $user = new User();
        $user->password_expires_at = '2023-12-31 23:59:59';

        expect($user->password_expires_at)->toBe('2023-12-31 23:59:59');
    });

    test('user can have current team', function () {
        $user = new User();
        $user->current_team_id = 1;

        expect($user->current_team_id)->toBe(1);
    });

    test('user can have profile photo', function () {
        $user = new User();
        $user->profile_photo_path = '/storage/profile-photos/user.jpg';

        expect($user->profile_photo_path)->toBe('/storage/profile-photos/user.jpg');
    });

    test('user can have remember token', function () {
        $user = new User();
        $user->remember_token = 'abc123def456';

        expect($user->remember_token)->toBe('abc123def456');
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        Assert::assertSame('Mario', $user->first_name);
        Assert::assertSame('Rossi', $user->last_name);
        Assert::assertSame('Mario Rossi', $user->name);
    });

    test('user has activation status', function () {
        $user = new User;
        $user->is_active = true;

        Assert::assertSame(true, $user->is_active);
    });

    test('user has otp capability', function () {
        $user = new User;
        $user->is_otp = true;

        Assert::assertSame(true, $user->is_otp);
    });

    test('user can have language preference', function () {
        $user = new User;
        $user->lang = 'it';

        Assert::assertSame('it', $user->lang);
    });

    test('user has email verification tracking', function () {
        $user = new User;
        $verifiedAt = Carbon::parse('2023-01-01 12:00:00');
        $user->email_verified_at = $verifiedAt;

        Assert::assertNotNull($user->email_verified_at);
        Assert::assertSame('2023-01-01 12:00:00', $user->email_verified_at->format('Y-m-d H:i:s'));
    });

    test('user has password expiry tracking', function () {
        $user = new User;
        $expiresAt = Carbon::parse('2023-12-31 23:59:59');
        $user->password_expires_at = $expiresAt;

        Assert::assertNotNull($user->password_expires_at);
        Assert::assertSame('2023-12-31 23:59:59', $user->password_expires_at->format('Y-m-d H:i:s'));
    });

    test('user can have current team', function () {
        $user = new User;
        $user->current_team_id = 1;

        Assert::assertSame(1, $user->current_team_id);
    });

    test('user can have profile photo', function () {
        $user = new User;
        $user->profile_photo_path = '/storage/profile-photos/user.jpg';

        Assert::assertSame('/storage/profile-photos/user.jpg', $user->profile_photo_path);
    });

    test('user can have remember token', function () {
        $user = new User;
        $user->remember_token = 'abc123def456';

        Assert::assertSame('abc123def456', $user->remember_token);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    });
});
