<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Modules\Comment\Models\Contracts\CanComment as CommentCanComment;
use Modules\Comment\Tests\TestCase;
use Modules\User\Models\BaseUser;
use Modules\User\Models\User;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('CanComment getKey and getMorphClass are compatible with Eloquent Model on PHP 8.4', function (): void {
    foreach (['getKey', 'getMorphClass'] as $method) {
        $interface = new ReflectionMethod(CommentCanComment::class, $method);
        $model = new ReflectionMethod(Model::class, $method);

        Assert::assertFalse($interface->hasReturnType(), $method);
        Assert::assertFalse($model->hasReturnType(), $method);
    }
});

test('BaseUser implements Comment CanComment contract', function (): void {
    Assert::assertTrue((new ReflectionClass(BaseUser::class))->implementsInterface(CommentCanComment::class));
});

test('User model implements Comment CanComment via BaseUser', function (): void {
    Assert::assertTrue(class_exists(User::class));
    Assert::assertTrue((new ReflectionClass(User::class))->implementsInterface(CommentCanComment::class));
});

test('User CanComment retired from app Contracts to Models Contracts old', function (): void {
    $userModule = dirname(__DIR__, 3).'/User';

    Assert::assertFileDoesNotExist($userModule.'/app/Contracts/CanComment.php');
    Assert::assertFileExists($userModule.'/app/Contracts/CanComment.php.old');
    Assert::assertFileExists($userModule.'/app/Models/Contracts/CanComment.php.old');
});

test('autoload User class does not fatal on getKey signature (PHP 8.4)', function (): void {
    Assert::assertTrue(class_exists(User::class, true));
});
