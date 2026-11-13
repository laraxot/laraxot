<?php

declare(strict_types=1);

namespace Modules\Activity\Tests\Unit;

<<<<<<< HEAD
use Mockery;
use Mockery\MockInterface;
=======
>>>>>>> 13cf1831 (.)
use Modules\Activity\Models\Policies\ActivityPolicy;
use Modules\Activity\Tests\TestCase;
use Modules\User\Models\User;
use PHPUnit\Framework\Assert;

<<<<<<< HEAD
uses(\Modules\Activity\Tests\TestCase::class);

describe('Activity Policy', function (): void {
    test('user with permission can view', function (): void {
        // Create a mock user with permission
        /** @var MockInterface&User $user */
        $user = Mockery::mock(User::class);
        $user->shouldReceive('hasPermissionTo')->with('activity.view')->andReturn(true);

        $policy = new ActivityPolicy();
=======
uses(TestCase::class);

describe('Activity Policy', function (): void {
    test('user with permission can view', function (): void {
        /** @var TestCase $this */
        // Create a mock user with permission
        $user = $this->createUnitMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);

        $policy = new ActivityPolicy;
>>>>>>> 13cf1831 (.)
        $result = $policy->view($user);

        Assert::assertTrue($result);
    });

    test('user without permission cannot view', function (): void {
        // Create a mock user without permission
<<<<<<< HEAD
        /** @var MockInterface&User $user */
        $user = Mockery::mock(User::class);
        $user->shouldReceive('hasPermissionTo')->with('activity.view')->andReturn(false);

        $policy = new ActivityPolicy();
=======
        $user = $this->createUnitMock(User::class);
        $user->method('hasPermissionTo')->willReturn(false);

        $policy = new ActivityPolicy;
>>>>>>> 13cf1831 (.)
        $result = $policy->view($user);

        Assert::assertFalse($result);
    });
});
