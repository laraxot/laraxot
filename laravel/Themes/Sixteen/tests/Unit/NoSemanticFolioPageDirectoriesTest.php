<?php

declare(strict_types=1);

use function Safe\glob;
use function Safe\scandir;

/**
 * Il tema veste. Il dominio sta nel JSON CMS, non in una cartella Folio.
 * pages/domande-frequenti/ e pages/profile/ sono sbagliati: Folio darebbe a
 * quelle cartelle la precedenza su [container0] (FAQ) o creerebbe un alias
 * morto/competitivo col modulo User (profile/edit — creato ≥3 volte).
 *
 * @see laravel/Themes/Sixteen/docs/page-directory-structure.md
 * @see laravel/Themes/Sixteen/docs/bmad/knowledge/why-profile-edit-blade-must-not-exist.md
 * @see laravel/Themes/Sixteen/docs/bmad/knowledge/why-domande-frequenti-blade-php-must-not-exist.md
 */
test('sixteen pages ammette solo auth, container0 e tests', function (): void {
    $pagesRoot = dirname(__DIR__, 2).'/resources/views/pages';
    $allowed = ['[container0]', 'auth', 'tests'];

    /** @var array<int, string> */
    $scanned = scandir($pagesRoot) ?: [];
    $found = [];
    foreach ($scanned as $entry) {
        if ($entry === '.' || $entry === '..') {
            continue;
        }
        if (is_dir($pagesRoot.'/'.$entry)) {
            $found[] = $entry;
        }
    }

    $extra = array_values(array_diff($found, $allowed));

    expect($extra)->toBe([]);
    expect(is_dir($pagesRoot.'/domande-frequenti'))->toBeFalse();
    expect(is_file($pagesRoot.'/domande-frequenti/index.blade.php'))->toBeFalse();
    // Folio 1.2.0 prova MatchLiteralDirectories prima di MatchWildcardDirectories e MatchLiteralViews dopo: la cartella
    // omonima intercetta l'URL, un file `domande-frequenti.blade.php` perde contro [container0] (misurato) ma resta una
    // seconda sorgente morta che finge di possedere la pagina. Nessuna delle due forme.
    expect(glob($pagesRoot.'/domande-frequenti*') ?: [])->toBe([]);

    // Caso reale ripetuto: pages/profile/edit.blade.php (alias «fix 404»). FO usa
    // FrontofficeRoutes::personalAreaImpostazioni() → container0 area-personale/impostazioni.
    expect(is_dir($pagesRoot.'/profile'))->toBeFalse();
    expect(is_file($pagesRoot.'/profile/edit.blade.php'))->toBeFalse();
    expect(glob($pagesRoot.'/profile*') ?: [])->toBe([]);

    expect(is_dir($pagesRoot.'/[container0]'))->toBeTrue();
});

/*
 * Anche i FILE in radice di pages/ sono pagine: l'unico ammesso e' index.blade.php (`/{lingua}`, monta home.json).
 * home.blade.php e homepage.blade.php (creati 2026-10-07, tolti 10-11, ricreati 10-18 dal merge di laraxot/dev) erano
 * una seconda homepage scritta a mano accanto a home.json, e home.blade.php ridichiarava name('home') di index.blade.php.
 * Gli altri file in radice non servivano la loro URL (misurato 2026-10-23: privacy e cookie-policy le serve [container0]
 * dal JSON, counter/prova01/show/bootstrap-italia-showcase 404): sorgenti morte che fingono di possedere una pagina.
 * @see laravel/Themes/Sixteen/docs/bmad/knowledge/why-home-blade-must-not-exist.md
 */
test('in radice di pages ammette solo index.blade.php', function (): void {
    $pagesRoot = dirname(__DIR__, 2).'/resources/views/pages';

    /** @var array<int, string> */
    $scanned = scandir($pagesRoot) ?: [];
    $files = [];
    foreach ($scanned as $entry) {
        if (is_file($pagesRoot.'/'.$entry) && $entry !== '.gitkeep') {
            $files[] = $entry;
        }
    }

    expect(array_values(array_diff($files, ['index.blade.php'])))->toBe([]);
});
