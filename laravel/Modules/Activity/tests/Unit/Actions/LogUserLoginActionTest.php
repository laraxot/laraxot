<?php

declare(strict_types=1);

use Modules\Activity\Actions\LogUserLoginAction;
use Modules\Activity\Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use PHPUnit\Framework\Assert;

<<<<<<< HEAD
uses(\Modules\Activity\Tests\TestCase::class);

test('LogUserLoginAction can be instantiated', function () {
=======
uses(TestCase::class);

test('LogUserLoginAction can execute for a user', function (): void {
>>>>>>> 13cf1831 (.)
    $user = UserFactory::new()->createOne();
    Assert::assertInstanceOf(User::class, $user);

    $action = new LogUserLoginAction($user);
<<<<<<< HEAD

    Assert::assertSame($user, $action->user);
=======
    $activity = $action->execute();

    Assert::assertSame($user->getKey(), $activity->causer_id);
>>>>>>> 13cf1831 (.)
});
