<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
=======
use Tests\TestCase;
>>>>>>> f548be94 (.)
=======
use Tests\TestCase;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Modules\User\Enums\UserType;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;
=======
>>>>>>> f548be94 (.)
=======
=======
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

uses(TestCase::class);

test('user type enum has correct cases', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
    Assert::assertCount(5, UserType::cases());
    Assert::assertSame('master_admin', UserType::MasterAdmin->value);
    Assert::assertSame('backoffice_user', UserType::BoUser->value);
    Assert::assertSame('customer_user', UserType::CustomerUser->value);
    Assert::assertSame('system', UserType::System->value);
    Assert::assertSame('technician', UserType::Technician->value);
=======
=======
>>>>>>> 87273113 (.)
    expect(UserType::cases())->toHaveCount(5);

    expect(UserType::MasterAdmin->value)->toBe('master_admin');
    expect(UserType::BoUser->value)->toBe('backoffice_user');
    expect(UserType::CustomerUser->value)->toBe('customer_user');
    expect(UserType::System->value)->toBe('system');
    expect(UserType::Technician->value)->toBe('technician');
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    Assert::assertCount(5, UserType::cases());
    Assert::assertSame('master_admin', UserType::MasterAdmin->value);
    Assert::assertSame('backoffice_user', UserType::BoUser->value);
    Assert::assertSame('customer_user', UserType::CustomerUser->value);
    Assert::assertSame('system', UserType::System->value);
    Assert::assertSame('technician', UserType::Technician->value);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});

test('user type enum implements required interfaces', function (): void {
    $reflection = new ReflectionClass(UserType::class);

<<<<<<< HEAD
<<<<<<< HEAD
    Assert::assertTrue($reflection->implementsInterface(HasColor::class));
    Assert::assertTrue($reflection->implementsInterface(HasIcon::class));
    Assert::assertTrue($reflection->implementsInterface(HasLabel::class));
});

test('user type enum getLabel method returns translation keys', function (): void {
    Assert::assertSame('user::user_type.values.master_admin.label', UserType::MasterAdmin->getLabel());
    Assert::assertSame('user::user_type.values.backoffice_user.label', UserType::BoUser->getLabel());
    Assert::assertSame('user::user_type.values.customer_user.label', UserType::CustomerUser->getLabel());
    Assert::assertSame('user::user_type.values.system.label', UserType::System->getLabel());
    Assert::assertSame('user::user_type.values.technician.label', UserType::Technician->getLabel());
});

test('user type enum getColor method returns translation keys', function (): void {
    Assert::assertSame('user::user_type.values.master_admin.color', UserType::MasterAdmin->getColor());
    Assert::assertSame('user::user_type.values.backoffice_user.color', UserType::BoUser->getColor());
    Assert::assertSame('user::user_type.values.customer_user.color', UserType::CustomerUser->getColor());
    Assert::assertSame('user::user_type.values.system.color', UserType::System->getColor());
    Assert::assertSame('user::user_type.values.technician.color', UserType::Technician->getColor());
});

test('user type enum getIcon method returns translation keys', function (): void {
    Assert::assertSame('user::user_type.values.master_admin.icon', UserType::MasterAdmin->getIcon());
    Assert::assertSame('user::user_type.values.backoffice_user.icon', UserType::BoUser->getIcon());
    Assert::assertSame('user::user_type.values.customer_user.icon', UserType::CustomerUser->getIcon());
    Assert::assertSame('user::user_type.values.system.icon', UserType::System->getIcon());
    Assert::assertSame('user::user_type.values.technician.icon', UserType::Technician->getIcon());
});

test('user type enum getDefaultGuard method returns correct guards', function (): void {
    Assert::assertSame('web', UserType::MasterAdmin->getDefaultGuard());
    Assert::assertSame('web', UserType::BoUser->getDefaultGuard());
    Assert::assertSame('web', UserType::CustomerUser->getDefaultGuard());
    Assert::assertSame('web', UserType::System->getDefaultGuard());
    Assert::assertSame('api', UserType::Technician->getDefaultGuard());
});

test('user type enum can be compared', function (): void {
    Assert::assertSame(UserType::MasterAdmin, UserType::MasterAdmin);
    Assert::assertNotSame(UserType::MasterAdmin, UserType::BoUser);
});

