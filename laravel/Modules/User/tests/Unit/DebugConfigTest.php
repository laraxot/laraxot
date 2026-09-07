<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\Facades\DB;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;
=======
=======
>>>>>>> 87273113 (.)
namespace Modules\User\Tests\Unit;

use Illuminate\Support\Facades\DB;
use Modules\User\Tests\TestCase;
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
use Illuminate\Support\Facades\DB;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

uses(TestCase::class);

test('verify database connections config', function () {
<<<<<<< HEAD
<<<<<<< HEAD
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
=======
=======
>>>>>>> 87273113 (.)
    $mysql = config('database.connections.mysql.database');
    $user = config('database.connections.user.database');
    $media = config('database.connections.media.database');

    echo "\nMYSQL DB: ".$mysql;
    echo "\nUSER DB: ".$user;
    echo "\nMEDIA DB: ".$media;

    expect($user)->toBe($mysql);
    expect($media)->toBe($mysql);

    $resolvedUser = DB::connection('user')->getDatabaseName();
    echo "\nRESOLVED USER DB: ".$resolvedUser;

    expect($resolvedUser)->toBe($mysql);

    $profilesExists = DB::connection('user')->getSchemaBuilder()->hasTable('profiles');
    echo "\nPROFILES TABLE EXISTS: ".($profilesExists ? 'YES' : 'NO');

    $tenantsExists = DB::connection('user')->getSchemaBuilder()->hasTable('tenants');
    echo "\nTENANTS TABLE EXISTS: ".($tenantsExists ? 'YES' : 'NO');

    $migrations = DB::connection('user')->table('migrations')->get();
    echo "\nTOTAL MIGRATIONS IN DB: ".$migrations->count();
    foreach ($migrations as $m) {
        echo "\nRUN MIGRATION: ".$m->migration;
    }

    expect($profilesExists)->toBeTrue();
    expect($tenantsExists)->toBeTrue();
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
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
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});
