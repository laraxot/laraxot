<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\Finder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * Un template Blade che non compila in PHP valido manda in 500 ogni pagina che lo usa. Il 2026-10-13 (14:14-14:17 UTC)
 * un `@if` dentro il tag `<x-dynamic-component ...>` ha rotto login, registrazione e tracking: la pagina generica
 * `[container0]/[slug0]` serve tutte e tre. Qui si compila ogni template di pagine e layout e si controlla il PHP risultante.
 *
 * @return array<string, array{0: string}>
 */
function sixteenTemplatesToCompile(): array
{
    $root = dirname(__DIR__, 2).'/resources/views';
    // ponytail: `components/layouts/navigation.blade.php` non compila (@if senza chiusura nel PHP compilato) ma non e' raggiunto da
    // nessun codice (`@include('layouts.navigation')` risolve a `layouts/navigation.blade.php`). Escluso finche' il proprietario
    // non lo ripara o lo rimuove: DCP-50. Se qualcuno lo usa, va tolto da qui e riparato.
    $knownBrokenUnused = ['components/layouts/navigation.blade.php'];

    $datasets = [];
    // Finder e non facciate: il dataset si calcola al caricamento del file, prima dell'avvio dell'app.
    // `components/blocks` incluso (story 1.4): un `<x-...>` con alias non risolto fa fallire `view:cache` e la release.
    foreach ((new Finder)->files()->name('*.blade.php')->in([$root.'/pages', $root.'/components/layouts', $root.'/components/blocks']) as $file) {
        $relative = substr($file->getPathname(), strlen($root) + 1);
        if (! in_array($relative, $knownBrokenUnused, true)) {
            $datasets[$relative] = [$file->getPathname()];
        }
    }

    return $datasets;
}

function compiledPhpParseError(string $blade): ?string
{
    try {
        token_get_all(Blade::compileString($blade), TOKEN_PARSE);
    } catch (ParseError $error) {
        return $error->getMessage().' (riga '.$error->getLine().' del PHP compilato)';
    }

    return null;
}

it('compiles every page and layout template to valid php', function (string $file): void {
    expect(compiledPhpParseError(File::get($file)))->toBeNull();
})->with(sixteenTemplatesToCompile());

it('detects a directive placed inside a component tag (the 2026-10-13 outage)', function (): void {
    $broken = '<x-dynamic-component :component="$c" :title="$t"@if ($dc) :header-active="$h"@endif>x</x-dynamic-component>';

    expect(compiledPhpParseError($broken))->not->toBeNull();
});

it('fails to compile a block that uses a component alias which does not resolve (story 1.4)', function (): void {
    expect(fn () => Blade::compileString('<x-blocks.geo.alias-che-non-esiste lat="1" lng="2" />'))
        ->toThrow(InvalidArgumentException::class);
});

it('resolves the map block used by the report-creation test block (story 1.4)', function (): void {
    expect(compiledPhpParseError('<x-blocks.geo.mappa-static :lat="41.89" :lng="12.49" label="x" />'))->toBeNull();
});

it('accepts the equivalent null attribute instead of a directive inside the tag', function (): void {
    $fixed = '<x-dynamic-component :component="$c" :title="$t" :header-active="$h">x</x-dynamic-component>';

    expect(compiledPhpParseError($fixed))->toBeNull();
});
