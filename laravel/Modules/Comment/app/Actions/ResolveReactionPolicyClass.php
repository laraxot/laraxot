<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Modules\Comment\Policies\ReactionPolicy;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class ResolveReactionPolicyClass
{
    use QueueableAction;

    /** @return class-string */
    public function execute(): string
    {
        return $this->handle();
    }

    /** @return class-string */
    public function handle(): string
    {
        /** @var class-string $class */
        $class = self::resolveClassString(
            'comments.policies.reaction',
            ReactionPolicy::class,
        );

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

        /** @var class-string $class */
        return $class;
    }
}
