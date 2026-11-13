<?php

declare(strict_types=1);

use Symfony\Component\Finder\Finder;
use Tests\TestCase;

use function Safe\preg_match;
use function Safe\preg_match_all;

uses(TestCase::class);

/**
 * Audit front office 2026-11-12: `/{lang}/tickets/track` mostrava all'utente la chiave grezza
 * `fixcity::ticket.track.my_tickets_title` (e altre sei) in tutte e quattro le lingue, perche' la vista citava chiavi
 * che nessun file lang definiva. Nessun errore, nessun log, nessun test rosso: la vista rende la chiave stessa.
 *
 * La guardia chiede al traduttore reale (loader Laraxot incluso, senza fallback: `hasForLocale`) se ogni chiave
 * letterale `fixcity::file.chiave` citata dalle viste del tema esiste nella lingua. I prefissi dinamici
 * (`'fixcity::personal_area.nav.'.$voce`) sono esclusi: finiscono con un punto e non sono chiavi.
 */
dataset('front office locales', ['it', 'en', 'de', 'es']);

/**
 * @return array<string, string> chiave => primo punto di uso (file:riga)
 */
function sixteenFixcityLiteralKeysCitedByViews(): array
{
    $skip = '#/(pages_old|_old|old|vendor|node_modules)/|\.old(\.|$)#';
    $keys = [];
    foreach (Finder::create()->files()->in(dirname(__DIR__, 2).'/resources/views')->name('*.blade.php') as $file) {
        $path = $file->getPathname();
        if (preg_match($skip, $path) === 1) {
            continue;
        }
        foreach (explode("\n", $file->getContents()) as $index => $line) {
            if (preg_match_all('/(?:__|trans|trans_choice|@lang)\(\s*[\'"](fixcity::[a-z0-9_]+\.[A-Za-z0-9_.\-]*[A-Za-z0-9_])[\'"]/', $line, $matches) === 0) {
                continue;
            }
            foreach ($matches[1] as $key) {
                $keys[$key] ??= $file->getRelativePathname().':'.($index + 1);
            }
        }
    }

    return $keys;
}

it('finds the literal fixcity keys cited by the theme views', function (): void {
    // Una scansione vuota darebbe un falso verde: oggi sono 90, la soglia e' bassa apposta (rinomine, non crescita).
    expect(count(sixteenFixcityLiteralKeysCitedByViews()))->toBeGreaterThan(50);
});

it('translates every literal fixcity key cited by the theme views', function (string $locale): void {
    $translator = app('translator');
    $missing = [];
    foreach (sixteenFixcityLiteralKeysCitedByViews() as $key => $where) {
        if (! $translator->hasForLocale($key, $locale)) {
            $missing[] = $key.'  ('.$where.')';
        }
    }

    expect($missing)->toBe([]);
})->with('front office locales');
