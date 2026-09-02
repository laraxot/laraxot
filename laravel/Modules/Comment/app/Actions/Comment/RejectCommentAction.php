<?php

declare(strict_types=1);

namespace Modules\Comment\Actions\Comment;

use Modules\Comment\Events\CommentRejectedEvent;
use Modules\Comment\Models\Comment;
use Spatie\QueueableAction\QueueableAction;

class RejectCommentAction
{
    use QueueableAction;

    public function execute(Comment $comment): Comment
    {
        return $this->handle($comment);
    }

    public function handle(Comment $comment): Comment
    {
        $comment->delete();

        event(new CommentRejectedEvent($comment));

        return $comment;
    }
}
