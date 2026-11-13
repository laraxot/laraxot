<?php

declare(strict_types=1);

namespace Modules\Activity\Tests\Unit\Actions;

use Modules\Activity\Actions\LogModelCreatedAction;
use Modules\Activity\Actions\LogModelDeletedAction;
use Modules\Activity\Actions\LogModelUpdatedAction;
use Modules\Activity\Actions\LogUserLogoutAction;
use Modules\Activity\Tests\TestCase;
<<<<<<< HEAD
use Modules\User\Models\User;
use PHPUnit\Framework\Assert;

uses(\Modules\Activity\Tests\TestCase::class);
=======
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
>>>>>>> 13cf1831 (.)

/**
 * @param  array<string, mixed>  $attributes
 */
<<<<<<< HEAD
function createActivityLifecycleUser(array $attributes = []): User
{
    return activityCreateUser($attributes);
=======
function createUser(array $attributes = []): User
{
    return (new UserFactory)->createOne($attributes);
>>>>>>> 13cf1831 (.)
}

test('Activity Lifecycle Actions', function () {

    test('can log model creation via LogModelCreatedAction', function () {
<<<<<<< HEAD
        $user = createActivityLifecycleUser(['name' => 'New User']);
        $action = new LogModelCreatedAction(model: $user);
=======
        $user = createUser(['name' => 'New User']);
        $action = new LogModelCreatedAction($user);
>>>>>>> 13cf1831 (.)
        $activity = $action->execute();

        Assert::assertSame('created', $activity->log_name);
        Assert::assertSame($user->id, $activity->subject_id);
        Assert::assertStringContainsString((string) 'User was created', (string) $activity->description);
        Assert::assertArrayHasKey('name', (array) $activity->properties);
    });

    test('can log model update via LogModelUpdatedAction', function () {
<<<<<<< HEAD
        $user = createActivityLifecycleUser(['name' => 'Old Name']);
=======
        $user = createUser(['name' => 'Old Name']);
>>>>>>> 13cf1831 (.)
        $user->name = 'New Name';
        // Note: in memory changes only for this test, as LogModelUpdatedAction uses getChanges()
        $user->syncChanges();

<<<<<<< HEAD
        $action = new LogModelUpdatedAction(model: $user);
=======
        $action = new LogModelUpdatedAction($user);
>>>>>>> 13cf1831 (.)
        $activity = $action->execute();

        Assert::assertSame('updated', $activity->log_name);
        Assert::assertSame($user->id, $activity->subject_id);
        Assert::assertStringContainsString((string) 'User was updated', (string) $activity->description);
    });

    test('can log model deletion via LogModelDeletedAction', function () {
<<<<<<< HEAD
        $user = createActivityLifecycleUser();
        $action = new LogModelDeletedAction(model: $user);
=======
        $user = createUser();
        $action = new LogModelDeletedAction($user);
>>>>>>> 13cf1831 (.)
        $activity = $action->execute();

        Assert::assertSame('deleted', $activity->log_name);
        Assert::assertSame($user->id, $activity->subject_id);
        Assert::assertStringContainsString((string) 'User was deleted', (string) $activity->description);
    });

    test('can log user logout via LogUserLogoutAction', function () {
<<<<<<< HEAD
        $user = createActivityLifecycleUser(['name' => 'Logged Out User']);
        $action = new LogUserLogoutAction(user: $user);
=======
        $user = createUser(['name' => 'Logged Out User']);
        $action = new LogUserLogoutAction($user);
>>>>>>> 13cf1831 (.)
        $activity = $action->execute();

        Assert::assertSame('logout', $activity->log_name);
        Assert::assertSame($user->id, $activity->causer_id);
        Assert::assertStringContainsString((string) 'User Logged Out User logged out', (string) $activity->description);
    });
});
