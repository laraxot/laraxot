<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Modules\Comment\Models\Reaction;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class ResolveReactionModelClassAction
{
    use QueueableAction;

    /** @return class-string<Reaction> */
    public function execute(): string
    {
        return $this->handle();
    }

    /** @return class-string<Reaction> */
    public function handle(): string
    {
        $class = self::resolveClassString('comments.models.reaction', Reaction::class);
        Assert::true(is_a($class, Reaction::class, true));

        return $class;
    }

    /** @param class-string $default @return class-string */
    private static function resolveClassString(string $key, string $default): string
    {
        $class = config($key, $default);
        if (! is_string($class) || $class === '') {
            return $default;
        }
        Assert::classExists($class);

        return $class;
    }
}
