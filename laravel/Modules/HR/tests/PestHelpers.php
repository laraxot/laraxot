<?php

declare(strict_types=1);

use Modules\HR\Database\Factories\EmployeeFactory;
use Modules\HR\Models\Employee;
use Modules\HR\Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use PHPUnit\Framework\Assert;
use Spatie\Permission\Models\Role;

/**
 * Helper Pest/PHPStan — modulo HR.
 *
 * @see Modules/Intervention/tests/PestHelpers.php
 * @see Modules/Timber/tests/PestHelpers.php
 */
function hrTest(): TestCase
{
    Assert::assertNotNull(TestCase::$currentTest);

    return TestCase::$currentTest;
}

function hrSkipTest(string $message = ''): void
{
    hrTest()->markTestSkipped($message);
}

/**
 * @param  array<string, mixed>  $attributes
 */
function hrCreateEmployee(array $attributes = []): Employee
{
    return EmployeeFactory::new()->createOne($attributes);
}

function hrUserWithRole(string $role): User
{
    Role::findOrCreate($role);

    $user = UserFactory::new()->createOne();
    $user->syncRoles([$role]);

    return $user;
}
