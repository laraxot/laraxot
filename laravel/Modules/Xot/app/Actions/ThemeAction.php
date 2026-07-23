<?php

declare(strict_types=1);

namespace Modules\Xot\Actions;

use Illuminate\Support\Facades\Config;
use Spatie\QueueableAction\QueueableAction;

/**
 * Class ThemeAction
 * Gestisce il tema dell'applicazione.
 */
class ThemeAction
{
    use QueueableAction;

    private static string $currentTheme = 'default';

    public static function setTheme(string $theme): void
    {
        self::$currentTheme = $theme;
        Config::set('theme.active', $theme);
    }

    public static function getTheme(): string
    {
        return self::$currentTheme;
    }

    public static function isTheme(string $theme): bool
    {
        return self::$currentTheme === $theme;
    }

    public static function getThemePath(): string
    {
        return resource_path('themes/'.self::$currentTheme);
    }

    public function execute(): void
    {
    }
}
