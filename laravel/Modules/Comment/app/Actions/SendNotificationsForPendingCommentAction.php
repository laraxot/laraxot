<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Modules\Comment\Actions\Notification\SendNotificationsForPendingCommentAction as PendingCommentNotificationAction;
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
        app(PendingCommentNotificationAction::class)
            ->onQueue()
            ->execute($comment);
    }
}
