<?php

declare(strict_types=1);

namespace Themes\Sixteen\Actions\Url;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\QueueableAction\QueueableAction;

use function Safe\parse_url;

/**
 * Normalizza URL da CMS/JSON (es. `/it/servizi`) → path locale corrente.
 */
final class NormalizeStoredFrontofficeUrlAction
{
    use QueueableAction;

    public function execute(string $url): string
    {
        if ($url === '' || $url === '#') {
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

        $localized = app(BuildLocalizedFrontofficePathAction::class)->execute($normalized !== '/' ? $normalized : '/');

        return $localized;
    }
}
