<?php

declare(strict_types=1);

use Dom\Element;
use Dom\HTMLDocument;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

use function Safe\file_get_contents;
use function Safe\json_decode;
use function Safe\preg_replace;

uses(TestCase::class);

/*
 * Decisione D35 (Themes/Sixteen/docs/bmad/decision-log.md): nella fascia slim, dove il riferimento Design Comuni scrive "Nome della Regione",
 * il tenant dimostrativo mostra "Demo" come TESTO (nessun URL, nessun link finto). Il contenuto vive in sections/header.json
 * (`region.name`, `account.label`); la Blade e' solo il vestito. Se l'ente fornisce `config('ente')` (DCP-289) vince l'ente.
 * Il pulsante «Accedi all'area personale» ha il markup del riferimento: btn-primary btn-icon btn-full, span.rounded-icon, svg.icon-primary #it-user.
 */
const SLIM_BAR_LOCALES = ['it', 'en', 'de', 'es'];

/**
 * @param  array<string, mixed>  $ente
 */
function slimBarDocument(TestCase $test, string $locale, array $ente): HTMLDocument
{
    config(['ente' => $ente]);
    $html = $test->get('/'.$locale)->assertOk()->getContent();
    Assert::assertIsString($html);

    return HTMLDocument::createFromString($html, LIBXML_NOERROR);
}

function slimBarText(?Element $node): string
{
    return trim(preg_replace('/\s+/u', ' ', (string) $node?->textContent));
}

/** Etichetta dell'account per lingua, letta dalla stessa sorgente che usa il front office. */
function slimBarAccountLabel(string $locale): string
{
    $path = base_path('config/local/fixcity/database/content/sections/header.json');
    $json = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
    $label = data_get($json, 'blocks.'.$locale.'.0.data.account.label');
    Assert::assertIsString($label);

    return $label;
}

it('shows Demo as plain text in the slim bar, never as a link, in every language', function (string $locale): void {
    /** @var TestCase $this */
    $document = slimBarDocument($this, $locale, ['social' => []]);

    $region = $document->querySelectorAll('header .it-header-slim-wrapper-content > .navbar-brand');
    expect($region->length)->toBe(1);

    $node = $region->item(0);
    expect($node?->tagName)->toBe('SPAN')
        ->and(slimBarText($node))->toBe('Demo')
        ->and($node?->hasAttribute('href'))->toBeFalse()
        ->and($node?->hasAttribute('aria-label'))->toBeFalse()
        ->and($node?->hasAttribute('title'))->toBeFalse();

    $slim = slimBarText($document->querySelector('header .it-header-slim-wrapper'));
    expect($slim)->not->toContain('{')->not->toContain('}')->not->toContain('pub_theme::');
    expect($document->querySelectorAll('header .it-header-slim-wrapper-content a.navbar-brand')->length)->toBe(0);
})->with(SLIM_BAR_LOCALES);

it('lets the region of the ente win over Demo, with a link name that follows it', function (string $locale): void {
    /** @var TestCase $this */
    $document = slimBarDocument($this, $locale, [
        'region_name' => 'Regione di Prova',
        'region_url' => 'https://regione.example.test/portale',
        'social' => [],
    ]);

    $link = $document->querySelector('header .it-header-slim-wrapper-content > a.navbar-brand');
    expect($link)->not->toBeNull()
        ->and($link?->getAttribute('href'))->toBe('https://regione.example.test/portale')
        ->and(slimBarText($link))->toBe('Regione di Prova')
        ->and((string) $link?->getAttribute('aria-label'))->toContain('Regione di Prova')->not->toContain('Demo')
        ->and((string) $link?->getAttribute('title'))->toContain('Regione di Prova')->not->toContain('Demo');
    expect(slimBarText($document->querySelector('header')))->not->toContain('Demo');
})->with(SLIM_BAR_LOCALES);

it('shows the region of the ente as plain text when the ente gave no url', function (): void {
    /** @var TestCase $this */
    $document = slimBarDocument($this, 'it', ['region_name' => 'Regione di Prova', 'social' => []]);

    $node = $document->querySelector('header .it-header-slim-wrapper-content > .navbar-brand');
    expect($node?->tagName)->toBe('SPAN')
        ->and(slimBarText($node))->toBe('Regione di Prova')
        ->and(slimBarText($document->querySelector('header')))->not->toContain('Demo');
});

it('renders the personal area button with the reference markup and the label from header.json', function (string $locale): void {
    /** @var TestCase $this */
    $document = slimBarDocument($this, $locale, ['social' => []]);
    $label = slimBarAccountLabel($locale);

    $cta = '.it-header-slim-right-zone a.btn.btn-primary.btn-icon.btn-full[data-element="personal-area-login"]';
    $button = $document->querySelector($cta);
    expect($button)->not->toBeNull();

    $glyph = $document->querySelector($cta.' > span.rounded-icon[aria-hidden="true"] > svg.icon.icon-primary > use');
    expect($glyph)->not->toBeNull()
        ->and(str_ends_with((string) $glyph?->getAttribute('href'), '/sprites.svg#it-user'))->toBeTrue();

    $text = $document->querySelector($cta.' > span.d-none.d-lg-block');
    expect(slimBarText($text))->toBe($label)
        ->and((string) $button?->getAttribute('aria-label'))->toBe($label)
        ->and((string) $button?->getAttribute('href'))->not->toBe('#')->not->toBe('')
        ->and($button?->tagName)->toBe('A');
    expect(slimBarText($document->querySelector('.it-header-slim-right-zone')))->not->toContain('pub_theme::');
})->with(SLIM_BAR_LOCALES);
