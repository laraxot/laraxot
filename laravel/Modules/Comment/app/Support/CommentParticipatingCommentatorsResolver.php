<?php

declare(strict_types=1);

namespace Modules\Comment\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;

class CommentParticipatingCommentatorsResolver
{
    /** @return Collection<int, CanComment> */
    public static function resolve(Comment $comment): Collection
    {
        $commentable = $comment->commentable;
        if (! is_object($commentable) || ! method_exists($commentable, 'getKey') || ! method_exists($commentable, 'getMorphClass')) {
            return new Collection;
        }

        /** @var Collection<int, CanComment> $participants */
        $participants = Comment::query()
            ->distinct('commentator_id', 'commentator_type')
            ->where('commentable_id', $commentable->getKey())
            ->where('commentable_type', $commentable->getMorphClass())
            ->approved()
            ->get()
            ->map(fn (Comment $item): array => [
                'commentator_id' => $item->commentator_id,
                'commentator_type' => $item->commentator_type,
            ])
            ->filter(fn (array $related): bool => $related['commentator_type'] !== null)
            ->filter(function (array $related) use ($comment): bool {
                if ($related['commentator_type'] !== $comment->commentator_type) {
                    return true;
                }

                return $related['commentator_id'] !== $comment->commentator_id;
            })
            ->groupBy('commentator_type')
            ->flatMap(function (Collection $related, string $class): Collection {
                $resolvedClass = str_contains($class, '\\') ? $class : Relation::getMorphedModel($class);
                if (! is_string($resolvedClass) || ! class_exists($resolvedClass)) {
                    return new Collection;
                }

                $ids = $related->pluck('commentator_id')->toArray();
                /** @var class-string<Model> $modelClass */
                $modelClass = $resolvedClass;
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

        return $participants;
    }
}
