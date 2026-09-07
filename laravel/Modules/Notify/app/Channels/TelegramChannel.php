<?php

declare(strict_types=1);

namespace Modules\Notify\Channels;

use Exception;
use Illuminate\Notifications\Notification;
use Modules\Notify\Datas\TelegramData;
use Modules\Notify\Factories\TelegramActionFactory;

/**
 * Canale di notifica per l'invio di messaggi Telegram.
 *
 * Questo canale utilizza il driver Telegram configurato in config/telegram.php
 * per inviare messaggi Telegram attraverso il provider selezionato.
 */
class TelegramChannel
{
    /**
     * Factory per la creazione di azioni Telegram.
     */
    private TelegramActionFactory $factory;

    /**
     * Crea una nuova istanza del canale.
     */
    public function __construct(TelegramActionFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Invia la notifica attraverso il canale Telegram.
     *
     * @param  Notification  $notification  Notifica da inviare
     * @return array<string, mixed>|null Risultato dell'operazione o null in caso di errore
     *
     * @throws Exception Se la notifica non ha il metodo toTelegram o il driver non è supportato
     */
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
    public function send(object $notifiable, Notification $notification): ?array
=======
    public function send(mixed $notifiable, Notification $notification): ?array
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    public function send(object $notifiable, Notification $notification): ?array
>>>>>>> bdc49995 (.)
=======
    public function send(mixed $notifiable, Notification $notification): ?array
>>>>>>> a988596b (first)
    {
        if (! method_exists($notification, 'toTelegram')) {
            throw new Exception('Notification does not have toTelegram method');
        }

        $telegramData = $notification->toTelegram($notifiable);

        if (! ($telegramData instanceof TelegramData)) {
            throw new Exception('toTelegram method must return an instance of TelegramData');
        }

        $action = $this->factory->create();

        return $action->execute($telegramData);
    }
}
