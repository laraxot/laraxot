<?php

declare(strict_types=1);

namespace Modules\Notify\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketStatusChangedNotification extends Notification
{
    use Queueable;

    /**
     * @return void
     */
    public function __construct(
        public Model $ticket,
        public string $oldStatus,
        public string $newStatus
    ) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
<<<<<<< HEAD
        return (new MailMessage)
=======
        return (new MailMessage())
>>>>>>> a988596b (first)
            ->subject('Ticket Status Changed')
            ->line("Ticket status has changed from {$this->oldStatus} to {$this->newStatus}")
            ->action('View Ticket', url('/'));
    }

    /**
     * @return array{old_status: string, new_status: string}
     */
    public function toArray(object $notifiable): array
    {
        return [
            'old_status' => $this->oldStatus,
<<<<<<< HEAD
<<<<<<< HEAD
            'new_status' => $this->newStatus];
=======
            'new_status' => $this->newStatus,
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'new_status' => $this->newStatus];
>>>>>>> a988596b (first)
    }
}
