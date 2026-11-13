<?php

declare(strict_types=1);

namespace Modules\Comment\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Modules\Comment\Actions\Resolve\ResolveCommentatorModelsAction;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Enums\NotificationSubscriptionType;
use Modules\Comment\Exceptions\CannotCreateComment;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\CommentNotificationSubscription;
use Modules\Comment\Models\Contracts\CanComment;
use RuntimeException;

trait HasComments
{
    /**
     * Thread di commenti sul soggetto (article, ticket, comment stesso).
     *
     * @return MorphMany<Comment, $this>
     */
    public function comments(): MorphMany
    {
        /** @var class-string<Comment> $commentClass */
        $commentClass = CommentConfigData::make()->models['comment'];

        return $this->morphMany($commentClass, 'commentable');
    }

    /** @return MorphMany<CommentNotificationSubscription, $this> */
    public function notificationSubscriptions(): MorphMany
    {
        /** @var class-string<CommentNotificationSubscription> $subscriptionClass */
        $subscriptionClass = CommentConfigData::make()->models['comment_notification_subscription'];

        return $this->morphMany($subscriptionClass, 'commentable');
    }

    /** @return Collection<int, CanComment> */
    public function subscribers(?NotificationSubscriptionType $type = null): Collection
    {
        return $this
            ->notificationSubscriptions()
            ->when($type, fn (Builder $builder) => $builder->where('type', $type))
            ->with('subscriber')
            ->get()
            ->map(fn (CommentNotificationSubscription $subscription): ?Model => $subscription->subscriber)
            ->filter(static fn (?Model $subscriber): bool => $subscriber instanceof CanComment)
            ->map(static function (Model $subscriber): CanComment {
                if (! $subscriber instanceof CanComment) {
                    throw new RuntimeException('Subscriber must implement CanComment.');
                }

                return $subscriber;
            })
            ->values()
            ->pipe(static function (Collection $subscribers): Collection {
                /** @var Collection<int, CanComment> $typed */
                $typed = $subscribers;

                return $typed;
            });
    }

    public function comment(string $text, ?CanComment $commentator = null): Comment
    {
        $commentator ??= auth()->user();

        if (! CommentConfigData::make()->allowAnonymous) {
            if (! $commentator) {
                throw CannotCreateComment::userIsRequired();
            }
        }

        /** @var class-string<Comment> $commentClass */
        $commentClass = CommentConfigData::make()->models['comment'];
        $parentId = $this::class === $commentClass
            ? $this->getKey()
            : null;

        /** @var Comment $comment */
        $comment = $this->comments()->create([
            'commentator_id' => $commentator?->getKey(),
            'commentator_type' => $commentator?->getMorphClass(),
            'original_text' => $text,
            'parent_id' => $parentId,
        ]);

        if ($comment->shouldBeAutomaticallyApproved() && ! $comment->isApproved()) {
            $comment->forceFill(['approved_at' => now()])->save();
        }

        if ($comment->shouldBeAutomaticallyApproved()) {
            return $comment->refresh();
        }

        return $comment;
    }

    /**
     * Tutti i commentator del commentable, qualunque sia lo stato dei commenti e senza escludere nessuno
     * (a differenza di {@see Comment::participatingCommentators()}, che parte da un commento).
     *
     * @return Collection<int, CanComment>
     */
    public function participatingCommentators(): Collection
    {
        $references = $this->comments()
            ->distinct('commentator_id', 'commentator_type')
            ->get()
            ->map(fn (Comment $comment): array => [
                'commentator_id' => $comment->commentator_id,
                'commentator_type' => $comment->commentator_type,
            ]);

        return app(ResolveCommentatorModelsAction::class)->execute($references);
    }

    abstract public function commentableName(): string;

    abstract public function commentUrl(): string;
}
