<?php

declare(strict_types=1);

namespace Modules\Notify\Channels;

use Exception;
use Illuminate\Notifications\Notification;
use Modules\Notify\Actions\SMS\SendSmsFactorSMSAction;
use Modules\Notify\Datas\SmsData;

/**
 * Canale di notifica per l'invio di messaggi SMS.
 *
 * Questo canale utilizza il driver SMS configurato in config/sms.php
 * per inviare messaggi SMS attraverso il provider selezionato.
 */
class SmsChannel
{
    /**
     * Crea una nuova istanza del canale.
     */
    public function __construct(private readonly SendSmsFactorSMSAction $action)
    {
    }

    /**
     * Invia la notifica attraverso il canale SMS.
     *
     * @param  mixed  $notifiable  Entità che riceve la notifica
     * @param  Notification  $notification  Notifica da inviare
     *
     * @return array<string, mixed>|null Risultato dell'operazione o null in caso di errore
     *
     * @throws Exception Se la notifica non ha il metodo toSms o il driver non è supportato
     */
    public function send(mixed $notifiable, Notification $notification): ?array
    {
        if (! method_exists($notification, 'toSms')) {
            throw new Exception('Notification does not have toSms method');
        }

        $smsData = $notification->toSms($notifiable);

        if (! ($smsData instanceof SmsData)) {
            throw new Exception('toSms method must return an instance of SmsData');
        }

        return $this->action->execute($smsData);
    }
}
