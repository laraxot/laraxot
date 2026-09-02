<?php

declare(strict_types=1);

namespace Modules\Comment\Actions\Comment;

use Modules\Comment\Actions\Resolve\ResolveCommentApprovingUsersAction;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use Spatie\QueueableAction\QueueableAction;

class ShouldAutomaticallyApproveCommentAction
{
    use QueueableAction;

    public function execute(Comment $comment): bool
    {
        if ((bool) config('comments.automatically_approve_all_comments', false)) {
            return true;
        }

        $commentator = $comment->commentator;
        if (! $commentator instanceof CanComment) {
            return false;
        }

        return app(ResolveCommentApprovingUsersAction::class)->execute($comment)->contains(
            static fn (CanComment $user): bool => $user->getMorphClass() === $commentator->getMorphClass()
                && $user->getKey() === $commentator->getKey(),
        );
    }
}
