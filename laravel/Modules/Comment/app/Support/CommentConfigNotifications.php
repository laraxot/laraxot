<?php

declare(strict_types=1);

namespace Modules\Comment\Support;

use Illuminate\Notifications\Notification;
use InvalidArgumentException;
use Modules\Comment\Actions\Notification\NotifyApprovedCommentAction;
use Modules\Comment\Actions\Notification\SendNotificationsForPendingCommentAction;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use Webmozart\Assert\Assert;

class CommentConfigNotifications
{
    public static function enabled(): bool
    {
        return (bool) config('comments.notifications.enabled', true);
    }

    public static function pendingCommentNotification(Comment $comment): Notification
    {
        $notificationClass = self::resolveNotificationClass('comments.notifications.notifications.pending_comment');
        $notification = app($notificationClass, ['comment' => $comment]);
        Assert::isInstanceOf($notification, Notification::class);

        return $notification;
    }

    public static function approvedCommentNotification(Comment $comment, CanComment $commentator): Notification
    {
        $notificationClass = self::resolveNotificationClass('comments.notifications.notifications.approved_comment');
        $notification = app($notificationClass, compact('comment', 'commentator'));
        Assert::isInstanceOf($notification, Notification::class);

        return $notification;
    }

    public static function mailFromAddress(): string
    {
        return CommentConfig::configString(
            'comments.notifications.mail.from.address',
            CommentConfig::configString('mail.from.address'),
        );
    }

    public static function mailFromName(): string
    {
        return CommentConfig::configString(
            'comments.notifications.mail.from.name',
            CommentConfig::configString('mail.from.name'),
        );
    }

    public static function sendPendingAction(): SendNotificationsForPendingCommentAction
    {
        $action = CommentConfig::resolveAction('comments.actions.send_notifications_for_pending_comment');
        Assert::isInstanceOf($action, SendNotificationsForPendingCommentAction::class);

        return $action;
    }

    public static function sendApprovedAction(): NotifyApprovedCommentAction
    {
        $action = CommentConfig::resolveAction('comments.actions.send_notifications_for_approved_comment');
        Assert::isInstanceOf($action, NotifyApprovedCommentAction::class);

        return $action;
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
