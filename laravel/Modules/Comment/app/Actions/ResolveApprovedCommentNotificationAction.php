<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Illuminate\Notifications\Notification;
use InvalidArgumentException;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class ResolveApprovedCommentNotificationAction
{
    use QueueableAction;

    public function execute(Comment $comment, CanComment $commentator): Notification
    {
        return $this->handle($comment, $commentator);
    }

    public function handle(Comment $comment, CanComment $commentator): Notification
    {
        $notificationClass = self::resolveNotificationClass('comments.notifications.notifications.approved_comment');
        $notification = app($notificationClass, compact('comment', 'commentator'));
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
