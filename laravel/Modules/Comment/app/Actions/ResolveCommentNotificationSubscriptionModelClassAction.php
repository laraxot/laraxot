<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Modules\Comment\Models\CommentNotificationSubscription;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class ResolveCommentNotificationSubscriptionModelClassAction
{
    use QueueableAction;

    /** @return class-string<CommentNotificationSubscription> */
    public function execute(): string
    {
        return $this->handle();
    }

    /** @return class-string<CommentNotificationSubscription> */
    public function handle(): string
    {
        $class = self::resolveClassString(
            'comments.models.comment_notification_subscription',
            CommentNotificationSubscription::class,
        );
        Assert::true(is_a($class, CommentNotificationSubscription::class, true));

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
