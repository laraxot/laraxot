<?php

declare(strict_types=1);

namespace Modules\Auth\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class EmailTwoFactorCodeNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $code,
        private readonly int $minutes,
    ) {
    }

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Codice di verifica accesso')
            ->greeting('Codice di verifica')
            ->line("Usa questo codice per completare l'accesso:")
            ->line($this->code)
            ->line("Il codice scade tra {$this->minutes} minuti.")
            ->line('Se non hai richiesto tu questo accesso, ignora questa email.');
    }
}
