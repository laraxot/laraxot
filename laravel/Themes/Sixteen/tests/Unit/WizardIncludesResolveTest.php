<?php

declare(strict_types=1);

use Symfony\Component\Finder\Finder;

use function Safe\file_get_contents;
use function Safe\preg_match_all;

/**
 * Il riepilogo del wizard e' fatto di partial inclusi per nome. Il 2026-11-11 un mirror ha cancellato
 * `wizard/report-partials/images-preview.blade.php` (anteprime foto, DCP-182) lasciando l'`@include` in `summary-sections`:
 * "View not found" e 500 su `/tickets/create` per ogni utente loggato, mentre i test passavano (`BladeTemplatesCompileTest` non
 * guarda `components/wizard` e comunque compila, non risolve gli include). Qui ogni `@include` di quella cartella, letterale o
 * con prefisso `$variabile.`, deve puntare a un file che esiste. Un prefisso non risolvibile fa fallire il test, non lo salta.
 */
test('every include of the wizard views points to an existing view', function (): void {
    $views = dirname(__DIR__, 2).'/resources/views';

    $sources = [];
    foreach ((new Finder)->files()->name('*.blade.php')->in($views.'/components/wizard') as $file) {
        $sources[substr($file->getPathname(), strlen($views) + 1)] = (string) file_get_contents($file->getPathname());
    }
    expect($sources)->not->toBe([], 'nessuna vista del wizard letta: la guardia passerebbe a vuoto');

    $prefixes = [];
    foreach ($sources as $source) {
        preg_match_all('/\$(\w+)\s*=\s*\'(pub_theme::[^\']+)\'\s*;/', $source, $assignments, PREG_SET_ORDER);
        foreach ($assignments as $assignment) {
            $prefixes[$assignment[1]] = $assignment[2];
        }
    }

    $checked = 0;
    $problems = [];
    foreach ($sources as $relative => $source) {
        preg_match_all('/@include\(\s*(?:\$(\w+)\s*\.\s*)?\'([^\']+)\'/', $source, $includes, PREG_SET_ORDER);
        foreach ($includes as $include) {
            $variable = $include[1];
            if ($variable !== '' && ! isset($prefixes[$variable])) {
                $problems[] = $relative.': prefisso $'.$variable.' non risolvibile';

                continue;
            }
            $name = ($variable === '' ? '' : $prefixes[$variable]).$include[2];
            if (! str_starts_with($name, 'pub_theme::')) {
                continue; // altri namespace (moduli): non sono nel tema
            }
            $checked++;
            $target = $views.'/'.str_replace('.', '/', substr($name, strlen('pub_theme::'))).'.blade.php';
            if (! is_file($target)) {
                $problems[] = $relative.': '.$name.' non esiste';
            }
        }
    }

    expect($checked)->toBeGreaterThan(4, 'troppi pochi include controllati: la guardia passerebbe a vuoto');
    expect($problems)->toBe([], "\n".implode("\n", $problems));
});
