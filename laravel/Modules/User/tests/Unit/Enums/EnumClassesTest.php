<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Enums\SocialProviderEnum;
use Modules\User\Enums\SystemRole;
use Modules\User\Enums\UserType;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('UserType enum has all cases', function (): void {
    $cases = UserType::cases();

    Assert::assertCount(5, $cases);
    foreach ($cases as $case) {
        Assert::assertInstanceOf(UserType::class, $case);
    }
});

test('UserType enum cases have correct values', function (): void {
    Assert::assertSame('master_admin', UserType::MasterAdmin->value);
    Assert::assertSame('backoffice_user', UserType::BoUser->value);
    Assert::assertSame('customer_user', UserType::CustomerUser->value);
    Assert::assertSame('system', UserType::System->value);
    Assert::assertSame('technician', UserType::Technician->value);
});

test('UserType getDefaultGuard method works', function (): void {
    Assert::assertSame('web', UserType::MasterAdmin->getDefaultGuard());
    Assert::assertSame('web', UserType::BoUser->getDefaultGuard());
    Assert::assertSame('web', UserType::CustomerUser->getDefaultGuard());
    Assert::assertSame('web', UserType::System->getDefaultGuard());
    Assert::assertSame('api', UserType::Technician->getDefaultGuard());
});

test('UserType getLabel method returns translation keys', function (): void {
    Assert::assertSame('user::user_type.values.master_admin.label', UserType::MasterAdmin->getLabel());
    Assert::assertSame('user::user_type.values.backoffice_user.label', UserType::BoUser->getLabel());
    Assert::assertSame('user::user_type.values.customer_user.label', UserType::CustomerUser->getLabel());
    Assert::assertSame('user::user_type.values.system.label', UserType::System->getLabel());
    Assert::assertSame('user::user_type.values.technician.label', UserType::Technician->getLabel());
});

test('UserType getColor method returns translation keys', function (): void {
    Assert::assertSame('user::user_type.values.master_admin.color', UserType::MasterAdmin->getColor());
    Assert::assertSame('user::user_type.values.backoffice_user.color', UserType::BoUser->getColor());
    Assert::assertSame('user::user_type.values.customer_user.color', UserType::CustomerUser->getColor());
    Assert::assertSame('user::user_type.values.system.color', UserType::System->getColor());
    Assert::assertSame('user::user_type.values.technician.color', UserType::Technician->getColor());
});

test('UserType getIcon method returns translation keys', function (): void {
    Assert::assertSame('user::user_type.values.master_admin.icon', UserType::MasterAdmin->getIcon());
    Assert::assertSame('user::user_type.values.backoffice_user.icon', UserType::BoUser->getIcon());
});

test('SystemRole enum can be instantiated', function (): void {
    foreach (SystemRole::cases() as $case) {
        Assert::assertInstanceOf(SystemRole::class, $case);
    }
});