test('user type enum can be used in match statements', function (): void {
    $getMatchResult = static function (UserType $type): string {
=======
=======
>>>>>>> 87273113 (.)
    expect($reflection->implementsInterface(HasColor::class))->toBeTrue();
    expect($reflection->implementsInterface(HasIcon::class))->toBeTrue();
    expect($reflection->implementsInterface(HasLabel::class))->toBeTrue();
});

test('user type enum getLabel method returns correct labels', function (): void {
    expect(UserType::MasterAdmin->getLabel())->toBe('master_admin');
    expect(UserType::BoUser->getLabel())->toBe('backoffice_user');
    expect(UserType::CustomerUser->getLabel())->toBe('customer_user');
    expect(UserType::System->getLabel())->toBe('system');
    expect(UserType::Technician->getLabel())->toBe('technician');
});

test('user type enum getColor method returns correct colors', function (): void {
    expect(UserType::MasterAdmin->getColor())->toBe('success');
    expect(UserType::BoUser->getColor())->toBe('warning');
    expect(UserType::CustomerUser->getColor())->toBe('gray');
    expect(UserType::System->getColor())->toBe('blue');
    expect(UserType::Technician->getColor())->toBe('green');
});

test('user type enum getIcon method returns correct icons', function (): void {
    expect(UserType::MasterAdmin->getIcon())->toBe('heroicon-m-pencil');
    expect(UserType::BoUser->getIcon())->toBe('heroicon-m-pencil');
    expect(UserType::CustomerUser->getIcon())->toBe('heroicon-m-pencil');
    expect(UserType::System->getIcon())->toBe('heroicon-m-pencil');
    expect(UserType::Technician->getIcon())->toBe('heroicon-m-pencil');
});

test('user type enum getDefaultGuard method returns correct guards', function (): void {
    expect(UserType::MasterAdmin->getDefaultGuard())->toBe('web');
    expect(UserType::BoUser->getDefaultGuard())->toBe('web');
    expect(UserType::CustomerUser->getDefaultGuard())->toBe('web');
    expect(UserType::System->getDefaultGuard())->toBe('web');
    expect(UserType::Technician->getDefaultGuard())->toBe('api');
});

test('user type enum can be used in database queries', function (): void {
    $masterAdmin = UserType::MasterAdmin;
    $boUser = UserType::BoUser;

    expect($masterAdmin->value)->toBe('master_admin');
    expect($boUser->value)->toBe('backoffice_user');
});

test('user type enum can be compared', function (): void {
    $type1 = UserType::MasterAdmin;
    $type2 = UserType::MasterAdmin;
    $type3 = UserType::BoUser;

    expect($type1)->toBe($type2);
    expect($type1)->not->toBe($type3);
});

test('user type enum can be used in match statements', function (): void {
    $getMatchResult = function (UserType $type): string {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    Assert::assertTrue($reflection->implementsInterface(HasColor::class));
    Assert::assertTrue($reflection->implementsInterface(HasIcon::class));
    Assert::assertTrue($reflection->implementsInterface(HasLabel::class));
});

test('user type enum getLabel method returns translation keys', function (): void {
    Assert::assertSame('user::user_type.values.master_admin.label', UserType::MasterAdmin->getLabel());
    Assert::assertSame('user::user_type.values.backoffice_user.label', UserType::BoUser->getLabel());
    Assert::assertSame('user::user_type.values.customer_user.label', UserType::CustomerUser->getLabel());
    Assert::assertSame('user::user_type.values.system.label', UserType::System->getLabel());
    Assert::assertSame('user::user_type.values.technician.label', UserType::Technician->getLabel());
});

test('user type enum getColor method returns translation keys', function (): void {
    Assert::assertSame('user::user_type.values.master_admin.color', UserType::MasterAdmin->getColor());
    Assert::assertSame('user::user_type.values.backoffice_user.color', UserType::BoUser->getColor());
    Assert::assertSame('user::user_type.values.customer_user.color', UserType::CustomerUser->getColor());
    Assert::assertSame('user::user_type.values.system.color', UserType::System->getColor());
    Assert::assertSame('user::user_type.values.technician.color', UserType::Technician->getColor());
});

test('user type enum getIcon method returns translation keys', function (): void {
    Assert::assertSame('user::user_type.values.master_admin.icon', UserType::MasterAdmin->getIcon());
    Assert::assertSame('user::user_type.values.backoffice_user.icon', UserType::BoUser->getIcon());
    Assert::assertSame('user::user_type.values.customer_user.icon', UserType::CustomerUser->getIcon());
    Assert::assertSame('user::user_type.values.system.icon', UserType::System->getIcon());
    Assert::assertSame('user::user_type.values.technician.icon', UserType::Technician->getIcon());
});

test('user type enum getDefaultGuard method returns correct guards', function (): void {
    Assert::assertSame('web', UserType::MasterAdmin->getDefaultGuard());
    Assert::assertSame('web', UserType::BoUser->getDefaultGuard());
    Assert::assertSame('web', UserType::CustomerUser->getDefaultGuard());
    Assert::assertSame('web', UserType::System->getDefaultGuard());
    Assert::assertSame('api', UserType::Technician->getDefaultGuard());
});

test('user type enum can be compared', function (): void {
    Assert::assertSame(UserType::MasterAdmin, UserType::MasterAdmin);
    Assert::assertNotSame(UserType::MasterAdmin, UserType::BoUser);
});

test('user type enum can be used in match statements', function (): void {
    $getMatchResult = static function (UserType $type): string {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        return match ($type) {
            UserType::MasterAdmin => 'admin',
            UserType::BoUser => 'backoffice',
            UserType::CustomerUser => 'customer',
            UserType::System => 'system',
            UserType::Technician => 'technician',
        };
    };

<<<<<<< HEAD
<<<<<<< HEAD
    Assert::assertSame('admin', $getMatchResult(UserType::MasterAdmin));
    Assert::assertSame('backoffice', $getMatchResult(UserType::BoUser));
    Assert::assertSame('customer', $getMatchResult(UserType::CustomerUser));
    Assert::assertSame('system', $getMatchResult(UserType::System));
    Assert::assertSame('technician', $getMatchResult(UserType::Technician));
});

test('user type enum can be serialized', function (): void {
    $serialized = serialize(UserType::MasterAdmin);

    Assert::assertMatchesRegularExpression('/^E:\d+:"Modules\\\User\\\Enums\\\UserType:MasterAdmin";$/', $serialized);
});

test('user type enum can be unserialized', function (): void {
    $serialized = serialize(UserType::MasterAdmin);
    $unserialized = \Safe\unserialize($serialized);

    Assert::assertInstanceOf(UserType::class, $unserialized);
    Assert::assertSame(UserType::MasterAdmin, $unserialized);
=======
=======
>>>>>>> 87273113 (.)
    expect($getMatchResult(UserType::MasterAdmin))->toBe('admin');
    expect($getMatchResult(UserType::BoUser))->toBe('backoffice');
    expect($getMatchResult(UserType::CustomerUser))->toBe('customer');
    expect($getMatchResult(UserType::System))->toBe('system');
    expect($getMatchResult(UserType::Technician))->toBe('technician');
});

test('user type enum can be serialized', function (): void {
    $type = UserType::MasterAdmin;

    expect(serialize($type))->toBe('O:32:"Modules\User\Enums\UserType":1:{s:4:"name";s:11:"MasterAdmin";}');
});

test('user type enum can be unserialized', function (): void {
    $serialized = 'O:32:"Modules\User\Enums\UserType":1:{s:4:"name";s:11:"MasterAdmin";}';
    $unserialized = unserialize($serialized);

    expect($unserialized)->toBe(UserType::MasterAdmin);
});

test('user type enum has correct string representation', function (): void {
    expect(UserType::MasterAdmin->value)->toBe('master_admin');
    expect(UserType::BoUser->value)->toBe('backoffice_user');
    expect(UserType::CustomerUser->value)->toBe('customer_user');
    expect(UserType::System->value)->toBe('system');
    expect(UserType::Technician->value)->toBe('technician');
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    Assert::assertSame('admin', $getMatchResult(UserType::MasterAdmin));
    Assert::assertSame('backoffice', $getMatchResult(UserType::BoUser));
    Assert::assertSame('customer', $getMatchResult(UserType::CustomerUser));
    Assert::assertSame('system', $getMatchResult(UserType::System));
    Assert::assertSame('technician', $getMatchResult(UserType::Technician));
});

test('user type enum can be serialized', function (): void {
    $serialized = serialize(UserType::MasterAdmin);

    Assert::assertMatchesRegularExpression('/^E:\d+:"Modules\\\User\\\Enums\\\UserType:MasterAdmin";$/', $serialized);
});

test('user type enum can be unserialized', function (): void {
    $serialized = serialize(UserType::MasterAdmin);
    $unserialized = \Safe\unserialize($serialized);

    Assert::assertInstanceOf(UserType::class, $unserialized);
    Assert::assertSame(UserType::MasterAdmin, $unserialized);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});
