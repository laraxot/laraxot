<?php

declare(strict_types=1);

namespace Modules\Comment\Actions\Reaction;

use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use Spatie\QueueableAction\QueueableAction;

class ReactToCommentAction
{
    use QueueableAction;

    public function execute(Comment $comment, string $reaction, ?CanComment $commentator = null): Comment
    {
        $commentator ??= auth()->user();

        $comment->reactions()->firstOrCreate([
            'commentator_id' => $commentator?->getKey(),
            'commentator_type' => $commentator?->getMorphClass(),
            'reaction' => $reaction,
        ]);

        return $comment;
    }
}
