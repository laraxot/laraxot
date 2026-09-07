<?php

declare(strict_types=1);

namespace Modules\Notify\Notifications\Channels;

use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class TelegramChannel
{
    /**
     * Invia la notifica tramite Telegram.
     */
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
    public function send(object $notifiable, Notification $notification): void
=======
    public function send(mixed $notifiable, Notification $notification): void
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    public function send(object $notifiable, Notification $notification): void
>>>>>>> bdc49995 (.)
=======
    public function send(mixed $notifiable, Notification $notification): void
>>>>>>> a988596b (first)
    {
        if (! method_exists($notification, 'toTelegram')) {
            throw new Exception('Il metodo toTelegram() non è definito nella notifica.');
        }

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
        if (! method_exists($notifiable, 'routeNotificationForTelegram')) {
=======
        if (! is_object($notifiable) || ! method_exists($notifiable, 'routeNotificationForTelegram')) {
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        if (! method_exists($notifiable, 'routeNotificationForTelegram')) {
>>>>>>> bdc49995 (.)
=======
        if (! is_object($notifiable) || ! method_exists($notifiable, 'routeNotificationForTelegram')) {
>>>>>>> a988596b (first)
            throw new Exception('Il metodo routeNotificationForTelegram() non è definito nel notifiable.');
        }

        // TODO: Implementare il metodo toTelegram nella notifica
        $message = 'Messaggio Telegram placeholder';
        $chatId = $notifiable->routeNotificationForTelegram();

        if (empty($chatId)) {
            throw new Exception('Chat ID Telegram non trovato per il notifiable.');
        }

        // TODO: Implementare BotTelegramAction e TelegramMessageData
        // Per ora, logghiamo solo l'intento di invio
        Log::debug('Telegram notification would be sent', [
            'chat_id' => $chatId,
<<<<<<< HEAD
<<<<<<< HEAD
            'message' => $message]);
=======
            'message' => $message,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'message' => $message]);
>>>>>>> a988596b (first)
    }
}
