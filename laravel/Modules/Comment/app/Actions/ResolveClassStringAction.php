<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class ResolveClassStringAction
{
    use QueueableAction;

    /** @param class-string $default @return class-string */
    public function execute(string $key, string $default): string
    {
        return $this->handle($key, $default);
    }

    /** @param class-string $default @return class-string */
    public function handle(string $key, string $default): string
    {
        $class = config($key, $default);
        if (! is_string($class) || $class === '') {
            return $default;
        }
        Assert::classExists($class);

        return $class;
    }
}
