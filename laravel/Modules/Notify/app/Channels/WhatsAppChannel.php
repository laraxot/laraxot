<?php

declare(strict_types=1);

namespace Modules\Notify\Channels;

use Exception;
use Illuminate\Notifications\Notification;
use Modules\Notify\Datas\WhatsAppData;
use Modules\Notify\Factories\WhatsAppActionFactory;

/**
 * Canale di notifica per l'invio di messaggi WhatsApp.
 *
 * Questo canale utilizza il driver WhatsApp configurato in config/whatsapp.php
 * per inviare messaggi WhatsApp attraverso il provider selezionato.
 */
class WhatsAppChannel
{
    /**
     * Factory per la creazione di azioni WhatsApp.
     */
    private WhatsAppActionFactory $factory;

    /**
     * Crea una nuova istanza del canale.
     */
    public function __construct(WhatsAppActionFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Invia la notifica attraverso il canale WhatsApp.
     *
     * @param  Notification  $notification  Notifica da inviare
     * @return array<string, mixed>|null Risultato dell'operazione o null in caso di errore
     *
     * @throws Exception Se la notifica non ha il metodo toWhatsApp o il driver non è supportato
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
        if (! method_exists($notification, 'toWhatsApp')) {
            throw new Exception('Notification does not have toWhatsApp method');
        }

        $whatsAppData = $notification->toWhatsApp($notifiable);

        if (! ($whatsAppData instanceof WhatsAppData)) {
            throw new Exception('toWhatsApp method must return an instance of WhatsAppData');
        }

        $action = $this->factory->create();

        return $action->execute($whatsAppData);
    }
}
