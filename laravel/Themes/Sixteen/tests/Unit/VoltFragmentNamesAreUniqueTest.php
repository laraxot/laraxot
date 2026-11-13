<?php

declare(strict_types=1);

use function Safe\file_get_contents;
use function Safe\preg_match_all;

/**
 * Volt scrive il frammento di `@volt('nome')` in `storage/framework/views/<md5 del nome>.blade.php`: il percorso del file NON
 * entra nel nome. Due pagine con lo stesso `@volt('container0.view')` si sovrascrivono a vicenda, e una compilazione completa delle
 * viste (`view:cache`, `optimize`) mette per ultima `pages_old/`, quindi la pagina viva perdeva il frammento nuovo (`data-dc`):
 * area personale, wizard e dettaglio blog senza lo scope Design Comuni.
 *
 * @see laravel/Themes/Sixteen/docs/bmad/audit/fo-giro-loggato.md (FO-01, FO-02, FO-08..FO-12)
 */
test('i nomi @volt delle pagine vive non compaiono in nessun altro file del tema', function (): void {
    $views = dirname(__DIR__, 2).'/resources/views';
    $live = [];
    $others = [];

    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($views, FilesystemIterator::SKIP_DOTS));
    foreach ($files as $file) {
        if (! $file instanceof SplFileInfo || ! str_ends_with($file->getFilename(), '.blade.php')) {
            continue;
        }
        $isLive = str_starts_with($file->getPathname(), $views.'/pages/');
        preg_match_all("/@volt\\(\\s*'([^']+)'\\s*\\)/", (string) file_get_contents($file->getPathname()), $matches);
        foreach ($matches[1] as $name) {
            if ($isLive) {
                $live[$name][] = $file->getPathname();
            } else {
                $others[$name][] = $file->getPathname();
            }
        }
    }

    expect(array_intersect_key($others, $live))->toBe([]);
});
