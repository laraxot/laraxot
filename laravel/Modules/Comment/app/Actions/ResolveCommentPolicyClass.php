<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Modules\Comment\Policies\CommentPolicy;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class ResolveCommentPolicyClass
{
    use QueueableAction;

    /**
     * @return class-string
     */
    public function execute(): string
    {
        /** @var class-string $class */
        $class = self::resolveClassString(
            'comments.policies.comment',
            CommentPolicy::class,
        );

        return $class;
    }

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
