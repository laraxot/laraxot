<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Spatie\QueueableAction\QueueableAction;

class CheckRichiedeApprovazioneAction
{
    use QueueableAction;

    public function execute(): bool
    {
        return $this->handle();
    }

    public function handle(): bool
    {
        return (bool) config('commenti.require_approval', false);
    }
}
