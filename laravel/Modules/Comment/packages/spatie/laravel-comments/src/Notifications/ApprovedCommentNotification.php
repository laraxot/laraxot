<?php

namespace Spatie\Comments\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Spatie\Comments\Models\Comment;
use Spatie\Comments\Models\Concerns\Interfaces\CanComment;
use Spatie\Comments\Support\Config;

class ApprovedCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Comment $comment, public CanComment $commentator) {}

    public function via()
    {
        return 'mail';
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->from(Config::notificationMailFromAddress(), Config::notificationMailFromName())
            ->subject((string) __('comments::notifications.approved_comment_mail_subject'))
            ->markdown('comments::mail.approvedCommentNotification', [
                'comment' => $this->comment,
                'commentator' => $this->commentator,
                'topLevelComment' => $this->comment->topLevel(),
            ]);
    }
}
