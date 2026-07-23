<?php

declare(strict_types=1);

namespace Modules\Comment\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;

class ApprovedCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Comment $comment, public CanComment $commentator) {}

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
            ->subject((string) __('comment::notifications.approved_comment_mail_subject'))
            ->markdown('comment::mail.approved-comment-notification', [
                'comment' => $this->comment,
                'commentator' => $this->commentator,
                'topLevelComment' => $this->comment->topLevel(),
            ]);
    }
}
