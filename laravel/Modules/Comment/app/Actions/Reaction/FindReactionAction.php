<?php

declare(strict_types=1);

namespace Modules\Comment\Actions\Reaction;

use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\Comment\Models\Reaction;
use Spatie\QueueableAction\QueueableAction;

class FindReactionAction
{
    use QueueableAction;

    public function execute(Comment $comment, string $reaction, ?CanComment $commentator = null): ?Reaction
    {
        $commentator ??= auth()->user();

        if (! $commentator instanceof CanComment) {
            return null;
        }

        $found = $comment->reactions()
            ->where('commentator_id', $commentator->getKey())
            ->where('commentator_type', $commentator->getMorphClass())
            ->where('reaction', $reaction)
            ->first();

        return $found instanceof Reaction ? $found : null;
    }
}
