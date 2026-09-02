<?php

declare(strict_types=1);

namespace Modules\Comment\Actions\Comment;

use Illuminate\Support\Facades\URL;
use Modules\Comment\Models\Comment;
use Spatie\QueueableAction\QueueableAction;

class BuildCommentRejectUrlAction
{
    use QueueableAction;

    public function execute(Comment $comment): string
    {
        return URL::signedRoute('comment::comment.reject', $comment, now()->addWeek());
    }
}
