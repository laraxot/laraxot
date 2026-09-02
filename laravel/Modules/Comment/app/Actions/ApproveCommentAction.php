<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Modules\Comment\Actions\Notification\NotifyApprovedCommentAction;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Events\CommentApprovedEvent;
use Modules\Comment\Models\Comment;
use Spatie\QueueableAction\QueueableAction;

class ApproveCommentAction
{
    use QueueableAction;

    public function execute(Comment $comment): Comment
    {
        return $this->handle($comment);
    }

    public function handle(Comment $comment): Comment
    {
        if ($comment->isApproved()) {
            return $comment;
        }

        $comment->update([
            'approved_at' => now(),
        ]);

        event(new CommentApprovedEvent($comment));

        $config = CommentConfigData::make();
        if ($config->notifications['enabled'] ?? true) {
            app(NotifyApprovedCommentAction::class)->onQueue()->execute($comment);
        }

        return $comment;
    }
}
