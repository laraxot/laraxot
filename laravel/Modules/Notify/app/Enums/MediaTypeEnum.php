<?php

declare(strict_types=1);

namespace Modules\Notify\Enums;

use Modules\Xot\Traits\EnumTrait;

/**
 * Enum per i tipi di media supportati
 *
 * Questo enum centralizza la gestione dei tipi di media disponibili
 * e fornisce metodi helper per ottenere le opzioni e le etichette.
 */
enum MediaTypeEnum: string
{
    use EnumTrait;

    case IMAGE = 'image';
    case VIDEO = 'video';
    case DOCUMENT = 'document';
    case AUDIO = 'audio';

    /**
     * Restituisce le opzioni per il componente Select di Filament
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::IMAGE->value => 'Image',
            self::VIDEO->value => 'Video',
            self::DOCUMENT->value => 'Document',
<<<<<<< HEAD
<<<<<<< HEAD
            self::AUDIO->value => 'Audio'];
=======
            self::AUDIO->value => 'Audio',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            self::AUDIO->value => 'Audio'];
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
            self::IMAGE->value => __('notify::whatsapp.media_types.image'),
            self::VIDEO->value => __('notify::whatsapp.media_types.video'),
            self::DOCUMENT->value => __('notify::whatsapp.media_types.document'),
<<<<<<< HEAD
<<<<<<< HEAD
            self::AUDIO->value => __('notify::whatsapp.media_types.audio')];
=======
            self::AUDIO->value => __('notify::whatsapp.media_types.audio'),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            self::AUDIO->value => __('notify::whatsapp.media_types.audio')];
>>>>>>> a988596b (first)
    }

    /**
     * Verifica se un tipo di media è supportato
     */
    public static function isSupported(string $type): bool
    {
        return in_array($type, array_column(self::cases(), 'value'), strict: true);
    }

    /**
     * Restituisce il tipo di media predefinito
     */
    public static function getDefault(): self
    {
        return self::IMAGE;
    }
}
