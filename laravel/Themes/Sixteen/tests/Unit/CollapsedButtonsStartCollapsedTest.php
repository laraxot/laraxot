<?php

declare(strict_types=1);

use Symfony\Component\Finder\Finder;

use function Safe\file_get_contents;
use function Safe\preg_match;
use function Safe\preg_match_all;

/**
 * WCAG 4.1.2: `aria-expanded` deve dire la verita'. Le FAQ, le pratiche della segnalazione e la trascrizione del video avevano pulsanti
 * `accordion-button collapsed` con `aria-expanded="true"` e pannello chiuso (copiato dal riferimento Design Comuni "per la parity"): chi usa un
 * lettore di schermo sentiva "espanso" su un elemento chiuso, e dopo il primo clic lo stato restava sfasato (audit 2026-11-11, F-01).
 * Un pulsante con classe `collapsed` e' chiuso per definizione di Bootstrap, quindi non puo' dichiarare `true`; con `false` il JS condiviso lo alterna.
 */
test('no collapsed toggle button declares aria-expanded true', function (): void {
    $views = dirname(__DIR__, 2).'/resources/views';

    $collapsed = 0;
    $problems = [];
    foreach ((new Finder)->files()->name('*.blade.php')->in($views) as $file) {
        $source = (string) file_get_contents($file->getPathname());
        preg_match_all('/<button\b[^>]*>/s', $source, $tags);
        foreach ($tags[0] as $tag) {
            if (preg_match('/\bclass="[^"]*\bcollapsed\b[^"]*"/', $tag) !== 1) {
                continue;
            }
            $collapsed++;
            if (str_contains($tag, 'aria-expanded="true"')) {
                $problems[] = substr($file->getPathname(), strlen($views) + 1);
            }
        }
    }

    expect($collapsed)->toBeGreaterThan(2, 'nessun pulsante `collapsed` letto: la guardia passerebbe a vuoto');
    expect($problems)->toBe([], "pulsanti `collapsed` con aria-expanded=\"true\":\n".implode("\n", $problems));
});
