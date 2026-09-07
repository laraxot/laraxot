<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Rules\CheckOtpExpiredRule;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('CheckOtpExpiredRule can be instantiated', function () {
    $user = UserFactory::new()->makeOne();
    $rule = new CheckOtpExpiredRule($user);

    Assert::assertInstanceOf(CheckOtpExpiredRule::class, $rule);
});

test('CheckOtpExpiredRule has validate and message methods', function () {
    $user = UserFactory::new()->makeOne();
    $rule = new CheckOtpExpiredRule($user);
=======
=======
>>>>>>> 87273113 (.)
uses(Modules\User\Tests\TestCase::class);

use Modules\User\Models\User;
use Modules\User\Rules\CheckOtpExpiredRule;

test('CheckOtpExpiredRule can be instantiated', function () {
    $user = User::factory()->make();
    $rule = new CheckOtpExpiredRule($user);

    expect($rule)->toBeInstanceOf(CheckOtpExpiredRule::class);
});

test('CheckOtpExpiredRule has validate and message methods', function () {
    $user = User::factory()->make();
    $rule = new CheckOtpExpiredRule($user);

    expect(method_exists($rule, 'validate'))->toBeTrue()
        ->and(method_exists($rule, 'message'))->toBeTrue();
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Rules\CheckOtpExpiredRule;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('CheckOtpExpiredRule can be instantiated', function () {
    $user = UserFactory::new()->makeOne();
    $rule = new CheckOtpExpiredRule($user);

    Assert::assertInstanceOf(CheckOtpExpiredRule::class, $rule);
});

test('CheckOtpExpiredRule has validate and message methods', function () {
    $user = UserFactory::new()->makeOne();
    $rule = new CheckOtpExpiredRule($user);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});
