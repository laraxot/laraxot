<?php

declare(strict_types=1);

use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Policies\CommentPolicy;
use Modules\Comment\Tests\Support\ParityCommentatorStub;
use Modules\Comment\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('CommentPolicy create accepts Laraxot CanComment user', function (): void {
    $policy = new CommentPolicy;
    $user = new ParityCommentatorStub(1);

    Assert::assertTrue($policy->create($user));
    Assert::assertFalse($policy->create(null));
});

test('comments config uses native CommentPolicy not Spatie', function (): void {
    Assert::assertSame(CommentPolicy::class, CommentConfigData::make()->policies['comment']);
});

test('CommentPolicy see accepts Comment CanComment contract', function (): void {
    $policy = new CommentPolicy;
    $user = new ParityCommentatorStub(42);
    $comment = new \Modules\Comment\Models\Comment();

    Assert::assertFalse($policy->see($user, $comment));
});
