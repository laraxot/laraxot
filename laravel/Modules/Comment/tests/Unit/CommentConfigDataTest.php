<?php

declare(strict_types=1);

use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Models\Comment;
use Modules\Comment\Notifications\ApprovedCommentNotification;
use Modules\Comment\Policies\CommentPolicy;
use Modules\Comment\Tests\Support\ParityCommentatorStub;
use Modules\Comment\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('CommentConfigData make legge allow_anonymous_comments da config', function (): void {
    config(['comments.allow_anonymous_comments' => true]);

    Assert::assertTrue(CommentConfigData::make()->allowAnonymous);
});

test('CommentConfigData make espone policies tipizzate', function (): void {
    Assert::assertSame(CommentPolicy::class, CommentConfigData::make()->policies['comment']);
});

test('CommentConfigData approvedCommentNotification risolve via container', function (): void {
    $comment = new Comment([
        'original_text' => 'test',
        'approved_at' => now(),
    ]);
    $subscriber = new ParityCommentatorStub(1);

    $notification = CommentConfigData::make()->approvedCommentNotification($comment, $subscriber);

    Assert::assertInstanceOf(ApprovedCommentNotification::class, $notification);
});
