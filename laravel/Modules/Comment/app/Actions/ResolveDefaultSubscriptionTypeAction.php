<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Spatie\QueueableAction\QueueableAction;

class ResolveDefaultSubscriptionTypeAction
{
    use QueueableAction;

    public function execute(): string
    {
        return $this->handle();
    }

    public function handle(): string
    {
        $value = config('commenti.default_subscription_type', 'participating');

        return is_string($value) ? $value : 'participating';
    }
}
