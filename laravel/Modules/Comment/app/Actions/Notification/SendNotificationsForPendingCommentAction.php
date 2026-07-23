<?php

declare(strict_types=1);

namespace Modules\Comment\Actions\Notification;

use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use Spatie\QueueableAction\QueueableAction;

class SendNotificationsForPendingCommentAction
{
    use QueueableAction;

    public function execute(Comment $comment): void
    {
        if (! $comment->isPending()) {
            return;
        }

        $notification = CommentConfigData::make()->pendingCommentNotification($comment);

        $comment
            ->getApprovingUsers()
            ->each(fn (CanComment $user) => $user->notify($notification));
    }
}
