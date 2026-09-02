<?php

declare(strict_types=1);

namespace Modules\Comment\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Modules\Comment\Enums\NotificationSubscriptionType;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\Comment\Models\Contracts\SupportsCommentNotifications;
use Modules\Comment\Datas\CommentConfigData;
use RuntimeException;

// Laraxot module file — see docs/wiki for domain contract.
// Laraxot module file — see docs/wiki for domain contract.
class SendNotificationsForApprovedCommentJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public Comment $comment) {}

    public function handle(): void
    {
        $commentable = $this->getCommentable();
        $parentComment = $this->getParentComment();

        /** @var Collection<int, CanComment> $subscribers */
        $subscribers = $this->getSubscribers($commentable);
        /** @var Collection<int, CanComment> $parentSubscribers */
        $parentSubscribers = $parentComment instanceof SupportsCommentNotifications
            ? $this->getSubscribers($parentComment)
            : collect();
        /** @var Collection<int, CanComment> $mentionees */
        $mentionees = $this->getMentionees($this->comment, $commentable);

        $subscribers
            ->merge($parentSubscribers)
            ->merge($mentionees)
            ->each(function (CanComment $subscriber): void {
                $config = CommentConfigData::make();
                $notification = $config->approvedCommentNotification($this->comment, $subscriber);
                $subscriber->notify($notification);
            });
    }

    protected function getCommentable(): SupportsCommentNotifications
    {
        $commentable = $this->comment->topLevel()->commentable;

        if (! $commentable instanceof SupportsCommentNotifications) {
            throw new RuntimeException('Commentable must support comment notifications.');
        }

        return $commentable;
    }

    protected function getParentComment(): ?Comment
    {
        return ! $this->comment->isTopLevel() ? $this->comment->parentComment : null;
    }

    /** @return Collection<int, CanComment> */
    protected function getSubscribers(SupportsCommentNotifications $commentable): Collection
    {
        $subscribingToAll = $commentable->subscribers(NotificationSubscriptionType::All);
        $participatingSubs = $commentable->subscribers(NotificationSubscriptionType::Participating);
        $activeParticipants = $commentable->participatingCommentators()
            ->filter(fn (CanComment $participatingUser): bool => $participatingSubs->contains($participatingUser));

        $commentator = $this->comment->commentator;

        return $subscribingToAll
            ->merge($activeParticipants)
            ->unique(fn (CanComment $subscriber): string => $subscriber::class.'-'.(string) $subscriber->getKey())
            ->reject(function (CanComment $subscriber) use ($commentator): bool {
                if (! $commentator instanceof CanComment) {
                    return false;
                }

                return $subscriber->getMorphClass() === $commentator->getMorphClass()
                    && $subscriber->getKey() === $commentator->getKey();
            });
    }

    /** @return Collection<int, CanComment> */
    protected function getMentionees(Comment $comment, SupportsCommentNotifications $commentable): Collection
    {
        if (! CommentConfigData::make()->mentionsEnabled()) {
            return new Collection;
        }

        $unsubscribed = $commentable->subscribers(NotificationSubscriptionType::None);
        $mentionees = [];

        foreach ($comment->getMentionees() as $mentionee) {
            if (! $mentionee instanceof CanComment) {
                continue;
            }

            if ($unsubscribed->contains($mentionee)) {
                continue;
            }

            $mentionees[] = $mentionee;
        }

        return new Collection($mentionees);
    }
}
