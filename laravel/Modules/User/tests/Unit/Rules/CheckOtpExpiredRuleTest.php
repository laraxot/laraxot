<?php

declare(strict_types=1);

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
});
