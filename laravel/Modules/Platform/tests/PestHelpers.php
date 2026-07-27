<?php

declare(strict_types=1);

use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Platform\Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use PHPUnit\Framework\Assert;

/**
 * Helper Pest/PHPStan — modulo Platform.
 *
 * @see Modules/Cms/tests/PestHelpers.php
 */
function platformTest(): TestCase
{
    Assert::assertNotNull(TestCase::$currentTest);

    return TestCase::$currentTest;
}

/**
 * @param  array<string, mixed>  $attributes
 */
function platformCreateUser(array $attributes = []): User
{
    return UserFactory::new()->createOne($attributes);
}

function platformActingAs(Authenticatable $user, ?string $driver = null): TestCase
{
    return platformTest()->actingAs($user, $driver);
}
