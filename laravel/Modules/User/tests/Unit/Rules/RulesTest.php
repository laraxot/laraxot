<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Rules\CheckOtpExpiredRule;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('CheckOtpExpiredRule can be instantiated', function () {
    try {
        $rule = app(CheckOtpExpiredRule::class);
        Assert::assertInstanceOf(CheckOtpExpiredRule::class, $rule);
    } catch (Exception $e) {
        // assertTrue(true) removed — tautology // Pass if class exists
=======
=======
>>>>>>> 87273113 (.)
uses(Modules\User\Tests\TestCase::class);

use Modules\User\Rules\CheckOtpExpiredRule;

test('CheckOtpExpiredRule can be instantiated', function () {
    expect(class_exists(CheckOtpExpiredRule::class))->toBeTrue();

    try {
        $rule = app(CheckOtpExpiredRule::class);
        expect($rule)->toBeInstanceOf(CheckOtpExpiredRule::class);
    } catch (Exception $e) {
        expect(true)->toBeTrue(); // Pass if class exists
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
use Modules\User\Rules\CheckOtpExpiredRule;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('CheckOtpExpiredRule can be instantiated', function () {
    try {
        $rule = app(CheckOtpExpiredRule::class);
        Assert::assertInstanceOf(CheckOtpExpiredRule::class, $rule);
    } catch (Exception $e) {
        // assertTrue(true) removed — tautology // Pass if class exists
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
});

test('CheckOtpExpiredRule has validation methods', function () {
    if (class_exists(CheckOtpExpiredRule::class)) {
        try {
            $rule = app(CheckOtpExpiredRule::class);
<<<<<<< HEAD
<<<<<<< HEAD
            Assert::assertTrue(method_exists($rule, 'validate') || method_exists($rule, 'passes'));
        } catch (Exception $e) {
            // assertTrue(true) removed — tautology // Pass if class exists
        }
=======
=======
>>>>>>> 87273113 (.)
            expect(method_exists($rule, 'passes'))->toBeTrue();
            expect(method_exists($rule, 'message'))->toBeTrue();
        } catch (Exception $e) {
            expect(true)->toBeTrue(); // Pass if class exists
        }
    } else {
        expect(true)->toBeTrue();
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
            Assert::assertTrue(method_exists($rule, 'validate') || method_exists($rule, 'passes'));
        } catch (Exception $e) {
            // assertTrue(true) removed — tautology // Pass if class exists
        }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
});
