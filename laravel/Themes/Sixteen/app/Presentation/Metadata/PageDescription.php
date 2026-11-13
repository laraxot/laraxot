<?php

declare(strict_types=1);

namespace Themes\Sixteen\Presentation\Metadata;

use Safe\Exceptions\JsonException;

use function Safe\json_decode;

/**
 * Descrizione (`<meta name="description">`) di una pagina CMS nella lingua corrente.
 *
 * `Page::description` non e' una colonna traducibile: nel JSON della pagina puo' essere una stringa oppure un oggetto per lingua
 * (`{"it": "...", "en": "..."}`), e il modello lo restituisce come testo grezzo. Senza questa lettura il `<meta>` conteneva il JSON stesso
 * (`/it/tickets/confirmation`). Ordine: lingua corrente, italiano, prima voce non vuota; una stringa semplice resta com'e'.
 */
final class PageDescription
{
    public static function resolve(mixed $raw, string $locale): string
    {
        if (! is_string($raw)) {
            return '';
        }

        $text = trim($raw);
        if ($text === '' || ! str_starts_with($text, '{')) {
            return $text;
        }

        try {
            $byLocale = json_decode($text, true);
        } catch (JsonException) {
            return $text;
        }

        if (! is_array($byLocale)) {
            return $text;
        }

        foreach ([$locale, 'it'] as $key) {
            $value = $byLocale[$key] ?? null;
            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        foreach ($byLocale as $value) {
            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        return '';
    }
}
