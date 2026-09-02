<?php

declare(strict_types=1);

namespace Modules\Comment\Actions\Comment;

use Modules\Comment\Models\Comment;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Spatie\QueueableAction\QueueableAction;

class BuildCommentUrlAction
{
    use QueueableAction;

    public function execute(Comment $comment): string
    {
        $top = $comment->topLevel();
        $commentable = $top->commentable;
        $base = '';
        if (is_object($commentable) && method_exists($commentable, 'commentUrl')) {
            $base = SafeStringCastAction::cast($commentable->commentUrl());
        }

        return $base."#comment-{$comment->id}";
    }
}
