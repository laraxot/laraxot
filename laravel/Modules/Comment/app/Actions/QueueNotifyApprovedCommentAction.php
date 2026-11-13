<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Modules\Comment\Actions\Notification\SendNotificationsForApprovedCommentAction;
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
        app(SendNotificationsForApprovedCommentAction::class)
            ->onQueue()
            ->execute($comment);
    }
}
