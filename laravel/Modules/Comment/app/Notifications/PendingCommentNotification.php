<?php

declare(strict_types=1);

namespace Modules\Comment\Notifications;

use Closure;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Models\Comment;

class PendingCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public static ?Closure $sendTo = null;

    public function __construct(public Comment $comment) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        if (! method_exists($notifiable, 'routeNotificationFor')) {
            return ['mail'];
        }

        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        unset($notifiable);

        $config = CommentConfigData::make();

        return (new MailMessage)
            ->from(
                $config->notificationMailFromAddress(),
                $config->notificationMailFromName(),
            )
            ->subject((string) __('comment::notifications.pending_comment_mail_subject'))
            ->markdown('comment::mail.pending-comment-notification', [
                'comment' => $this->comment,
                'topLevelComment' => $this->comment->topLevel(),
            ]);
    }

    public static function sendTo(Closure $sendTo): void
    {
        static::$sendTo = $sendTo;
    }
}
