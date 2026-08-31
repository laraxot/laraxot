<?php

declare(strict_types=1);

/**
 * Costanti definite prima delle extension Larastan/Pest.
 *
 * LarastanStubFilesExtension legge LARAVEL_VERSION durante il bootstrap PHPStan.
 * Se la costante non è ancora definita (race con bootstrap Laravel), PHPStan
 * fallisce con: Undefined constant "Larastan\Larastan\LARAVEL_VERSION".
 */
if (! defined('LARAVEL_VERSION')) {
    $laravelVersion = '12.0.0';
    $installed = __DIR__.'/vendor/composer/installed.php';
    if (is_file($installed)) {
        /** @var array{versions?: array<string, array{pretty_version?: string}>} $packages */
        $packages = require $installed;
        $pretty = $packages['versions']['laravel/framework']['pretty_version'] ?? null;
        if (is_string($pretty) && $pretty !== '') {
            $laravelVersion = ltrim($pretty, 'v');
        }
    }

    define('LARAVEL_VERSION', $laravelVersion);
}
