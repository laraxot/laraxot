<?php

declare(strict_types=1);

namespace Modules\Comment\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use RuntimeException;

use function Safe\preg_match_all;

class CommentMentioneesResolver
{
    /** @return Collection<int, CanComment> */
    public static function resolve(Comment $comment): Collection
    {
        preg_match_all('/data-mention="([^"]+)"/', $comment->original_text, $matches);
        $mentioneeIds = $matches[1];
        if ($mentioneeIds === []) {
            return new Collection;
        }

        $modelClass = CommentConfigData::make()->models['commentator'] ?? null;
        if (! is_string($modelClass) || $modelClass === '' || ! is_a($modelClass, Model::class, true)) {
            return new Collection;
        }

        /** @var class-string<Model> $commentatorClass */
        $commentatorClass = $modelClass;
        /** @var Model $instance */
        $instance = new $commentatorClass;

        $mentionees = $commentatorClass::query()
            ->whereIn($instance->getKeyName(), $mentioneeIds)
            ->get()
            ->filter(static fn (Model $item): bool => $item instanceof CanComment)
            ->map(static function (Model $item): CanComment {
                if (! $item instanceof CanComment) {
                    throw new RuntimeException('Mentionee must implement CanComment.');
                }

                return $item;
            })
            ->values();

        /** @var Collection<int, CanComment> $mentionees */
        return $mentionees;
    }
}
