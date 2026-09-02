<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Spatie\QueueableAction\QueueableAction;

class ResolveAllowedReactionsAction
{
    use QueueableAction;

    /** @return list<string> */
    public function execute(): array
    {
        return $this->handle();
    }

    /** @return list<string> */
    public function handle(): array
    {
        $reactions = config('comments.allowed_reactions', []);
        if (! is_array($reactions)) {
            return [];
        }

        return array_values(array_filter($reactions, is_string(...)));
    }
}
