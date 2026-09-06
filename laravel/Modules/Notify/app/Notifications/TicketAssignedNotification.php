<?php

declare(strict_types=1);

namespace Modules\Notify\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Xot\Contracts\UserContract;

class TicketAssignedNotification extends Notification
{
    use Queueable;

    /**
     * @return void
     */
    public function __construct(
        public object $ticket, // No Ticket model in this module; callers pass a Model or a plain object.
        public UserContract $assignedBy
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('New Ticket Assigned')
            ->line("A new ticket has been assigned to you by {$this->assignedBy->name}")
            ->action('View Ticket', url('/'));
    }

    /**
     * @return array{assigned_by: string|null}
     */
    public function toArray(object $notifiable): array
    {
        return [
            'assigned_by' => $this->assignedBy->id,
        ];
    }
}
