<?php

declare(strict_types=1);

namespace Modules\Comment\Actions\Resolve;

use Illuminate\Support\Collection;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use Spatie\QueueableAction\QueueableAction;

class ResolveParticipatingCommentatorsAction
{
    use QueueableAction;

    public function __construct(private readonly ResolveCommentatorModelsAction $resolveCommentatorModels) {}

    /** @return Collection<int, CanComment> */
    public function execute(Comment $comment): Collection
    {
        $commentable = $comment->commentable;
        if (! is_object($commentable) || ! method_exists($commentable, 'getKey') || ! method_exists($commentable, 'getMorphClass')) {
            return new Collection;
        }

        $references = Comment::query()
            ->distinct('commentator_id', 'commentator_type')
            ->where('commentable_id', $commentable->getKey())
            ->where('commentable_type', $commentable->getMorphClass())
            ->approved()
            ->get()
            ->map(fn (Comment $item): array => [
                'commentator_id' => $item->commentator_id,
                'commentator_type' => $item->commentator_type,
            ])
            ->filter(function (array $related) use ($comment): bool {
                if ($related['commentator_type'] !== $comment->commentator_type) {
                    return true;
                }

                return $related['commentator_id'] !== $comment->commentator_id;
            });

        return $this->resolveCommentatorModels->execute($references);
    }
}
