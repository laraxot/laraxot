<?php

declare(strict_types=1);

use Symfony\Component\Finder\Finder;

/**
 * Un sorgente PHP che non compila e' invisibile finche' qualcuno non lo carica: il tema non e' nell'autoload della radice,
 * quindi il sito in esecuzione non lo tocca, ma PHPStan sul tema, un test o un `composer dump-autoload` si' (13 file nel
 * 2026-11-11: righe vecchie e nuove incollate sulla stessa riga, `use` doppi, `try` senza `catch`). La fusione sbagliata
 * arriva dal remoto `laraxot/theme_sixteen_fila5` e ogni mirror la riporta: la story `mirror-merge-corruption-php-recovery`
 * era `done` e il difetto e' tornato. `php -l` e non il solo parser, perche' un `use` doppio e' un errore di compilazione
 * che il parser accetta. I template Blade li copre `BladeTemplatesCompileTest`.
 */
test('every PHP source of the theme compiles', function (): void {
    $root = dirname(__DIR__, 2);
    $dirs = array_values(array_filter(
        array_map(static fn (string $d): string => $root.'/'.$d, ['app', 'routes', 'config', 'database', 'http', 'src']),
        'is_dir',
    ));
    expect($dirs)->not->toBe([], 'nessuna cartella da controllare: la guardia passerebbe a vuoto');

    $checked = 0;
    $broken = [];
    foreach ((new Finder)->files()->name('*.php')->notName('*.blade.php')->in($dirs) as $file) {
        $checked++;
        $output = [];
        $code = 0;
        exec(escapeshellarg(PHP_BINARY).' -l '.escapeshellarg($file->getPathname()).' 2>&1', $output, $code);
        if ($code !== 0) {
            $broken[] = substr($file->getPathname(), strlen($root) + 1).': '.trim(implode(' ', $output));
        }
    }

    expect($checked)->toBeGreaterThan(50, 'troppo pochi file letti: la guardia passerebbe a vuoto');
    expect($broken)->toBe([], "\n".implode("\n", $broken));
});
