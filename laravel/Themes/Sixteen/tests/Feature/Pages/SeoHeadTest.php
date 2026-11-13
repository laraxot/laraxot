<?php

declare(strict_types=1);

use Dom\HTMLDocument;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

use function Pest\Laravel\get;

uses(TestCase::class);

/**
 * DCP-288 (FO-6-15, 17, 18, 20, 22, 25): metadati e indirizzi canonici delle pagine servite dal layout `x-layouts.main`.
 */
function headOf(string $url): HTMLDocument
{
    $html = get($url)->assertOk()->getContent();
    Assert::assertIsString($html);

    return HTMLDocument::createFromString($html, LIBXML_NOERROR);
}

function headContent(HTMLDocument $document, string $selector): ?string
{
    return $document->querySelector($selector)?->getAttribute('content');
}

it('brands the title of inner pages and repeats it in og:title and twitter:title', function (): void {
    $document = headOf('/it/tickets');

    expect($document->querySelector('title')?->textContent)->toBe('Elenco segnalazioni | FixCity')
        ->and(headContent($document, 'meta[property="og:title"]'))->toBe('Elenco segnalazioni | FixCity')
        ->and(headContent($document, 'meta[name="twitter:title"]'))->toBe('Elenco segnalazioni | FixCity');
});

it('does not repeat the brand where the title already has it', function (string $url, string $title): void {
    expect(headOf($url)->querySelector('title')?->textContent)->toBe($title);
})->with([
    'home' => ['/it', "FixCity \u{2014} Segnala un problema, segui la soluzione"],
    'login' => ['/it/auth/login', 'Accedi a FixCity'],
]);

it('marks private and service pages noindex and leaves the public ones alone', function (string $url, ?string $robots): void {
    expect(headContent(headOf($url), 'meta[name="robots"]'))->toBe($robots);
})->with([
    'login' => ['/it/auth/login', 'noindex,follow'],
    'tracking' => ['/en/tickets/track', 'noindex,follow'],
    'confirmation' => ['/it/tickets/confirmation', 'noindex,follow'],
    'list' => ['/it/tickets', null],
    'privacy' => ['/it/privacy', null],
]);

it('declares the theme color and the regional og:locale', function (string $locale, string $regional): void {
    $document = headOf('/'.$locale.'/tickets');

    expect(headContent($document, 'meta[name="theme-color"]'))->toBe('#007a52')
        ->and(headContent($document, 'meta[property="og:locale"]'))->toBe($regional);
})->with([
    ['it', 'it_IT'],
    ['en', 'en_GB'],
    ['de', 'de_DE'],
    ['es', 'es_ES'],
]);

it('does not invent an og:image or an icon', function (): void {
    $document = headOf('/it/tickets');

    expect($document->querySelector('meta[property="og:image"]'))->toBeNull()
        ->and($document->querySelector('link[rel~="icon"]'))->toBeNull();
});

it('keeps the page number in canonical and alternates and drops the filters', function (string $query, string $suffix): void {
    $document = headOf('/it/tickets'.$query);

    expect($document->querySelector('link[rel="canonical"]')?->getAttribute('href'))->toEndWith('/it/tickets'.$suffix)
        ->and($document->querySelector('link[hreflang="de"]')?->getAttribute('href'))->toEndWith('/de/tickets'.$suffix)
        ->and($document->querySelector('link[hreflang="x-default"]')?->getAttribute('href'))->toEndWith('/it/tickets'.$suffix);
})->with([
    'no query' => ['', ''],
    'page 2' => ['?page=2', '?page=2'],
    'page 2 with a filter' => ['?q=buca&page=2', '?page=2'],
    'page 1 is the base page' => ['?page=1', ''],
    'a filter only' => ['?q=buca', ''],
    'not a number' => ['?page=abc', ''],
    'negative' => ['?page=-3', ''],
]);

it('gives the categories page a title that says what its heading says', function (string $locale): void {
    $document = headOf('/'.$locale.'/lista-categorie');
    $heading = trim((string) $document->querySelector('main h1')?->textContent);

    expect($heading)->not->toBe('')
        ->and($document->querySelector('title')?->textContent)->toBe($heading.' | FixCity');
})->with(['it', 'en', 'de', 'es']);
