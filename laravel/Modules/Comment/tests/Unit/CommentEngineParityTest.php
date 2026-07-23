<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Modules\Comment\Actions\Comment\ApproveCommentAction;
use Modules\Comment\Actions\Notification\SendNotificationsForPendingCommentAction;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Enums\NotificationSubscriptionType;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\Comment\Notifications\ApprovedCommentNotification;
use Modules\Comment\Notifications\PendingCommentNotification;
use Modules\Comment\Tests\Support\ParityCommentableStub;
use Modules\Comment\Tests\Support\ParityCommentatorStub;
use Modules\Comment\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

function parityCommentator(int|string $key): CanComment
{
    return new ParityCommentatorStub($key);
}

function seedTicketForCommentTests(): ParityCommentableStub
{
    $stub = new ParityCommentableStub;
    $stub->forceFill(['id' => random_int(1, 999_999)]);

    return $stub;
}

/**
 * @return array<string, mixed>
 */
function baseCommentAttributes(): array
{
    $ticket = seedTicketForCommentTests();

    return [
        'commentator_id' => 1,
        'commentator_type' => 'parity-user',
        'commentable_type' => $ticket->getMorphClass(),
        'commentable_id' => $ticket->getKey(),
        'original_text' => 'Testo commento',
        'text' => 'Testo commento',
    ];
}

test('nested reply stores parent_id and nestedComments relation', function (): void {
    $parent = Comment::factory()->approved()->create(baseCommentAttributes());
    $reply = $parent->comment('Risposta annidata', parityCommentator(2));

    Assert::assertSame($parent->id, $reply->parent_id);
    $parentKey = $parent->getKey();
    Assert::assertSame($parentKey !== null && is_scalar($parentKey) ? (string) $parentKey : '', is_scalar($reply->commentable_id) ? (string) $reply->commentable_id : '');

    $freshParent = $parent->fresh();
    Assert::assertInstanceOf(Comment::class, $freshParent);
    Assert::assertCount(1, $freshParent->nestedComments);
});

test('react and deleteReaction update reactionCounts', function (): void {
    $commentator = parityCommentator(3);
    $comment = Comment::factory()->approved()->create(baseCommentAttributes());

    $comment->react('👍', $commentator);
    $comment->react('❤️', $commentator);

    Assert::assertCount(2, $comment->reactionCounts());
    Assert::assertNotNull($comment->findReaction('👍', $commentator));

    $comment->deleteReaction('👍', $commentator);

    $freshComment = $comment->fresh();
    Assert::assertInstanceOf(Comment::class, $freshComment);
    Assert::assertCount(1, $freshComment->reactionCounts());
    Assert::assertNull($freshComment->findReaction('👍', $commentator));
});

test('ApproveCommentAction approves pending comment', function (): void {
    config(['comments.notifications.enabled' => false]);

    $comment = Comment::factory()->pending()->createOne([
        ...baseCommentAttributes(),
        'commentator_id' => null,
        'commentator_type' => null,
    ]);

    $freshComment = $comment->fresh();
    Assert::assertInstanceOf(Comment::class, $freshComment);

    app(ApproveCommentAction::class)->execute($freshComment);

    $approved = $comment->fresh();
    Assert::assertInstanceOf(Comment::class, $approved);
    Assert::assertTrue($approved->isApproved());
});

test('CommentConfigData builds ApprovedCommentNotification for subscriber', function (): void {
    $comment = Comment::factory()->approved()->createOne([
        ...baseCommentAttributes(),
        'commentator_id' => null,
        'commentator_type' => null,
    ]);
    $subscriber = parityCommentator(10);

    $resolved = CommentConfigData::make()->approvedCommentNotification($comment, $subscriber);

    Assert::assertInstanceOf(ApprovedCommentNotification::class, $resolved);
    Assert::assertTrue($resolved->comment->is($comment));
});

test('SendNotificationsForPendingCommentAction notifies approving users', function (): void {
    Notification::fake();

    $approver = parityCommentator(20);
    PendingCommentNotification::$sendTo = static fn (): Collection => collect([$approver]);

    $comment = Comment::factory()->pending()->create(baseCommentAttributes());

    app(SendNotificationsForPendingCommentAction::class)->execute($comment);

    Notification::assertSentTo($approver, PendingCommentNotification::class);
});

test('subscriber can opt out from comment notifications', function (): void {
    $subscriber = parityCommentator(30);
    $ticket = seedTicketForCommentTests();

    $subscriber->subscribeToCommentNotifications($ticket, NotificationSubscriptionType::Participating);
    Assert::assertSame(NotificationSubscriptionType::Participating, $subscriber->notificationSubscriptionType($ticket));

    $subscriber->unsubscribeFromCommentNotifications($ticket);
    Assert::assertSame(NotificationSubscriptionType::None, $subscriber->notificationSubscriptionType($ticket));
});

afterEach(function (): void {
    PendingCommentNotification::$sendTo = null;
});
