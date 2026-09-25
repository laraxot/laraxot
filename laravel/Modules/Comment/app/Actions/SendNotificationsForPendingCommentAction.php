<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Modules\Comment\Jobs\SendNotificationsForPendingCommentJob;
use Modules\Comment\Models\Comment;
use Spatie\QueueableAction\QueueableAction;

class SendNotificationsForPendingCommentAction
{
    use QueueableAction;

    public function execute(Comment $comment): void
    {
        $this->handle($comment);
    }

    public function handle(Comment $comment): void
    {
        dispatch(new SendNotificationsForPendingCommentJob($comment));
    }
}
