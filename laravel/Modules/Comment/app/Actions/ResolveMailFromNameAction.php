<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Spatie\QueueableAction\QueueableAction;

class ResolveMailFromNameAction
{
    use QueueableAction;

    public function execute(): string
    {
        return $this->handle();
    }

    public function handle(): string
    {
        $value = config('comments.notifications.mail.from.name', config('mail.from.name') ?? '');

        return is_string($value) ? $value : '';
    }
}
