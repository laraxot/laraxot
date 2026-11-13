<?php

declare(strict_types=1);

namespace Modules\Activity\Tests\Unit;

<<<<<<< HEAD
use Mockery;
use Mockery\MockInterface;
=======
>>>>>>> 13cf1831 (.)
use Modules\Activity\Models\Policies\SnapshotPolicy;
use Modules\Activity\Tests\TestCase;
use Modules\User\Models\Policies\UserBasePolicy;
use Modules\User\Models\User;
use PHPUnit\Framework\Assert;

<<<<<<< HEAD
uses(\Modules\Activity\Tests\TestCase::class);

describe('Snapshot Policy', function (): void {
    test('policy extends user base policy', function (): void {
<<<<<<< .merge_file_2BB1Tx
        $policy = new SnapshotPolicy();
=======
uses(TestCase::class);

describe('Snapshot Policy', function (): void {
    test('policy extends user base policy', function (): void {
        /** @var TestCase $this */
        $policy = new SnapshotPolicy;
>>>>>>> 13cf1831 (.)

        Assert::assertInstanceOf(UserBasePolicy::class, $policy);
=======
        Assert::assertTrue((new \ReflectionClass(SnapshotPolicy::class))->isSubclassOf(UserBasePolicy::class));
>>>>>>> .merge_file_P888Q7
    });

    test('policy has expected public methods', function (): void {
        $reflection = new \ReflectionClass(SnapshotPolicy::class);
        $expectedMethods = ['view', 'create', 'update', 'delete', 'restore', 'forceDelete'];

        foreach ($expectedMethods as $method) {
            Assert::assertTrue($reflection->hasMethod($method), "Missing method: {$method}");
        }
    });

    test('user with permission can view', function (): void {
<<<<<<< HEAD
        /** @var MockInterface&User $user */
        $user = Mockery::mock(User::class);
        $user->shouldReceive('hasPermissionTo')->with('snapshot.view')->andReturn(true);

        $policy = new SnapshotPolicy();
=======
        $user = $this->createUnitMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);

        $policy = new SnapshotPolicy;
>>>>>>> 13cf1831 (.)
        $result = $policy->view($user);

        Assert::assertTrue($result);
    });

    test('user without permission cannot view', function (): void {
<<<<<<< HEAD
        /** @var MockInterface&User $user */
        $user = Mockery::mock(User::class);
        $user->shouldReceive('hasPermissionTo')->with('snapshot.view')->andReturn(false);

        $policy = new SnapshotPolicy();
=======
        $user = $this->createUnitMock(User::class);
        $user->method('hasPermissionTo')->willReturn(false);

        $policy = new SnapshotPolicy;
>>>>>>> 13cf1831 (.)
        $result = $policy->view($user);

        Assert::assertFalse($result);
    });
});
