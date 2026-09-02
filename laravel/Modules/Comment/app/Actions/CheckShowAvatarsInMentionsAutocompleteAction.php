<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Spatie\QueueableAction\QueueableAction;

class CheckShowAvatarsInMentionsAutocompleteAction
{
    use QueueableAction;

    public function execute(): bool
    {
        return $this->handle();
    }

    public function handle(): bool
    {
        return (bool) config('comments.ui.show_avatars_in_mentions_autocomplete', true);
    }
}
