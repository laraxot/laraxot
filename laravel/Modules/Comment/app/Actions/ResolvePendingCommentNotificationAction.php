<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Illuminate\Notifications\Notification;
use InvalidArgumentException;
use Modules\Comment\Models\Comment;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class ResolvePendingCommentNotificationAction
{
    use QueueableAction;

    public function execute(Comment $comment): Notification
    {
        return $this->handle($comment);
    }

    public function handle(Comment $comment): Notification
    {
        $notificationClass = self::resolveNotificationClass('comments.notifications.notifications.pending_comment');
        $notification = app($notificationClass, ['comment' => $comment]);
        Assert::isInstanceOf($notification, Notification::class);

        return $notification;
    }

    /** @return class-string */
    private static function resolveNotificationClass(string $key): string
    {
        $class = config($key);
        if (! is_string($class) || $class === '') {
            throw new InvalidArgumentException("Missing config key: {$key}");
        }
        Assert::classExists($class);

        return $class;
    }
}
