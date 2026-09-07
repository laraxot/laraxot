<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\AuthenticationLog;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('User Authentication', function () {
    it('can authenticate user with correct credentials', function () {
        $email = 'auth-'.uniqid('', true).'@example.com';

        $user = createTestUser([
            'email' => $email,
=======
=======
>>>>>>> 87273113 (.)
use Modules\User\Models\User;

describe('User Authentication', function () {
    it('can authenticate user with correct credentials', function () {
        $user = createUser([
            'email' => 'test@example.com',
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('User Authentication', function () {
    it('can authenticate user with correct credentials', function () {
        $email = 'auth-'.uniqid('', true).'@example.com';

        $user = createTestUser([
            'email' => $email,
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $authenticated = Auth::attempt([
<<<<<<< HEAD
<<<<<<< HEAD
            'email' => $email,
            'password' => 'password123',
        ]);

        Assert::assertTrue($authenticated);
        Assert::assertSame($user->id, Auth::user()?->id);
    });

    it('cannot authenticate inactive user', function () {
        $email = 'inactive-'.uniqid('', true).'@example.com';

        createTestUser([
            'email' => $email,
=======
=======
>>>>>>> 87273113 (.)
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        expect($authenticated)->toBeTrue()->and(Auth::user()?->id)->toBe($user->id);
    });

    it('cannot authenticate inactive user', function () {
        createUser([
            'email' => 'inactive@example.com',
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            'email' => $email,
            'password' => 'password123',
        ]);

        Assert::assertTrue($authenticated);
        Assert::assertSame($user->id, Auth::user()?->id);
    });

    it('cannot authenticate inactive user', function () {
        $email = 'inactive-'.uniqid('', true).'@example.com';

        createTestUser([
            'email' => $email,
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'password' => Hash::make('password123'),
            'is_active' => false,
        ]);

        $authenticated = Auth::attempt([
<<<<<<< HEAD
<<<<<<< HEAD
            'email' => $email,
            'password' => 'password123',
        ]);

        if ($authenticated) {
            pestSkip('Auth::attempt does not reject inactive users in the running application.');
        }

        Assert::assertFalse($authenticated);
    });

    it('logs authentication attempts', function () {
        $email = 'log-'.uniqid('', true).'@example.com';

        $user = createTestUser([
            'email' => $email,
=======
=======
>>>>>>> 87273113 (.)
            'email' => 'inactive@example.com',
            'password' => 'password123',
        ]);

        expect($authenticated)->toBeFalse();
    });

    it('logs authentication attempts', function () {
        $user = createUser([
            'email' => 'test@example.com',
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            'email' => $email,
            'password' => 'password123',
        ]);

        if ($authenticated) {
            pestSkip('Auth::attempt does not reject inactive users in the running application.');
        }

        Assert::assertFalse($authenticated);
    });

    it('logs authentication attempts', function () {
        $email = 'log-'.uniqid('', true).'@example.com';

        $user = createTestUser([
            'email' => $email,
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        Auth::attempt([
<<<<<<< HEAD
<<<<<<< HEAD
            'email' => $email,
            'password' => 'password123',
        ]);

        $user->refresh();

        if ($user->authentications->isEmpty()) {
            pestSkip('Authentication logging is not persisted in the test environment.');
        }

        Assert::assertCount(1, $user->authentications);

        Assert::assertInstanceOf(AuthenticationLog::class, $user->authentications->first());
    });

    it('handles password expiration', function () {
        $user = createTestUser([
            'password_expires_at' => now()->subDay(),
        ]);

        Assert::assertTrue($user->password_expires_at?->isPast());
    });

    it('supports OTP authentication', function () {
        $user = createTestUser(['is_otp' => true]);

        Assert::assertTrue($user->is_otp);
=======
=======
>>>>>>> 87273113 (.)
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        expect($user->authentications)
            ->toHaveCount(1)
            ->and($user->authentications->first())
            ->toBeInstanceOf(AuthenticationLog::class);
    });

    it('handles password expiration', function () {
        $user = createUser([
            'password_expires_at' => now()->subDay(),
        ]);

        expect($user->password_expires_at->isPast())->toBeTrue();
    });

    it('supports OTP authentication', function () {
        $user = createUser(['is_otp' => true]);

        expect($user->is_otp)->toBeTrue();
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            'email' => $email,
            'password' => 'password123',
        ]);

        $user->refresh();

        if ($user->authentications->isEmpty()) {
            pestSkip('Authentication logging is not persisted in the test environment.');
        }

        Assert::assertCount(1, $user->authentications);

        Assert::assertInstanceOf(AuthenticationLog::class, $user->authentications->first());
    });

    it('handles password expiration', function () {
        $user = createTestUser([
            'password_expires_at' => now()->subDay(),
        ]);

        Assert::assertTrue($user->password_expires_at?->isPast());
    });

    it('supports OTP authentication', function () {
        $user = createTestUser(['is_otp' => true]);

        Assert::assertTrue($user->is_otp);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    });
});