test('SocialProviderEnum can be instantiated', function (): void {
    foreach (SocialProviderEnum::cases() as $case) {
        Assert::assertInstanceOf(SocialProviderEnum::class, $case);
    }
=======
=======
>>>>>>> 87273113 (.)
uses(Modules\User\Tests\TestCase::class);

use Modules\User\Enums\SocialProviderEnum;
use Modules\User\Enums\SystemRole;
use Modules\User\Enums\UserType;

test('UserType enum has all cases', function () {
    $cases = UserType::cases();

    expect($cases)->toHaveCount(5)
        ->and($cases)->each->toBeInstanceOf(UserType::class);
});

test('UserType enum cases have correct values', function () {
    expect(UserType::MasterAdmin->value)->toBe('master_admin')
        ->and(UserType::BoUser->value)->toBe('backoffice_user')
        ->and(UserType::CustomerUser->value)->toBe('customer_user')
        ->and(UserType::System->value)->toBe('system')
        ->and(UserType::Technician->value)->toBe('technician');
});

test('UserType getDefaultGuard method works', function () {
    expect(UserType::MasterAdmin->getDefaultGuard())->toBe('web')
        ->and(UserType::BoUser->getDefaultGuard())->toBe('web')
        ->and(UserType::CustomerUser->getDefaultGuard())->toBe('web')
        ->and(UserType::System->getDefaultGuard())->toBe('web')
        ->and(UserType::Technician->getDefaultGuard())->toBe('api');
});

test('UserType getLabel method works', function () {
    expect(UserType::MasterAdmin->getLabel())->toBe('master_admin')
        ->and(UserType::BoUser->getLabel())->toBe('backoffice_user')
        ->and(UserType::CustomerUser->getLabel())->toBe('customer_user')
        ->and(UserType::System->getLabel())->toBe('system')
        ->and(UserType::Technician->getLabel())->toBe('technician');
});

test('UserType getColor method works', function () {
    expect(UserType::MasterAdmin->getColor())->toBe('success')
        ->and(UserType::BoUser->getColor())->toBe('warning')
        ->and(UserType::CustomerUser->getColor())->toBe('gray')
        ->and(UserType::System->getColor())->toBe('blue')
        ->and(UserType::Technician->getColor())->toBe('green');
});

test('UserType getIcon method works', function () {
    expect(UserType::MasterAdmin->getIcon())->toBe('heroicon-m-pencil')
        ->and(UserType::BoUser->getIcon())->toBe('heroicon-m-pencil');
});

test('SystemRole enum can be instantiated', function () {
    $cases = SystemRole::cases();

    expect($cases)->each->toBeInstanceOf(SystemRole::class);
});

test('SocialProviderEnum can be instantiated', function () {
    $cases = SocialProviderEnum::cases();

    expect($cases)->each->toBeInstanceOf(SocialProviderEnum::class);
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
use Modules\User\Enums\SocialProviderEnum;
use Modules\User\Enums\SystemRole;
use Modules\User\Enums\UserType;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('UserType enum has all cases', function (): void {
    $cases = UserType::cases();

    Assert::assertCount(5, $cases);
    foreach ($cases as $case) {
        Assert::assertInstanceOf(UserType::class, $case);
    }
});

test('UserType enum cases have correct values', function (): void {
    Assert::assertSame('master_admin', UserType::MasterAdmin->value);
    Assert::assertSame('backoffice_user', UserType::BoUser->value);
    Assert::assertSame('customer_user', UserType::CustomerUser->value);
    Assert::assertSame('system', UserType::System->value);
    Assert::assertSame('technician', UserType::Technician->value);
});

test('UserType getDefaultGuard method works', function (): void {
    Assert::assertSame('web', UserType::MasterAdmin->getDefaultGuard());
    Assert::assertSame('web', UserType::BoUser->getDefaultGuard());
    Assert::assertSame('web', UserType::CustomerUser->getDefaultGuard());
    Assert::assertSame('web', UserType::System->getDefaultGuard());
    Assert::assertSame('api', UserType::Technician->getDefaultGuard());
});

test('UserType getLabel method returns translation keys', function (): void {
    Assert::assertSame('user::user_type.values.master_admin.label', UserType::MasterAdmin->getLabel());
    Assert::assertSame('user::user_type.values.backoffice_user.label', UserType::BoUser->getLabel());
    Assert::assertSame('user::user_type.values.customer_user.label', UserType::CustomerUser->getLabel());
    Assert::assertSame('user::user_type.values.system.label', UserType::System->getLabel());
    Assert::assertSame('user::user_type.values.technician.label', UserType::Technician->getLabel());
});

test('UserType getColor method returns translation keys', function (): void {
    Assert::assertSame('user::user_type.values.master_admin.color', UserType::MasterAdmin->getColor());
    Assert::assertSame('user::user_type.values.backoffice_user.color', UserType::BoUser->getColor());
    Assert::assertSame('user::user_type.values.customer_user.color', UserType::CustomerUser->getColor());
    Assert::assertSame('user::user_type.values.system.color', UserType::System->getColor());
    Assert::assertSame('user::user_type.values.technician.color', UserType::Technician->getColor());
});

test('UserType getIcon method returns translation keys', function (): void {
    Assert::assertSame('user::user_type.values.master_admin.icon', UserType::MasterAdmin->getIcon());
    Assert::assertSame('user::user_type.values.backoffice_user.icon', UserType::BoUser->getIcon());
});

test('SystemRole enum can be instantiated', function (): void {
    foreach (SystemRole::cases() as $case) {
        Assert::assertInstanceOf(SystemRole::class, $case);
    }
});

test('SocialProviderEnum can be instantiated', function (): void {
    foreach (SocialProviderEnum::cases() as $case) {
        Assert::assertInstanceOf(SocialProviderEnum::class, $case);
    }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});
