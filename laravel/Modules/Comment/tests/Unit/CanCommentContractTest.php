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
    $interfaceGetKey = new ReflectionMethod(CommentCanComment::class, 'getKey');
    $modelGetKey = new ReflectionMethod(Model::class, 'getKey');
    $interfaceMorphClass = new ReflectionMethod(CommentCanComment::class, 'getMorphClass');
    $modelMorphClass = new ReflectionMethod(Model::class, 'getMorphClass');

    Assert::assertSame('mixed', (string) $interfaceGetKey->getReturnType());
    Assert::assertFalse($modelGetKey->hasReturnType());
    Assert::assertSame('string', (string) $interfaceMorphClass->getReturnType());
    Assert::assertFalse($modelMorphClass->hasReturnType());
});

test('BaseUser does not depend on optional Comment module', function (): void {
    Assert::assertFalse((new ReflectionClass(BaseUser::class))->implementsInterface(CommentCanComment::class));
});

test('User model does not depend on optional Comment module', function (): void {
    Assert::assertTrue(class_exists(User::class));
    Assert::assertFalse((new ReflectionClass(User::class))->implementsInterface(CommentCanComment::class));
});

test('User CanComment contracts are fully retired from User module', function (): void {
    $userModule = dirname(__DIR__, 3).'/User';

    Assert::assertFileDoesNotExist($userModule.'/app/Contracts/CanComment.php');
    Assert::assertFileDoesNotExist($userModule.'/app/Contracts/CanComment.php.old');
    Assert::assertFileDoesNotExist($userModule.'/app/Models/Contracts/CanComment.php.old');
});

test('autoload User class does not fatal on getKey signature (PHP 8.4)', function (): void {
    Assert::assertTrue(class_exists(User::class, true));
});
