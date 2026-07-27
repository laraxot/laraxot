<?php

declare(strict_types=1);

/*
 * Standard Rector Configuration for Laravel Modules
 *
 * Minimal configuration compatible with base Rector installation
 * Updated: 2025-11-24
 */
return static function ($rectorConfig): void {
    // Paths to analyze
    $rectorConfig->paths([
        __DIR__,
    ]);

    // Paths to skip
    $rectorConfig->skip([
        __DIR__.'/vendor',
        __DIR__.'/docs',
        __DIR__.'/tests/coverage',
    ]);

    // PHP version target
    $rectorConfig->phpVersion(80100);

    // Rule sets
    $rectorConfig->sets([
        __DIR__.'/../../vendor/rector/rector/config/set/level/up-to-php81.php',
        'code-quality',
        'dead-code',
        'early-return',

        // Type declarations (commented - enable carefully)
        // SetList::TYPE_DECLARATION,

        // Coding style
        // SetList::CODING_STYLE,
    ]);

    // Import names for cleaner code
    $rectorConfig->importNames();

    // Import short classes
    $rectorConfig->importShortClasses(false);
};
