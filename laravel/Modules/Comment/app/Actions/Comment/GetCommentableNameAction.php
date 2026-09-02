<?php

declare(strict_types=1);

namespace Modules\Comment\Actions\Comment;

use Modules\Comment\Models\Comment;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Spatie\QueueableAction\QueueableAction;

class GetCommentableNameAction
{
    use QueueableAction;

    public function execute(Comment $comment): string
    {
        $commentable = $comment->commentable;
        if (is_object($commentable) && method_exists($commentable, 'commentableName')) {
            return SafeStringCastAction::cast($commentable->commentableName());
        }

        return '';
    }
}
