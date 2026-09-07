<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
namespace Modules\User\Tests\Feature;

use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('User Model Simple', function (): void {
    test('user model can be instantiated', function (): void {
        /** @var TestCase $this */
        $user = new User;

        Assert::assertInstanceOf(User::class, $user);
    });

    test('user model can access connection', function (): void {
        $user = new User;

        Assert::assertSame('user', $user->getConnectionName());
    });

    test('user model can create basic record', function (): void {
        /* @var TestCase $this */
        TestCase::skipUnlessUsersTableReady();

        $user = createTestUser([
            'name' => 'Test User',
            'first_name' => 'Test',
            'last_name' => 'User',
            'lang' => 'it',
            'is_active' => true,
        ]);

        Assert::assertInstanceOf(User::class, $user);
    });
=======
=======
>>>>>>> 87273113 (.)
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;

// Simple test to verify model instantiation
uses(TestCase::class);

test('user model can be instantiated', function () {
    $user = new User();

    expect($user)->toBeInstanceOf(User::class);
});

test('user model can access connection', function () {
    $user = new User();

    // This should work if the connection resolver is properly set up
    expect($user->getConnectionName())->toBe('user');
});

test('user model can create basic record', function () {
    $userData = [
        'name' => 'Test User',
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test-'.uniqid().'@example.com',
        'password' => bcrypt('password'),
        'lang' => 'it',
        'is_active' => true,
    ];

    $user = User::create($userData);

    expect($user)
        ->toBeInstanceOf(User::class)
        ->name->toBe('Test User')
        ->email->toBe($userData['email'])
        ->lang->toBe('it')
        ->is_active->toBe(true);

    // Clean up
    $user->delete();
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
namespace Modules\User\Tests\Feature;

use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('User Model Simple', function (): void {
    test('user model can be instantiated', function (): void {
        /** @var TestCase $this */
        $user = new User;

        Assert::assertInstanceOf(User::class, $user);
    });

    test('user model can access connection', function (): void {
        $user = new User;

        Assert::assertSame('user', $user->getConnectionName());
    });

    test('user model can create basic record', function (): void {
        /* @var TestCase $this */
        TestCase::skipUnlessUsersTableReady();

        $user = createTestUser([
            'name' => 'Test User',
            'first_name' => 'Test',
            'last_name' => 'User',
            'lang' => 'it',
            'is_active' => true,
        ]);

        Assert::assertInstanceOf(User::class, $user);
    });
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});
