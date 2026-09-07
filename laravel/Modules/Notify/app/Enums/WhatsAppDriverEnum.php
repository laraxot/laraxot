<?php

declare(strict_types=1);

namespace Modules\Notify\Enums;

use Modules\Xot\Traits\EnumTrait;

/**
 * Enum per i driver WhatsApp supportati
 *
 * Questo enum centralizza la gestione dei driver WhatsApp disponibili
 * e fornisce metodi helper per ottenere le opzioni e le etichette.
 */
enum WhatsAppDriverEnum: string
{
    use EnumTrait;

    case TWILIO = 'twilio';
    case MESSAGEBIRD = 'messagebird';
    case VONAGE = 'vonage';
    case INFOBIP = 'infobip';

    /**
     * Restituisce le opzioni per il componente Select di Filament
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::TWILIO->value => 'Twilio',
            self::MESSAGEBIRD->value => 'MessageBird',
            self::VONAGE->value => 'Vonage',
<<<<<<< HEAD
<<<<<<< HEAD
            self::INFOBIP->value => 'Infobip'];
=======
            self::INFOBIP->value => 'Infobip',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            self::INFOBIP->value => 'Infobip'];
>>>>>>> a988596b (first)
    }

    /**
     * Restituisce le etichette localizzate per il componente Select di Filament
     *
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return [
            self::TWILIO->value => __('notify::whatsapp.drivers.twilio'),
            self::MESSAGEBIRD->value => __('notify::whatsapp.drivers.messagebird'),
            self::VONAGE->value => __('notify::whatsapp.drivers.vonage'),
<<<<<<< HEAD
<<<<<<< HEAD
            self::INFOBIP->value => __('notify::whatsapp.drivers.infobip')];
=======
            self::INFOBIP->value => __('notify::whatsapp.drivers.infobip'),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            self::INFOBIP->value => __('notify::whatsapp.drivers.infobip')];
>>>>>>> a988596b (first)
    }

    /**
     * Verifica se un driver è supportato
     */
    public static function isSupported(string $driver): bool
    {
        return in_array($driver, array_column(self::cases(), 'value'), strict: true);
    }

    /**
     * Restituisce il driver predefinito dal file di configurazione
     */
    public static function getDefault(): self
    {
        $default = config('whatsapp.default', self::TWILIO->value);

        return self::from(is_string($default) ? $default : self::TWILIO->value);
    }
}
