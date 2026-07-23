<?php

declare(strict_types=1);

namespace Modules\Comment\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Enums\NotificationSubscriptionType;
use Modules\Comment\Exceptions\CannotCreateComment;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\CommentNotificationSubscription;
use Modules\Comment\Models\Contracts\CanComment;
use RuntimeException;

trait HasComments
{
    /** @return MorphMany<Comment, $this> */
    public function comments(): MorphMany // @phpstan-ignore method.childReturnType
    {
        /** @var class-string<Comment> $commentClass */
        $commentClass = CommentConfigData::make()->models['comment'];

        return $this->morphMany($commentClass, 'commentable'); // @phpstan-ignore return.type
    }

    /** @return MorphMany<CommentNotificationSubscription, $this> */
    public function notificationSubscriptions(): MorphMany
    {
        /** @var class-string<CommentNotificationSubscription> $subscriptionClass */
        $subscriptionClass = CommentConfigData::make()->models['comment_notification_subscription'];

        return $this->morphMany($subscriptionClass, 'commentable'); // @phpstan-ignore return.type
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
            'commentator_id' => $commentator?->getKey() ?? null,
            'commentator_type' => $commentator?->getMorphClass() ?? null,
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
     * @return Collection<int, CanComment>
     */
    public function participatingCommentators(): Collection
    {
        /** @var Collection<int, CanComment> $participants */
        $participants = $this->comments()
            ->distinct('commentator_id', 'commentator_type')
            ->get()
            ->map(function (Comment $comment): array {
                return [
                    'commentator_id' => $comment->commentator_id,
                    'commentator_type' => $comment->commentator_type,
                ];
            })
            ->filter(function (array $related): bool {
                return $related['commentator_type'] !== null;
            })
            ->groupBy('commentator_type')
            ->map(fn (Collection $related): array => $related->pluck('commentator_id')->toArray())
            ->flatMap(function (array $ids, string $class): Collection {
                if (! class_exists($class)) {
                    $resolved = Relation::getMorphedModel($class);
                    $class = is_string($resolved) ? $resolved : $class;
                }

                if (! class_exists($class)) {
                    /** @var Collection<int, CanComment> $empty */
                    $empty = new Collection;

                    return $empty;
                }

                /** @var class-string<Model> $modelClass */
                $modelClass = $class;
                /** @var Model $model */
                $model = new $modelClass;

                $resolved = $modelClass::query()
                    ->whereIn($model->getKeyName(), $ids)
                    ->get()
                    ->filter(static fn (Model $item): bool => $item instanceof CanComment)
                    ->map(static fn (Model $item): CanComment => $item);

                /** @var Collection<int, CanComment> $resolved */
                return $resolved;
            });

        return $participants->pipe(static function (Collection $collection): Collection {
            /** @var Collection<int, CanComment> $typed */
            $typed = $collection;

            return $typed;
        });
    }

    abstract public function commentableName(): string;

    abstract public function commentUrl(): string;
}
