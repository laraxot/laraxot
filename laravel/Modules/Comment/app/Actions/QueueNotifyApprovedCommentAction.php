<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Modules\Comment\Jobs\SendNotificationsForApprovedCommentJob;
use Modules\Comment\Models\Comment;
use Spatie\QueueableAction\QueueableAction;

class QueueNotifyApprovedCommentAction
{
    use QueueableAction;

    public function execute(Comment $comment): Comment
    {
        $this->handle($comment);

        return $comment;
    }

    public function handle(Comment $comment): void
    {
        dispatch(new SendNotificationsForApprovedCommentJob($comment));
    }
}
