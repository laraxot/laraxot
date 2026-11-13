<?php

declare(strict_types=1);

use Symfony\Component\Finder\Finder;
use Tests\TestCase;

use function Safe\file_get_contents;
use function Safe\json_decode;
use function Safe\preg_match;

uses(TestCase::class);

/*
 * DCP-289: nessun dato dell'ente finto nei contenuti del front office. Il numero verde, il nome della regione e i recapiti arrivano da
 * `config('ente')`; i file dei contenuti possono solo RIFERIRLI (`"ente": "toll_free"`, `:value` nell'etichetta). Le repliche di prova del
 * riferimento Design Comuni (`pages/tests/*`, `tests.*.json`) tengono i valori del riferimento e non sono front office.
 *
 * Una voce con `"url": "#"` resta nel JSON ed e' nascosta in resa da ResolveContactGroupsAction: riappare quando riceve un URL vero.
 */

/**
 * @return list<string> percorsi dei JSON di contenuto del front office, senza le repliche di prova
 */
function dcp289LiveContentFiles(): array
{
    $finder = Finder::create()->files()->name('*.json')->notName('tests.*.json')->notPath('/pages\/tests\//')
        ->in([base_path('config/local/fixcity/database/content'), base_path('Modules/Fixcity/config/database/content')]);

    $paths = [];
    foreach ($finder as $file) {
        $paths[] = $file->getPathname();
    }

    return $paths;
}

/**
 * @return list<string> una riga "percorso = valore" per ogni stringa del documento
 */
function dcp289Leaves(mixed $node, string $path = ''): array
{
    if (is_string($node)) {
        return [$path.' = '.$node];
    }
    if (! is_array($node)) {
        return [];
    }
    $leaves = [];
    foreach ($node as $key => $value) {
        $leaves = [...$leaves, ...dcp289Leaves($value, $path === '' ? (string) $key : $path.'.'.$key)];
    }

    return $leaves;
}

it('has no fake toll-free number in the front office content files', function (): void {
    $offenders = [];
    foreach (dcp289LiveContentFiles() as $file) {
        foreach (dcp289Leaves(json_decode(file_get_contents($file), true)) as $leaf) {
            if (preg_match('/05 ?0505|tel:050505/', $leaf) === 1) {
                $offenders[] = basename($file).': '.$leaf;
            }
        }
    }

    expect($offenders)->toBe([]);
});

it('allows the explicit Demo region label while forbidding a fake region URL', function (): void {
    $offenders = [];
    foreach (dcp289LiveContentFiles() as $file) {
        if (basename($file) !== 'header.json') {
            continue;
        }
        foreach (dcp289Leaves(json_decode(file_get_contents($file), true)) as $leaf) {
            if (preg_match('/\.region\.url = ./', $leaf) === 1
                || (preg_match('/\.region\.name = /', $leaf) === 1 && ! str_ends_with($leaf, ' = Demo'))) {
                $offenders[] = $leaf;
            }
        }
    }

    expect($offenders)->toBe([]);
});

it('has no fake toll-free number in the strings of the live help boxes', function (string $locale): void {
    $phone = data_get(require base_path('Modules/Fixcity/lang/'.$locale.'/ticket_list.php'), 'contacts.phone');

    expect($phone)->toBeString()->toContain(':value')->not->toMatch('/\d/');
})->with(['it', 'en', 'de', 'es']);

it('has no fake toll-free number in the live contact templates and in the theme parity config', function (): void {
    $files = [
        'resources/views/components/blocks/report-partials/contacts-card.blade.php',
        'resources/views/components/blocks/report-partials/contacts.blade.php',
        'resources/views/components/blocks/partials/dc-contacts.blade.php',
        'resources/views/components/blocks/ticket-list/design-comuni.blade.php',
        'resources/views/components/sections/header/v1.blade.php',
        'resources/views/components/sections/footer/v1.blade.php',
        'config/sixteen.php',
    ];

    $offenders = [];
    foreach ($files as $file) {
        if (preg_match('/05 ?0505|tel:050505/', file_get_contents(base_path('Themes/Sixteen/'.$file))) === 1) {
            $offenders[] = $file;
        }
    }

    expect($offenders)->toBe([]);
});
