<?php

declare(strict_types=1);

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
=======
>>>>>>> 13cf1831 (.)
use Modules\Activity\Actions\LogModelUpdatedAction;
use Modules\Activity\Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;
use PHPUnit\Framework\Assert;

<<<<<<< HEAD
uses(\Modules\Activity\Tests\TestCase::class);

test('LogModelUpdatedAction can be instantiated', function () {
    $model = new class() extends Model
    {
        protected $table = 'test_models';

        protected $fillable = ['name'];
    };
    $user = UserFactory::new()->createOne();
    Assert::assertInstanceOf(Model::class, $user);

    $action = new LogModelUpdatedAction($model, $user);

    Assert::assertSame($user, $action->user);
=======
uses(TestCase::class);

test('LogModelUpdatedAction can execute for a model', function (): void {
    $model = UserFactory::new()->createOne();
    $action = new LogModelUpdatedAction($model);
    $activity = $action->execute();

    Assert::assertSame($model->getKey(), $activity->subject_id);
>>>>>>> 13cf1831 (.)
});
