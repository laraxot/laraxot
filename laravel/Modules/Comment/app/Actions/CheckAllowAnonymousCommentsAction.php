<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Spatie\QueueableAction\QueueableAction;

class CheckAllowAnonymousCommentsAction
{
    use QueueableAction;

    public function execute(): bool
    {
        return $this->handle();
    }

    public function handle(): bool
    {
        return (bool) config('comments.allow_anonymous_comments', false);
    }
}
