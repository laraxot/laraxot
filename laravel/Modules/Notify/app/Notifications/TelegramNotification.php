<?php

declare(strict_types=1);

/**
 * @see https://iftikhar-ahmed.medium.com/send-push-notifications-in-laravel-using-firebase-on-your-android-device-f585621db900
 * @see https://github.com/laravel-notification-channels/telegram
 */

namespace Modules\Notify\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Notify\Channels\TelegramChannel;

/**
 * Classe per inviare notifiche tramite Telegram.
 */
class TelegramNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $message;

    /** @var array<string, mixed> */
    protected array $options;

    /**
     * Create a new notification instance.
     *
     * @param  string  $message  Il messaggio da inviare tramite Telegram
     * @param  array<string, mixed>  $options  Opzioni aggiuntive per la notifica
     */
    public function __construct(string $message, array $options = [])
    {
        $this->message = $message;
        $this->options = $options;
    }

    /**
     * Get the notification's delivery channels.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @param  object  $_notifiable  The entity to be notified (l'entità da notificare)
     * @return array<int, class-string>
     */
    public function via(object $_notifiable): array
=======
     * @return array<int, class-string>
     */
<<<<<<< HEAD
    public function via(mixed $_notifiable): array
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    public function via(object $_notifiable): array
>>>>>>> bdc49995 (.)
=======
     * @param  mixed  $_notifiable  The entity to be notified (l'entità da notificare)
     *
     * @return array<int, class-string>
     */
    public function via(mixed $_notifiable): array
>>>>>>> a988596b (first)
    {
        return [TelegramChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  object|null  $notifiable  The entity to be notified
<<<<<<< HEAD
=======
     *
>>>>>>> a988596b (first)
     * @return array<string, mixed>
     */
    public function toArray(?object $notifiable): array
    {
        // return $this->data->toArray();
        return [];
    }

    /**
     * Get the Telegram representation of the notification.
     */
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
    public function toTelegram(object $notifiable): string
=======
    public function toTelegram(mixed $notifiable): string
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    public function toTelegram(object $notifiable): string
>>>>>>> bdc49995 (.)
=======
    public function toTelegram(mixed $notifiable): string
>>>>>>> a988596b (first)
    {
        return $this->message;
    }
}
