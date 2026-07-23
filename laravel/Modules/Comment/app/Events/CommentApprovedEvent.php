<?php

declare(strict_types=1);

namespace Modules\Comment\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Comment\Models\Comment;

class CommentApprovedEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Comment $comment) {}
}
