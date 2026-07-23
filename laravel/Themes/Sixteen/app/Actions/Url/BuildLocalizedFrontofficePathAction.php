<?php

declare(strict_types=1);

namespace Themes\Sixteen\Actions\Url;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\QueueableAction\QueueableAction;

/**
 * Path CMS/JSON localizzati senza prefisso lingua hardcoded.
 */
final class BuildLocalizedFrontofficePathAction
{
    use QueueableAction;

    public function execute(string $path): string
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
}
