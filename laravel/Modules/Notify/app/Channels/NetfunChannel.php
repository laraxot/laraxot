<?php

declare(strict_types=1);

namespace Modules\Notify\Channels;

use Exception;
use Illuminate\Notifications\Notification;
use Modules\Notify\Actions\SMS\SendSmsFactorSMSAction;
use Modules\Notify\Datas\SmsData;

class NetfunChannel
{
    public function __construct(
        private readonly SendSmsFactorSMSAction $action,
    ) {}

    /**
     * Il ritorno è quello di SendSmsFactorSMSAction::execute(), già tipizzato alla fonte.
     *
     * @return array{status_code: int, status_txt: string}|null
     */
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
    public function send(object $notifiable, Notification $notification): ?array
    {
        if (! method_exists($notifiable, 'routeNotificationForNetfun')) {
=======
    public function send(mixed $notifiable, Notification $notification): ?array
    {
        if (! is_object($notifiable) || ! method_exists($notifiable, 'routeNotificationForNetfun')) {
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    public function send(object $notifiable, Notification $notification): ?array
    {
        if (! method_exists($notifiable, 'routeNotificationForNetfun')) {
>>>>>>> bdc49995 (.)
=======
    public function send(mixed $notifiable, Notification $notification): ?array
    {
        if (! is_object($notifiable) || ! method_exists($notifiable, 'routeNotificationForNetfun')) {
>>>>>>> a988596b (first)
            return null;
        }

        $recipient = $notifiable->routeNotificationForNetfun($notification);
        if (! $recipient) {
            return null;
        }

        if (! method_exists($notification, 'toNetfun')) {
            throw new Exception('Il metodo toNetfun() non è implementato nella notifica');
        }

        $message = $notification->toNetfun($notifiable);

        $smsData = SmsData::from([
            'recipient' => $recipient,
            'body' => is_string($message)
                ? $message
                : (is_object($message) && method_exists($message, 'getContent') ? $message->getContent() : ''),
<<<<<<< HEAD
<<<<<<< HEAD
            'from' => '']);
=======
            'from' => '',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'from' => '']);
>>>>>>> a988596b (first)

        return $this->action->execute($smsData);
    }
}
