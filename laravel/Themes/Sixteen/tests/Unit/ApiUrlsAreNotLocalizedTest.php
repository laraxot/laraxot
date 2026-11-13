<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;

use function Safe\file_get_contents;
use function Safe\preg_match;

/*
 * DCP-186: le pagine Folio dell'API rispondono JSON solo senza prefisso lingua. Misurato il 2026-10-14:
 * `/api/tickets/list` -> 200 application/json, `/it/api/tickets/list` -> 200 text/html (il layout, oscurato dal catch-all
 * `[container0]/[slug0]/[container1]`). Un blocco che localizza l'URL dell'API ottiene HTML con stato 200: `response.ok` e'
 * vero, `response.json()` fallisce e il «carica altre» della lista sparisce in silenzio. Tutti gli altri consumatori usano
 * gia' il percorso senza lingua; questa guardia impedisce che un blocco nuovo lo localizzi.
 */
$viewsRoot = dirname(__DIR__, 2).'/resources/views';

it('never localizes an api url, because the prefixed path returns the html layout instead of json', function () use ($viewsRoot): void {
    $scanned = 0;
    $offenders = [];

    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsRoot, FilesystemIterator::SKIP_DOTS)) as $file) {
        if (! $file instanceof SplFileInfo || ! str_ends_with($file->getFilename(), '.blade.php') || str_contains($file->getPathname(), '/pages_old/')) {
            continue;
        }
        $scanned++;
        if (preg_match('/localizeURL\(\s*[\'"]\/api\//', file_get_contents($file->getPathname())) === 1) {
            $offenders[] = substr($file->getPathname(), strlen($viewsRoot) + 1);
        }
    }

    Assert::assertGreaterThan(0, $scanned, 'Nessuna vista letta: la guardia passerebbe a vuoto.');
    Assert::assertSame([], $offenders, "Usano localizeURL('/api/...'), che risponde HTML: togliere la localizzazione.");
});
