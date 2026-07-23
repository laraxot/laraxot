<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Models\Comment;
use Modules\Comment\Notifications\PendingCommentNotification;
use Modules\Comment\Tests\Support\ParityCommentatorStub;
use Modules\Comment\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

beforeEach(function (): void {
    config(['comments.automatically_approve_all_comments' => false]);
    PendingCommentNotification::$sendTo = null;
});

afterEach(function (): void {
    PendingCommentNotification::$sendTo = null;
});

test('shouldBeAutomaticallyApproved returns true when config approves all', function (): void {
    config(['comments.automatically_approve_all_comments' => true]);

    $comment = new Comment;

    Assert::assertTrue($comment->shouldBeAutomaticallyApproved());
});

test('shouldBeAutomaticallyApproved returns true when commentator is approving user', function (): void {
    $approver = new ParityCommentatorStub(42);

    PendingCommentNotification::$sendTo = static fn (): Collection => collect([$approver]);

    $comment = new Comment;
    $comment->setRelation('commentator', $approver);

    Assert::assertTrue($comment->shouldBeAutomaticallyApproved());
});

test('shouldBeAutomaticallyApproved returns false for non-approving commentator', function (): void {
    $approver = new ParityCommentatorStub(1);
    $commentator = new ParityCommentatorStub(99);

    PendingCommentNotification::$sendTo = static fn (): Collection => collect([$approver]);

    $comment = new Comment;
    $comment->setRelation('commentator', $commentator);

    Assert::assertFalse($comment->shouldBeAutomaticallyApproved());
});

test('automatically_approve_all_comments reads config', function (): void {
    config(['comments.automatically_approve_all_comments' => true]);

    Assert::assertTrue(CommentConfigData::make()->autoApproveAll);
});
