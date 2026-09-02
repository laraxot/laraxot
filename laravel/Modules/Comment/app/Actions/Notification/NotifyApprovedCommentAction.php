<?php

declare(strict_types=1);

namespace Modules\Comment\Actions\Notification;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Enums\NotificationSubscriptionType;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use RuntimeException;
use Spatie\QueueableAction\QueueableAction;

class NotifyApprovedCommentAction
{
    use QueueableAction;

    public function execute(Comment $comment): Comment
    {
        $this->handle($comment);

        return $comment;
    }

    public function handle(Comment $comment): void
    {
        $commentable = $this->getCommentable($comment);
        $parentComment = $this->getParentComment($comment);

        $this
            ->getSubscribers($comment, $commentable)
            ->merge($parentComment ? $this->getSubscribers($comment, $parentComment) : $this->emptySubscribers())
            ->merge($this->getMentionees($comment, $commentable))
            ->each(function (CanComment $subscriber) use ($comment): void {
                $notification = CommentConfigData::make()->approvedCommentNotification($comment, $subscriber);
                $subscriber->notify($notification);
            });
    }

    protected function getCommentable(Comment $comment): Model
    {
        $commentable = $comment->topLevel()->commentable;
        if (! $commentable instanceof Model) {
            throw new RuntimeException('Commentable model not found.');
        }

        return $commentable;
    }

    protected function getParentComment(Comment $comment): ?Comment
    {
        if ($comment->isTopLevel()) {
            return null;
        }

        $parent = $comment->parentComment;

        return $parent instanceof Comment ? $parent : null;
    }

    /** @return Collection<int, CanComment> */
    protected function getSubscribers(Comment $comment, Model $commentable): Collection
    {
        if (! method_exists($commentable, 'subscribers') || ! method_exists($commentable, 'participatingCommentators')) {
            /** @var Collection<int, CanComment> */
            return collect();
        }

        /** @var Collection<int, CanComment> $subscribingToAll */
        $subscribingToAll = $commentable->subscribers(NotificationSubscriptionType::All);
        /** @var Collection<int, CanComment> $participatingSubs */
        $participatingSubs = $commentable->subscribers(NotificationSubscriptionType::Participating);
        /** @var Collection<int, CanComment> $participating */
        $participating = $commentable->participatingCommentators();

        $activeParticipants = $participating->filter(
            static function (mixed $participatingUser) use ($participatingSubs): bool {
                if (! $participatingUser instanceof CanComment) {
                    return false;
                }

                return $participatingSubs->contains(
                    static fn (CanComment $subscriber): bool => $subscriber->getMorphClass() === $participatingUser->getMorphClass()
                        && $subscriber->getKey() === $participatingUser->getKey(),
                );
            },
        );

        $commentator = $comment->commentator;

        /** @var Collection<int, CanComment> $finalCollection */
        $finalCollection = $subscribingToAll
            ->merge($activeParticipants)
            ->unique(fn (CanComment $subscriber): string => $subscriber::class.'-'.(string) $subscriber->getKey())
            ->reject(function (CanComment $subscriber) use ($commentator): bool {
                if (! $commentator instanceof CanComment) {
                    return false;
                }

                return $subscriber->getMorphClass() === $commentator->getMorphClass()
                    && $subscriber->getKey() === $commentator->getKey();
            })
            ->values();

        return $finalCollection;
    }

    /** @return Collection<int, CanComment> */
    protected function getMentionees(Comment $comment, Model $commentable): Collection
    {
        if (! (CommentConfigData::make()->mentions['enabled'] ?? false) || ! method_exists($commentable, 'subscribers')) {
            return $this->emptySubscribers();
        }

        $mentionees = $comment->getMentionees();
        /** @var Collection<int, CanComment> $unsubscribed */
        $unsubscribed = $commentable->subscribers(NotificationSubscriptionType::None);

        return $mentionees->reject(fn (CanComment $mentionee): bool => $unsubscribed->contains($mentionee));
    }

    /** @return Collection<int, CanComment> */
    protected function emptySubscribers(): Collection
    {
        /** @var Collection<int, CanComment> */
        return collect();
    }
}
