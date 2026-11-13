<?php

declare(strict_types=1);

namespace Modules\Comment\Actions\Notification;

use Illuminate\Support\Collection;
use Modules\Comment\Actions\CommentatorUniqueKeyAction;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Enums\NotificationSubscriptionType;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\Comment\Models\Contracts\SupportsCommentNotifications;
use RuntimeException;
use Spatie\QueueableAction\QueueableAction;

final class SendNotificationsForApprovedCommentAction
{
    use QueueableAction;

    public function execute(Comment $comment): void
    {
        $commentable = $this->getCommentable($comment);
        $parentComment = ! $comment->isTopLevel() ? $comment->parentComment : null;

        /** @var Collection<int, CanComment> $subscribers */
        $subscribers = $this->getSubscribers($commentable, $comment);
        /** @var Collection<int, CanComment> $parentSubscribers */
        $parentSubscribers = $parentComment instanceof SupportsCommentNotifications
            ? $this->getSubscribers($parentComment, $comment)
            : collect();
        /** @var Collection<int, CanComment> $mentionees */
        $mentionees = $this->getMentionees($comment, $commentable);

        $subscribers
            ->merge($parentSubscribers)
            ->merge($mentionees)
            ->each(function (CanComment $subscriber) use ($comment): void {
                $subscriber->notify(CommentConfigData::make()->approvedCommentNotification($comment, $subscriber));
            });
    }

    private function getCommentable(Comment $comment): SupportsCommentNotifications
    {
        $commentable = $comment->topLevel()->commentable;

        if (! $commentable instanceof SupportsCommentNotifications) {
            throw new RuntimeException('Commentable must support comment notifications.');
        }

        return $commentable;
    }

    /** @return Collection<int, CanComment> */
    private function getSubscribers(SupportsCommentNotifications $commentable, Comment $comment): Collection
    {
        $subscribingToAll = $commentable->subscribers(NotificationSubscriptionType::All);
        $participatingSubs = $commentable->subscribers(NotificationSubscriptionType::Participating);
        $activeParticipants = $commentable->participatingCommentators()
            ->filter(fn (CanComment $user): bool => $participatingSubs->contains($user));
        $commentator = $comment->commentator;

        return $subscribingToAll
            ->merge($activeParticipants)
            ->unique(static fn (CanComment $subscriber): string => app(CommentatorUniqueKeyAction::class)->execute($subscriber))
            ->reject(static function (CanComment $subscriber) use ($commentator): bool {
                return $commentator instanceof CanComment
                    && $subscriber->getMorphClass() === $commentator->getMorphClass()
                    && $subscriber->getKey() === $commentator->getKey();
            });
    }

    /** @return Collection<int, CanComment> */
    private function getMentionees(Comment $comment, SupportsCommentNotifications $commentable): Collection
    {
        if (! CommentConfigData::make()->mentionsEnabled()) {
            return new Collection;
        }

        $unsubscribed = $commentable->subscribers(NotificationSubscriptionType::None);
        $mentionees = [];
        foreach ($comment->getMentionees() as $mentionee) {
            if ($mentionee instanceof CanComment && ! $unsubscribed->contains($mentionee)) {
                $mentionees[] = $mentionee;
            }
        }

        return new Collection($mentionees);
    }
}
