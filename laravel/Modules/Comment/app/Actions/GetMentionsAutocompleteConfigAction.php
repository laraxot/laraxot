<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use InvalidArgumentException;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class GetMentionsAutocompleteConfigAction
{
    use QueueableAction;

    public function execute(): object
    {
        return $this->handle();
    }

    public function handle(): object
    {
        $class = config('comments.actions.resolve_mentions_autocomplete');
        if (! is_string($class) || $class === '') {
            throw new InvalidArgumentException('Missing config key: comments.actions.resolve_mentions_autocomplete');
        }
        Assert::classExists($class);
        $action = app($class);
        Assert::object($action);

        return $action;
    }
}
