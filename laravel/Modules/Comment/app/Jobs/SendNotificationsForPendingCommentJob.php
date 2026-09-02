<?php

declare(strict_types=1);

namespace Modules\Comment\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Models\Comment;

class SendNotificationsForPendingCommentJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public Comment $comment) {}

    public function handle(): void
    {
        if (! $this->comment->isPending()) {
            return;
        }

        $notification = CommentConfigData::make()->pendingCommentNotification($this->comment);

        $this->comment
            ->getApprovingUsers()
            ->each(fn (object $user) => $user->notify($notification));
    }
}
