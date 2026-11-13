<?php

declare(strict_types=1);

namespace Themes\Sixteen\Infrastructure\Url;

/**
 * Alias Design Comuni (path IT storici) → container FixCity canonici.
 *
 * Una sola mappa riusabile: Folio shell, redirect 301, test di contratto.
 * Non inventare path nel Blade — qui e' la SSoT degli alias di primo livello.
 */
final class FrontofficeContainerAliases
{
    /**
     * @return array<string, string>
     */
    public static function map(): array
    {
        return [
            'servizi' => 'services',
            'eventi' => 'events',
            'novita' => 'blog',
            'faq' => 'domande-frequenti',
            'segnalazioni' => 'tickets',
        ];
    }

    public static function canonical(string $container0): ?string
    {
        return self::map()[$container0] ?? null;
    }
}
