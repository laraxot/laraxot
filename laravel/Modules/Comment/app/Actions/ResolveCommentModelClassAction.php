<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Modules\Comment\Models\Comment;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class ResolveCommentModelClassAction
{
    use QueueableAction;

    /** @return class-string<Comment> */
    public function execute(): string
    {
        return $this->handle();
    }

    /** @return class-string<Comment> */
    public function handle(): string
    {
        $class = self::resolveClassString('comments.models.comment', Comment::class);
        Assert::true(is_a($class, Comment::class, true));

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
