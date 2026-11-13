<?php

declare(strict_types=1);

use function Safe\file_get_contents;
use function Safe\preg_match_all;

/**
 * Ogni `@include($var.'.nome')` deve trovare il file nella cartella che `$var` indica (`$parts`, `$wizard`, ...:
 * qualunque variabile assegnata a una stringa `pub_theme::cartella`).
 *
 * Il file che include puo' ereditare `$var` dal padre (es. `report-step/layout-narrow` usa `$parts` assegnato da
 * `report-step/design-comuni`): la variabile si cerca nel file stesso, poi nelle cartelle antenate.
 *
 * Una vista mancante non rompe il build ne' i test che non la rendono: la pagina va in 500 solo quando viene
 * servita. Caso reale: `report-partials/{rating,stepper}`, `wizard/summary-*`, `auth/{field,check,error-summary}` e le
 * famiglie `content-page`, `report-step`, `appointment-step` cancellate dalla sync "." 6cb55f0d (2026-11-11), con
 * `/it/tickets`, `/it/auth/login` e `/it/domande-frequenti` in 500.
 *
 * Non copre gli include col nome composto a runtime (`...layout-'.$d('layout')`): quelli si provano con la scansione
 * HTTP, vedi la story.
 *
 * @see laravel/Themes/Sixteen/docs/stories/auth-components-regression-restore.story.md
 */
test('ogni partial incluso con una variabile pub_theme esiste su disco', function (): void {
    $viewsRoot = dirname(__DIR__, 2).'/resources/views';
    $sources = [];
    /** @var array<string, array<string, string>> $assigned cartella-del-file => [variabile => cartella-vista] */
    $assigned = [];

    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsRoot, FilesystemIterator::SKIP_DOTS));
    foreach ($files as $file) {
        if (! $file instanceof SplFileInfo || ! str_ends_with($file->getFilename(), '.blade.php')) {
            continue;
        }

        $source = file_get_contents($file->getPathname());
        $sources[$file->getPathname()] = $source;
        preg_match_all('/\$(\w+)\s*=\s*[\'"]pub_theme::([\w.\-]+)[\'"]\s*;/', $source, $bases, PREG_SET_ORDER);
        foreach ($bases as [, $variable, $viewFolder]) {
            $assigned[$file->getPath()][$variable] = str_replace('.', '/', $viewFolder);
        }
    }

    $missing = [];
    foreach ($sources as $path => $source) {
        preg_match_all('/\$(\w+)\s*\.\s*[\'"]\.([\w\-]+)[\'"]/', $source, $used, PREG_SET_ORDER);
        foreach ($used as [, $variable, $partial]) {
            $folder = null;
            for ($dir = dirname($path); str_starts_with($dir, $viewsRoot); $dir = dirname($dir)) {
                if (isset($assigned[$dir][$variable])) {
                    $folder = $assigned[$dir][$variable];
                    break;
                }
            }

            if ($folder !== null && ! is_file($viewsRoot.'/'.$folder.'/'.$partial.'.blade.php')) {
                $missing[] = substr($path, strlen($viewsRoot) + 1).' -> '.$folder.'/'.$partial;
            }
        }
    }

    expect(array_values(array_unique($missing)))->toBe([]);
});

/*
 * Le viste nuove sotto resources/views/components e resources/views/layouts devono poter entrare in git. Le righe
 * `components` e `layouts` senza slash iniziale (aggiunte dalla sync 6cb55f0d) le ignoravano a qualunque profondita':
 * i file ripristinati restavano su disco, fuori da ogni commit, e la sync successiva li perdeva di nuovo.
 */
test('il gitignore del tema non nasconde resources/views/components ne layouts', function (): void {
    $lines = explode("\n", file_get_contents(dirname(__DIR__, 2).'/.gitignore'));

    expect(array_intersect($lines, ['components', 'components/', 'layouts', 'layouts/', '**/components', '**/layouts']))->toBe([]);
});
