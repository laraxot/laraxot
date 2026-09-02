<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Spatie\QueueableAction\QueueableAction;

class ResolveCommentatorModelNameFieldAction
{
    use QueueableAction;

    public function execute(): string
    {
        return $this->handle();
    }

    public function handle(): string
    {
        return (string) config('comments.models.name', 'name');
    }
}
