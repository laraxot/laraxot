<?php

declare(strict_types=1);

namespace Themes\Sixteen\Infrastructure\Url;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

use function Safe\parse_url;

/**
 * Utility URL CMS/JSON — path localizzati senza prefisso lingua hardcoded.
 *
 * Il menu header utente usa `route('<folio-name>')` verificate con `php artisan folio:list`.
 * Non aggiungere wrapper che duplicano path inventati per voci menu.
 *
 * Canon: laravel/Themes/Sixteen/docs/wiki/concepts/fo-folio-named-routes-header.md
 */
final class FrontofficeUrl
{
    public static function path(string $path): string
    {
        if ($path === '' || ! str_starts_with($path, '/')) {
            return $path;
        }

        /** @var string|null $localized */
        $localized = LaravelLocalization::getLocalizedURL(
            LaravelLocalization::getCurrentLocale(),
            $path
        );

        return is_string($localized) && $localized !== '' ? $localized : $path;
    }

    /**
     * Normalizza URL da CMS/JSON (es. `/it/servizi`) → path locale corrente.
     */
    public static function fromStoredUrl(string $url): string
    {
        if ($url === '' || $url === '#' || self::isExternal($url)) {
            return $url;
        }

        $path = parse_url($url, PHP_URL_PATH);
        if (! is_string($path) || $path === '') {
            return $url;
        }

        $normalized = '/'.ltrim($path, '/');
        $supported = array_keys(LaravelLocalization::getSupportedLocales());
        $segments = explode('/', ltrim($normalized, '/'));
        if ($segments !== [] && in_array($segments[0], $supported, true)) {
            $normalized = '/'.implode('/', array_slice($segments, 1));
        }

        return self::path($normalized !== '/' ? $normalized : '/');
    }

    /**
     * Link che non sono path del sito: altro host (social, portale regione) o schema non web (`tel:`, `mailto:`).
     */
    private static function isExternal(string $url): bool
    {
        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (! is_string($scheme)) {
            return false;
        }
        if (! in_array(strtolower($scheme), ['http', 'https'], true)) {
            return true;
        }
        $host = parse_url($url, PHP_URL_HOST);

        return is_string($host) && strcasecmp($host, request()->getHost()) !== 0;
    }

    /**
     * Solo parity Design Comuni / demo statiche — non usare in header produzione.
     */
    public static function testsParity(string $slug): string
    {
        return self::path('/tests/'.$slug);
    }
}
