<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('verify database connections config', function () {
    $userDatabase = config('database.connections.user.database');
    $defaultDriver = config('database.connections.mysql.driver');
    $userDriver = config('database.connections.user.driver');

    Assert::assertIsString($userDatabase);
    Assert::assertSame('mysql', $defaultDriver);
    Assert::assertSame('mysql', $userDriver);
    Assert::assertNotSame('sqlite', $userDriver);

    $resolvedUser = DB::connection('user')->getDatabaseName();
    Assert::assertSame($userDatabase, $resolvedUser);

    $profilesExists = DB::connection('user')->getSchemaBuilder()->hasTable('profiles');
    $tenantsExists = DB::connection('user')->getSchemaBuilder()->hasTable('tenants');

    Assert::assertTrue($profilesExists);
    Assert::assertTrue($tenantsExists);
});
