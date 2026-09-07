<?php

declare(strict_types=1);

namespace Modules\Notify\Enums;

use Modules\Xot\Traits\EnumTrait;

/**
 * Enum per i driver Telegram supportati
 *
 * Questo enum centralizza la gestione dei driver Telegram disponibili
 * e fornisce metodi helper per ottenere le opzioni e le etichette.
 */
enum TelegramDriverEnum: string
{
    use EnumTrait;

    case TELEGRAM = 'telegram';
    case BOTAPI = 'botapi';
    case LARAVEL_TELEGRAM = 'laravel-telegram';

    /**
     * Restituisce le opzioni per il componente Select di Filament
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::TELEGRAM->value => 'Telegram',
            self::BOTAPI->value => 'Bot API',
<<<<<<< HEAD
<<<<<<< HEAD
            self::LARAVEL_TELEGRAM->value => 'Laravel Telegram'];
=======
            self::LARAVEL_TELEGRAM->value => 'Laravel Telegram',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            self::LARAVEL_TELEGRAM->value => 'Laravel Telegram'];
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
            self::TELEGRAM->value => __('notify::telegram.drivers.telegram'),
            self::BOTAPI->value => __('notify::telegram.drivers.botapi'),
<<<<<<< HEAD
<<<<<<< HEAD
            self::LARAVEL_TELEGRAM->value => __('notify::telegram.drivers.laravel_telegram')];
=======
            self::LARAVEL_TELEGRAM->value => __('notify::telegram.drivers.laravel_telegram'),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            self::LARAVEL_TELEGRAM->value => __('notify::telegram.drivers.laravel_telegram')];
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
        $default = config('telegram.default', self::TELEGRAM->value);

        return self::from(is_string($default) ? $default : self::TELEGRAM->value);
    }
}
