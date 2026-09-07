<?php

declare(strict_types=1);

namespace Modules\Notify\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Notify\Datas\SmsData;

/**
 * Class SmsNotification
 *
 * Notification class for sending SMS messages through various providers.
 */
class SmsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The SMS data.
     */
    protected SmsData $smsData;

    /**
     * Additional configuration options.
     *
     * @var array<string, mixed>
     */
    protected array $config;

    /**
     * Create a new notification instance.
     *
     * @param  string|SmsData  $content  The content of the SMS or SmsData object
     * @param  array<string, mixed>  $config  Configuration options including provider
     */
    public function __construct(string|SmsData $content, array $config = [])
    {
        if ($content instanceof SmsData) {
            $this->smsData = $content;
        } else {
            $recipient = $config['recipient'] ?? ($config['to'] ?? '');
            $from = $config['from'] ?? '';

            $this->smsData = SmsData::from([
                'body' => $content,
                'recipient' => is_scalar($recipient) ? (string) $recipient : '',
<<<<<<< HEAD
<<<<<<< HEAD
                'from' => is_scalar($from) ? (string) $from : '']);
=======
                'from' => is_scalar($from) ? (string) $from : '',
            ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'from' => is_scalar($from) ? (string) $from : '']);
>>>>>>> a988596b (first)
        }

        $this->config = $config;
    }

    /**
     * Get the notification's delivery channels.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @param  object  $notifiable  The entity to be notified (l'entità da notificare)
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
=======
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
<<<<<<< HEAD
=======
     * @param  mixed  $notifiable  The entity to be notified (l'entità da notificare)
     * @return array<int, string>
     */
    public function via(mixed $notifiable): array
    {
>>>>>>> a988596b (first)
        if (is_object($notifiable) && method_exists($notifiable, 'routeNotificationFor')) {
            return ['sms'];
        }

<<<<<<< HEAD
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> bdc49995 (.)
=======
>>>>>>> a988596b (first)
        return ['sms'];
    }

    /**
     * Get the SMS representation of the notification.
     */
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
    public function toSms(object $notifiable): SmsData
    {
        // If the notifiable entity has a routeNotificationForSms method,
        // we'll use that to get the destination phone number
        if (method_exists($notifiable, 'routeNotificationForSms')) {
=======
=======
>>>>>>> a988596b (first)
    public function toSms(mixed $notifiable): SmsData
    {
        // If the notifiable entity has a routeNotificationForSms method,
        // we'll use that to get the destination phone number
        if (is_object($notifiable) && method_exists($notifiable, 'routeNotificationForSms')) {
<<<<<<< HEAD
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    public function toSms(object $notifiable): SmsData
    {
        // If the notifiable entity has a routeNotificationForSms method,
        // we'll use that to get the destination phone number
        if (method_exists($notifiable, 'routeNotificationForSms')) {
>>>>>>> bdc49995 (.)
=======
>>>>>>> a988596b (first)
            $routeResult = $notifiable->routeNotificationForSms($this);
            $this->smsData->recipient = is_scalar($routeResult) ? (string) $routeResult : '';
        }

        return $this->smsData;
    }

    /**
     * Get the provider configuration for this notification.
     *
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Get the provider to use for sending the SMS.
     */
    public function getProvider(): ?string
    {
        $provider = $this->config['provider'] ?? null;

        return is_string($provider) ? $provider : null;
    }
}
