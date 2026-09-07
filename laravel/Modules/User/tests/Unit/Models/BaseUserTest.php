<?php

declare(strict_types=1);

namespace Modules\User\Tests\Unit\Models;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Modules\User\Models\BaseUser;
use Modules\User\Models\User as DomainUser;
use Modules\User\Tests\TestCase;
use Modules\User\Tests\Unit\Models\Fixtures\TestBaseUser;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('Base User', function (): void {
    test('base user extends eloquent model', function (): void {
        $baseUser = new TestBaseUser;
        Assert::assertInstanceOf(Model::class, $baseUser);
    });

    test('base user has correct table name', function (): void {
        $baseUser = new TestBaseUser;
        Assert::assertSame('test_users', $baseUser->getTable());
    });

    test('base user can be instantiated', function (): void {
        $baseUser = new TestBaseUser;
        Assert::assertInstanceOf(BaseUser::class, $baseUser);
    });

    test('base user has proper inheritance chain', function (): void {
        $baseUser = new TestBaseUser;
        Assert::assertInstanceOf(BaseUser::class, $baseUser);
        Assert::assertInstanceOf(Model::class, $baseUser);
    });

    test('base user has authentication traits', function (): void {
        $baseUser = new TestBaseUser;
        Assert::assertInstanceOf(DomainUser::class, $baseUser);
        $traits = \class_uses_recursive($baseUser);

        Assert::assertContains(Notifiable::class, $traits);
    });
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\BaseUser;
use Modules\User\Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->baseUser = new class extends BaseUser {
        protected $table = 'test_users';
    };
});

test('base user extends eloquent model', function () {
    expect($this->baseUser)->toBeInstanceOf(Model::class);
});

test('base user has correct table name', function () {
    expect($this->baseUser->getTable())->toBe('test_users');
});

test('base user can be instantiated', function () {
    expect($this->baseUser)->toBeInstanceOf(BaseUser::class);
});

test('base user has proper inheritance chain', function () {
    expect($this->baseUser)->toBeInstanceOf(BaseUser::class);
    expect($this->baseUser)->toBeInstanceOf(Model::class);
});

test('base user has authentication traits', function () {
    $traits = class_uses($this->baseUser);

    expect($traits)->toContain(User::class);
    expect($traits)->toContain(Notifiable::class);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Modules\User\Models\BaseUser;
use Modules\User\Tests\TestCase;
use Modules\User\Tests\Unit\Models\Fixtures\TestBaseUser;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('Base User', function (): void {
    test('base user extends eloquent model', function (): void {
        $baseUser = new TestBaseUser;
        Assert::assertInstanceOf(Model::class, $baseUser);
    });

    test('base user has correct table name', function (): void {
        $baseUser = new TestBaseUser;
        Assert::assertSame('test_users', $baseUser->getTable());
    });

    test('base user can be instantiated', function (): void {
        $baseUser = new TestBaseUser;
        Assert::assertInstanceOf(BaseUser::class, $baseUser);
    });

    test('base user has proper inheritance chain', function (): void {
        $baseUser = new TestBaseUser;
        Assert::assertInstanceOf(BaseUser::class, $baseUser);
        Assert::assertInstanceOf(Model::class, $baseUser);
    });

    test('base user has authentication traits', function (): void {
        $baseUser = new TestBaseUser;
        Assert::assertInstanceOf(User::class, $baseUser);
        $traits = \class_uses_recursive($baseUser);

        Assert::assertContains(Notifiable::class, $traits);
    });
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});
